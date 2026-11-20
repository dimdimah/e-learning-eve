<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('Tambah User Baru') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="evaly-card">
                <div class="p-6">
                    <form action="{{ route('admin.users.store') }}" method="POST" class="max-w-lg space-y-4">
                        @csrf

                        <div>
                            <label class="block text-foreground text-sm font-medium mb-2">Nama</label>
                            <input type="text"
                                   name="name"
                                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required />
                        </div>

                        <div>
                            <label class="block text-foreground text-sm font-medium mb-2">Email</label>
                            <input type="email"
                                   name="email"
                                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required />
                        </div>

                        <div>
                            <label class="block text-foreground text-sm font-medium mb-2">Password</label>
                            <input type="password"
                                   name="password"
                                   minlength="8"
                                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required />
                            <small class="text-muted-foreground block mt-1">Minimal 8 karakter</small>
                        </div>

                        <div>
                            <label class="block text-foreground text-sm font-medium mb-2">Role</label>
                            <div class="space-y-1">
                                @foreach ($roles as $role)
                                    <div class="flex items-center">
                                        <input type="checkbox"
                                               name="role_ids[]"
                                               value="{{ $role->id }}"
                                               class="mr-2 text-primary"
                                               {{ isset($old['role_ids']) && in_array($role->id, $old['role_ids']) ? 'checked' : '' }} />
                                        <span class="text-sm text-foreground">{{ $role->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit"
                                    class="evaly-btn-primary">
                                Simpan User
                            </button>
                            <a href="{{ route('admin.users.index') }}"
                               class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
    </div>
</x-app-layout>