# Mobile Auth API

Backend sekarang menyediakan auth JSON untuk Flutter/mobile dengan Sanctum.

## Endpoint dasar

- `POST /api/register`
- `POST /api/login`
- `GET /api/me`
- `PATCH /api/me`
- `POST /api/logout`

Header untuk endpoint terlindungi:

```http
Authorization: Bearer {token}
Accept: application/json
```

Endpoint konten admin, uploader, dan katalog publik ada di [mobile-content-api.md](./mobile-content-api.md).

## Social login

Provider yang didukung:

- Google
- Facebook
- X
- Discord
- GitHub

### 1. Ambil daftar provider

```http
GET /api/auth/providers
```

### 2. Login via token provider

Dipakai saat Flutter sudah dapat `access_token` atau `id_token`.

```http
POST /api/auth/{provider}/token
Content-Type: application/json

{
  "token": "provider-access-token-or-id-token"
}
```

Contoh provider:

- `google`
- `facebook`
- `x`
- `discord`
- `github`

### 3. OAuth redirect flow

Kalau mobile/web mau pakai flow browser:

- `GET /api/auth/{provider}/redirect`
- `GET /api/auth/{provider}/callback`

Jika `MOBILE_AUTH_REDIRECT` diisi, callback akan redirect ke URL itu dengan query:

- `status`
- `provider`
- `token`
- `name`
- `email`
- `role`

## Env yang harus diisi

```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI="${APP_URL}/api/auth/google/callback"

FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT_URI="${APP_URL}/api/auth/facebook/callback"

X_CLIENT_ID=
X_CLIENT_SECRET=
X_REDIRECT_URI="${APP_URL}/api/auth/x/callback"

DISCORD_CLIENT_ID=
DISCORD_CLIENT_SECRET=
DISCORD_REDIRECT_URI="${APP_URL}/api/auth/discord/callback"

GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URI="${APP_URL}/api/auth/github/callback"

MOBILE_AUTH_REDIRECT=
```
