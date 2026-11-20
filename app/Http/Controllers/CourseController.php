<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Tampilkan daftar semua course.
     */
    public function index()
    {
        $courses = Course::latest()->get();

        return view('courses.index', compact('courses'));
    }

    /**
     * Tampilkan form tambah course.
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Simpan course baru dari form tambah.
     */
    public function store(Request $request)
    {
        // validasi input form dulu sebelum disimpan
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);

        Course::create($data);

        return redirect(route('admin.courses.index'))->with('success', 'Course berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail satu course.
     */
    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }

    /**
     * Tampilkan form ubah course.
     */
    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    /**
     * Simpan perubahan course dari form ubah.
     */
    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);

        $course->update($data);

        return redirect(route('admin.courses.index'))->with('success', 'Course berhasil diubah.');
    }

    /**
     * Hapus satu course.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect(route('admin.courses.index'))->with('success', 'Course berhasil dihapus.');
    }
}
