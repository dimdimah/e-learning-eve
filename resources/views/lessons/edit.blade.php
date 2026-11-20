<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                {{ __('Ubah Lesson: ') }} {{ $lesson->title }}
            </h2>
            <a href="{{ route('admin.courses.show', $course) }}"
               class="text-sm text-muted-foreground hover:text-foreground">
                &larr; Kembali ke Course
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="evaly-card">
            <div class="p-6">
                <form id="lesson-form"
                      action="{{ route('admin.courses.lessons.update', [$course, $lesson]) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="max-w-2xl space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Judul Materi -->
                    <div>
                        <label class="block text-foreground text-sm font-medium mb-2">
                            Judul Materi <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="title"
                               value="{{ old('title', $lesson->title) }}"
                               class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               required />
                        @error('title')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi Materi -->
                    <div>
                        <label class="block text-foreground text-sm font-medium mb-2">Deskripsi Materi</label>
                        <textarea name="description"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('description', $lesson->description) }}</textarea>
                        @error('description')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Urutan Lesson -->
                    <div>
                        <label class="block text-foreground text-sm font-medium mb-2">
                            Nomor Urutan (Order) <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               name="order_position"
                               value="{{ old('order_position', $lesson->order_position) }}"
                               min="1"
                               class="w-32 px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               required />
                        <small class="text-xs text-muted-foreground block mt-1">
                            Urutan tampil materi saat dibuka oleh student.
                        </small>
                        @error('order_position')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Upload Video (dengan Async Upload & Progress Bar) -->
                    <div class="border border-border rounded-xl p-4 bg-muted/30 space-y-3">
                        <label class="block text-foreground text-sm font-medium">
                            File Video Pembelajaran
                        </label>

                        @if ($lesson->video_path)
                            <div class="flex items-center justify-between p-3 bg-background border border-border rounded-lg text-sm">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <p class="font-medium text-foreground">{{ $lesson->video_original_name ?? 'video.mp4' }}</p>
                                        <span class="evaly-badge evaly-badge-green text-xs">Ready</span>
                                    </div>
                                </div>
                                <a href="{{ route('lessons.video', $lesson) }}" target="_blank" class="evaly-btn-secondary text-xs">
                                    Lihat Video
                                </a>
                            </div>
                        @else
                            <p class="text-xs text-muted-foreground italic">Belum ada file video yang diunggah.</p>
                        @endif

                        <div class="pt-2">
                            <label class="block text-xs font-medium text-muted-foreground mb-1">
                                {{ $lesson->video_path ? 'Ganti dengan file video baru (opsional):' : 'Pilih file video:' }}
                            </label>
                            <input type="file"
                                   id="video-input"
                                   name="video"
                                   accept="video/mp4,video/webm,video/quicktime,video/x-matroska"
                                   class="block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-primary-foreground hover:file:bg-primary/90 cursor-pointer" />
                        </div>

                        <!-- Hidden input untuk path video hasil async upload -->
                        <input type="hidden" id="uploaded_video_path" name="uploaded_video_path" value="" />
                        <input type="hidden" id="uploaded_video_name" name="uploaded_video_name" value="" />

                        <!-- Container Status & Progress Bar -->
                        <div id="upload-progress-container" class="hidden space-y-2 pt-2 border-t border-border">
                            <div class="flex items-center justify-between text-xs">
                                <span id="upload-status-label" class="font-medium text-foreground">Mengunggah file video...</span>
                                <span id="upload-percentage" class="text-muted-foreground font-mono">0%</span>
                            </div>

                            <div class="w-full h-2.5 bg-muted rounded-full overflow-hidden border border-border">
                                <div id="upload-progress-bar"
                                     class="h-full bg-primary rounded-full transition-all duration-150"
                                     style="width: 0%"></div>
                            </div>

                            <div id="upload-badge-wrapper" class="pt-1">
                                <span id="upload-badge" class="evaly-badge evaly-badge-yellow">Uploading...</span>
                            </div>
                        </div>

                        @error('video')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4 pt-4 border-t border-border">
                        <button type="submit"
                                id="btn-submit"
                                class="evaly-btn-primary">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.courses.show', $course) }}"
                           class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Async Video Upload with XMLHttpRequest -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const videoInput = document.getElementById('video-input');
            const progressContainer = document.getElementById('upload-progress-container');
            const progressBar = document.getElementById('upload-progress-bar');
            const percentageLabel = document.getElementById('upload-percentage');
            const statusLabel = document.getElementById('upload-status-label');
            const badge = document.getElementById('upload-badge');
            const hiddenPath = document.getElementById('uploaded_video_path');
            const hiddenName = document.getElementById('uploaded_video_name');
            const submitBtn = document.getElementById('btn-submit');

            videoInput.addEventListener('change', function () {
                const file = videoInput.files[0];
                if (!file) return;

                progressContainer.classList.remove('hidden');
                progressBar.style.width = '0%';
                percentageLabel.textContent = '0%';
                statusLabel.textContent = 'Mengunggah: ' + file.name;
                badge.className = 'evaly-badge evaly-badge-yellow';
                badge.textContent = 'Uploading...';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50');

                const formData = new FormData();
                formData.append('video', file);
                formData.append('_token', '{{ csrf_token() }}');

                // kirim via XMLHttpRequest untuk track live progress
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.courses.lessons.upload-async', $course) }}', true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.upload.onprogress = function (e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        progressBar.style.width = percent + '%';
                        percentageLabel.textContent = percent + '%';
                    }
                };

                xhr.onload = function () {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50');

                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const res = JSON.parse(xhr.responseText);
                            hiddenPath.value = res.video_path;
                            hiddenName.value = res.original_name;
                            progressBar.style.width = '100%';
                            percentageLabel.textContent = '100%';
                            statusLabel.textContent = 'Unggahan selesai: ' + res.original_name;
                            badge.className = 'evaly-badge evaly-badge-green';
                            badge.textContent = 'Ready (Tersimpan)';
                        } catch (err) {
                            badge.className = 'evaly-badge evaly-badge-red';
                            badge.textContent = 'Failed (Respon tidak valid)';
                        }
                    } else {
                        let errorMessage = 'Terjadi kesalahan saat mengunggah video.';
                        try {
                            const errRes = JSON.parse(xhr.responseText);
                            if (errRes.message) {
                                errorMessage = errRes.message;
                            } else if (errRes.errors && errRes.errors.video) {
                                errorMessage = errRes.errors.video.join(', ');
                            }
                        } catch (e) {
                            if (xhr.status === 413) {
                                errorMessage = 'Ukuran file video terlalu besar (melebihi batas server).';
                            } else if (xhr.status === 419) {
                                errorMessage = 'Sesi web telah berakhir (CSRF token expired). Silakan refresh halaman.';
                            }
                        }
                        badge.className = 'evaly-badge evaly-badge-red';
                        badge.textContent = 'Failed (Gagal: HTTP ' + xhr.status + ')';
                        statusLabel.textContent = errorMessage;
                    }
                };

                xhr.onerror = function () {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50');
                    badge.className = 'evaly-badge evaly-badge-red';
                    badge.textContent = 'Failed (Koneksi terputus)';
                    statusLabel.textContent = 'Gagal mengunggah video. Periksa koneksi Anda.';
                };

                xhr.send(formData);
            });
        });
    </script>
</x-app-layout>
