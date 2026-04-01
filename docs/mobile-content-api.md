# Mobile Content API

Endpoint ini disiapkan supaya portal admin, uploader, dan katalog publik bisa dipakai langsung dari Flutter.

## Header

Untuk endpoint terlindungi:

```http
Authorization: Bearer {token}
Accept: application/json
```

## Publik

- `GET /api/genres`
- `GET /api/works`
- `GET /api/works/{work}`
- `GET /api/works/{work}/chapters/{chapter}`

Filter katalog publik:

- `q`
- `type`
- `genre_id`

## Profil

- `GET /api/me`
- `PATCH /api/me`
- `POST /api/logout`

## Admin

### Dashboard dan master data

- `GET /api/admin/dashboard`
- `GET /api/admin/roles`
- `GET /api/admin/users`
- `GET /api/admin/users/{user}`
- `PATCH /api/admin/users/{user}`
- `GET /api/admin/genres`
- `POST /api/admin/genres`
- `GET /api/admin/genres/{genre}`
- `PATCH /api/admin/genres/{genre}`
- `DELETE /api/admin/genres/{genre}`

### Approval dan moderasi

- `GET /api/admin/works`
- `GET /api/admin/works/pending`
- `GET /api/admin/works/{work}`
- `POST /api/admin/works/{work}/approve`
- `POST /api/admin/works/{work}/reject`
- `DELETE /api/admin/works/{work}`
- `GET /api/admin/chapters`
- `GET /api/admin/chapters/{chapter}`
- `DELETE /api/admin/chapters/{chapter}`
- `GET /api/admin/chapter-images`
- `GET /api/admin/chapter-images/{chapterImage}`
- `DELETE /api/admin/chapter-images/{chapterImage}`

Filter admin yang tersedia:

- works: `status`, `type`, `q`
- users: `role`, `role_id`, `q`
- genres: `q`
- chapters: `work_id`, `type`, `q`
- chapter-images: `work_id`, `chapter_id`

## Uploader

### Dashboard dan karya

- `GET /api/uploader/dashboard`
- `GET /api/uploader/works`
- `POST /api/uploader/works`
- `GET /api/uploader/works/{work}`
- `PATCH /api/uploader/works/{work}`
- `DELETE /api/uploader/works/{work}`
- `POST /api/uploader/works/{work}/submit`
- `GET /api/uploader/works/{work}/analytics`

### Chapter

- `GET /api/uploader/works/{work}/chapters`
- `POST /api/uploader/works/{work}/chapters`
- `GET /api/uploader/works/{work}/chapters/{chapter}`
- `PATCH /api/uploader/works/{work}/chapters/{chapter}`
- `DELETE /api/uploader/works/{work}/chapters/{chapter}`

### Gambar chapter

- `GET /api/uploader/chapters/{chapter}/images`
- `POST /api/uploader/chapters/{chapter}/images`
- `GET /api/uploader/chapters/{chapter}/images/{chapterImage}`
- `PATCH /api/uploader/chapters/{chapter}/images/{chapterImage}`
- `DELETE /api/uploader/chapters/{chapter}/images/{chapterImage}`

Catatan upload:

- cover karya dikirim lewat field multipart `cover`
- upload gambar chapter bisa pakai field `image` untuk single upload
- bisa juga pakai field `images[]` untuk multi upload
