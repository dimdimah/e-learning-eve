<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('Tambah Role Baru') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="evaly-card">
                <div class="p-6">
                    <form action="{{ route('admin.roles.store') }}" method="POST" class="max-w-lg space-y-4">
                        @csrf

                        <div>
                            <label class="block text-foreground text-sm font-medium mb-2">Nama Role</label>
                            <input type="text"
                                   name="name"
                                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required />
                        </div>

                        <div>
                            <label class="block text-foreground text-sm font-medium mb-2">Guard</label>
                            <input type="text"
                                   name="guard_name"
                                   value="web"
                                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required />
                            <small class="text-muted-foreground block mt-1">Default: web</small>
                        </div>

                        <div>
                            <label class="block text-foreground text-sm font-medium mb-2">Akses (centang = on, hilangkan = off)</label>
                            <div class="space-y-1">
                                @foreach ($permissions as $permission)
                                    <div class="flex items-center">
                                        <input type="checkbox"
                                               name="permission_ids[]"
                                               value="{{ $permission->id }}"
                                               class="mr-2 text-primary"
                                               {{ isset($old['permission_ids']) && in_array($permission->id, $old['permission_ids']) ? 'checked' : '' }} />
                                        <span class="text-sm text-foreground">{{ $permission->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit"
                                    class="evaly-btn-primary">
                                Simpan Role
                            </button>
                            <a href="{{ route('admin.roles.index') }}"
                               class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
    </div>
</x-app-layout>