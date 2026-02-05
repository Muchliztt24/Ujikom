@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Edit Karya</h1>
        <p>Perbarui informasi karya: <strong style="color: #48c9b0;">{{ $work->title }}</strong></p>
    </div>

    <div class="content-body">
        <form action="{{ route('works.update', $work) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                           value="{{ old('title', $work->title) }}"
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
                              placeholder="Tuliskan deskripsi singkat tentang karya ini...">{{ old('description', $work->description) }}</textarea>
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
                        <option value="comic" {{ old('type', $work->type) == 'comic' ? 'selected' : '' }}>📚 Comic / Manga</option>
                        <option value="novel" {{ old('type', $work->type) == 'novel' ? 'selected' : '' }}>📖 Novel</option>
                    </select>
                    @error('type')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Genre -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 12px; color: #1e5f4f; font-weight: 600;">
                        Genre <span style="color: #dc3545;">*</span>
                    </label>
                    <div style="padding: 16px; border: 1px solid #dee2e6; border-radius: 8px; background: #f8f9fa;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px;">
                            @php
                                $genres = \App\Models\Genre::all();
                                $selectedGenres = old('genre_ids', $work->genres->pluck('id')->toArray());
                            @endphp
                            @foreach($genres as $genre)
                                <label style="display: flex; align-items: center; gap: 8px; padding: 10px; background: {{ in_array($genre->id, $selectedGenres) ? '#e8f5e9' : 'white' }}; border: 1px solid {{ in_array($genre->id, $selectedGenres) ? '#48c9b0' : '#dee2e6' }}; border-radius: 6px; cursor: pointer; transition: all 0.3s;"
                                       onmouseover="if (!this.querySelector('input').checked) { this.style.borderColor='#48c9b0'; this.style.background='#f0faf8' }"
                                       onmouseout="if (!this.querySelector('input').checked) { this.style.borderColor='#dee2e6'; this.style.background='white' }">
                                    <input type="checkbox" 
                                           name="genre_ids[]" 
                                           value="{{ $genre->id }}"
                                           {{ in_array($genre->id, $selectedGenres) ? 'checked' : '' }}
                                           style="width: 18px; height: 18px; cursor: pointer;"
                                           onchange="this.closest('label').style.background = this.checked ? '#e8f5e9' : 'white'; this.closest('label').style.borderColor = this.checked ? '#48c9b0' : '#dee2e6';">
                                    <span style="font-size: 14px; color: #495057;">{{ $genre->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @error('genre_ids')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                    <small style="color: #6c757d; font-size: 13px; margin-top: 5px; display: block;">
                        Genre yang dipilih: <strong>{{ $work->genres->pluck('name')->implode(', ') ?: 'Belum ada' }}</strong>
                    </small>
                </div>

                <!-- Cover -->
                <div style="margin-bottom: 30px;">
                    <label style="display: block; margin-bottom: 8px; color: #1e5f4f; font-weight: 600;">
                        Cover Image
                    </label>
                    
                    @if($work->cover)
                        <div style="margin-bottom: 15px; padding: 15px; background: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <img src="{{ asset('storage/' . $work->cover) }}" 
                                     style="width: 100px; height: 140px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                <div style="flex: 1;">
                                    <p style="color: #495057; font-size: 14px; margin: 0 0 8px 0; font-weight: 500;">
                                        📷 Cover Saat Ini
                                    </p>
                                    <p style="color: #6c757d; font-size: 13px; margin: 0;">
                                        Upload gambar baru untuk mengganti cover yang ada
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

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
                            <p style="color: #48c9b0; font-size: 13px; margin-top: 8px; font-weight: 600;">
                                ✓ Cover Baru (Belum Tersimpan)
                            </p>
                        </div>
                        <button type="button" 
                                onclick="document.getElementById('coverInput').click()"
                                style="padding: 10px 20px; background: #48c9b0; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; transition: all 0.3s;"
                                onmouseover="this.style.background='#3db89e'"
                                onmouseout="this.style.background='#48c9b0'">
                            📷 {{ $work->cover ? 'Ganti Cover' : 'Pilih Cover' }}
                        </button>
                        <p style="color: #6c757d; font-size: 13px; margin-top: 10px; margin-bottom: 0;">
                            Format: JPG, PNG, JPEG (Max 2MB)
                        </p>
                    </div>
                    @error('cover')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Info Box -->
                <div style="padding: 15px; background: #e7f3ff; border-left: 4px solid #17a2b8; border-radius: 6px; margin-bottom: 25px;">
                    <div style="display: flex; gap: 10px; align-items: start;">
                        <span style="font-size: 24px;">ℹ️</span>
                        <div>
                            <strong style="color: #004085; display: block; margin-bottom: 5px;">Catatan:</strong>
                            <p style="margin: 0; color: #004085; font-size: 14px; line-height: 1.6;">
                                Mengubah informasi karya tidak akan mempengaruhi chapter dan gambar yang sudah ada. 
                                Jika tidak mengganti cover, cover lama akan tetap digunakan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 10px; padding-top: 20px; border-top: 1px solid #dee2e6;">
                    <button type="submit" 
                            style="padding: 12px 30px; background: #48c9b0; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                            onmouseover="this.style.background='#3db89e'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(72, 201, 176, 0.3)'"
                            onmouseout="this.style.background='#48c9b0'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        ✓ Update Karya
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