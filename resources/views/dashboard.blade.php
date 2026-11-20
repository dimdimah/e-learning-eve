<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="evaly-card">
            <div class="p-6">
                <p class="text-foreground mb-4">Selamat belajar, {{ Auth::user()->name }}!</p>
                <p class="text-sm text-muted-foreground mb-6">
                    Lihat kursus yang sudah dipublish untuk mulai belajar.
                </p>
                <a href="{{ route('my.courses') }}" class="evaly-btn-primary">
                    Buka Kursus Saya
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
