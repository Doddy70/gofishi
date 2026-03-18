<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialLoginController extends Controller
{
    /**
     * Redirect the user to the provider's authentication page.
     *
     * @param string $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        // Pastikan provider didukung
        if (!in_array($provider, ['facebook', 'google', 'github'])) {
            abort(404);
        }
        
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        if (!in_array($provider, ['facebook', 'google', 'github'])) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();

            // Cari pengguna berdasarkan provider_id
            $findUser = User::where('provider_id', $socialUser->id)->where('provider', $provider)->first();

            if ($findUser) {
                // Jika pengguna ada, langsung login
                Auth::guard('web')->login($findUser);
                return redirect()->route('index');
            }

            // Jika tidak ada, cari berdasarkan email atau buat pengguna baru
            $user = User::where('email', $socialUser->email)->first();

            if (!$user) {
                // Buat pengguna baru jika email tidak terdaftar
                $user = User::create([
                    'username' => $socialUser->getNickname() ?? $socialUser->getName(),
                    'email' => $socialUser->email,
                    'provider' => $provider,
                    'provider_id' => $socialUser->id,
                    'avatar' => $socialUser->getAvatar(),
                    'password' => null, // Tidak ada password untuk pengguna socialite
                ]);
            } else {
                // Jika email sudah ada, tautkan akun sosial media
                $user->provider = $provider;
                $user->provider_id = $socialUser->id;
                $user->avatar = $socialUser->getAvatar();
                $user->save();
            }

            Auth::guard('web')->login($user);
            return redirect()->route('index');

        } catch (Exception $e) {
            Session::flash('error', 'Login via ' . ucfirst($provider) . ' gagal. Silakan coba lagi.');
            return redirect()->route('user.login');
        }
    }
}
