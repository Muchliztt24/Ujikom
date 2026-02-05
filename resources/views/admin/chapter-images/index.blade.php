@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Kelola Gambar Chapter</h1>
        <p>Monitor dan kelola semua gambar chapter dari seluruh karya</p>
    </div>

    <div class="content-body">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #1e5f4f; margin: 0;">Semua Gambar Chapter</h3>
            <div style="color: #6c757d; font-size: 14px;">
                Total: <strong style="color: #48c9b0;">{{ $images->total() }}</strong> gambar
            </div>
        </div>

        @if (session('success'))
            <div style="padding: 15px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #28a745;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background: linear-gradient(135deg, #48c9b0, #5fd4bd); padding: 20px; border-radius: 12px; color: white;">
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Gambar</div>
                <div style="font-size: 32px; font-weight: 700;">{{ $images->total() }}</div>
            </div>
            <div style="background: linear-gradient(135deg, #2d8b73, #48c9b0); padding: 20px; border-radius: 12px; color: white;">
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Halaman Ini</div>
                <div style="font-size: 32px; font-weight: 700;">{{ $images->count() }}</div>
            </div>
            <div style="background: linear-gradient(135deg, #1e5f4f, #2d8b73); padding: 20px; border-radius: 12px; color: white;">
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Per Halaman</div>
                <div style="font-size: 32px; font-weight: 700;">20</div>
            </div>
        </div>

        <!-- Images Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            @forelse($images as $image)
                <div style="background: white; border-radius: 12px; overflow: hidden; border: 1px solid #dee2e6; transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.1)'"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    
                    <!-- Image Preview -->
                    <div style="position: relative;">
                        <img src="{{ asset('storage/' . $image->image_url) }}" 
                             alt="Page {{ $image->page_number }}"
                             style="width: 100%; height: 280px; object-fit: cover; cursor: pointer;"
                             onclick="window.location.href='{{ route('admin.chapter-images.show', $image) }}'">
                        
                        <!-- Page Badge -->
                        <div style="position: absolute; top: 10px; left: 10px; background: rgba(45, 139, 115, 0.95); color: white; padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 13px;">
                            Page {{ $image->page_number }}
                        </div>
                    </div>

                    <!-- Info -->
                    <div style="padding: 16px;">
                        <!-- Work Title -->
                        <div style="font-weight: 600; color: #1e5f4f; margin-bottom: 6px; font-size: 14px; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $image->chapter->work->title }}
                        </div>

                        <!-- Chapter Info -->
                        <div style="font-size: 13px; color: #6c757d; margin-bottom: 12px;">
                            Chapter {{ $image->chapter->chapter_number }}
                            @if($image->chapter->title)
                                <div style="font-size: 12px; color: #9aa0a6; margin-top: 2px;">
                                    {{ Str::limit($image->chapter->title, 20) }}
                                </div>
                            @endif
                        </div>

                        <!-- Uploader Info -->
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; padding: 8px; background: #f8f9fa; border-radius: 6px;">
                            <div style="width: 24px; height: 24px; background: #48c9b0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 11px;">
                                {{ strtoupper(substr($image->chapter->work->user->name, 0, 1)) }}
                            </div>
                            <div style="flex: 1; font-size: 12px; color: #495057;">
                                {{ $image->chapter->work->user->name }}
                            </div>
                        </div>

                        <!-- Actions -->
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('admin.chapter-images.show', $image) }}" 
                               style="flex: 1; padding: 8px; background: #17a2b8; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; text-align: center;">
                                👁️ Lihat
                            </a>
                            <form action="{{ route('admin.chapter-images.destroy', $image) }}" method="POST" style="flex: 1;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin ingin menghapus gambar ini?')" 
                                        style="width: 100%; padding: 8px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 60px; color: #6c757d;">
                    <div style="font-size: 64px; margin-bottom: 16px;">🖼️</div>
                    <h3 style="color: #495057; margin-bottom: 8px;">Belum Ada Gambar</h3>
                    <p style="color: #6c757d;">Gambar chapter akan muncul di sini setelah uploader mengunggahnya</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($images->hasPages())
            <div style="margin-top: 30px;">
                {{ $images->links() }}
            </div>
        @endif
    </div>
@endsection