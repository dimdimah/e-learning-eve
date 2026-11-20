<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LessonController extends Controller
{
    /**
     * Tampilkan form tambah lesson baru untuk suatu course.
     */
    public function create(Course $course): View
    {
        // cari nomor urut berikutnya agar otomatis terisi
        $nextOrder = ($course->lessons()->max('order_position') ?? 0) + 1;

        return view('lessons.create', compact('course', 'nextOrder'));
    }

    /**
     * Simpan lesson baru ke dalam database.
     */
    public function store(Request $request, Course $course): RedirectResponse
    {
        // validasi input lesson dari admin
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order_position' => 'nullable|integer|min:1',
            'video' => 'nullable|file|max:262144|mimetypes:video/*,application/octet-stream|extensions:mp4,mov,avi,webm,mkv,m4v,3gp',
        ]);

        $order = $request->order_position ?: (($course->lessons()->max('order_position') ?? 0) + 1);

        $videoPath = null;
        $originalName = null;
        $videoStatus = 'ready';

        // simpan file video ke storage private jika ada file yang diunggah
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $videoPath = $file->store('videos', 'local');
            $originalName = $file->getClientOriginalName();
            $videoStatus = 'ready';
        } elseif ($request->filled('uploaded_video_path')) {
            // jika sebelumnya sudah diunggah via ajax async
            $videoPath = $request->uploaded_video_path;
            $originalName = $request->uploaded_video_name;
            $videoStatus = 'ready';
        }

        // buat data lesson baru pada course
        $course->lessons()->create([
            'title' => $request->title,
            'description' => $request->description,
            'order_position' => $order,
            'video_path' => $videoPath,
            'video_original_name' => $originalName,
            'video_status' => $videoStatus,
        ]);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Materi lesson berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit lesson.
     */
    public function edit(Course $course, Lesson $lesson): View
    {
        return view('lessons.edit', compact('course', 'lesson'));
    }

    /**
     * Perbarui data lesson.
     */
    public function update(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        // validasi input pembaruan lesson
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order_position' => 'required|integer|min:1',
            'video' => 'nullable|file|max:262144|mimetypes:video/*,application/octet-stream|extensions:mp4,mov,avi,webm,mkv,m4v,3gp',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'order_position' => $request->order_position,
        ];

        // cek apakah ada video baru yang diunggah
        if ($request->hasFile('video')) {
            // hapus video lama jika ada
            if ($lesson->video_path && Storage::disk('local')->exists($lesson->video_path)) {
                Storage::disk('local')->delete($lesson->video_path);
            }

            $file = $request->file('video');
            $data['video_path'] = $file->store('videos', 'local');
            $data['video_original_name'] = $file->getClientOriginalName();
            $data['video_status'] = 'ready';
        } elseif ($request->filled('uploaded_video_path')) {
            if ($lesson->video_path && Storage::disk('local')->exists($lesson->video_path)) {
                Storage::disk('local')->delete($lesson->video_path);
            }
            $data['video_path'] = $request->uploaded_video_path;
            $data['video_original_name'] = $request->uploaded_video_name;
            $data['video_status'] = 'ready';
        }

        // simpan perubahan data lesson
        $lesson->update($data);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Materi lesson berhasil diperbarui.');
    }

    /**
     * Hapus lesson beserta file video jika ada.
     */
    public function destroy(Course $course, Lesson $lesson): RedirectResponse
    {
        // hapus file video fisik dari storage
        if ($lesson->video_path && Storage::disk('local')->exists($lesson->video_path)) {
            Storage::disk('local')->delete($lesson->video_path);
        }

        $lesson->delete();

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Materi lesson berhasil dihapus.');
    }

    /**
     * Endpoint upload video asynchronous via AJAX/XHR dengan progress bar.
     */
    public function uploadAsync(Request $request, Course $course): JsonResponse
    {
        // jika ada error bawaan PHP pada file yang diunggah
        if ($request->hasFile('video') && ! $request->file('video')->isValid()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Gagal mengunggah file: '.$request->file('video')->getErrorMessage(),
            ], 422);
        }

        // validasi file video yang diunggah secara async
        $request->validate([
            'video' => 'required|file|max:262144|mimetypes:video/*,application/octet-stream|extensions:mp4,mov,avi,webm,mkv,m4v,3gp',
        ], [
            'video.required' => 'Pilih file video terlebih dahulu.',
            'video.file' => 'File yang diunggah tidak valid.',
            'video.max' => 'Ukuran file video maksimal 250 MB.',
            'video.extensions' => 'Format file video harus mp4, mov, avi, webm, mkv, atau 3gp.',
            'video.mimetypes' => 'Format file video tidak didukung.',
        ]);

        $file = $request->file('video');
        $path = $file->store('videos', 'local');

        return response()->json([
            'status' => 'ready',
            'video_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'message' => 'Video berhasil diunggah.',
        ]);
    }

    /**
     * Pindahkan urutan lesson ke atas (naik).
     */
    public function moveUp(Course $course, Lesson $lesson): RedirectResponse
    {
        // cari lesson tepat sebelum lesson ini
        $previousLesson = $course->lessons()
            ->where('order_position', '<', $lesson->order_position)
            ->orderByDesc('order_position')
            ->first();

        if ($previousLesson) {
            $currentOrder = $lesson->order_position;
            $lesson->update(['order_position' => $previousLesson->order_position]);
            $previousLesson->update(['order_position' => $currentOrder]);
        }

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Urutan materi berhasil dipindahkan.');
    }

    /**
     * Pindahkan urutan lesson ke bawah (turun).
     */
    public function moveDown(Course $course, Lesson $lesson): RedirectResponse
    {
        // cari lesson tepat sesudah lesson ini
        $nextLesson = $course->lessons()
            ->where('order_position', '>', $lesson->order_position)
            ->orderBy('order_position')
            ->first();

        if ($nextLesson) {
            $currentOrder = $lesson->order_position;
            $lesson->update(['order_position' => $nextLesson->order_position]);
            $nextLesson->update(['order_position' => $currentOrder]);
        }

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Urutan materi berhasil dipindahkan.');
    }

    /**
     * Stream / sajikan file video private dengan proteksi otorisasi.
     */
    public function streamVideo(Lesson $lesson): mixed
    {
        $user = auth()->user();

        // jika bukan admin/guru pengelola course, pastikan course sudah published
        if (! $user->can('manage-courses')) {
            if ($lesson->course->status !== 'published') {
                abort(403, 'Akses ditolak. Kursus belum dipublikasikan.');
            }
        }

        // pastikan file video ada di storage private
        if (! $lesson->video_path || ! Storage::disk('local')->exists($lesson->video_path)) {
            abort(404, 'File video tidak ditemukan.');
        }

        // sajikan file video dari storage private
        return Storage::disk('local')->response($lesson->video_path, $lesson->video_original_name ?? 'video.mp4');
    }
}
