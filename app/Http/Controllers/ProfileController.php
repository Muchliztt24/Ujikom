<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $layout = $user->role?->name === 'admin' || $user->role?->name === 'uploader'
            ? 'layouts.admin'
            : 'layouts.user';

        return view('profile.edit', compact('user', 'layout'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_avatar' => ['nullable', 'boolean'],
            'profile_visibility' => ['required', Rule::in(['public', 'private'])],
            'email_notifications' => ['nullable', 'boolean'],
            'reading_history_visible' => ['nullable', 'boolean'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->bio = $validated['bio'] ?? null;
        $user->profile_visibility = $validated['profile_visibility'];
        $user->email_notifications = (bool) ($request->boolean('email_notifications'));
        $user->reading_history_visible = (bool) ($request->boolean('reading_history_visible'));

        if ($request->boolean('remove_avatar')) {
            $user->avatar = null;
        }

        if ($request->hasFile('avatar')) {
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
