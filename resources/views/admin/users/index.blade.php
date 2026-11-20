<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                {{ __('Kelola User') }}
            </h2>

            <a href="{{ route('admin.users.create') }}"
               class="evaly-btn-primary">
                + Tambah User
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="evaly-badge evaly-badge-green">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="evaly-badge evaly-badge-red">
                {{ session('error') }}
            </div>
        @endif

        <div class="evaly-card">
                <div class="p-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-border">
                                <th class="py-2 text-left text-muted-foreground font-medium">Nama</th>
                                <th class="py-2 text-left text-muted-foreground font-medium">Email</th>
                                <th class="py-2 text-left text-muted-foreground font-medium">Role</th>
                                <th class="py-2 text-right text-muted-foreground font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="border-b border-border">
                                    <td class="py-3 font-medium text-foreground">{{ $user->name }}</td>
                                    <td class="py-3 text-muted-foreground">{{ $user->email }}</td>
                                    <td class="py-3">
                                        @foreach ($user->roles as $role)
                                            <span class="evaly-badge evaly-badge-green mr-1">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="text-primary hover:underline text-sm">Ubah</a>
                                        <span class="text-muted-foreground">|</span>
                                        <form action="{{ route('admin.users.destroy', $user) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Yakin menghapus user ini?')">
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
                                        Belum ada user. Klik "+ Tambah User" untuk membuat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</x-app-layout>