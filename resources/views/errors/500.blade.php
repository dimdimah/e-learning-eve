<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>500 - Terjadi Kesalahan Server | Evaly Learn</title>

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
                <div class="w-16 h-16 rounded-full bg-yellow-100 text-yellow-700 mx-auto flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 mb-3">
                    Kode Status: 500 Server Error
                </div>

                <h1 class="text-2xl font-bold text-foreground mb-2">
                    Terjadi Kesalahan Server
                </h1>

                <p class="text-sm text-muted-foreground mb-8 leading-relaxed">
                    Sistem mengalami kendala sementara saat memproses permintaan Anda. Silakan muat ulang halaman atau coba kembali beberapa saat lagi.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <button type="button"
                            onclick="window.location.reload()"
                            class="evaly-btn-secondary text-sm w-full sm:w-auto px-5 py-2.5 cursor-pointer">
                        Muat Ulang Halaman
                    </button>
                    <a href="{{ url('/') }}" class="evaly-btn-primary text-sm w-full sm:w-auto px-5 py-2.5">
                        Ke Halaman Utama
                    </a>
                </div>
            </div>
        </main>

        <footer class="border-t border-border py-4 text-center text-xs text-muted-foreground">
            &copy; {{ date('Y') }} Evaly Learn. All rights reserved.
        </footer>
    </body>
</html>
