<nav class="sticky top-0 z-40 bg-background border-b border-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-8">
                <a href="{{ url('/') }}"
                   class="flex items-center gap-2 text-lg font-semibold text-foreground">
                    <span>Evaly Learn</span>
                </a>

                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ url('/dashboard') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-muted transition-colors duration-150">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.courses.index') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-muted transition-colors duration-150">
                        Courses
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('profile.edit') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-muted transition-colors duration-150">
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-muted transition-colors duration-150">
                            Logout
                        </button>
                    </form>
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