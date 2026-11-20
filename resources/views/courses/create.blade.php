<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('Tambah Course') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="evaly-card">
                <div class="p-6">
                    <form action="{{ route('admin.courses.store') }}" method="POST" class="max-w-xl space-y-4">
                        @csrf

                        <div>
                            <label class="block text-foreground text-sm font-medium mb-2">Judul Course</label>
                            <input type="text"
                                   name="title"
                                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required />
                        </div>

                        <div>
                            <label class="block text-foreground text-sm font-medium mb-2">Deskripsi</label>
                            <textarea name="description"
                                      rows="4"
                                      class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                      required></textarea>
                        </div>

                        <div>
                            <label class="block text-foreground text-sm font-medium mb-2">Status Publikasi</label>
                            <select name="status"
                                    class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit"
                                    class="evaly-btn-primary">
                                Simpan Course
                            </button>
                            <a href="{{ route('admin.courses.index') }}"
                               class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
    </div>
</x-app-layout>