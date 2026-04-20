<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;

class SocialLoginController extends Controller
{
    /**
     * Redirect the user to the provider's authentication page.
     *
     * @param string $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider(Request $request, $provider)
    {
        // Pastikan provider didukung
        if (!in_array($provider, ['facebook', 'google', 'github'])) {
            abort(404);
        }

        // If this auth flow starts from checkout, remember it so callback can continue checkout.
        if ($request->boolean('checkout_redirect') || str_contains(url()->previous(), '/perahu/checkout')) {
            session()->put('checkout_redirect', true);
            session()->put('checkout_return_url', route('frontend.perahu.checkout', ['step' => 2]));
            
            \Illuminate\Support\Facades\Log::channel('single')->info('SocialLoginController::redirectToProvider - Setting checkout context', [
                'timestamp' => now()->toDateTimeString(),
                'session_id' => $request->getSession()->getId(),
                'provider' => $provider,
                'checkout_redirect_param' => $request->boolean('checkout_redirect'),
                'previous_url' => url()->previous(),
            ]);
        }

        $callbackRoute = $request->routeIs('vendor.login.provider') ? 'vendor.login.callback' : 'user.login.callback';

        // Force callback URL to current app route (host/port-safe for local env).
        return Socialite::driver($provider)
            ->redirectUrl(route($callbackRoute, ['provider' => $provider]))
            ->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        if (!in_array($provider, ['facebook', 'google', 'github'])) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
            $isVendor = $request->routeIs('vendor.login.callback');
            $guard = $isVendor ? 'vendor' : 'web';
            $model = $isVendor ? Vendor::class : User::class;
            $dashboardRoute = $isVendor ? 'vendor.dashboard' : 'user.dashboard';
            $loginRoute = $isVendor ? 'vendor.login' : 'user.login';

            // DIAGNOSTIC LOGGING - Callback entry
            \Illuminate\Support\Facades\Log::channel('single')->info('SocialLoginController::handleProviderCallback - Entry', [
                'timestamp' => now()->toDateTimeString(),
                'session_id' => $request->getSession()->getId(),
                'provider' => $provider,
                'social_id' => $socialUser->getId(),
                'social_email' => $socialUser->getEmail(),
                'checkout_redirect' => session()->get('checkout_redirect'),
                'checkout_return_url' => session()->get('checkout_return_url'),
            ]);

            // Cari pengguna berdasarkan provider_id
            $findUser = $model::where('provider_id', $socialUser->getId())
                ->where('provider', $provider)
                ->first();

            if ($findUser) {
                Auth::guard($guard)->login($findUser);
                
                \Illuminate\Support\Facades\Log::channel('single')->info('SocialLoginController::handleProviderCallback - Found existing user', [
                    'user_id' => $findUser->id,
                    'checkout_redirect' => session()->get('checkout_redirect'),
                ]);
                
                if (session()->pull('checkout_redirect')) {
                    if (empty($findUser->dob)) {
                        session()->put('checkout_complete_profile', true);
                    }
                    $checkoutReturnUrl = session()->pull('checkout_return_url', route('frontend.perahu.checkout', ['step' => 2]));
                    
                    \Illuminate\Support\Facades\Log::channel('single')->info('SocialLoginController - Redirecting to checkout (existing user)', [
                        'redirect_url' => $checkoutReturnUrl,
                        'user_id' => $findUser->id,
                    ]);
                    
                    return redirect()->to($checkoutReturnUrl);
                }
                return redirect()->route($dashboardRoute);
            }

            // Jika tidak ada, cari berdasarkan email atau buat pengguna baru
            $user = $model::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                $username = $socialUser->getNickname() ?? explode('@', $socialUser->getEmail())[0];
                $name = trim($socialUser->getName() ?? '');
                $nameParts = preg_split('/\s+/', $name);
                $firstName = $nameParts[0] ?? '';
                $lastName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';

                $data = [
                    'username' => Str::slug($username),
                    'email' => $socialUser->getEmail(),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'password' => null,
                    'status' => 1,
                    'email_verified_at' => now(),
                ];

                if ($isVendor) {
                    $data['fname'] = $firstName;
                    $data['lname'] = $lastName;
                    $data['photo'] = $socialUser->getAvatar();
                } else {
                    $data['avatar'] = $socialUser->getAvatar();
                    $data['name'] = $socialUser->getName();
                }

                $user = $model::create($data);
                
                \Illuminate\Support\Facades\Log::channel('single')->info('SocialLoginController::handleProviderCallback - Created new user', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            } else {
                $user->provider = $provider;
                $user->provider_id = $socialUser->getId();
                if ($isVendor) {
                    $user->photo = $socialUser->getAvatar();
                    $user->email_verified_at = $user->email_verified_at ?? now();
                } else {
                    $user->avatar = $socialUser->getAvatar();
                }
                $user->save();
                
                \Illuminate\Support\Facades\Log::channel('single')->info('SocialLoginController::handleProviderCallback - Updated existing email user', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            }

            Auth::guard($guard)->login($user);
            
            if (session()->pull('checkout_redirect')) {
                if (empty($user->dob)) {
                    session()->put('checkout_complete_profile', true);
                }
                $checkoutReturnUrl = session()->pull('checkout_return_url', route('frontend.perahu.checkout', ['step' => 2]));
                
                \Illuminate\Support\Facades\Log::channel('single')->info('SocialLoginController - Redirecting to checkout (new/updated user)', [
                    'redirect_url' => $checkoutReturnUrl,
                    'user_id' => $user->id,
                ]);
                
                return redirect()->to($checkoutReturnUrl);
            }
            return redirect()->route($dashboardRoute);

        } catch (Exception $e) {
            Session::flash('error', 'Login via ' . ucfirst($provider) . ' gagal. Silakan coba lagi.');
            return redirect()->route($loginRoute);
        }
    }
}
