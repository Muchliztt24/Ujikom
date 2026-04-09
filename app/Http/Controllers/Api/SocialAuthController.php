<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Support\SocialAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Throwable;

class SocialAuthController extends Controller
{
    public function providers(): JsonResponse
    {
        return response()->json([
            'data' => collect(SocialAuth::names())->map(function (string $provider) {
                return [
                    'provider' => $provider,
                    'enabled' => SocialAuth::isConfigured($provider),
                    'redirect_url' => route('api.auth.social.redirect', $provider),
                    'token_login_url' => route('api.auth.social.token', $provider),
                ];
            })->values(),
        ]);
    }

    public function redirect(Request $request, string $provider): JsonResponse|RedirectResponse
    {
        $this->ensureProviderIsSupported($provider);
        $this->ensureProviderIsConfigured($provider);

        $url = Socialite::driver($provider)
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        if (! $request->expectsJson()) {
            return redirect()->away($url);
        }

        return response()->json([
            'provider' => $provider,
            'auth_url' => $url,
        ]);
    }

    public function callback(string $provider): JsonResponse|RedirectResponse
    {
        $this->ensureProviderIsSupported($provider);
        $this->ensureProviderIsConfigured($provider);

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            $payload = $this->completeSocialLogin($provider, $socialUser);
        } catch (Throwable $exception) {
            Log::warning('Social auth callback failed.', [
                'provider' => $provider,
                'message' => $exception->getMessage(),
            ]);

            return $this->buildCallbackFailureResponse($provider, $exception->getMessage());
        }

        $mobileRedirect = config('services.mobile_auth.redirect');

        if ($mobileRedirect) {
            $separator = str_contains($mobileRedirect, '?') ? '&' : '?';

            return redirect()->away($mobileRedirect.$separator.http_build_query([
                'status' => 'success',
                'provider' => $provider,
                'token' => $payload['token'],
                'name' => $payload['user']['name'],
                'email' => $payload['user']['email'],
                'role' => $payload['user']['role'],
            ]));
        }

        return response()->json($payload);
    }

    public function tokenLogin(Request $request, string $provider): JsonResponse
    {
        $this->ensureProviderIsSupported($provider);
        $this->ensureProviderIsConfigured($provider);

        $validator = Validator::make($request->all(), [
            'token' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Token provider wajib diisi.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $socialUser = Socialite::driver($provider)
                ->stateless()
                ->userFromToken($request->string('token')->toString());
        } catch (Throwable $exception) {
            Log::warning('Social token login failed.', [
                'provider' => $provider,
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'message' => 'Token dari provider tidak valid atau sudah kedaluwarsa.',
                'provider' => $provider,
            ], 401);
        }

        return response()->json($this->completeSocialLogin($provider, $socialUser));
    }

    private function completeSocialLogin(string $provider, SocialiteUser $socialUser): array
    {
        $providerColumn = $this->providerColumn($provider);
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
        $user->save();

        $token = $user->createToken($provider.'-login')->plainTextToken;

        return [
            'message' => 'Login '.$provider.' berhasil.',
            'provider' => $provider,
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'role' => $user->role?->name ?? 'user',
            ],
            'provider_user' => [
                'id' => $providerId,
                'nickname' => $socialUser->getNickname(),
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'avatar' => $socialUser->getAvatar(),
                'raw' => Arr::except($socialUser->user, ['access_token']),
            ],
        ];
    }

    private function buildCallbackFailureResponse(string $provider, string $message): JsonResponse|RedirectResponse
    {
        $mobileRedirect = config('services.mobile_auth.redirect');

        if ($mobileRedirect) {
            $separator = str_contains($mobileRedirect, '?') ? '&' : '?';

            return redirect()->away($mobileRedirect.$separator.http_build_query([
                'status' => 'error',
                'provider' => $provider,
                'message' => $message,
            ]));
        }

        return response()->json([
            'message' => 'Login '.$provider.' gagal.',
            'provider' => $provider,
            'error' => $message,
        ], 400);
    }

    private function ensureProviderIsSupported(string $provider): void
    {
        abort_unless(in_array($provider, SocialAuth::names(), true), 404);
    }

    private function ensureProviderIsConfigured(string $provider): void
    {
        abort_unless(SocialAuth::isConfigured($provider), 503, 'Provider belum dikonfigurasi di .env.');
    }

    private function providerColumn(string $provider): string
    {
        return SocialAuth::providerColumn($provider);
    }
}
