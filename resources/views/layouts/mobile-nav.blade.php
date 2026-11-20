<nav class="bg-background border-b border-border">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6">
        <a href="{{ url('/') }}"
           class="flex items-center gap-2 text-lg font-semibold text-foreground">
            <span>Evaly Learn</span>
        </a>

        <button type="button"
                onclick="toggleMobileMenu()"
                class="flex items-center justify-center w-10 h-10 rounded-lg bg-muted text-foreground hover:bg-muted/80 transition-colors"
                aria-label="Menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Mobile Menu (hidden by default) -->
    <div id="mobile-menu" class="hidden border-t border-border">
        <div class="px-4 py-2 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('dashboard') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
                Dashboard
            </a>

            <!-- Menu muncul sesuai permission yang diaktifkan admin -->
            @can('manage-courses')
                <div class="pt-4 pb-2">
                    <p class="px-3 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Kelola</p>
                </div>

                <a href="{{ route('admin.courses.index') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                          {{ request()->routeIs('admin.courses.*') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
                    Kelola Course
                </a>
            @endcan

            @can('manage-users')
                <a href="{{ route('admin.users.index') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                          {{ request()->routeIs('admin.users.*') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
                    Kelola User
                </a>
            @endcan

            @can('manage-roles')
                <a href="{{ route('admin.roles.index') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                          {{ request()->routeIs('admin.roles.*') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
                    Kelola Role
                </a>
            @endcan

            @can('view-my-courses')
                <div class="pt-4 pb-2">
                    <p class="px-3 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Belajar</p>
                </div>

                <a href="{{ route('katalog') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                          {{ request()->routeIs('katalog') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
                    Katalog Kursus
                </a>

                <a href="{{ route('my.courses') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                          {{ request()->routeIs('my.courses') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
                    Kursus Saya
                </a>
            @endcan
        </div>

        <!-- User Profile (mobile) -->
        @auth
            <div class="border-t border-border px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="text-sm font-medium text-primary">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-foreground truncate">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-muted-foreground truncate">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.edit') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-muted transition-colors">
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-muted transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="border-t border-border px-4 py-3 flex items-center gap-3">
                <a href="{{ route('login') }}" class="evaly-btn-secondary text-xs flex-1 text-center">Login</a>
                <a href="{{ route('register') }}" class="evaly-btn-primary text-xs flex-1 text-center">Register</a>
            </div>
        @endauth
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
}
</script>