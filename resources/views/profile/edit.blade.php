@extends($layout)

@section('content')
    <div class="profile-page profile-reveal">
        <div class="profile-shell">
            <section class="profile-hero">
                <div class="profile-hero-copy">
                    <span class="profile-pill">Account Center</span>
                    <h1>Profile & Settings</h1>
                    <p>Kelola identitas akun, foto profile, preferensi privasi, dan keamanan dari satu tempat yang rapi.</p>
                </div>
                <div class="profile-hero-card">
                    <div class="profile-avatar-xl">
                        @if ($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                        @else
                            <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div>
                        <strong>{{ $user->name }}</strong>
                        <span>{{ '@'.$user->username }}</span>
                        <small>{{ ucfirst($user->role?->name ?? 'user') }} • {{ $user->email }}</small>
                    </div>
                </div>
            </section>

            @if (session('success'))
                <div class="profile-alert success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="profile-alert danger">Beberapa data perlu diperbaiki sebelum disimpan.</div>
            @endif

            <div class="profile-tabs" role="tablist">
                <button type="button" class="profile-tab is-active" data-tab-target="identity">Profile</button>
                <button type="button" class="profile-tab" data-tab-target="account">Account</button>
                <button type="button" class="profile-tab" data-tab-target="privacy">Privacy</button>
                <button type="button" class="profile-tab" data-tab-target="security">Security</button>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
                @csrf
                @method('PATCH')

                <section class="profile-panel is-active" data-tab-panel="identity">
                    <div class="profile-card">
                        <div class="profile-card-head">
                            <div>
                                <h2>Informasi Utama</h2>
                                <p>Atur identitas publik yang muncul di dashboard dan halaman profile.</p>
                            </div>
                        </div>

                        <div class="profile-grid">
                            <div class="profile-avatar-upload">
                                <div class="profile-avatar-preview">
                                    @if ($user->avatar_url)
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                                    @else
                                        <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <label class="profile-upload-label">
                                    <span>Pilih Foto Profile</span>
                                    <input type="file" name="avatar" accept="image/*">
                                </label>
                                <label class="profile-checkbox">
                                    <input type="checkbox" name="remove_avatar" value="1">
                                    <span>Hapus foto saat ini</span>
                                </label>
                                @error('avatar')<small class="profile-error">{{ $message }}</small>@enderror
                            </div>

                            <div class="profile-fields">
                                <div class="profile-field">
                                    <label for="name">Nama Lengkap</label>
                                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}">
                                    @error('name')<small class="profile-error">{{ $message }}</small>@enderror
                                </div>

                                <div class="profile-field">
                                    <label for="username">Username</label>
                                    <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}">
                                    @error('username')<small class="profile-error">{{ $message }}</small>@enderror
                                </div>

                                <div class="profile-field">
                                    <label for="bio">Bio</label>
                                    <textarea id="bio" name="bio" rows="6">{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')<small class="profile-error">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="profile-panel" data-tab-panel="account">
                    <div class="profile-card">
                        <div class="profile-card-head">
                            <div>
                                <h2>Account</h2>
                                <p>Kelola informasi login dan preferensi komunikasi akun.</p>
                            </div>
                        </div>

                        <div class="profile-fields">
                            <div class="profile-field">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}">
                                @error('email')<small class="profile-error">{{ $message }}</small>@enderror
                            </div>

                            <div class="profile-grid-two">
                                <label class="profile-toggle">
                                    <input type="checkbox" name="email_notifications" value="1" {{ old('email_notifications', $user->email_notifications) ? 'checked' : '' }}>
                                    <span>
                                        <strong>Email Notifications</strong>
                                        <small>Terima info aktivitas akun dan update penting lewat email.</small>
                                    </span>
                                </label>

                                <label class="profile-toggle">
                                    <input type="checkbox" name="reading_history_visible" value="1" {{ old('reading_history_visible', $user->reading_history_visible) ? 'checked' : '' }}>
                                    <span>
                                        <strong>Tampilkan Riwayat Baca</strong>
                                        <small>Izinkan modul akun menampilkan progres dan history secara penuh.</small>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="profile-panel" data-tab-panel="privacy">
                    <div class="profile-card">
                        <div class="profile-card-head">
                            <div>
                                <h2>Privacy</h2>
                                <p>Atur visibilitas data akun yang tampil di ekosistem Nokomi.</p>
                            </div>
                        </div>

                        <div class="profile-fields">
                            <div class="profile-field">
                                <label for="profile_visibility">Visibilitas Profile</label>
                                <select id="profile_visibility" name="profile_visibility">
                                    <option value="public" {{ old('profile_visibility', $user->profile_visibility) === 'public' ? 'selected' : '' }}>Public</option>
                                    <option value="private" {{ old('profile_visibility', $user->profile_visibility) === 'private' ? 'selected' : '' }}>Private</option>
                                </select>
                                @error('profile_visibility')<small class="profile-error">{{ $message }}</small>@enderror
                            </div>

                            <div class="profile-note">
                                <strong>Saran pengaturan</strong>
                                <p>Mode <em>public</em> cocok untuk uploader yang ingin profil dan katalog terlihat jelas. Mode <em>private</em> lebih aman untuk akun pembaca biasa.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="profile-panel" data-tab-panel="security">
                    <div class="profile-card">
                        <div class="profile-card-head">
                            <div>
                                <h2>Security</h2>
                                <p>Ubah password akun dengan aman. Isi password baru hanya jika ingin mengganti.</p>
                            </div>
                        </div>

                        <div class="profile-fields">
                            <div class="profile-field">
                                <label for="current_password">Password Saat Ini</label>
                                <input id="current_password" name="current_password" type="password" autocomplete="current-password">
                                @error('current_password')<small class="profile-error">{{ $message }}</small>@enderror
                            </div>

                            <div class="profile-grid-two">
                                <div class="profile-field">
                                    <label for="password">Password Baru</label>
                                    <input id="password" name="password" type="password" autocomplete="new-password">
                                    @error('password')<small class="profile-error">{{ $message }}</small>@enderror
                                </div>

                                <div class="profile-field">
                                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="profile-actions">
                    <button type="submit" class="profile-btn primary">Simpan Perubahan</button>
                    <a href="{{ route('dashboard') }}" class="profile-btn secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .profile-page {
            display: grid;
            gap: 20px;
        }
        .profile-shell {
            max-width: 1080px;
            display: grid;
            gap: 20px;
        }
        .profile-hero {
            position: relative;
            overflow: hidden;
            padding: 30px;
            border-radius: 24px;
            background: linear-gradient(135deg, rgba(29, 83, 69, 0.96), rgba(17, 34, 58, 0.92));
            border: 1px solid rgba(72, 201, 176, 0.18);
            display: flex;
            justify-content: space-between;
            gap: 22px;
            flex-wrap: wrap;
        }
        .profile-hero::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            right: -80px;
            top: -80px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.14), transparent 62%);
        }
        .profile-hero-copy {
            position: relative;
            z-index: 1;
            max-width: 620px;
        }
        .profile-pill {
            display: inline-flex;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,0.12);
            color: #d7fff5;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .profile-hero h1 {
            font-size: 38px;
            margin: 0 0 8px;
        }
        .profile-hero p {
            color: rgba(230, 242, 239, 0.82);
            line-height: 1.7;
            margin: 0;
        }
        .profile-hero-card {
            position: relative;
            z-index: 1;
            min-width: 280px;
            padding: 18px;
            border-radius: 20px;
            background: rgba(8, 19, 26, 0.34);
            border: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 16px;
            backdrop-filter: blur(10px);
        }
        .profile-hero-card strong,
        .profile-hero-card span,
        .profile-hero-card small {
            display: block;
        }
        .profile-hero-card span,
        .profile-hero-card small {
            color: rgba(230, 242, 239, 0.72);
            margin-top: 4px;
        }
        .profile-avatar-xl,
        .profile-avatar-preview {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2d8b73, #48c9b0);
            display: grid;
            place-items: center;
            color: white;
            font-size: 34px;
            font-weight: 800;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(0,0,0,0.2);
        }
        .profile-avatar-preview {
            width: 120px;
            height: 120px;
            font-size: 42px;
        }
        .profile-avatar-xl img,
        .profile-avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .profile-alert {
            padding: 14px 16px;
            border-radius: 14px;
            font-weight: 600;
        }
        .profile-alert.success {
            background: rgba(72, 201, 176, 0.14);
            border: 1px solid rgba(72, 201, 176, 0.3);
        }
        .profile-alert.danger {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #fecaca;
        }
        .profile-tabs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .profile-tab {
            padding: 12px 16px;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            background: rgba(255,255,255,0.04);
            color: inherit;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            transition: all 0.25s ease;
        }
        .profile-tab.is-active {
            background: linear-gradient(135deg, #2d8b73, #48c9b0);
            color: white;
            border-color: transparent;
        }
        .profile-form {
            display: grid;
            gap: 18px;
        }
        .profile-panel {
            display: none;
        }
        .profile-panel.is-active {
            display: block;
        }
        .profile-card {
            padding: 24px;
            border-radius: 22px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .profile-card-head {
            margin-bottom: 18px;
        }
        .profile-card-head h2 {
            margin: 0 0 6px;
        }
        .profile-card-head p {
            margin: 0;
            color: var(--text-secondary, var(--admin-text-soft));
        }
        .profile-grid {
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            gap: 24px;
        }
        .profile-grid-two {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }
        .profile-avatar-upload {
            display: grid;
            gap: 14px;
            align-content: start;
        }
        .profile-upload-label {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 12px 14px;
            border-radius: 14px;
            background: rgba(255,255,255,0.06);
            border: 1px dashed rgba(255,255,255,0.18);
            cursor: pointer;
            font-weight: 700;
        }
        .profile-upload-label input {
            display: none;
        }
        .profile-fields {
            display: grid;
            gap: 16px;
        }
        .profile-field {
            display: grid;
            gap: 8px;
        }
        .profile-field label {
            font-weight: 700;
        }
        .profile-field input,
        .profile-field textarea,
        .profile-field select {
            width: 100%;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.04);
            color: inherit;
            font: inherit;
        }
        .profile-field input:focus,
        .profile-field textarea:focus,
        .profile-field select:focus {
            outline: none;
            border-color: rgba(72, 201, 176, 0.45);
            box-shadow: 0 0 0 3px rgba(72, 201, 176, 0.12);
        }
        .profile-checkbox,
        .profile-toggle {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 16px;
            border-radius: 16px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .profile-checkbox input,
        .profile-toggle input {
            margin-top: 3px;
        }
        .profile-toggle strong {
            display: block;
            margin-bottom: 4px;
        }
        .profile-toggle small {
            color: var(--text-secondary, var(--admin-text-soft));
            line-height: 1.6;
        }
        .profile-note {
            padding: 18px;
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(45, 139, 115, 0.12), rgba(96, 165, 250, 0.08));
            border: 1px solid rgba(72, 201, 176, 0.14);
        }
        .profile-note strong {
            display: block;
            margin-bottom: 8px;
        }
        .profile-note p {
            margin: 0;
            color: var(--text-secondary, var(--admin-text-soft));
            line-height: 1.7;
        }
        .profile-error {
            color: #fca5a5;
        }
        .profile-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .profile-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 22px;
            border-radius: 14px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
        }
        .profile-btn.primary {
            background: linear-gradient(135deg, #2d8b73, #48c9b0);
            color: white;
        }
        .profile-btn.secondary {
            background: rgba(255,255,255,0.06);
            color: inherit;
            border: 1px solid rgba(255,255,255,0.08);
        }
        .profile-reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.55s ease, transform 0.55s ease;
        }
        .profile-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        @media (max-width: 900px) {
            .profile-grid,
            .profile-grid-two {
                grid-template-columns: 1fr;
            }
            .profile-hero h1 {
                font-size: 32px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('.profile-reveal')?.classList.add('is-visible');

            const tabs = document.querySelectorAll('[data-tab-target]');
            const panels = document.querySelectorAll('[data-tab-panel]');

            tabs.forEach((tab) => {
                tab.addEventListener('click', () => {
                    const target = tab.dataset.tabTarget;
                    tabs.forEach((item) => item.classList.toggle('is-active', item === tab));
                    panels.forEach((panel) => panel.classList.toggle('is-active', panel.dataset.tabPanel === target));
                });
            });
        });
    </script>
@endsection
