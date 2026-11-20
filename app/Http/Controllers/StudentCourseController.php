<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentCourseController extends Controller
{
    /**
     * Tampilkan katalog kursus yang tersedia untuk diambil siswa.
     */
    public function catalog(Request $request): View
    {
        // ambil semua kursus yang berstatus published
        $courses = Course::withCount('lessons')
            ->where('status', 'published')
            ->latest()
            ->get();

        // daftar id kursus yang sudah diambil oleh user saat ini
        $enrolledIds = auth()->check()
            ? auth()->user()->courses()->pluck('courses.id')->toArray()
            : [];

        return view('katalog', compact('courses', 'enrolledIds'));
    }

    /**
     * Tampilkan daftar kursus yang sudah diambil/di-enroll oleh siswa di dashboard.
     */
    public function myCourses(Request $request): View
    {
        // hanya ambil kursus yang sudah diambil oleh siswa yang login
        $courses = $request->user()
            ->courses()
            ->where('status', 'published')
            ->withCount('lessons')
            ->latest()
            ->get();

        return view('my-courses', compact('courses'));
    }

    /**
     * Daftarkan/ambil kursus untuk siswa.
     */
    public function enroll(Request $request, Course $course): RedirectResponse
    {
        if ($course->status !== 'published') {
            abort(404, 'Kursus tidak ditemukan atau belum dipublikasikan.');
        }

        // simpan relasi kursus yang diambil oleh siswa
        $request->user()->courses()->syncWithoutDetaching([$course->id]);

        return redirect()->route('student.courses.show', $course)
            ->with('success', 'Selamat! Anda berhasil mengambil kursus "'.$course->title.'".');
    }

    /**
     * Tampilkan detail course dan pemutar materi lesson untuk student.
     */
    public function show(Request $request, Course $course): View
    {
        // student hanya boleh melihat course yang sudah dipublish
        if ($course->status !== 'published') {
            abort(404, 'Kursus tidak ditemukan.');
        }

        $user = $request->user();

        // jika student belum terdaftar, otomatis daftarkan saat mengakses ruang belajar
        if ($user && ! $user->can('manage-courses') && ! $user->hasEnrolled($course)) {
            $user->courses()->syncWithoutDetaching([$course->id]);
        }

        // ambil semua materi lesson yang sudah diurutkan
        $lessons = $course->lessons()->orderBy('order_position')->get();

        // tentukan materi lesson yang sedang diputar
        $activeLesson = null;
        if ($request->filled('lesson')) {
            $activeLesson = $course->lessons()->where('id', $request->lesson)->first();
        }

        if (! $activeLesson) {
            $activeLesson = $lessons->first();
        }

        return view('courses.learn', compact('course', 'lessons', 'activeLesson'));
    }
}
