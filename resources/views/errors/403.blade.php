<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>403 - Akses Ditolak | Evaly Learn</title>

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
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-950/40 text-red-600 dark:text-red-400 mx-auto flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300 mb-3">
                    Kode Status: 403 Forbidden
                </div>

                <h1 class="text-2xl font-bold text-foreground mb-2">
                    Akses Ditolak
                </h1>

                <p class="text-sm text-muted-foreground mb-8 leading-relaxed">
                    {{ $exception->getMessage() ?: 'Maaf, akun Anda tidak memiliki hak akses untuk membuka halaman atau aksi ini.' }}
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <button type="button"
                            onclick="window.history.back()"
                            class="evaly-btn-secondary text-sm w-full sm:w-auto px-5 py-2.5 cursor-pointer">
                        &larr; Halaman Sebelumnya
                    </button>
                    @auth
                        <a href="{{ route('dashboard') }}" class="evaly-btn-primary text-sm w-full sm:w-auto px-5 py-2.5">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="evaly-btn-primary text-sm w-full sm:w-auto px-5 py-2.5">
                            Login Akun
                        </a>
                    @endauth
                </div>
            </div>
        </main>

        <footer class="border-t border-border py-4 text-center text-xs text-muted-foreground">
            &copy; {{ date('Y') }} Evaly Learn. All rights reserved.
        </footer>
    </body>
</html>
