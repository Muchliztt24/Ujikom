@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Edit Gambar Chapter</h1>
        <p>
            <strong style="color: #48c9b0;">{{ $chapter->work->title }}</strong> - 
            Chapter {{ $chapter->chapter_number }}
            @if($chapter->title)
                ({{ $chapter->title }})
            @endif
        </p>
    </div>

    <div class="content-body">
        <form action="{{ route('chapters.images.update', [$chapter, $image]) }}" 
              method="POST" 
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="max-width: 800px;">
                <!-- Current Image Preview -->
                <div style="margin-bottom: 25px; padding: 20px; background: #f8f9fa; border-radius: 10px; border: 1px solid #dee2e6;">
                    <h4 style="color: #1e5f4f; margin-bottom: 15px; font-size: 16px;">
                        📷 Gambar Saat Ini
                    </h4>
                    <div style="display: flex; gap: 20px; align-items: start; flex-wrap: wrap;">
                        <img src="{{ asset('storage/' . $image->image_url) }}" 
                             style="max-width: 300px; max-height: 400px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); object-fit: contain;">
                        <div style="flex: 1; min-width: 250px;">
                            <div style="margin-bottom: 12px;">
                                <span style="display: inline-block; padding: 6px 12px; background: #48c9b0; color: white; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                    Page {{ $image->page_number }}
                                </span>
                            </div>
                            <p style="color: #6c757d; font-size: 14px; line-height: 1.6; margin: 0;">
                                Upload gambar baru untuk mengganti gambar yang sedang ditampilkan. 
                                Jika tidak upload gambar baru, gambar lama akan tetap digunakan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Page Number -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #1e5f4f; font-weight: 600;">
                        Nomor Halaman <span style="color: #dc3545;">*</span>
                    </label>
                    <input type="number" 
                           name="page_number" 
                           class="form-control" 
                           required
                           min="1"
                           value="{{ old('page_number', $image->page_number) }}"
                           style="width: 200px; padding: 12px; border: 1px solid #dee2e6; border-radius: 6px; font-size: 14px; transition: all 0.3s;"
                           onfocus="this.style.borderColor='#48c9b0'; this.style.boxShadow='0 0 0 3px rgba(72, 201, 176, 0.1)'"
                           onblur="this.style.borderColor='#dee2e6'; this.style.boxShadow='none'"
                           placeholder="1">
                    @error('page_number')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                    <small style="color: #6c757d; font-size: 13px; margin-top: 5px; display: block;">
                        Nomor urut halaman dalam chapter ini
                    </small>
                </div>

                <!-- Replace Image -->
                <div style="margin-bottom: 30px;">
                    <label style="display: block; margin-bottom: 12px; color: #1e5f4f; font-weight: 600;">
                        Ganti Gambar <span style="color: #6c757d; font-size: 13px; font-weight: 400;">(opsional)</span>
                    </label>
                    
                    <div style="border: 2px dashed #dee2e6; border-radius: 8px; padding: 30px; text-align: center; background: #f8f9fa; transition: all 0.3s;"
                         onmouseover="this.style.borderColor='#48c9b0'; this.style.background='#f0faf8'"
                         onmouseout="this.style.borderColor='#dee2e6'; this.style.background='#f8f9fa'">
                        
                        <input type="file" 
                               name="image" 
                               class="form-control"
                               accept="image/*"
                               id="imageInput"
                               style="display: none;"
                               onchange="previewImage(event)">
                        
                        <div id="newImagePreview" style="margin-bottom: 15px; display: none;">
                            <img id="previewImg" style="max-width: 250px; max-height: 350px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); object-fit: contain;">
                            <p style="color: #48c9b0; font-size: 13px; margin-top: 10px; font-weight: 600;">
                                ✓ Gambar Baru (Preview)
                            </p>
                        </div>
                        
                        <button type="button" 
                                onclick="document.getElementById('imageInput').click()"
                                style="padding: 12px 30px; background: #48c9b0; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 15px; transition: all 0.3s;"
                                onmouseover="this.style.background='#3db89e'; this.style.transform='translateY(-2px)'"
                                onmouseout="this.style.background='#48c9b0'; this.style.transform='translateY(0)'">
                            📁 Pilih Gambar Baru
                        </button>
                        
                        <p style="color: #6c757d; font-size: 13px; margin: 12px 0 0 0;">
                            Format: JPG, PNG, JPEG, GIF (Max 5MB)
                        </p>
                    </div>

                    @error('image')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 8px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Info Box -->
                <div style="padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 6px; margin-bottom: 25px;">
                    <div style="display: flex; gap: 10px; align-items: start;">
                        <span style="font-size: 24px;">💡</span>
                        <div>
                            <strong style="color: #856404; display: block; margin-bottom: 5px;">Catatan:</strong>
                            <ul style="margin: 0; padding-left: 20px; color: #856404; font-size: 14px; line-height: 1.6;">
                                <li>Jika tidak upload gambar baru, gambar lama tetap digunakan</li>
                                <li>Upload gambar baru akan menggantikan gambar lama secara permanen</li>
                                <li>Pastikan nomor halaman tidak duplikat dengan gambar lain di chapter ini</li>
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
                        ✓ Update Gambar
                    </button>
                    <a href="{{ route('chapters.images.index', $chapter) }}" 
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
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('newImagePreview').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection