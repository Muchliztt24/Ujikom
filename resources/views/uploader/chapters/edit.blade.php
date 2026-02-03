@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Edit Chapter</h1>
        <p>Untuk karya: <strong style="color: #48c9b0;">{{ $work->title }}</strong></p>
    </div>

    <div class="content-body">
        <form action="{{ route('works.chapters.update', [$work, $chapter]) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="max-width: 800px;">
                <!-- Info Current Chapter -->
                <div style="padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 6px; margin-bottom: 25px;">
                    <div style="display: flex; gap: 10px; align-items: start;">
                        <span style="font-size: 24px;">📝</span>
                        <div>
                            <strong style="color: #856404; display: block; margin-bottom: 5px;">
                                Sedang Mengedit: Chapter {{ $chapter->chapter_number }}
                            </strong>
                            <p style="margin: 0; color: #856404; font-size: 14px;">
                                @if($chapter->title)
                                    {{ $chapter->title }}
                                @else
                                    (Tanpa Judul)
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Nomor Chapter -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #1e5f4f; font-weight: 600;">
                        Nomor Chapter <span style="color: #dc3545;">*</span>
                    </label>
                    <input type="number" 
                           name="chapter_number" 
                           class="form-control" 
                           required
                           min="1"
                           value="{{ old('chapter_number', $chapter->chapter_number) }}"
                           style="width: 200px; padding: 12px; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px; transition: all 0.3s;"
                           onfocus="this.style.borderColor='#48c9b0'; this.style.boxShadow='0 0 0 3px rgba(72, 201, 176, 0.1)'"
                           onblur="this.style.borderColor='#dee2e6'; this.style.boxShadow='none'"
                           placeholder="1">
                    @error('chapter_number')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                    <small style="color: #6c757d; font-size: 13px; margin-top: 5px; display: block;">
                        ⚠️ Hati-hati mengubah nomor chapter, pastikan tidak duplikat
                    </small>
                </div>

                <!-- Judul Chapter -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #1e5f4f; font-weight: 600;">
                        Judul Chapter <span style="color: #6c757d; font-size: 13px; font-weight: 400;">(opsional)</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           class="form-control"
                           value="{{ old('title', $chapter->title) }}"
                           style="width: 100%; padding: 12px; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px; transition: all 0.3s;"
                           onfocus="this.style.borderColor='#48c9b0'; this.style.boxShadow='0 0 0 3px rgba(72, 201, 176, 0.1)'"
                           onblur="this.style.borderColor='#dee2e6'; this.style.boxShadow='none'"
                           placeholder="Contoh: Pertemuan Pertama">
                    @error('title')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Isi Chapter (untuk Novel) -->
                <div style="margin-bottom: 30px;">
                    <label style="display: block; margin-bottom: 8px; color: #1e5f4f; font-weight: 600;">
                        Isi Chapter <span style="color: #6c757d; font-size: 13px; font-weight: 400;">(Text untuk Novel)</span>
                    </label>
                    <textarea name="text_content" 
                              rows="12"
                              class="form-control"
                              style="width: 100%; padding: 12px; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px; resize: vertical; font-family: 'Courier New', monospace; line-height: 1.6; transition: all 0.3s;"
                              onfocus="this.style.borderColor='#48c9b0'; this.style.boxShadow='0 0 0 3px rgba(72, 201, 176, 0.1)'"
                              onblur="this.style.borderColor='#dee2e6'; this.style.boxShadow='none'"
                              placeholder="Tuliskan isi chapter di sini...">{{ old('text_content', $chapter->text_content) }}</textarea>
                    @error('text_content')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                    
                    @if($chapter->text_content)
                        <div style="margin-top: 8px; padding: 10px; background: #d4edda; border-radius: 6px; border-left: 4px solid #28a745;">
                            <small style="color: #155724; font-size: 13px;">
                                ✓ Chapter ini memiliki text content ({{ strlen($chapter->text_content) }} karakter)
                            </small>
                        </div>
                    @else
                        <small style="color: #6c757d; font-size: 13px; margin-top: 5px; display: block;">
                            💡 Kosongkan jika ini adalah Comic/Manga
                        </small>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 25px; border: 1px solid #dee2e6;">
                    <strong style="color: #495057; display: block; margin-bottom: 10px; font-size: 14px;">
                        🔗 Quick Actions:
                    </strong>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="{{ route('chapters.images.index', $chapter) }}" 
                           style="padding: 8px 16px; background: #17a2b8; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 500; transition: all 0.3s;"
                           onmouseover="this.style.background='#138496'"
                           onmouseout="this.style.background='#17a2b8'">
                            🖼️ Kelola Gambar Chapter
                        </a>
                        <a href="{{ route('works.chapters.index', $work) }}" 
                           style="padding: 8px 16px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 500; transition: all 0.3s;"
                           onmouseover="this.style.background='#5a6268'"
                           onmouseout="this.style.background='#6c757d'">
                            📚 Lihat Semua Chapter
                        </a>
                    </div>
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 10px; padding-top: 20px; border-top: 1px solid #dee2e6;">
                    <button type="submit" 
                            style="padding: 12px 30px; background: #48c9b0; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                            onmouseover="this.style.background='#3db89e'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(72, 201, 176, 0.3)'"
                            onmouseout="this.style.background='#48c9b0'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        ✓ Update Chapter
                    </button>
                    <a href="{{ route('works.chapters.index', $work) }}" 
                       style="padding: 12px 30px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block; transition: all 0.3s;"
                       onmouseover="this.style.background='#5a6268'"
                       onmouseout="this.style.background='#6c757d'">
                        ✗ Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection