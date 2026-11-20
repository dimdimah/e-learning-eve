<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Evaly Learn') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-background text-foreground min-h-screen flex flex-col justify-between">
        <!-- Top Header Guest -->
        <header class="border-b border-border bg-background">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-lg font-semibold text-foreground">
                    <span>Evaly Learn</span>
                </a>

                <div class="flex items-center gap-4">
                    <a href="{{ route('katalog') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                        Katalog Kursus
                    </a>
                    <a href="{{ url('/') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                        &larr; Beranda
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Form Slot -->
        <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-border py-4 text-center text-xs text-muted-foreground">
            &copy; {{ date('Y') }} Evaly Learn. All rights reserved.
        </footer>
    </body>
</html>
