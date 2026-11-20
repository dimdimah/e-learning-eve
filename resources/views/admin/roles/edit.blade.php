<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('Ubah Role:') }} {{ $role->name }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="evaly-card">
                <div class="p-6">
                    <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="max-w-lg space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-foreground text-sm font-medium mb-2">Nama Role</label>
                            <input type="text"
                                   name="name"
                                   value="{{ $role->name }}"
                                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required />
                        </div>

                        <div>
                            <label class="block text-foreground text-sm font-medium mb-2">Guard</label>
                            <input type="text"
                                   name="guard_name"
                                   value="{{ $role->guard_name }}"
                                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required />
                        </div>

                        <p class="text-xs text-muted-foreground">
                            Untuk mengubah akses (on/off), centang langsung di halaman Kelola Role.
                        </p>

                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit"
                                    class="evaly-btn-primary">
                                Perbarui Role
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