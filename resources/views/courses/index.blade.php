<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                {{ __('Kelola Course') }}
            </h2>

            {{-- Tombol Buka Dialog Modal Tambah Course --}}
            <button type="button"
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'create-course')"
                    class="evaly-btn-primary cursor-pointer">
                + Tambah Course
            </button>
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
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="py-2 text-left text-muted-foreground font-medium">Judul</th>
                            <th class="py-2 text-left text-muted-foreground font-medium">Status</th>
                            <th class="py-2 text-left text-muted-foreground font-medium">Dibuat</th>
                            <th class="py-2 text-right text-muted-foreground font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                            <tr class="border-b border-border">
                                <td class="py-3 font-medium text-foreground">{{ $course->title }}</td>
                                <td class="py-3">
                                    @if ($course->status == 'published')
                                        <span class="evaly-badge evaly-badge-green">Published</span>
                                    @else
                                        <span class="evaly-badge evaly-badge-yellow">Draft</span>
                                    @endif
                                </td>
                                <td class="py-3 text-muted-foreground">{{ $course->created_at->format('d M Y') }}</td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('admin.courses.show', $course) }}"
                                       class="text-primary hover:underline text-sm">Lihat</a>
                                    <span class="text-muted-foreground">|</span>
                                    <a href="{{ route('admin.courses.edit', $course) }}"
                                       class="text-primary hover:underline text-sm">Ubah</a>
                                    <span class="text-muted-foreground">|</span>
                                    <form action="{{ route('admin.courses.destroy', $course) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus course ini?')">
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
                                    Belum ada course. Klik "+ Tambah Course" untuk membuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Dialog Modal Tambah Course --}}
    <x-modal name="create-course" :show="$errors->any()" focusable maxWidth="lg">
        <div class="p-6">
            <div class="flex items-center justify-between border-b border-border pb-3 mb-5">
                <div>
                    <h3 class="text-lg font-semibold text-foreground">
                        Tambah Course Baru
                    </h3>
                    <p class="text-xs text-muted-foreground mt-0.5">
                        Isi form di bawah ini untuk membuat kursus baru.
                    </p>
                </div>
                <button type="button"
                        x-on:click="$dispatch('close')"
                        class="text-muted-foreground hover:text-foreground text-2xl font-light leading-none p-1 transition-colors cursor-pointer"
                        title="Tutup dialog">
                    &times;
                </button>
            </div>

            <form action="{{ route('admin.courses.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-foreground text-sm font-medium mb-1.5">
                        Judul Course <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           placeholder="Contoh: Laravel 12 Fundamental"
                           class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                           required
                           autofocus />
                    @error('title')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-foreground text-sm font-medium mb-1.5">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description"
                              rows="4"
                              placeholder="Deskripsi singkat mengenai isi dan tujuan kursus..."
                              class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-foreground text-sm font-medium mb-1.5">
                        Status Publikasi <span class="text-red-500">*</span>
                    </label>
                    <select name="status"
                            class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Hanya Admin)</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published (Tampil ke Siswa)</option>
                    </select>
                    @error('status')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-border mt-6">
                    <button type="button"
                            x-on:click="$dispatch('close')"
                            class="px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="evaly-btn-primary cursor-pointer">
                        Simpan Course
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>