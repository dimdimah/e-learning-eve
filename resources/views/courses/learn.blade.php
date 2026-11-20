<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-foreground leading-tight">
                    {{ $course->title }}
                </h2>
                <p class="text-xs text-muted-foreground mt-0.5">
                    Materi Pembelajaran Kursus
                </p>
            </div>

            <a href="{{ route('my.courses') }}"
               class="evaly-btn-secondary text-sm">
                &larr; Kembali ke Kursus Saya
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if ($lessons->isEmpty())
            <div class="evaly-card p-12 text-center text-muted-foreground">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-muted-foreground mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <p class="font-medium text-foreground">Belum ada materi pembelajaran</p>
                <p class="text-sm mt-1">Materi untuk kursus ini sedang dipersiapkan oleh pengajar.</p>
                <div class="mt-6">
                    <a href="{{ route('my.courses') }}" class="evaly-btn-primary">
                        Lihat Kursus Lainnya
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kolom Kiri: Player Video & Rincian Materi Aktif -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Area Video Player -->
                    <div class="evaly-card overflow-hidden bg-black">
                        @if ($activeLesson && $activeLesson->video_path)
                            <video controls
                                   autoplay
                                   class="w-full aspect-video bg-black focus:outline-none"
                                   src="{{ route('lessons.video', $activeLesson) }}">
                                Browser Anda tidak mendukung pemutar video HTML5.
                            </video>
                        @else
                            <div class="w-full aspect-video flex flex-col items-center justify-center bg-muted/40 text-muted-foreground p-8 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-3 opacity-40 text-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <p class="font-medium text-foreground text-base">Tidak ada video pada materi ini</p>
                                <p class="text-xs mt-1">Materi ini disajikan dalam bentuk teks rangkuman di bawah.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Informasi Materi Aktif -->
                    <div class="evaly-card p-6">
                        <div class="flex items-center justify-between pb-3 border-b border-border mb-4">
                            <div>
                                <span class="evaly-badge evaly-badge-gray text-xs mb-1">
                                    Materi ke-{{ $activeLesson->order_position }}
                                </span>
                                <h3 class="text-xl font-bold text-foreground">{{ $activeLesson->title }}</h3>
                            </div>

                            @if ($activeLesson->video_path)
                                <span class="evaly-badge evaly-badge-green text-xs">Video Siap</span>
                            @endif
                        </div>

                        <div class="prose max-w-none text-foreground text-sm leading-relaxed whitespace-pre-line mb-6">
                            {{ $activeLesson->description ?: 'Tidak ada deskripsi tambahan untuk materi ini.' }}
                        </div>

                        <!-- Tombol Navigasi Materi Sebelumnya / Selanjutnya -->
                        @php
                            $currentIndex = $lessons->search(fn($l) => $l->id === $activeLesson->id);
                            $prevLesson = $currentIndex > 0 ? $lessons->get($currentIndex - 1) : null;
                            $nextLesson = $currentIndex < $lessons->count() - 1 ? $lessons->get($currentIndex + 1) : null;
                        @endphp

                        <div class="flex items-center justify-between pt-4 border-t border-border">
                            @if ($prevLesson)
                                <a href="{{ route('student.courses.show', [$course, 'lesson' => $prevLesson->id]) }}"
                                   class="evaly-btn-secondary text-sm">
                                    &larr; Materi Sebelumnya
                                </a>
                            @else
                                <span class="text-sm text-muted-foreground opacity-50">&larr; Awal Materi</span>
                            @endif

                            @if ($nextLesson)
                                <a href="{{ route('student.courses.show', [$course, 'lesson' => $nextLesson->id]) }}"
                                   class="evaly-btn-primary text-sm">
                                    Materi Selanjutnya &rarr;
                                </a>
                            @else
                                <span class="text-sm text-muted-foreground opacity-50">Selesai Kursus &check;</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Daftar Playlist Materi -->
                <div class="lg:col-span-1 space-y-4">
                    <div class="evaly-card p-5">
                        <div class="flex items-center justify-between pb-3 border-b border-border mb-3">
                            <h4 class="font-semibold text-foreground text-base">Daftar Materi</h4>
                            <span class="text-xs text-muted-foreground">{{ $lessons->count() }} Pelajaran</span>
                        </div>

                        <div class="space-y-1.5 max-h-[600px] overflow-y-auto pr-1">
                            @foreach ($lessons as $index => $item)
                                @php
                                    $isActive = $item->id === $activeLesson->id;
                                @endphp
                                <a href="{{ route('student.courses.show', [$course, 'lesson' => $item->id]) }}"
                                   class="flex items-start gap-3 p-3 rounded-lg text-sm transition-colors duration-150
                                          {{ $isActive ? 'bg-primary text-primary-foreground font-medium shadow-sm' : 'hover:bg-muted text-foreground' }}">
                                    <!-- Nomor Urut -->
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-semibold shrink-0 mt-0.5
                                                {{ $isActive ? 'bg-primary-foreground/20 text-primary-foreground' : 'bg-muted text-muted-foreground' }}">
                                        {{ $item->order_position }}
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="truncate leading-tight {{ $isActive ? 'text-primary-foreground' : 'text-foreground' }}">
                                            {{ $item->title }}
                                        </p>

                                        <div class="flex items-center gap-2 mt-1">
                                            @if ($item->video_path)
                                                <span class="inline-flex items-center gap-1 text-[11px] {{ $isActive ? 'text-primary-foreground/80' : 'text-muted-foreground' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    </svg>
                                                    Video
                                                </span>
                                            @else
                                                <span class="text-[11px] {{ $isActive ? 'text-primary-foreground/70' : 'text-muted-foreground' }}">
                                                    Teks
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($isActive)
                                        <div class="shrink-0 mt-1">
                                            <span class="w-2 h-2 rounded-full bg-primary-foreground block animate-pulse"></span>
                                        </div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Ringkasan Info Kursus -->
                    <div class="evaly-card p-5">
                        <h5 class="text-xs font-semibold text-muted-foreground uppercase tracking-wider mb-2">Tentang Kursus Ini</h5>
                        <p class="text-sm text-foreground leading-relaxed">{{ $course->description }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
