<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Katalog Kursus - Evaly Learn</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-background text-foreground flex flex-col min-h-screen">
        <!-- Navigation (Sama persis seperti Landing Page) -->
        <nav class="sticky top-0 z-40 bg-background/95 backdrop-blur border-b border-border">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-8">
                        <a href="{{ url('/') }}"
                           class="flex items-center gap-2 text-lg font-semibold text-foreground">
                            <span>Evaly Learn</span>
                        </a>

                        <a href="{{ route('katalog') }}"
                           class="text-sm font-semibold text-primary transition-colors">
                            Katalog Kursus
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        @auth
                            @can('view-my-courses')
                                <a href="{{ route('my.courses') }}"
                                   class="text-sm font-medium text-muted-foreground hover:text-foreground transition-colors hidden sm:inline-block">
                                    Kursus Saya
                                </a>
                            @endcan
                            <a href="{{ route('dashboard') }}"
                               class="evaly-btn-primary">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-muted transition-colors duration-150">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                               class="evaly-btn-primary">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content (Ukuran dan Spacing Identik Landing Page) -->
        <main class="flex-grow py-16 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header Katalog -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-10">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-medium mb-2">
                            Katalog Pembelajaran
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-semibold text-foreground">
                            Katalog Kursus
                        </h1>
                        <p class="text-sm text-muted-foreground mt-1">
                            Pilih dan ambil kursus yang Anda inginkan untuk masuk ke dashboard belajar siswa.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        @auth
                            @can('view-my-courses')
                                <a href="{{ route('my.courses') }}" class="evaly-btn-secondary text-xs">
                                    Lihat Kursus Saya &rarr;
                                </a>
                            @endcan
                        @endauth
                    </div>
                </div>

                <!-- Alert Sukses Flash -->
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm font-medium flex items-center justify-between">
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Course Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($courses as $course)
                        @php
                            $isEnrolled = in_array($course->id, $enrolledIds);
                        @endphp
                        <div class="evaly-card p-6 flex flex-col justify-between hover:border-foreground/30 transition-all duration-200">
                            <div>
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <h3 class="font-semibold text-foreground text-base leading-snug">
                                        {{ $course->title }}
                                    </h3>
                                    @if ($isEnrolled)
                                        <span class="evaly-badge evaly-badge-green shrink-0 text-xs">
                                            ✓ Terdaftar
                                        </span>
                                    @else
                                        <span class="evaly-badge evaly-badge-gray shrink-0 text-xs">
                                            Tersedia
                                        </span>
                                    @endif
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

                                @auth
                                    @if ($isEnrolled)
                                        <a href="{{ route('student.courses.show', $course) }}"
                                           class="evaly-btn-secondary text-xs">
                                            Lanjut Belajar &rarr;
                                        </a>
                                    @else
                                        <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="evaly-btn-primary text-xs cursor-pointer">
                                                + Ambil Kursus
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('register') }}"
                                       class="evaly-btn-primary text-xs">
                                        Daftar untuk Ambil &rarr;
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-16 text-center text-muted-foreground evaly-card p-8">
                            <p class="text-base font-medium">Belum ada kursus yang tersedia di katalog saat ini.</p>
                            <p class="text-xs text-muted-foreground mt-1">Silakan kembali lagi nanti saat materi baru telah dipublikasikan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>

        <!-- Footer (Sama persis seperti Landing Page) -->
        <footer class="border-t border-border py-12 px-4 sm:px-6 lg:px-8 mt-auto">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-2 text-lg font-semibold text-foreground">
                        <span>Evaly Learn</span>
                    </div>

                    <div class="flex items-center gap-6">
                        <a href="{{ url('/') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            Beranda
                        </a>
                        <a href="{{ route('katalog') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            Katalog
                        </a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                                Dashboard
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-border text-center text-sm text-muted-foreground">
                    © {{ date('Y') }} Evaly Learn. All rights reserved.
                </div>
            </div>
        </footer>
    </body>
</html>
