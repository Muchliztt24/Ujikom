@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Tambah Chapter Baru</h1>
        <p>Untuk karya: <strong style="color: #48c9b0;">{{ $work->title }}</strong></p>
    </div>

    <div class="content-body">
        <form action="{{ route('works.chapters.store', $work) }}" method="POST">
            @csrf

            <div style="max-width: 800px;">
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
                           style="width: 200px; padding: 12px; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px; transition: all 0.3s;"
                           onfocus="this.style.borderColor='#48c9b0'; this.style.boxShadow='0 0 0 3px rgba(72, 201, 176, 0.1)'"
                           onblur="this.style.borderColor='#dee2e6'; this.style.boxShadow='none'"
                           placeholder="1">
                    @error('chapter_number')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                    <small style="color: #6c757d; font-size: 13px; margin-top: 5px; display: block;">
                        Masukkan nomor urut chapter (contoh: 1, 2, 3, dst)
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
                           style="width: 100%; padding: 12px; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px; transition: all 0.3s;"
                           onfocus="this.style.borderColor='#48c9b0'; this.style.boxShadow='0 0 0 3px rgba(72, 201, 176, 0.1)'"
                           onblur="this.style.borderColor='#dee2e6'; this.style.boxShadow='none'"
                           placeholder="Contoh: Pertemuan Pertama">
                    @error('title')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                    <small style="color: #6c757d; font-size: 13px; margin-top: 5px; display: block;">
                        Kosongkan jika chapter tidak memiliki judul khusus
                    </small>
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
                              placeholder="Tuliskan isi chapter di sini...

Khusus untuk Novel. 
Jika ini adalah Comic/Manga, kosongkan field ini dan upload gambar setelah chapter dibuat."></textarea>
                    @error('text_content')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                    <small style="color: #6c757d; font-size: 13px; margin-top: 5px; display: block;">
                        💡 <strong>Tips:</strong> Kosongkan jika ini adalah Comic/Manga. Anda bisa upload gambar setelah chapter dibuat.
                    </small>
                </div>

                <!-- Info Box -->
                <div style="padding: 15px; background: #e7f3ff; border-left: 4px solid #17a2b8; border-radius: 6px; margin-bottom: 25px;">
                    <div style="display: flex; gap: 10px; align-items: start;">
                        <span style="font-size: 24px;">ℹ️</span>
                        <div>
                            <strong style="color: #004085; display: block; margin-bottom: 5px;">Catatan Penting:</strong>
                            <ul style="margin: 0; padding-left: 20px; color: #004085; font-size: 14px; line-height: 1.6;">
                                <li>Untuk <strong>Novel</strong>: Isi field "Isi Chapter" dengan text cerita</li>
                                <li>Untuk <strong>Comic/Manga</strong>: Kosongkan "Isi Chapter", lalu upload gambar setelah chapter tersimpan</li>
                                <li>Nomor chapter harus unik untuk setiap karya</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 10px; padding-top: 20px; border-top: 1px solid #dee2e6;">
                    <button type="submit" 
                            style="padding: 12px 30px; background: #48c9b0; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                            onmouseover="this.style.background='#3db89e'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(72, 201, 176, 0.3)'"
                            onmouseout="this.style.background='#48c9b0'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        ✓ Simpan Chapter
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