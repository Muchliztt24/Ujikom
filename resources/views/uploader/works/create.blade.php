@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Tambah Karya Baru</h1>
        <p>Buat karya manga atau novel baru</p>
    </div>

    <div class="content-body">
        <form action="{{ route('works.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="max-width: 800px;">
                <!-- Judul -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #1e5f4f; font-weight: 600;">
                        Judul Karya <span style="color: #dc3545;">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           class="form-control" 
                           required
                           style="width: 100%; padding: 12px; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px; transition: all 0.3s;"
                           onfocus="this.style.borderColor='#48c9b0'; this.style.boxShadow='0 0 0 3px rgba(72, 201, 176, 0.1)'"
                           onblur="this.style.borderColor='#dee2e6'; this.style.boxShadow='none'"
                           placeholder="Masukkan judul karya">
                    @error('title')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #1e5f4f; font-weight: 600;">
                        Deskripsi
                    </label>
                    <textarea name="description" 
                              rows="5"
                              class="form-control"
                              style="width: 100%; padding: 12px; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px; resize: vertical; transition: all 0.3s;"
                              onfocus="this.style.borderColor='#48c9b0'; this.style.boxShadow='0 0 0 3px rgba(72, 201, 176, 0.1)'"
                              onblur="this.style.borderColor='#dee2e6'; this.style.boxShadow='none'"
                              placeholder="Tuliskan deskripsi singkat tentang karya ini..."></textarea>
                    @error('description')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Type -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #1e5f4f; font-weight: 600;">
                        Tipe Karya <span style="color: #dc3545;">*</span>
                    </label>
                    <select name="type" 
                            class="form-control" 
                            required
                            style="width: 100%; padding: 12px; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px; background: white; cursor: pointer; transition: all 0.3s;"
                            onfocus="this.style.borderColor='#48c9b0'; this.style.boxShadow='0 0 0 3px rgba(72, 201, 176, 0.1)'"
                            onblur="this.style.borderColor='#dee2e6'; this.style.boxShadow='none'">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="comic">📚 Comic / Manga</option>
                        <option value="novel">📖 Novel</option>
                    </select>
                    @error('type')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Cover -->
                <div style="margin-bottom: 30px;">
                    <label style="display: block; margin-bottom: 8px; color: #1e5f4f; font-weight: 600;">
                        Cover Image
                    </label>
                    <div style="border: 2px dashed #dee2e6; border-radius: 8px; padding: 20px; text-align: center; background: #f8f9fa; transition: all 0.3s;"
                         onmouseover="this.style.borderColor='#48c9b0'; this.style.background='#f0faf8'"
                         onmouseout="this.style.borderColor='#dee2e6'; this.style.background='#f8f9fa'">
                        <input type="file" 
                               name="cover" 
                               class="form-control"
                               accept="image/*"
                               id="coverInput"
                               style="display: none;"
                               onchange="previewCover(event)">
                        <div id="coverPreview" style="margin-bottom: 15px; display: none;">
                            <img id="previewImage" style="max-width: 200px; max-height: 300px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        </div>
                        <button type="button" 
                                onclick="document.getElementById('coverInput').click()"
                                style="padding: 10px 20px; background: #48c9b0; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; transition: all 0.3s;"
                                onmouseover="this.style.background='#3db89e'"
                                onmouseout="this.style.background='#48c9b0'">
                            📷 Pilih Cover
                        </button>
                        <p style="color: #6c757d; font-size: 13px; margin-top: 10px; margin-bottom: 0;">
                            Format: JPG, PNG, JPEG (Max 2MB)
                        </p>
                    </div>
                    @error('cover')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 10px; padding-top: 20px; border-top: 1px solid #dee2e6;">
                    <button type="submit" 
                            style="padding: 12px 30px; background: #48c9b0; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                            onmouseover="this.style.background='#3db89e'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(72, 201, 176, 0.3)'"
                            onmouseout="this.style.background='#48c9b0'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        ✓ Simpan Karya
                    </button>
                    <a href="{{ route('works.index') }}" 
                       style="padding: 12px 30px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block; transition: all 0.3s;"
                       onmouseover="this.style.background='#5a6268'"
                       onmouseout="this.style.background='#6c757d'">
                        ✗ Batal
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        function previewCover(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('coverPreview').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection