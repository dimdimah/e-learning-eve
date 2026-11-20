<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Evaly Learn - Platform E-Learning</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-background text-foreground">
        <!-- Navigation -->
        <nav class="sticky top-0 z-40 bg-background border-b border-border">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-8">
                        <a href="{{ url('/') }}"
                           class="flex items-center gap-2 text-lg font-semibold text-foreground">
                            <span>Evaly Learn</span>
                        </a>

                        <a href="{{ route('katalog') }}"
                           class="text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">
                            Katalog Kursus
                        </a>
                    </div>

                    <div class="flex items-center gap-2">
                        @auth
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

        <!-- Hero Section -->
        <section class="pt-16 pb-24 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-muted mb-6">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-sm text-muted-foreground">Platform E-Learning Modern</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-semibold text-foreground leading-tight mb-6">
                    Belajar dengan<br>
                    <span class="text-primary">lebih efektif</span>
                </h1>

                <p class="text-lg text-muted-foreground mb-8 max-w-2xl mx-auto">
                    Platform e-learning yang dirancang untuk kemudahan belajar, pengelolaan kelas, dan pembelajaran yang efektif.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}"
                       class="evaly-btn-primary text-base px-6 py-3">
                        Mulai Belajar
                    </a>
                    <a href="{{ route('login') }}"
                       class="evaly-btn-secondary text-base px-6 py-3">
                        Masuk ke Akun
                    </a>
                </div>

                <div class="mt-12 flex flex-wrap items-center justify-center gap-x-8 gap-y-4 text-sm text-muted-foreground">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Gratis untuk pemula</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Setup dalam menit</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Tanpa credit card</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Catalog Showcase Section -->
        <section id="katalog" class="py-20 px-4 sm:px-6 lg:px-8 border-t border-border">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-12">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-medium mb-2">
                            Katalog Pembelajaran
                        </div>
                        <h2 class="text-3xl font-semibold text-foreground">
                            Pilih Kursus untuk Mulai Belajar
                        </h2>
                        <p class="text-muted-foreground text-sm mt-1">
                            Ambil materi yang Anda butuhkan dan pelajari langsung dari dashboard belajar siswa.
                        </p>
                    </div>
                    <a href="{{ route('katalog') }}" class="evaly-btn-secondary text-xs">
                        Lihat Semua Kursus &rarr;
                    </a>
                </div>

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
                                        Ambil Kursus &rarr;
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center text-muted-foreground">
                            Belum ada kursus yang tersedia.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24 px-4 sm:px-6 lg:px-8 bg-muted">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-semibold text-foreground mb-4">
                        Fitur Unggulan
                    </h2>
                    <p class="text-lg text-muted-foreground max-w-2xl mx-auto">
                        Semua yang Anda butuhkan untuk mengelola pembelajaran secara efektif
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Feature 1 -->
                    <div class="evaly-card p-6">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-foreground mb-2">Kelola Course</h3>
                        <p class="text-muted-foreground">
                            Buat, edit, dan kelola course dengan mudah. Atur status publikasi dan deskripsi lengkap.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="evaly-card p-6">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-foreground mb-2">Kelola User</h3>
                        <p class="text-muted-foreground">
                            Kelola siswa, guru, dan admin dengan role-based access control yang aman.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="evaly-card p-6">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-foreground mb-2">Role Management</h3>
                        <p class="text-muted-foreground">
                            Kelola role dan permission untuk kontrol akses yang tepat di setiap level user.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="evaly-card p-6">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-foreground mb-2">Multi-role</h3>
                        <p class="text-muted-foreground">
                            Mendukung role Super Admin, Guru, dan Siswa dengan izin akses yang berbeda.
                        </p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="evaly-card p-6">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-foreground mb-2">Responsive</h3>
                        <p class="text-muted-foreground">
                            Tampilan yang responsif dan optimal di semua perangkat, dari desktop hingga mobile.
                        </p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="evaly-card p-6">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-foreground mb-2">Aman</h3>
                        <p class="text-muted-foreground">
                            Data user aman dengan enkripsi password dan proteksi terhadap akses tidak sah.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-semibold text-foreground mb-4">
                    Siap untuk mulai?
                </h2>
                <p class="text-lg text-muted-foreground mb-8">
                    Daftar sekarang dan mulai mengelola pembelajaran Anda dengan lebih efektif.
                </p>
                <a href="{{ route('register') }}"
                   class="evaly-btn-primary text-base px-8 py-4">
                    Mulai Gratis Sekarang
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-border py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-2 text-lg font-semibold text-foreground">
                        <span>Evaly Learn</span>
                    </div>

                    <div class="flex items-center gap-6">
                        <a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            Terms
                        </a>
                        <a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            Privacy
                        </a>
                        <a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            Contact
                        </a>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-border text-center text-sm text-muted-foreground">
                    © {{ date('Y') }} Evaly Learn. All rights reserved.
                </div>
            </div>
        </footer>
    </body>
</html>