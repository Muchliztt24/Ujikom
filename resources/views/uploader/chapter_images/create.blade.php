@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <h1>Upload Gambar Chapter</h1>
        <p>
            <strong style="color: #48c9b0;">{{ $chapter->work->title }}</strong> - 
            Chapter {{ $chapter->chapter_number }}
            @if($chapter->title)
                ({{ $chapter->title }})
            @endif
        </p>
    </div>

    <div class="content-body">
        <form action="{{ route('chapters.images.store', $chapter) }}" 
              method="POST" 
              enctype="multipart/form-data">
            @csrf

            <div style="max-width: 900px;">
                <!-- Info Box -->
                <div style="padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 6px; margin-bottom: 25px;">
                    <div style="display: flex; gap: 10px; align-items: start;">
                        <span style="font-size: 24px;">💡</span>
                        <div>
                            <strong style="color: #856404; display: block; margin-bottom: 5px;">Tips Upload:</strong>
                            <ul style="margin: 0; padding-left: 20px; color: #856404; font-size: 14px; line-height: 1.6;">
                                <li>Anda bisa upload <strong>multiple gambar sekaligus</strong></li>
                                <li>Gambar akan otomatis diurutkan berdasarkan nama file</li>
                                <li>Format yang didukung: JPG, PNG, JPEG, GIF</li>
                                <li>Ukuran maksimal per file: 5MB</li>
                                <li>Pastikan gambar sudah diurutkan sesuai halaman sebelum upload</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Upload Area -->
                <div style="margin-bottom: 30px;">
                    <label style="display: block; margin-bottom: 12px; color: #1e5f4f; font-weight: 600; font-size: 16px;">
                        Pilih Gambar Chapter <span style="color: #dc3545;">*</span>
                    </label>
                    
                    <div style="border: 3px dashed #48c9b0; border-radius: 12px; padding: 40px; text-align: center; background: linear-gradient(135deg, #f0faf8 0%, #ffffff 100%); transition: all 0.3s;"
                         id="dropZone"
                         onmouseover="this.style.borderColor='#2d8b73'; this.style.background='linear-gradient(135deg, #e6f5f1 0%, #ffffff 100%)'"
                         onmouseout="this.style.borderColor='#48c9b0'; this.style.background='linear-gradient(135deg, #f0faf8 0%, #ffffff 100%)'">
                        
                        <input type="file" 
                               name="images[]" 
                               class="form-control"
                               accept="image/*"
                               multiple
                               required
                               id="imageInput"
                               style="display: none;"
                               onchange="previewImages(event)">
                        
                        <div id="uploadIcon" style="margin-bottom: 20px;">
                            <svg width="80" height="80" viewBox="0 0 80 80" fill="none" style="margin: 0 auto;">
                                <circle cx="40" cy="40" r="38" fill="#48c9b0" opacity="0.1"/>
                                <path d="M40 20V60M20 40H60" stroke="#48c9b0" stroke-width="4" stroke-linecap="round"/>
                                <path d="M30 35L40 25L50 35" stroke="#48c9b0" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        
                        <button type="button" 
                                onclick="document.getElementById('imageInput').click()"
                                style="padding: 15px 40px; background: #48c9b0; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 16px; transition: all 0.3s; margin-bottom: 15px;"
                                onmouseover="this.style.background='#3db89e'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(72, 201, 176, 0.4)'"
                                onmouseout="this.style.background='#48c9b0'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            📁 Pilih Gambar
                        </button>
                        
                        <p style="color: #1e5f4f; font-size: 15px; margin: 10px 0 5px 0; font-weight: 500;">
                            atau drag & drop gambar ke sini
                        </p>
                        <p style="color: #6c757d; font-size: 13px; margin: 0;">
                            Bisa pilih banyak file sekaligus (Ctrl+Click atau Shift+Click)
                        </p>
                    </div>

                    @error('images')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 8px; display: block;">{{ $message }}</small>
                    @enderror
                    @error('images.*')
                        <small style="color: #dc3545; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Preview Area -->
                <div id="previewContainer" style="display: none; margin-bottom: 30px;">
                    <div style="background: white; border: 1px solid #dee2e6; border-radius: 8px; padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h4 style="color: #1e5f4f; margin: 0; font-size: 16px;">
                                📋 Preview Gambar yang Akan Diupload
                            </h4>
                            <span id="fileCount" style="padding: 6px 12px; background: #48c9b0; color: white; border-radius: 20px; font-size: 13px; font-weight: 600;"></span>
                        </div>
                        <div id="previewGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; max-height: 400px; overflow-y: auto; padding: 10px;"></div>
                    </div>
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 10px; padding-top: 20px; border-top: 1px solid #dee2e6;">
                    <button type="submit" 
                            id="submitBtn"
                            disabled
                            style="padding: 14px 35px; background: #48c9b0; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 15px; transition: all 0.3s; opacity: 0.5;"
                            onmouseover="if(!this.disabled) { this.style.background='#3db89e'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(72, 201, 176, 0.3)'; }"
                            onmouseout="if(!this.disabled) { this.style.background='#48c9b0'; this.style.transform='translateY(0)'; this.style.boxShadow='none'; }">
                        ⬆️ Upload Gambar
                    </button>
                    <a href="{{ route('chapters.images.index', $chapter) }}" 
                       style="padding: 14px 35px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block; font-size: 15px; transition: all 0.3s;"
                       onmouseover="this.style.background='#5a6268'"
                       onmouseout="this.style.background='#6c757d'">
                        ✗ Batal
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        const dropZone = document.getElementById('dropZone');
        const imageInput = document.getElementById('imageInput');
        const previewContainer = document.getElementById('previewContainer');
        const previewGrid = document.getElementById('previewGrid');
        const fileCount = document.getElementById('fileCount');
        const submitBtn = document.getElementById('submitBtn');

        // Drag and Drop
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#2d8b73';
            dropZone.style.background = 'linear-gradient(135deg, #e6f5f1 0%, #ffffff 100%)';
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#48c9b0';
            dropZone.style.background = 'linear-gradient(135deg, #f0faf8 0%, #ffffff 100%)';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#48c9b0';
            dropZone.style.background = 'linear-gradient(135deg, #f0faf8 0%, #ffffff 100%)';
            
            const files = e.dataTransfer.files;
            imageInput.files = files;
            previewImages({ target: { files: files } });
        });

        // Preview Images
        function previewImages(event) {
            const files = event.target.files;
            
            if (files.length === 0) {
                previewContainer.style.display = 'none';
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                return;
            }

            previewContainer.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
            
            previewGrid.innerHTML = '';
            fileCount.textContent = files.length + ' file';

            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.style.cssText = 'position: relative; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s;';
                    div.onmouseover = function() { this.style.transform = 'scale(1.05)'; this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.2)'; };
                    div.onmouseout = function() { this.style.transform = 'scale(1)'; this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)'; };
                    
                    div.innerHTML = `
                        <img src="${e.target.result}" style="width: 100%; height: 150px; object-fit: cover;">
                        <div style="position: absolute; top: 5px; left: 5px; background: rgba(72, 201, 176, 0.9); color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                            #${index + 1}
                        </div>
                        <div style="padding: 8px; background: rgba(0,0,0,0.7); color: white; font-size: 11px; text-align: center; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            ${file.name}
                        </div>
                    `;
                    previewGrid.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }
    </script>
@endsection