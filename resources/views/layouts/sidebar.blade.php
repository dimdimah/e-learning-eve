<aside class="hidden lg:flex flex-col w-64 bg-background border-r border-border">
    <div class="flex items-center h-16 px-6 border-b border-border">
        <a href="{{ url('/') }}"
           class="flex items-center gap-2 text-lg font-semibold text-foreground">
            <span>Evaly Learn</span>
        </a>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                  {{ request()->routeIs('dashboard') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Menu muncul sesuai permission yang diaktifkan admin -->
        @can('manage-courses')
            <div class="pt-6 pb-2">
                <p class="px-3 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Kelola</p>
            </div>

            <!-- Kelola Course -->
            <a href="{{ route('admin.courses.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.courses.*') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>Kelola Course</span>
            </a>
        @endcan

        @can('manage-users')
            <!-- Kelola User -->
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.users.*') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Kelola User</span>
            </a>
        @endcan

        @can('manage-roles')
            <!-- Kelola Role -->
            <a href="{{ route('admin.roles.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.roles.*') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Kelola Role</span>
            </a>
        @endcan

        @can('view-my-courses')
            <div class="pt-6 pb-2">
                <p class="px-3 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Belajar</p>
            </div>

            <!-- Katalog Kursus -->
            <a href="{{ route('katalog') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('katalog') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Katalog Kursus</span>
            </a>

            <!-- Kursus Saya -->
            <a href="{{ route('my.courses') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('my.courses') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>Kursus Saya</span>
            </a>
        @endcan
    </nav>

    <!-- User Profile Section -->
    <div class="border-t border-border px-4 py-4">
        @auth
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
                <div class="flex items-center gap-2">
                    <a href="{{ route('profile.edit') }}"
                       class="text-muted-foreground hover:text-foreground transition-colors"
                       title="Profile">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-muted-foreground hover:text-foreground transition-colors"
                                title="Logout">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}" class="evaly-btn-secondary text-xs flex-1 text-center">Login</a>
                <a href="{{ route('register') }}" class="evaly-btn-primary text-xs flex-1 text-center">Register</a>
            </div>
        @endauth
    </div>
</aside>

<!-- Mobile Navigation (sidebar tersembunyi di mobile, gunakan top nav) -->
<div class="lg:hidden">
    @include('layouts.mobile-nav')
</div>