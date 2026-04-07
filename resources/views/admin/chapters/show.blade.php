@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Detail Chapter</h1>
        <p>Lihat isi chapter, halaman komik, dan informasi karya dalam satu tampilan.</p>
    </div>

    <div class="content-body">
        @if (session('success'))
            <div style="margin-bottom: 16px; padding: 14px 16px; background: #d4edda; color: #155724; border-radius: 10px;">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('admin.chapters.index') }}"
            style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 18px; text-decoration: none; color: var(--admin-accent); font-weight: 700;">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke daftar chapter</span>
        </a>

        <div style="display: grid; grid-template-columns: minmax(0, 1fr) 320px; gap: 24px; align-items: start;">
            <div style="display: grid; gap: 20px;">
                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 22px;">
                    <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 14px; align-items: center;">
                        <span style="display: inline-flex; align-items: center; padding: 8px 14px; border-radius: 999px; background: #e8f5f3; color: #1e5f4f; font-weight: 700; font-size: 13px;">
                            Chapter {{ $chapter->chapter_number }}
                        </span>
                        <span style="display: inline-flex; align-items: center; padding: 8px 14px; border-radius: 999px; background: {{ $chapter->work->type === 'comic' ? '#fff4e6' : '#eef3ff' }}; color: {{ $chapter->work->type === 'comic' ? '#c05621' : '#2b6cb0' }}; font-weight: 700; font-size: 13px;">
                            {{ $chapter->work->type === 'comic' ? 'Comic' : 'Novel' }}
                        </span>
                    </div>

                    <h2 style="margin: 0 0 8px; color: #1e5f4f; font-size: 28px;">
                        {{ $chapter->title ?: 'Tanpa judul chapter' }}
                    </h2>
                    <div style="color: #6c757d; font-size: 14px; line-height: 1.7;">
                        Karya: <strong>{{ $chapter->work->title }}</strong><br>
                        Dibuat: {{ $chapter->created_at?->format('d M Y H:i') ?? '-' }}<br>
                        Diperbarui: {{ $chapter->updated_at?->format('d M Y H:i') ?? '-' }}
                    </div>
                </div>

                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 22px;">
                    @if ($chapter->work->type === 'novel')
                        <div style="display: flex; justify-content: space-between; gap: 12px; align-items: center; margin-bottom: 16px; flex-wrap: wrap;">
                            <h3 style="margin: 0; color: #1e5f4f;">Isi Novel</h3>
                            <div style="color: #6c757d; font-size: 13px;">
                                {{ number_format(str_word_count(strip_tags($chapter->text_content ?? ''))) }} kata
                            </div>
                        </div>

                        @if ($chapter->text_content)
                            <div style="color: #343a40; line-height: 1.9; font-size: 15px; white-space: pre-line;">
                                {{ $chapter->text_content }}
                            </div>
                        @else
                            <div style="padding: 18px; background: #f8f9fa; border-radius: 10px; color: #6c757d;">
                                Teks chapter akan tampil di area ini.
                            </div>
                        @endif
                    @else
                        <div style="display: flex; justify-content: space-between; gap: 12px; align-items: center; margin-bottom: 16px; flex-wrap: wrap;">
                            <h3 style="margin: 0; color: #1e5f4f;">Halaman Komik</h3>
                            <div style="color: #6c757d; font-size: 13px;">
                                {{ $chapter->images->count() }} gambar
                            </div>
                        </div>

                        @if ($chapter->images->count())
                            <div style="display: grid; gap: 18px;">
                                @foreach ($chapter->images->sortBy('page_number') as $image)
                                    <div style="border: 1px solid #e9ecef; border-radius: 12px; padding: 14px; background: #fcfcfc;">
                                        <div style="font-weight: 700; color: #1e5f4f; margin-bottom: 10px;">
                                            Halaman {{ $image->page_number }}
                                        </div>
                                        <img src="{{ asset('storage/' . $image->image_url) }}"
                                            alt="Halaman {{ $image->page_number }}"
                                            style="width: 100%; height: auto; border-radius: 10px; display: block;">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="padding: 18px; background: #f8f9fa; border-radius: 10px; color: #6c757d;">
                                Halaman komik akan tampil di area ini.
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div style="display: grid; gap: 18px;">
                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 18px;">
                    <h3 style="margin: 0 0 14px; color: #1e5f4f;">Info Ringkas</h3>
                    <div style="display: grid; gap: 10px; color: #495057; font-size: 14px; line-height: 1.7;">
                        <div><strong>Work:</strong> {{ $chapter->work->title }}</div>
                        <div><strong>Author Asli:</strong> {{ $chapter->work->display_author }}</div>
                        <div><strong>Uploader:</strong> {{ $chapter->work->user->name }}</div>
                        <div><strong>Email:</strong> {{ $chapter->work->user->email }}</div>
                        <div><strong>Status Work:</strong> {{ ucfirst($chapter->work->status) }}</div>
                        <div><strong>Genre:</strong> {{ $chapter->work->genres->pluck('name')->implode(', ') ?: '-' }}</div>
                        @if ($chapter->work->type === 'comic')
                            <div><strong>Total Gambar:</strong> {{ $chapter->images->count() }}</div>
                        @else
                            <div><strong>Panjang Isi:</strong> {{ number_format(strlen($chapter->text_content ?? '')) }} karakter</div>
                        @endif
                    </div>
                </div>

                <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 14px; padding: 18px;">
                    <h3 style="margin: 0 0 14px; color: #1e5f4f;">Aksi Admin</h3>
                    <div style="display: grid; gap: 10px;">
                        <a href="{{ route('admin.works.show', $chapter->work) }}"
                            style="text-align: center; padding: 10px 12px; text-decoration: none; background: #17a2b8; color: white; border-radius: 10px; font-weight: 700;">
                            Lihat Karya
                        </a>
                        @if ($chapter->work->type === 'comic' && $chapter->images->count())
                            <a href="{{ route('admin.chapter-images.show', $chapter->images->sortBy('page_number')->first()) }}"
                                style="text-align: center; padding: 10px 12px; text-decoration: none; background: #28a745; color: white; border-radius: 10px; font-weight: 700;">
                                Lihat Gambar Pertama
                            </a>
                        @endif
                        <form action="{{ route('admin.chapters.destroy', $chapter) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Hapus chapter ini?')"
                                style="width: 100%; padding: 10px 12px; border: none; background: #dc3545; color: white; border-radius: 10px; font-weight: 700; cursor: pointer;">
                                Hapus Chapter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 960px) {
            div[style*="grid-template-columns: minmax(0, 1fr) 320px"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection


