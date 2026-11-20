<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>404 - Halaman Tidak Ditemukan | Evaly Learn</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-background text-foreground min-h-screen flex flex-col justify-between">
        <header class="border-b border-border bg-background">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ url('/') }}" class="text-lg font-semibold text-foreground">
                    Evaly Learn
                </a>
                <a href="{{ url('/') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                    &larr; Ke Beranda
                </a>
            </div>
        </header>

        <main class="flex-1 flex items-center justify-center p-4 sm:p-6">
            <div class="w-full max-w-md text-center evaly-card p-8 sm:p-10 shadow-xl border border-border">
                <div class="w-16 h-16 rounded-full bg-muted text-foreground mx-auto flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-muted text-muted-foreground mb-3">
                    Kode Status: 404 Not Found
                </div>

                <h1 class="text-2xl font-bold text-foreground mb-2">
                    Halaman Tidak Ditemukan
                </h1>

                <p class="text-sm text-muted-foreground mb-8 leading-relaxed">
                    {{ $exception->getMessage() ?: 'Halaman, tautan, atau materi kursus yang Anda tuju tidak ditemukan atau belum dipublikasikan.' }}
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('katalog') }}" class="evaly-btn-secondary text-sm w-full sm:w-auto px-5 py-2.5">
                        Katalog Kursus
                    </a>
                    <a href="{{ url('/') }}" class="evaly-btn-primary text-sm w-full sm:w-auto px-5 py-2.5">
                        Ke Halaman Utama &rarr;
                    </a>
                </div>
            </div>
        </main>

        <footer class="border-t border-border py-4 text-center text-xs text-muted-foreground">
            &copy; {{ date('Y') }} Evaly Learn. All rights reserved.
        </footer>
    </body>
</html>
