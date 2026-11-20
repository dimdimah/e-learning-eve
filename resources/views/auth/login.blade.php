<x-guest-layout>
    <div class="evaly-card p-8 sm:p-10 shadow-xl border border-border">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-foreground">
                Masuk ke Akun
            </h1>
            <p class="text-xs text-muted-foreground mt-1.5">
                Silakan login untuk mengakses materi dan fitur pembelajaran.
            </p>
        </div>

        <!-- Session Status Alert -->
        @if (session('status'))
            <div class="mb-4 text-xs font-medium text-green-700 bg-green-100 p-3 rounded-lg border border-green-200">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

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
                       autofocus
                       autocomplete="username" />
                @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-medium text-foreground">
                        Password <span class="text-red-500">*</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-muted-foreground hover:text-foreground transition-colors">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <div class="relative">
                    <input id="password"
                           type="password"
                           name="password"
                           placeholder="••••••••"
                           class="w-full px-3.5 py-2.5 pr-10 border border-border rounded-xl bg-background text-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                           required
                           autocomplete="current-password" />
                    <button type="button"
                            onclick="togglePasswordVisibility()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-muted-foreground hover:text-foreground cursor-pointer"
                            title="Tampilkan / Sembunyikan Password">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me"
                           type="checkbox"
                           class="rounded border-border text-primary focus:ring-primary h-4 w-4"
                           name="remember">
                    <span class="ms-2 text-xs text-muted-foreground">Ingat sesi saya</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                        class="evaly-btn-primary w-full py-2.5 text-sm font-semibold shadow-md cursor-pointer">
                    Masuk Sekarang &rarr;
                </button>
            </div>
        </form>

        <!-- Link Register Siswa -->
        <div class="mt-6 pt-5 border-t border-border text-center text-xs text-muted-foreground">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-semibold text-primary hover:underline ms-1">
                Daftar Akun Siswa Baru
            </a>
        </div>
    </div>

    <!-- Script Toggle Password Visibility -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18\" />';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M15 12a3 3 0 11-6 0 3 3 0 016 0z\" /><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\" />';
            }
        }
    </script>
</x-guest-layout>
