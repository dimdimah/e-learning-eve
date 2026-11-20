<x-guest-layout>
    <div class="evaly-card p-8 sm:p-10 shadow-xl border border-border">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-foreground">
                Pendaftaran Siswa Baru
            </h1>
            <p class="text-xs text-muted-foreground mt-1.5">
                Daftar sekarang untuk mulai mengambil kursus dan belajar materi interaktif.
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-medium text-foreground mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input id="name"
                       type="text"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Contoh: Dimas Wahyu"
                       class="w-full px-3.5 py-2.5 border border-border rounded-xl bg-background text-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                       required
                       autofocus
                       autocomplete="name" />
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-medium text-foreground mb-1.5">
                    Alamat Email <span class="text-red-500">*</span>
                </label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="nama@example.com"
                       class="w-full px-3.5 py-2.5 border border-border rounded-xl bg-background text-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                       required
                       autocomplete="username" />
                @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-medium text-foreground mb-1.5">
                    Password <span class="text-red-500">*</span>
                </label>
                <input id="password"
                       type="password"
                       name="password"
                       placeholder="Minimal 8 karakter"
                       class="w-full px-3.5 py-2.5 border border-border rounded-xl bg-background text-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                       required
                       autocomplete="new-password" />
                @error('password')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-medium text-foreground mb-1.5">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       placeholder="Ulangi password di atas"
                       class="w-full px-3.5 py-2.5 border border-border rounded-xl bg-background text-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                       required
                       autocomplete="new-password" />
                @error('password_confirmation')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                        class="evaly-btn-primary w-full py-2.5 text-sm font-semibold shadow-md cursor-pointer">
                    Daftar Akun Siswa &rarr;
                </button>
            </div>
        </form>

        <!-- Link ke Login -->
        <div class="mt-6 pt-5 border-t border-border text-center text-xs text-muted-foreground">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline ms-1">
                Masuk ke Akun
            </a>
        </div>
    </div>
</x-guest-layout>
