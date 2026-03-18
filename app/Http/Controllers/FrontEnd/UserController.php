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
    Config::set('services.google.redirect', url('login/google/callback'));
  }

  public function login(Request $request)
  {
    if (Auth::guard('web')->check()) {
      return redirect()->route('user.dashboard');
    }
    
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $information['bgImg'] = $misc->getBreadcrumb();
    $information['pageHeading'] = $misc->getPageHeading($language);

    return view('frontend.user.login', $information);
  }

  public function signup()
  {
    if (Auth::guard('web')->check()) {
      return redirect()->route('user.dashboard');
    }

    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $information['bgImg'] = $misc->getBreadcrumb();
    $information['pageHeading'] = $misc->getPageHeading($language);

    return view('frontend.user.signup', $information);
  }

  public function dashboard()
  {
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    
    $user = Auth::guard('web')->user();
    $information['user'] = $user;
    $information['bgImg'] = $misc->getBreadcrumb();
    
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

    $credentials = $request->only('username', 'password');

    if (Auth::guard('web')->attempt($credentials)) {
      $auth_user = Auth::guard('web')->user();

      if ($auth_user->status == 0) {
        Auth::guard('web')->logout();
        return redirect()->back()->with('error', 'Your account is deactivated!');
      }

      return redirect()->route('user.dashboard');
    } else {
      return redirect()->back()->with('error', 'Username or password does not match!');
    }
  }

  public function signupSubmit(Request $request)
  {
    $bs = Basic::first();

    $rules = [
      'username' => 'required|unique:users|max:255',
      'email' => 'required|email:rfc,dns|unique:users|max:255',
      'password' => 'required|confirmed|min:6',
      'dob' => 'required|date',
      'age_agreement' => 'accepted'
    ];

    if ($bs->google_recaptcha_status == 1) {
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

    if ($bs->user_email_verification == 1) {
      $user->email_verified_at = null;
      $user->verification_token = Str::random(32);
      $user->save();
      $this->sendVerificationEmail($user);
      return redirect()->back()->with('success', __('Silakan cek email Anda untuk verifikasi akun.'));
    } else {
      $user->email_verified_at = now();
      $user->save();
      Auth::guard('web')->login($user);
      return redirect()->route('user.dashboard');
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

    if ($info->smtp_status == 1) {
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

  public function logoutSubmit()
  {
    Auth::guard('web')->logout();
    return redirect()->route('user.login');
  }
}
