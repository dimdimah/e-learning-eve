<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-foreground leading-tight">
                    {{ __('Kursus Saya') }}
                </h2>
                <p class="text-xs text-muted-foreground mt-0.5">
                    Daftar kursus yang telah Anda ambil dan sedang Anda pelajari.
                </p>
            </div>

            <a href="{{ route('katalog') }}" class="evaly-btn-secondary text-xs">
                + Ambil Kursus Lainnya
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="evaly-badge evaly-badge-green">
                {{ session('success') }}
            </div>
        @endif

        <div class="evaly-card">
            <div class="p-6">
                @if ($courses->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($courses as $course)
                            <div class="evaly-card p-6 flex flex-col justify-between hover:border-foreground/30 transition-all duration-200">
                                <div>
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <h3 class="font-semibold text-foreground text-base leading-snug">
                                            {{ $course->title }}
                                        </h3>
                                        <span class="evaly-badge evaly-badge-green shrink-0 text-xs">
                                            Sedang Diikuti
                                        </span>
                                    </div>
                                    <p class="text-sm text-muted-foreground mb-4 line-clamp-3">
                                        {{ $course->description }}
                                    </p>
                                </div>

                                <div class="pt-4 border-t border-border flex items-center justify-between mt-auto">
                                    <span class="text-xs text-muted-foreground flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        {{ $course->lessons_count ?? $course->lessons->count() }} Materi
                                    </span>
                                    <a href="{{ route('student.courses.show', $course) }}"
                                       class="evaly-btn-primary text-xs">
                                        Lanjut Belajar &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 px-4 text-center">
                        <div class="w-12 h-12 rounded-full bg-primary/10 text-primary mx-auto flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-foreground mb-1">
                            Belum Ada Kursus yang Diambil
                        </h3>
                        <p class="text-sm text-muted-foreground mb-6 max-w-md mx-auto">
                            Anda belum mengambil kursus apapun. Kunjungi Katalog Kursus untuk melihat berbagai topik pembelajaran menarik dan mulai belajar!
                        </p>
                        <a href="{{ route('katalog') }}" class="evaly-btn-primary">
                            Jelajahi Katalog Kursus &rarr;
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
