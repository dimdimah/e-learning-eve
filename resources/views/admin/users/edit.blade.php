<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('Ubah User:') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="evaly-card">
                <div class="p-6">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="max-w-lg space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-foreground text-sm font-medium mb-2">Nama</label>
                            <input type="text"
                                   name="name"
                                   value="{{ $user->name }}"
                                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required />
                        </div>

                        <div>
                            <label class="block text-foreground text-sm font-medium mb-2">Email</label>
                            <input type="email"
                                   name="email"
                                   value="{{ $user->email }}"
                                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required />
                        </div>

                        @if ($user->password !== 'password')
                            <div>
                                <label class="block text-foreground text-sm font-medium mb-2">Password</label>
                                <input type="password"
                                       name="password"
                                       minlength="8"
                                       class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" />
                                <small class="text-muted-foreground block mt-1">Biarkan kosong jika tidak mengubah</small>
                            </div>
                        @endif

                        <div>
                            <label class="block text-foreground text-sm font-medium mb-2">Role</label>
                            <div class="space-y-1">
                                @foreach ($roles as $role)
                                    <div class="flex items-center">
                                        <input type="checkbox"
                                               name="role_ids[]"
                                               value="{{ $role->id }}"
                                               class="mr-2 text-primary"
                                               {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'checked' : '' }} />
                                        <span class="text-sm text-foreground">{{ $role->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit"
                                    class="evaly-btn-primary">
                                Perbarui User
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