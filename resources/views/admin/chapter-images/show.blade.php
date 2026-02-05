@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Detail Gambar Chapter</h1>
        <p>Informasi lengkap tentang gambar chapter ini</p>
    </div>

    <div class="content-body">
        <!-- Back Button -->
        <a href="{{ route('admin.chapter-images.index') }}" 
           style="display: inline-flex; align-items: center; gap: 8px; color: #6c757d; text-decoration: none; margin-bottom: 20px; padding: 8px 16px; border-radius: 6px; transition: all 0.3s;"
           onmouseover="this.style.background='#f8f9fa'; this.style.color='#48c9b0'"
           onmouseout="this.style.background='transparent'; this.style.color='#6c757d'">
            <span>←</span> Kembali ke Daftar
        </a>

        <div style="display: grid; grid-template-columns: 1fr 400px; gap: 30px; max-width: 1400px;">
            <!-- Left: Image Preview -->
            <div>
                <div style="background: white; border-radius: 12px; padding: 20px; border: 1px solid #dee2e6;">
                    <div style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="color: #1e5f4f; margin: 0;">Preview Gambar</h3>
                        <span style="padding: 6px 14px; background: #48c9b0; color: white; border-radius: 20px; font-weight: 700; font-size: 14px;">
                            Page {{ $chapterImage->page_number }}
                        </span>
                    </div>

                    <div style="text-align: center; background: #f8f9fa; border-radius: 8px; padding: 20px;">
                        <img src="{{ asset('storage/' . $chapterImage->image_url) }}" 
                             alt="Page {{ $chapterImage->page_number }}"
                             style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
                    </div>

                    <!-- Image Info -->
                    <div style="margin-top: 20px; padding: 16px; background: #f8f9fa; border-radius: 8px;">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                            <div>
                                <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">File Path</div>
                                <div style="font-size: 13px; color: #495057; font-family: monospace; word-break: break-all;">
                                    {{ $chapterImage->image_url }}
                                </div>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Upload Date</div>
                                <div style="font-size: 13px; color: #495057;">
                                    {{ $chapterImage->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Details -->
            <div>
                <!-- Work Info Card -->
                <div style="background: white; border-radius: 12px; padding: 20px; border: 1px solid #dee2e6; margin-bottom: 20px;">
                    <h4 style="color: #1e5f4f; margin-bottom: 16px; font-size: 16px;">📚 Informasi Karya</h4>
                    
                    @if($chapterImage->chapter->work->cover)
                        <div style="margin-bottom: 16px; text-align: center;">
                            <img src="{{ asset('storage/' . $chapterImage->chapter->work->cover) }}" 
                                 style="width: 120px; height: 160px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        </div>
                    @endif

                    <div style="margin-bottom: 12px;">
                        <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Judul Karya</div>
                        <div style="font-weight: 600; color: #1e5f4f; font-size: 15px;">
                            {{ $chapterImage->chapter->work->title }}
                        </div>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Tipe</div>
                        <span style="display: inline-block; padding: 4px 12px; background: {{ $chapterImage->chapter->work->type === 'novel' ? '#e7f3ff' : '#fff3cd' }}; color: {{ $chapterImage->chapter->work->type === 'novel' ? '#004085' : '#856404' }}; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            {{ $chapterImage->chapter->work->type === 'novel' ? '📖 Novel' : '🎨 Comic' }}
                        </span>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Status</div>
                        <span style="display: inline-block; padding: 4px 12px; background: {{ $chapterImage->chapter->work->status === 'published' ? '#d4edda' : '#f8d7da' }}; color: {{ $chapterImage->chapter->work->status === 'published' ? '#155724' : '#721c24' }}; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            {{ ucfirst($chapterImage->chapter->work->status) }}
                        </span>
                    </div>

                    @if($chapterImage->chapter->work->genres->count() > 0)
                        <div>
                            <div style="font-size: 12px; color: #6c757d; margin-bottom: 8px;">Genre</div>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                @foreach($chapterImage->chapter->work->genres as $genre)
                                    <span style="padding: 4px 10px; background: rgba(45, 139, 115, 0.1); color: #2d8b73; border-radius: 12px; font-size: 11px; font-weight: 600;">
                                        {{ $genre->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Chapter Info Card -->
                <div style="background: white; border-radius: 12px; padding: 20px; border: 1px solid #dee2e6; margin-bottom: 20px;">
                    <h4 style="color: #1e5f4f; margin-bottom: 16px; font-size: 16px;">📖 Informasi Chapter</h4>
                    
                    <div style="margin-bottom: 12px;">
                        <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Nomor Chapter</div>
                        <div style="font-weight: 600; color: #1e5f4f; font-size: 18px;">
                            Chapter {{ $chapterImage->chapter->chapter_number }}
                        </div>
                    </div>

                    @if($chapterImage->chapter->title)
                        <div style="margin-bottom: 12px;">
                            <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Judul Chapter</div>
                            <div style="color: #495057; font-size: 14px;">
                                {{ $chapterImage->chapter->title }}
                            </div>
                        </div>
                    @endif

                    <div style="margin-bottom: 12px;">
                        <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Total Gambar Chapter</div>
                        <div style="color: #495057; font-size: 14px; font-weight: 600;">
                            {{ $chapterImage->chapter->images->count() }} gambar
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Dibuat</div>
                        <div style="color: #495057; font-size: 13px;">
                            {{ $chapterImage->chapter->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Uploader Info Card -->
                <div style="background: white; border-radius: 12px; padding: 20px; border: 1px solid #dee2e6; margin-bottom: 20px;">
                    <h4 style="color: #1e5f4f; margin-bottom: 16px; font-size: 16px;">👤 Informasi Uploader</h4>
                    
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #48c9b0, #5fd4bd); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 20px;">
                            {{ strtoupper(substr($chapterImage->chapter->work->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #1e5f4f; font-size: 15px;">
                                {{ $chapterImage->chapter->work->user->name }}
                            </div>
                            <div style="font-size: 12px; color: #6c757d;">
                                {{ $chapterImage->chapter->work->user->email }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Total Karya</div>
                        <div style="color: #495057; font-size: 14px; font-weight: 600;">
                            {{ $chapterImage->chapter->work->user->works->count() }} karya
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div style="background: white; border-radius: 12px; padding: 20px; border: 1px solid #dee2e6;">
                    <h4 style="color: #1e5f4f; margin-bottom: 16px; font-size: 16px;">⚡ Actions</h4>
                    
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="{{ route('admin.works.show', $chapterImage->chapter->work) }}" 
                           style="padding: 12px; background: #17a2b8; color: white; text-decoration: none; border-radius: 6px; text-align: center; font-weight: 600; transition: all 0.3s;"
                           onmouseover="this.style.background='#138496'"
                           onmouseout="this.style.background='#17a2b8'">
                            📚 Lihat Karya
                        </a>

                        <a href="{{ route('admin.chapters.show', $chapterImage->chapter) }}" 
                           style="padding: 12px; background: #28a745; color: white; text-decoration: none; border-radius: 6px; text-align: center; font-weight: 600; transition: all 0.3s;"
                           onmouseover="this.style.background='#218838'"
                           onmouseout="this.style.background='#28a745'">
                            📖 Lihat Chapter
                        </a>

                        <form action="{{ route('admin.chapter-images.destroy', $chapterImage) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin ingin menghapus gambar ini? Tindakan ini tidak dapat dibatalkan!')" 
                                    style="width: 100%; padding: 12px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s;"
                                    onmouseover="this.style.background='#c82333'"
                                    onmouseout="this.style.background='#dc3545'">
                                🗑️ Hapus Gambar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            div[style*="grid-template-columns: 1fr 400px"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection