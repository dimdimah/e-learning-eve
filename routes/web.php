<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\UserManagementController;
use App\Models\Course;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // ambil daftar kursus yang sudah dipublish untuk katalog di landing page
    $courses = Course::withCount('lessons')->where('status', 'published')->latest()->take(6)->get();
    $enrolledIds = auth()->check() ? auth()->user()->courses()->pluck('courses.id')->toArray() : [];

    return view('welcome', compact('courses', 'enrolledIds'));
});

// katalog kursus lengkap
Route::get('/katalog', [StudentCourseController::class, 'catalog'])->name('katalog');

Route::get('/dashboard', function () {
    // dashboard ditampilkan beda sesuai permission user
    if (auth()->user()->can('manage-courses')) {
        $totalCourse = Course::count();

        return view('admin.dashboard', compact('totalCourse'));
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// daftar kursus yang sudah diambil (di-assign) oleh student
Route::get('/my-courses', [StudentCourseController::class, 'myCourses'])
    ->middleware(['auth', 'verified', 'permission:view-my-courses'])
    ->name('my.courses');

// aksi student mengambil / mendaftar kursus
Route::post('/courses/{course}/enroll', [StudentCourseController::class, 'enroll'])
    ->middleware(['auth', 'verified', 'permission:view-my-courses'])
    ->name('courses.enroll');

// student melihat detail course dan memutar materi lesson
Route::get('/courses/{course}', [StudentCourseController::class, 'show'])
    ->middleware(['auth', 'verified', 'permission:view-my-courses'])
    ->name('student.courses.show');

// stream file video materi dengan proteksi otorisasi
Route::get('/lessons/{lesson}/video', [LessonController::class, 'streamVideo'])
    ->middleware('auth')
    ->name('lessons.video');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// halaman kelola course, aksesnya diatur permission manage-courses
Route::middleware(['auth', 'permission:manage-courses'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('courses', CourseController::class);

    // kelola materi lesson pada course
    Route::get('/courses/{course}/lessons/create', [LessonController::class, 'create'])->name('courses.lessons.create');
    Route::post('/courses/{course}/lessons', [LessonController::class, 'store'])->name('courses.lessons.store');
    Route::get('/courses/{course}/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('courses.lessons.edit');
    Route::put('/courses/{course}/lessons/{lesson}', [LessonController::class, 'update'])->name('courses.lessons.update');
    Route::delete('/courses/{course}/lessons/{lesson}', [LessonController::class, 'destroy'])->name('courses.lessons.destroy');
    Route::post('/courses/{course}/lessons/upload-async', [LessonController::class, 'uploadAsync'])->name('courses.lessons.upload-async');
    Route::patch('/courses/{course}/lessons/{lesson}/move-up', [LessonController::class, 'moveUp'])->name('courses.lessons.move-up');
    Route::patch('/courses/{course}/lessons/{lesson}/move-down', [LessonController::class, 'moveDown'])->name('courses.lessons.move-down');
});

// halaman management role dan user, aksesnya diatur permission manage-roles dan manage-users
Route::middleware(['auth', 'permission:manage-users'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserManagementController::class);
});

Route::middleware(['auth', 'permission:manage-roles'])->prefix('admin')->name('admin.')->group(function () {
    // on/off akses role langsung dari halaman daftar role
    Route::patch('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions');
    Route::resource('roles', RoleController::class);
});

require __DIR__.'/auth.php';
