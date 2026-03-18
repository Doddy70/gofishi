<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Helpers\VendorPermissionHelper;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\Perahu;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\Vendor;
use App\Models\VendorInfo;
use App\Models\Visitor;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;

use App\Http\Requests\Vendor\RegistrationRequest;
use App\Services\VendorService;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    protected $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    //signup
    public function setLocaleAdmin(Request $request)
    {
        App::setLocale($request->code);
        $vendor_id = Auth::guard('vendor')->user()->id;
        $vendor = Vendor::find($vendor_id);
        $vendor->lang_code = 'admin_' . $request->code;
        $vendor->code = $request->code;
        $vendor->save();

        return $request->code;
    }

    public function signup()
    {
        if (Auth::guard('vendor')->check()) {
            return redirect()->route('vendor.dashboard');
        }
        else {
            $misc = new MiscellaneousController();
            $language = $misc->getLanguage();
            $information['seoInfo'] = $language->seoInfo()->select('meta_keywords_vendor_signup', 'meta_description_vendor_signup')->first();
            $information['pageHeading'] = $misc->getPageHeading($language);
            $information['recaptchaInfo'] = Basic::select('google_recaptcha_status')->first();
            $information['bgImg'] = $misc->getBreadcrumb();

            return view('frontend.vendor.auth.register', $information);
        }
    }

    /**
     * Handle vendor registration using Service Layer and Form Request.
     */
    public function create(RegistrationRequest $request)
    {
        if ($request->username == 'admin') {
            Session::flash('username_error', __('You can not use admin as a username') . '!');
            return redirect()->back();
        }

        $this->vendorService->register($request->validated());

        return redirect()->route('vendor.login');
    }

    //login
    public function login(Request $request)
    {
        if (Auth::guard('vendor')->check()) {
            return redirect()->route('vendor.dashboard');
        }
        else {
            $misc = new MiscellaneousController();
            $language = $misc->getLanguage();
            $information['seoInfo'] = $language->seoInfo()->select('meta_keywords_vendor_login', 'meta_description_vendor_login')->first();
            $information['pageHeading'] = $misc->getPageHeading($language);
            $information['bgImg'] = $misc->getBreadcrumb();
            $information['bs'] = Basic::query()->select('google_recaptcha_status', 'facebook_login_status', 'google_login_status')->first();

            if ($request->redirectPath == 'buy_plan') {
                Session::put('redirectTo', 'buy_plan');
            }
            if ($request->package) {
                Session::put('package', $request->package);
            }

            return view('frontend.vendor.auth.login', $information);
        }
    }

    //authenticate
    public function authentication(Request $request)
    {
        $redirectURL = Session::get('redirectTo');
        $packageIds = Session::get('package');

        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $info = Basic::select('google_recaptcha_status')->first();
        if ($info->google_recaptcha_status == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        if (Auth::guard('vendor')->attempt(['username' => $request->username, 'password' => $request->password])) {
            $authAdmin = Auth::guard('vendor')->user();
            $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_email_verification', 'vendor_admin_approval')->first();

            if ($setting->vendor_email_verification == 1 && $authAdmin->email_verified_at == NULL) {
                Session::flash('error', __('Please verify your email address') . '!');
                Auth::guard('vendor')->logout();
                return redirect()->back();
            }

            Session::put('secret_login', 0);
            if ($redirectURL == 'buy_plan') {
                Session::forget(['redirectTo', 'package']);
                return $packageIds ? redirect()->route('vendor.plan.extend.checkout', ['package_id' => $packageIds]) : redirect()->route('vendor.plan.extend.index');
            }
            return redirect()->route('vendor.dashboard');
        }
        
        return redirect()->back()->with('error', 'Incorrect username or password');
    }

    public function confirm_email()
    {
        $email = request()->input('token');
        $user = Vendor::where('email', $email)->first();
        $user->email_verified_at = now();
        $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_admin_approval')->first();
        if ($setting->vendor_admin_approval != 1) {
            $user->status = Vendor::STATUS_ACTIVE;
        }
        $user->save();
        Auth::guard('vendor')->login($user);
        return redirect()->route('vendor.dashboard');
    }

    public function logout()
    {
        Auth::guard('vendor')->logout();
        Session::forget('secret_login');
        return redirect()->route('vendor.login');
    }

    public function dashboard()
    {
        $vendor = Auth::guard('vendor')->user();
        $vendor_id = $vendor->id;
        
        $information['totalRooms'] = Perahu::where('vendor_id', $vendor_id)->count();
        $information['admin_setting'] = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_admin_approval', 'admin_approval_notice')->first();

        $support_status = DB::table('support_ticket_statuses')->first();
        if ($support_status->support_ticket_status == 'active') {
            $information['total_support_tickets'] = SupportTicket::where([['user_id', $vendor_id], ['user_type', 'vendor']])->count();
        }
        $information['support_status'] = $support_status;

        // Membership & Package Logic
        $information['current_package'] = VendorPermissionHelper::packagePermission($vendor_id);
        $information['package_count'] = Membership::where([['vendor_id', $vendor_id], ['status', 1]])->count();
        $information['current_membership'] = VendorPermissionHelper::userPackage($vendor_id);
        $information['next_package'] = VendorPermissionHelper::nextPackage($vendor_id);
        $information['next_membership'] = VendorPermissionHelper::nextMembership($vendor_id);
        $information['payment_logs'] = Membership::where('vendor_id', $vendor_id)->count();

        // Monthly Stats for Charts
        $rooms = Perahu::query()->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total'))
            ->where('vendor_id', $vendor_id)
            ->groupBy('month')
            ->whereYear('created_at', '=', date('Y'))
            ->get();

        $visitors = Visitor::query()->select(DB::raw('month(date) as month'), DB::raw('count(id) as total'))
            ->where('vendor_id', $vendor_id)
            ->groupBy('month')
            ->whereYear('date', '=', date('Y'))
            ->get();

        $months = [];
        $totalRoomsArr = [];
        $visitorArr = [];

        for ($i = 1; $i <= 12; $i++) {
            $dateObj = DateTime::createFromFormat('!m', $i);
            $monthName = $dateObj->format('M');
            array_push($months, $monthName);

            $roomFound = false;
            foreach ($rooms as $room) {
                if ($room->month == $i) {
                    $roomFound = true;
                    array_push($totalRoomsArr, $room->total);
                    break;
                }
            }
            if (!$roomFound) array_push($totalRoomsArr, 0);

            $visitorFound = false;
            foreach ($visitors as $visitor) {
                if ($visitor->month == $i) {
                    $visitorFound = true;
                    array_push($visitorArr, $visitor->total);
                    break;
                }
            }
            if (!$visitorFound) array_push($visitorArr, 0);
        }

        $information['monthArr'] = $months;
        $information['totalRoomsArr'] = $totalRoomsArr;
        $information['visitorArr'] = $visitorArr;

        return view('vendors.index', $information);
    }

    public function change_password()
    {
        return view('frontend.vendor.auth.change-password');
    }

    public function updated_password(Request $request)
    {
        $rules = [
            'current_password' => ['required', new MatchOldPasswordRule('vendor')],
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(['errors' => $validator->getMessageBag()->toArray()], 400);
        }

        Auth::guard('vendor')->user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        Session::flash('success', __('Password updated successfully') . '!');
        return response()->json(['status' => 'success'], 200);
    }

    public function editProfile()
    {
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
        $information['language'] = $language;
        $information['languages'] = Language::get();
        $information['vendor'] = Vendor::with('vendor_info')->where('id', Auth::guard('vendor')->user()->id)->first();
        return view('frontend.vendor.auth.edit-profile', $information);
    }

    public function updateProfile(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
        $id = $vendor->id;
        
        $rules = [
            'username' => ['required', Rule::unique('vendors', 'username')->ignore($id)],
            'email' => ['required', 'email', Rule::unique('vendors', 'email')->ignore($id)]
        ];

        if ($request->hasFile('photo')) {
            $rules['photo'] = 'mimes:png,jpeg,jpg|dimensions:min_width=80,max_width=80,min_height=80,max_height=80';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(['errors' => $validator->getMessageBag()], 400);
        }

        $in = $request->all();
        // File handling logic remains here for now, but should ideally move to VendorService
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/admin/img/vendor-photo/'), $fileName);
            @unlink(public_path('assets/admin/img/vendor-photo/') . $vendor->photo);
            $in['photo'] = $fileName;
        }

        $in['show_email_addresss'] = $request->show_email_addresss ? 1 : 0;
        $in['show_phone_number'] = $request->show_phone_number ? 1 : 0;
        $in['show_contact_form'] = $request->show_contact_form ? 1 : 0;
        $in['whatsapp_status'] = $request->whatsapp_status ? 1 : 0;

        $documentFields = ['ktp_file', 'boat_ownership_file', 'driving_license_file', 'self_photo_file'];
        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $fileName = uniqid() . '-' . $field . '.' . $file->getClientOriginalExtension();
                $file->storeAs('private/vendor-documents', $fileName);
                $in[$field] = $fileName;
            }
        }

        $vendor->update($in);

        // Update Multi-language info
        $languages = Language::get();
        foreach ($languages as $language) {
            $code = $language->code;
            VendorInfo::updateOrCreate(
                ['vendor_id' => $id, 'language_id' => $language->id],
                [
                    'name' => $request[$code . '_name'],
                    'country' => $request[$code . '_country'],
                    'city' => $request[$code . '_city'],
                    'state' => $request[$code . '_state'],
                    'zip_code' => $request[$code . '_zip_code'],
                    'address' => $request[$code . '_address'],
                    'details' => $request[$code . '_details'],
                ]
            );
        }

        Session::flash('success', __('Profile updated successfully') . '!');
        return Response::json(['status' => 'success'], 200);
    }

    public function transcation(Request $request)
    {
        $info['transcations'] = Transaction::where('vendor_id', Auth::guard('vendor')->user()->id)
            ->when($request->transcation_id, function ($query, $id) {
                return $query->where('transcation_id', 'like', '%' . $id . '%');
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('vendors.transcation', $info);
    }

    public function showDocument($filename)
    {
        $vendor = Auth::guard('vendor')->user();
        
        $documentColumns = ['ktp_file', 'boat_ownership_file', 'driving_license_file', 'self_photo_file'];
        
        // Security Check: Ensure the requested file belongs to the authenticated vendor
        $isOwner = false;
        foreach ($documentColumns as $column) {
            if ($vendor->{$column} == $filename) {
                $isOwner = true;
                break;
            }
        }

        if (!$isOwner) {
            abort(404);
        }

        $path = storage_path('app/private/vendor-documents/' . $filename);

        if (!Storage::disk('local')->exists('private/vendor-documents/' . $filename)) {
            abort(404);
        }

        return response()->file($path);
    }
}
