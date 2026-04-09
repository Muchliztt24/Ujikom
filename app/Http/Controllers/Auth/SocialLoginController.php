<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Support\SocialAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Throwable;

class SocialLoginController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        $this->ensureProviderIsSupported($provider);
        $this->ensureProviderIsConfigured($provider);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        $this->ensureProviderIsSupported($provider);
        $this->ensureProviderIsConfigured($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
            $user = $this->resolveUser($provider, $socialUser);
        } catch (Throwable $exception) {
            Log::warning('Web social login failed.', [
                'provider' => $provider,
                'message' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('login')
                ->withErrors([
                    'social_login' => 'Login '.Str::headline($provider).' gagal. Silakan coba lagi.',
                ]);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    private function resolveUser(string $provider, SocialiteUser $socialUser): User
    {
        $providerColumn = SocialAuth::providerColumn($provider);
        $providerId = (string) $socialUser->getId();
        $email = strtolower($socialUser->getEmail() ?: "{$provider}_{$providerId}@social.nokomi.local");
        $role = Role::query()->firstOrCreate(['name' => 'user']);

        $user = User::query()
            ->where($providerColumn, $providerId)
            ->orWhere('email', $email)
            ->first();

        if (! $user) {
            $user = new User();
            $user->password = Hash::make(Str::random(32));
            $user->role_id = $role->id;
        }

        $user->name = $socialUser->getName() ?: $socialUser->getNickname() ?: 'User '.Str::upper($provider);
        $user->email = $email;
        $user->avatar = $socialUser->getAvatar() ?: $user->avatar;
        $user->{$providerColumn} = $providerId;
        $user->role_id ??= $role->id;
        $user->email_verified_at ??= now();
        $user->save();

        return $user;
    }

    private function ensureProviderIsSupported(string $provider): void
    {
        abort_unless(in_array($provider, SocialAuth::names(), true), 404);
    }

    private function ensureProviderIsConfigured(string $provider): void
    {
        abort_unless(SocialAuth::isConfigured($provider), 503, 'Provider belum dikonfigurasi di .env.');
    }
}
