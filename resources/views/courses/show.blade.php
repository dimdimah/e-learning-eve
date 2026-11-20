<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                {{ __('Detail Course') }}
            </h2>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.courses.edit', $course) }}"
                   class="evaly-btn-secondary">
                    Ubah Course
                </a>
                <a href="{{ route('admin.courses.index') }}"
                   class="evaly-btn-secondary">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="evaly-badge evaly-badge-green">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card Informasi Course -->
        <div class="evaly-card">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-bold text-foreground">{{ $course->title }}</h3>

                    @if ($course->status == 'published')
                        <span class="evaly-badge evaly-badge-green">Published</span>
                    @else
                        <span class="evaly-badge evaly-badge-yellow">Draft</span>
                    @endif
                </div>

                <p class="text-foreground mb-4 leading-relaxed whitespace-pre-line">{{ $course->description }}</p>

                <p class="text-xs text-muted-foreground">
                    Dibuat pada: {{ $course->created_at->format('d M Y H:i') }}
                </p>
            </div>
        </div>

        <!-- Section Lesson Management -->
        <div class="evaly-card">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-border">
                    <div>
                        <h4 class="text-lg font-semibold text-foreground">Materi Pembelajaran (Lessons)</h4>
                        <p class="text-sm text-muted-foreground">Kelola dan atur urutan materi dalam kursus ini.</p>
                    </div>

                    <a href="{{ route('admin.courses.lessons.create', $course) }}"
                       class="evaly-btn-primary">
                        + Tambah Lesson
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-border text-muted-foreground">
                                <th class="py-2 px-3 text-left w-16">Urutan</th>
                                <th class="py-2 px-3 text-left">Judul Materi</th>
                                <th class="py-2 px-3 text-left">Video</th>
                                <th class="py-2 px-3 text-left">Status</th>
                                <th class="py-2 px-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($course->lessons as $index => $lesson)
                                <tr class="border-b border-border hover:bg-muted/50 transition-colors">
                                    <td class="py-3 px-3 font-semibold text-foreground">
                                        <div class="flex items-center gap-1">
                                            <span>#{{ $lesson->order_position }}</span>
                                            <div class="inline-flex flex-col ml-1">
                                                <!-- Tombol Naik -->
                                                <form action="{{ route('admin.courses.lessons.move-up', [$course, $lesson]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            title="Geser ke atas"
                                                            class="text-xs text-muted-foreground hover:text-foreground leading-none disabled:opacity-30"
                                                            {{ $index === 0 ? 'disabled' : '' }}>
                                                        ▲
                                                    </button>
                                                </form>
                                                <!-- Tombol Turun -->
                                                <form action="{{ route('admin.courses.lessons.move-down', [$course, $lesson]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            title="Geser ke bawah"
                                                            class="text-xs text-muted-foreground hover:text-foreground leading-none disabled:opacity-30"
                                                            {{ $loop->last ? 'disabled' : '' }}>
                                                        ▼
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <p class="font-medium text-foreground">{{ $lesson->title }}</p>
                                        @if ($lesson->description)
                                            <p class="text-xs text-muted-foreground line-clamp-1 mt-0.5">{{ $lesson->description }}</p>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-muted-foreground">
                                        @if ($lesson->video_path)
                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="truncate max-w-xs text-xs">{{ $lesson->video_original_name ?? 'video.mp4' }}</span>
                                                <a href="{{ route('lessons.video', $lesson) }}" target="_blank" class="text-xs text-primary hover:underline ml-1">Putar</a>
                                            </div>
                                        @else
                                            <span class="text-xs text-muted-foreground italic">Belum ada video</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3">
                                        @if ($lesson->video_status === 'ready' && $lesson->video_path)
                                            <span class="evaly-badge evaly-badge-green">Ready</span>
                                        @elseif ($lesson->video_status === 'uploading')
                                            <span class="evaly-badge evaly-badge-yellow">Uploading</span>
                                        @elseif ($lesson->video_status === 'failed')
                                            <span class="evaly-badge evaly-badge-red">Failed</span>
                                        @else
                                            <span class="evaly-badge evaly-badge-gray">Draft</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-right whitespace-nowrap">
                                        <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}"
                                           class="text-primary hover:underline text-sm">Ubah</a>
                                        <span class="text-muted-foreground mx-1">|</span>
                                        <form action="{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:underline text-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-muted-foreground">
                                        Belum ada materi pembelajaran dalam kursus ini. Klik <strong>"+ Tambah Lesson"</strong> untuk menambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
