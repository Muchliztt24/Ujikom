<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'min:3', 'max:50', 'alpha_dash', Rule::unique('users', 'username')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $userRole = Role::query()->firstOrCreate(['name' => 'user']);

        $user = User::query()->create([
            'name' => $request->string('name')->toString(),
            'username' => $request->filled('username')
                ? $request->string('username')->toString()
                : Str::slug($request->string('name')->toString()).'-'.str()->lower(Str::random(4)),
            'email' => $request->string('email')->lower()->toString(),
            'password' => $request->string('password')->toString(),
            'role_id' => $userRole->id,
        ]);

        $token = $user->createToken('mobile-auth')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $this->transformUser($user->fresh()),
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = [
            'email' => $request->string('email')->lower()->toString(),
            'password' => $request->string('password')->toString(),
        ];

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 401);
        }

        /** @var User $user */
        $user = User::query()
            ->with('role')
            ->where('email', $credentials['email'])
            ->firstOrFail();

        $token = $user->createToken('mobile-auth')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $this->transformUser($user),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user()->loadMissing('role');

        return response()->json([
            'user' => $this->transformUser($user),
        ]);
    }

    public function updateMe(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user()->loadMissing('role');

        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'username' => ['sometimes', 'required', 'string', 'min:3', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'bio' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'profile_visibility' => ['sometimes', 'required', Rule::in(['public', 'private'])],
            'email_notifications' => ['sometimes', 'boolean'],
            'reading_history_visible' => ['sometimes', 'boolean'],
            'avatar' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'password' => ['sometimes', 'required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = [];

        if ($request->filled('name')) {
            $payload['name'] = $request->string('name')->toString();
        }

        if ($request->filled('username')) {
            $payload['username'] = $request->string('username')->toString();
        }

        if ($request->filled('email')) {
            $payload['email'] = $request->string('email')->lower()->toString();
        }

        if ($request->exists('bio')) {
            $payload['bio'] = $request->input('bio');
        }

        if ($request->filled('profile_visibility')) {
            $payload['profile_visibility'] = $request->string('profile_visibility')->toString();
        }

        if ($request->has('email_notifications')) {
            $payload['email_notifications'] = $request->boolean('email_notifications');
        }

        if ($request->has('reading_history_visible')) {
            $payload['reading_history_visible'] = $request->boolean('reading_history_visible');
        }

        if ($request->hasFile('avatar')) {
            $payload['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('password')) {
            $payload['password'] = $request->string('password')->toString();
        }

        if ($payload !== []) {
            $user->update($payload);
        }

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user' => $this->transformUser($user->fresh()->loadMissing('role')),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }

    private function transformUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'avatar_url' => $user->avatar_url,
            'bio' => $user->bio,
            'profile_visibility' => $user->profile_visibility,
            'email_notifications' => (bool) $user->email_notifications,
            'reading_history_visible' => (bool) $user->reading_history_visible,
            'role' => $user->role?->name ?? 'user',
        ];
    }
}
