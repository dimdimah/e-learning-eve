<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                {{ __('Kelola Role') }}
            </h2>

            <a href="{{ route('admin.roles.create') }}"
               class="evaly-btn-primary">
                + Tambah Role
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="evaly-badge evaly-badge-green">
                {{ session('success') }}
            </div>
        @endif

        <div class="evaly-card">
                <div class="p-6">
                    <p class="text-sm text-muted-foreground mb-4">
                        Centang akses yang diizinkan untuk role tersebut, langsung tersimpan.
                    </p>

                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-border">
                                <th class="py-2 text-left text-muted-foreground font-medium">Nama Role</th>
                                <th class="py-2 text-left text-muted-foreground font-medium">Guard</th>
                                <th class="py-2 text-left text-muted-foreground font-medium">Akses (on/off)</th>
                                <th class="py-2 text-right text-muted-foreground font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $role)
                                <tr class="border-b border-border">
                                    <td class="py-3 font-medium text-foreground align-top">{{ $role->name }}</td>
                                    <td class="py-3 text-muted-foreground align-top">{{ $role->guard_name }}</td>
                                    <td class="py-3 align-top">
                                        <form action="{{ route('admin.roles.permissions', $role) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1.5">
                                                @foreach ($permissions as $permission)
                                                    <label class="flex items-start gap-2 cursor-pointer">
                                                        <input type="checkbox"
                                                               name="permission_ids[]"
                                                               value="{{ $permission->id }}"
                                                               {{ in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'checked' : '' }}
                                                               onchange="this.form.submit()"
                                                               class="mt-0.5 text-primary" />
                                                        <span>
                                                            <span class="block text-xs font-medium text-foreground">{{ $permission->name }}</span>
                                                            <span class="block text-xs text-muted-foreground">
                                                                @if ($permission->name === 'manage-courses')
                                                                    Kelola data course (buat, ubah, hapus)
                                                                @elseif ($permission->name === 'manage-users')
                                                                    Kelola data user & assignment role
                                                                @elseif ($permission->name === 'manage-roles')
                                                                    Kelola role & atur akses ini
                                                                @else
                                                                    Lihat daftar kursus terpublish
                                                                @endif
                                                            </span>
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>

                                            <div class="flex gap-2 mt-2">
                                                <button type="button"
                                                        onclick="checkAll(this.form, true)"
                                                        class="px-2 py-1 text-xs font-medium rounded-md border border-border text-foreground hover:bg-muted transition-colors">
                                                    Pilih Semua
                                                </button>
                                                <button type="button"
                                                        onclick="checkAll(this.form, false)"
                                                        class="px-2 py-1 text-xs font-medium rounded-md border border-border text-muted-foreground hover:bg-muted transition-colors">
                                                    Kosongkan
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="py-3 text-right align-top">
                                        <a href="{{ route('admin.roles.edit', $role) }}"
                                           class="text-primary hover:underline text-sm">Ubah</a>
                                        <span class="text-muted-foreground">|</span>
                                        <form action="{{ route('admin.roles.destroy', $role) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Yakin menghapus role ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:underline text-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-muted-foreground">
                                        Belum ada role. Klik "+ Tambah Role" untuk membuat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </div>

    <script>
        // centang / lepas semua checkbox akses lalu langsung simpan
        function checkAll(form, checked) {
            form.querySelectorAll('input[name="permission_ids[]"]').forEach(function (cb) {
                cb.checked = checked;
            });
            form.submit();
        }
    </script>
</x-app-layout>