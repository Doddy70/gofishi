<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Helpers\VendorPermissionHelper;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\SocialMedia;
use App\Models\Language;
use App\Models\User;
use App\Models\Vendor;
use App\Rules\MatchEmailRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\BasicSettings\MailTemplate;
use Illuminate\Mail\Message;

class UserController extends Controller
{
  public function __construct()
  {
    $bs = Basic::first();
    Config::set('services.facebook.client_id', $bs->facebook_app_id);
    Config::set('services.facebook.client_secret', $bs->facebook_app_secret);
    Config::set('services.facebook.redirect', url('user/login/facebook/callback'));

    Config::set('services.google.client_id', $bs->google_client_id);
    Config::set('services.google.client_secret', $bs->google_client_secret);
    Config::set('services.google.redirect', url('user/login/google/callback'));
  }

  public function login(Request $request)
  {
    if (Auth::guard('web')->check()) {
      return redirect()->intended(route('user.dashboard'));
    }

    $this->rememberIntendedUrlFromQuery($request);

    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $information['bgImg'] = $misc->getBreadcrumb();
    $information['pageHeading'] = $misc->getPageHeading($language);
    $information['bs'] = Basic::first();

    return view('frontend.user.login', $information);
  }

  /**
   * Airbnb-style ?redirect_url= / ?redirect= — only same-app URLs are stored.
   */
  protected function rememberIntendedUrlFromQuery(Request $request): void
  {
    $raw = $request->query('redirect_url', $request->query('redirect'));
    if (!is_string($raw) || $raw === '') {
      return;
    }
    $safe = $this->sanitizeIntendedRedirect($raw);
    if ($safe !== null) {
      $request->session()->put('url.intended', $safe);
    }
  }

  protected function sanitizeIntendedRedirect(string $value): ?string
  {
    $value = trim($value);
    if ($value === '') {
      return null;
    }
    $base = rtrim((string) config('app.url'), '/');
    if (str_starts_with($value, '/') && !str_starts_with($value, '//')) {
      return $base . $value;
    }
    if (filter_var($value, FILTER_VALIDATE_URL) && str_starts_with($value, $base)) {
      return $value;
    }

    return null;
  }

  public function signup(Request $request)
  {
    if (Auth::guard('web')->check()) {
      return redirect()->intended(route('user.dashboard'));
    }

    $this->rememberIntendedUrlFromQuery($request);

    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $information['bgImg'] = $misc->getBreadcrumb();
    $information['pageHeading'] = $misc->getPageHeading($language);
    $information['bs'] = Basic::first();

    return view('frontend.user.signup', $information);
  }

  public function dashboard()
  {
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    
    $user = Auth::guard('web')->user();
    
    // Fetch dashboard data
    $information['authUser'] = $user;
    $information['bookings'] = $user->roomBookings()->orderBy('id', 'desc')->get();
    $information['roomwishlists'] = DB::table('room_wishlists')->where('user_id', $user->id)->get();
    $information['reviews'] = $user->roomReview()
        ->with(['room.room_content', 'room.room_galleries'])
        ->latest()
        ->get();
    $information['bgImg'] = $misc->getBreadcrumb();
    $information['pageHeading'] = $misc->getPageHeading($language);
    
    return view('frontend.user.dashboard', $information);
  }

  public function loginSubmit(Request $request)
  {
    $rules = [
      'username' => 'required',
      'password' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $loginInput = trim((string) $request->input('username'));
    $password = (string) $request->input('password');

    // Support login with username OR email (matches UI placeholder).
    $attempted = false;
    if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
      $attempted = Auth::guard('web')->attempt(['email' => $loginInput, 'password' => $password]);
    } else {
      $attempted = Auth::guard('web')->attempt(['username' => $loginInput, 'password' => $password]);
      // Fallback: in case user typed email-like username or account mismatch.
      if (!$attempted) {
        $attempted = Auth::guard('web')->attempt(['email' => $loginInput, 'password' => $password]);
      }
    }

    if ($attempted) {
      // PRESERVE checkout context before session regenerate
      $checkoutData = [
          'room_id' => $request->session()->get('room_id'),
          'package_id' => $request->session()->get('package_id'),
          'checkout_redirect' => $request->session()->get('checkout_redirect'),
          'checkout_return_url' => $request->session()->get('checkout_return_url'),
          'day_package' => $request->session()->get('day_package'),
          'room_price' => $request->session()->get('room_price'),
          'checkInTime' => $request->session()->get('checkInTime'),
          'checkInDate' => $request->session()->get('checkInDate'),
          'checkOutTime' => $request->session()->get('checkOutTime'),
          'checkOutDate' => $request->session()->get('checkOutDate'),
          'price' => $request->session()->get('price'),
          'adult' => $request->session()->get('adult'),
          'children' => $request->session()->get('children'),
      ];

      \Illuminate\Support\Facades\Log::channel('single')->info('UserController::loginSubmit - Checkout data before regenerate', [
          'timestamp' => now()->toDateTimeString(),
          'session_id' => $request->getSession()->getId(),
          'checkout_data' => $checkoutData,
      ]);

      $request->session()->regenerate();
      $auth_user = Auth::guard('web')->user();

      // RESTORE checkout context after session regenerate
      foreach ($checkoutData as $key => $value) {
          if ($value !== null) {
              $request->session()->put($key, $value);
          }
      }

      // DIAGNOSTIC LOGGING - After regenerate
      \Illuminate\Support\Facades\Log::channel('single')->info('UserController::loginSubmit - Auth successful (after regenerate)', [
          'timestamp' => now()->toDateTimeString(),
          'session_id' => $request->getSession()->getId(),
          'user_id' => $auth_user->id,
          'room_id' => $request->session()->get('room_id'),
          'package_id' => $request->session()->get('package_id'),
          'checkout_redirect' => $request->session()->get('checkout_redirect'),
          'checkout_return_url' => $request->session()->get('checkout_return_url'),
          'referer' => $request->headers->get('referer'),
      ]);

      if ($auth_user->status == 0) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->back()->with('error', 'Your account is deactivated!');
      }

      // If login is initiated from checkout flow, continue checkout flow.
      // Check 3 sources: explicit param, session flag, and referrer URL
      $shouldContinueCheckout = $request->boolean('checkout_redirect') 
        || session()->pull('checkout_redirect', false)
        || str_contains($request->headers->get('referer', ''), '/perahu/checkout');
      
      \Illuminate\Support\Facades\Log::channel('single')->info('UserController::loginSubmit - Checkout decision', [
          'should_continue_checkout' => $shouldContinueCheckout,
          'checkout_redirect_param' => $request->boolean('checkout_redirect'),
          'checkout_redirect_session' => $request->session()->get('checkout_redirect'),
          'referer_contains_checkout' => str_contains($request->headers->get('referer', ''), '/perahu/checkout'),
      ]);
        
      if ($shouldContinueCheckout) {
        session()->put('checkout_redirect', true);
        $checkoutReturnUrl = session()->pull('checkout_return_url', route('frontend.perahu.checkout', ['step' => 2]));
        
        \Illuminate\Support\Facades\Log::channel('single')->info('UserController::loginSubmit - Redirecting to checkout', [
            'redirect_url' => $checkoutReturnUrl,
            'room_id' => $request->session()->get('room_id'),
            'package_id' => $request->session()->get('package_id'),
        ]);
        
        return redirect()->to($checkoutReturnUrl);
      }

      return redirect()->intended(route('user.dashboard'));
    } else {
      return redirect()->back()->with('error', 'Username or password does not match!');
    }
  }

  public function completeCheckoutProfile(Request $request)
  {
    $user = Auth::guard('web')->user();
    if (!$user) {
      return redirect()->route('user.login');
    }

    $request->validate([
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'dob' => 'required|date',
      'age_agreement' => 'accepted',
    ], [
      'age_agreement.accepted' => __('Anda harus menyatakan bahwa Anda berusia 17 tahun atau lebih.'),
      'dob.required' => __('Tanggal lahir wajib diisi.'),
    ]);

    $dob = Carbon::parse($request->dob);
    if ($dob->diffInYears(Carbon::now()) < 17) {
      return redirect()->back()->withErrors(['dob' => __('Usia minimal adalah 17 tahun untuk melanjutkan checkout.')])->withInput();
    }

    $user->name = trim($request->first_name . ' ' . $request->last_name);
    $user->dob = $request->dob;
    $user->save();

    Session::forget('checkout_complete_profile');
    Session::flash('success', __('Profil berhasil dilengkapi. Lanjutkan proses checkout.'));

    return redirect()->route('frontend.perahu.checkout', ['step' => 2]);
  }

  public function signupSubmit(Request $request)
  {
    $bs = Basic::first();
    $recaptchaOn = $bs && (int) ($bs->google_recaptcha_status ?? 0) === 1;

    // email:rfc,dns often fails on localhost / some valid domains (no MX lookup). RFC-only is enough.
    $rules = [
      'username' => 'required|unique:users|max:255',
      'email' => 'required|email|unique:users|max:255',
      'password' => 'required|confirmed|min:6',
      'dob' => 'required|date',
      'age_agreement' => 'accepted',
    ];

    if ($recaptchaOn) {
      $rules['g-recaptcha-response'] = 'required|captcha';
    }

    $messages = [
      'age_agreement.accepted' => __('Anda harus menyatakan bahwa Anda berusia 17 tahun atau lebih.'),
      'dob.required' => __('Tanggal lahir wajib diisi.')
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    // Age verification: must be at least 17 years old
    $dob = Carbon::parse($request->dob);
    if ($dob->diffInYears(Carbon::now()) < 17) {
      return redirect()->back()->withErrors(['dob' => __('Usia minimal adalah 17 tahun untuk mendaftar.')])->withInput();
    }

    $user = new User();
    $user->username = $request->username;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->dob = $request->dob;
    $user->status = 1;

    if ($bs && (int) ($bs->user_email_verification ?? 0) === 1) {
      $user->email_verified_at = null;
      $user->verification_token = Str::random(32);
      $user->save();
      $this->sendVerificationEmail($user);
      return redirect()->back()->with('success', __('Silakan cek email Anda untuk verifikasi akun.'));
    } else {
      $user->email_verified_at = now();
      $user->save();
      
      // PRESERVE checkout context before login
      $checkoutData = [
          'room_id' => $request->session()->get('room_id'),
          'package_id' => $request->session()->get('package_id'),
          'checkout_redirect' => $request->session()->get('checkout_redirect'),
          'checkout_return_url' => $request->session()->get('checkout_return_url'),
          'day_package' => $request->session()->get('day_package'),
          'room_price' => $request->session()->get('room_price'),
          'checkInTime' => $request->session()->get('checkInTime'),
          'checkInDate' => $request->session()->get('checkInDate'),
          'checkOutTime' => $request->session()->get('checkOutTime'),
          'checkOutDate' => $request->session()->get('checkOutDate'),
          'price' => $request->session()->get('price'),
          'adult' => $request->session()->get('adult'),
          'children' => $request->session()->get('children'),
      ];

      Auth::guard('web')->login($user);
      
      // RESTORE checkout context after login
      foreach ($checkoutData as $key => $value) {
          if ($value !== null) {
              $request->session()->put($key, $value);
          }
      }

      \Illuminate\Support\Facades\Log::channel('single')->info('UserController::signupSubmit - Signup successful', [
          'timestamp' => now()->toDateTimeString(),
          'user_id' => $user->id,
          'room_id' => $request->session()->get('room_id'),
          'package_id' => $request->session()->get('package_id'),
      ]);
      
      // Check for checkout context from 3 sources
      $shouldContinueCheckout = $request->boolean('checkout_redirect') 
        || session()->pull('checkout_redirect', false)
        || str_contains($request->headers->get('referer', ''), '/perahu/checkout');
      
      \Illuminate\Support\Facades\Log::channel('single')->info('UserController::signupSubmit - Checkout decision', [
          'should_continue_checkout' => $shouldContinueCheckout,
          'checkout_redirect_param' => $request->boolean('checkout_redirect'),
      ]);
        
      if ($shouldContinueCheckout) {
        session()->put('checkout_redirect', true);
        $checkoutReturnUrl = session()->pull('checkout_return_url', route('frontend.perahu.checkout', ['step' => 2]));
        
        \Illuminate\Support\Facades\Log::channel('single')->info('UserController::signupSubmit - Redirecting to checkout', [
            'redirect_url' => $checkoutReturnUrl,
            'room_id' => $request->session()->get('room_id'),
            'package_id' => $request->session()->get('package_id'),
        ]);
        
        return redirect()->to($checkoutReturnUrl);
      }
      
      return redirect()->intended(route('user.dashboard'));
    }
  }

  protected function sendVerificationEmail($user)
  {
    $mailTemplate = MailTemplate::where('mail_type', 'verify_email')->first();
    if (!$mailTemplate) return;

    $info = Basic::select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();

    $link = '<a href=' . url("user/signup-verify/" . $user->verification_token) . '>Click Here</a>';
    $body = str_replace(
      ['{username}', '{verification_link}', '{website_title}'],
      [$user->username, $link, $info->website_title],
      $mailTemplate->mail_body
    );

    if (!$info || $info->smtp_status != 1) {
      return;
    }

    Config::set('mail.mailers.smtp', [
      'transport' => 'smtp',
      'host' => $info->smtp_host,
      'port' => $info->smtp_port,
      'encryption' => $info->encryption,
      'username' => $info->smtp_username,
      'password' => $info->smtp_password,
    ]);

    Mail::send([], [], function (Message $message) use ($user, $info, $mailTemplate, $body) {
      $message->to($user->email)
        ->subject($mailTemplate->mail_subject)
        ->from($info->from_mail, $info->from_name)
        ->html($body, 'text/html');
    });
  }

  public function signupVerify($token)
  {
    $user = User::where('verification_token', $token)->firstOrFail();
    $user->email_verified_at = now();
    $user->verification_token = null;
    $user->save();

    Auth::guard('web')->login($user);
    return redirect()->route('user.dashboard')->with('success', __('Email berhasil diverifikasi.'));
  }

  public function redirectToDashboard()
  {
    return redirect()->route('user.dashboard');
  }


  public function editProfile()
  {
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $information['bgImg'] = $misc->getBreadcrumb();
    $information['pageHeading'] = $misc->getPageHeading($language);

    return view('frontend.user.edit-profile', $information);
  }

  public function updateProfile(Request $request)
  {
    $user = Auth::guard('web')->user();
    $rules = [
      'name' => 'required',
      'username' => 'required|unique:users,username,' . $user->id,
      'phone' => 'nullable',
      'country' => 'nullable',
      'city' => 'nullable',
      'state' => 'nullable',
      'zip_code' => 'nullable',
      'address' => 'nullable',
    ];

    if ($request->hasFile('image')) {
      $rules['image'] = 'mimes:jpeg,jpg,png,gif|max:2048';
    }

    $request->validate($rules);

    $user->name = $request->name;
    $user->username = $request->username;
    $user->phone = $request->phone;
    $user->country = $request->country;
    $user->city = $request->city;
    $user->state = $request->state;
    $user->zip_code = $request->zip_code;
    $user->address = $request->address;

    if ($request->hasFile('image')) {
        $img = $request->file('image');
        $extension = $img->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;
        $directory = public_path('assets/img/users/');
        @mkdir($directory, 0775, true);
        $img->move($directory, $fileName);
        
        // delete old image
        if ($user->image && file_exists($directory . $user->image)) {
            @unlink($directory . $user->image);
        }
        $user->image = $fileName;
    }

    $user->save();

    return redirect()->back()->with('success', __('Profil berhasil diperbarui.'));
  }

  public function changePassword()
  {
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $information['bgImg'] = $misc->getBreadcrumb();
    $information['pageHeading'] = $misc->getPageHeading($language);

    return view('frontend.user.change-password', $information);
  }

  public function updatePassword(Request $request)
  {
    $rules = [
      'current_password' => 'required',
      'new_password' => 'required|confirmed|min:6',
    ];

    $messages = [
      'current_password.required' => __('Kata sandi saat ini wajib diisi.'),
      'new_password.required' => __('Kata sandi baru wajib diisi.'),
      'new_password.confirmed' => __('Konfirmasi kata sandi baru tidak cocok.'),
      'new_password.min' => __('Kata sandi baru minimal 6 karakter.'),
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $user = Auth::guard('web')->user();

    if (!Hash::check($request->current_password, $user->password)) {
      return redirect()->back()->withErrors(['current_password' => __('Kata sandi saat ini salah.')]);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return redirect()->back()->with('success', __('Kata sandi berhasil diperbarui.'));
  }

  public function roomBookings(Request $request)
  {
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $information['bgImg'] = $misc->getBreadcrumb();
    $information['pageHeading'] = $misc->getPageHeading($language);

    $user = Auth::guard('web')->user();
    $query = $user->roomBookings()->orderBy('id', 'desc');

    if ($request->status) {
        $query->where('order_status', $request->status);
    }

    $information['bookings'] = $query->get();

    return view('frontend.user.booking.index', $information);
  }

  public function roomBookingDetails($id)
  {
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $information['bgImg'] = $misc->getBreadcrumb();
    $information['pageHeading'] = $misc->getPageHeading($language);

    $user = Auth::guard('web')->user();
    $booking = $user->roomBookings()->where('id', $id)->firstOrFail();

    $information['booking'] = $booking;

    return view('frontend.user.booking.details', $information);
  }

  public function supportTicket()
  {
     // Redirect to SupportTicketController if it exists, or handle here
     return redirect()->route('user.support_ticket.index');
  }

  public function loginByProvider($provider)
  {
    return Socialite::driver($provider)->redirect();
  }
  
  public function loginProviderCallback($provider)
  {
    try {
      $socialUser = Socialite::driver($provider)->user();
    } catch (\Exception $e) {
      return redirect()->route('user.login')->with('error', 'Error logging in with ' . $provider);
    }
    
    $authUser = User::where('email', $socialUser->getEmail())->first();
    
    if ($authUser) {
      Auth::guard('web')->login($authUser);
      return redirect()->route('user.dashboard');
    } else {
      $newUser = new User();
      $newUser->name = $socialUser->getName();
      $newUser->username = $socialUser->getNickname() ?? $socialUser->getName() . rand(100, 999);
      $newUser->email = $socialUser->getEmail();
      $newUser->password = Hash::make(Str::random(12)); // Random password
      $newUser->email_verified_at = now();
      $newUser->status = 1;
      $newUser->save();
      
      Auth::guard('web')->login($newUser);
      return redirect()->route('user.dashboard');
    }
  }

  public function logoutSubmit()
  {
    Auth::guard('web')->logout();
    return redirect()->route('user.login');
  }
}

