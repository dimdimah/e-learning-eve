<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // pastikan direktori video dan file video contoh sudah siap di storage lokal
        Artisan::call('app:setup-video');

        // permission dulu, ini yang jadi saklar on/off akses tiap role
        Permission::firstOrCreate(['name' => 'manage-courses', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage-users', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage-roles', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view-my-courses', 'guard_name' => 'web']);

        // flush cache permission biar yang baru dibuat kebaca middleware dan syncPermissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // buat role lalu kasih akses defaultnya
        $admin = Role::findOrCreate('admin', 'web');
        $admin->syncPermissions(['manage-courses', 'manage-users', 'manage-roles', 'view-my-courses']);

        $guru = Role::findOrCreate('guru', 'web');
        $guru->syncPermissions(['manage-courses', 'view-my-courses']);

        $student = Role::findOrCreate('student', 'web');
        $student->syncPermissions(['view-my-courses']);

        // akun admin demo (password: password) untuk keperluan demo
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Demo',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('admin');

        // akun guru demo (password: password) untuk keperluan demo
        $guruUser = User::firstOrCreate(
            ['email' => 'guru@example.com'],
            [
                'name' => 'Guru Demo',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $guruUser->assignRole('guru');

        // akun student demo (password: password) untuk keperluan demo
        $studentUser = User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student Demo',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $studentUser->assignRole('student');

        // data course published 1: Mastering Laravel
        $course1 = Course::firstOrCreate(
            ['title' => 'Mastering Laravel untuk Pemula'],
            [
                'description' => "Panduan lengkap mempelajari framework Laravel mulai dari nol hingga mahir membuat web dinamis.\n\nCocok untuk pemula yang ingin memahami arsitektur MVC, routing, controller, database migrations, dan Eloquent ORM.",
                'status' => 'published',
            ]
        );

        Lesson::firstOrCreate(
            ['course_id' => $course1->id, 'order_position' => 1],
            [
                'title' => 'Instalasi dan Konfigurasi Lingkungan Laravel',
                'description' => 'Mempersiapkan tools development: PHP, Composer, SQLite/MySQL, dan struktur dasar folder Laravel.',
                'video_path' => 'videos/sample.mp4',
                'video_original_name' => '01-instalasi-laravel.mp4',
                'video_status' => 'ready',
            ]
        );

        Lesson::firstOrCreate(
            ['course_id' => $course1->id, 'order_position' => 2],
            [
                'title' => 'Routing dan Controller Dasar',
                'description' => 'Memahami cara kerja route di web.php, membuat controller, dan passing data ke Blade view.',
                'video_path' => 'videos/sample.mp4',
                'video_original_name' => '02-routing-controller.mp4',
                'video_status' => 'ready',
            ]
        );

        Lesson::firstOrCreate(
            ['course_id' => $course1->id, 'order_position' => 3],
            [
                'title' => 'Blade Templating dan Komponen UI',
                'description' => 'Memanfaatkan Blade directive, layout inheritance, serta styling modern dengan Tailwind CSS.',
                'video_path' => 'videos/sample.mp4',
                'video_original_name' => '03-blade-tailwind.mp4',
                'video_status' => 'ready',
            ]
        );

        Lesson::firstOrCreate(
            ['course_id' => $course1->id, 'order_position' => 4],
            [
                'title' => 'Database Migration dan Eloquent ORM',
                'description' => 'Mendesain skema tabel melalui migration dan melakukan operasi query database menggunakan Eloquent Model.',
                'video_path' => null,
                'video_original_name' => null,
                'video_status' => 'ready',
            ]
        );

        // data course published 2: Dasar PHP
        $course2 = Course::firstOrCreate(
            ['title' => 'Dasar Pemrograman PHP Modern'],
            [
                'description' => 'Fondasi kuat pemrograman PHP 8.x, tipe data, fungsi, dan Object-Oriented Programming (OOP) yang bersih dan terstruktur.',
                'status' => 'published',
            ]
        );

        Lesson::firstOrCreate(
            ['course_id' => $course2->id, 'order_position' => 1],
            [
                'title' => 'Pengenalan Sintaks dan Fitur PHP 8.x',
                'description' => 'Menjelajahi fitur-fitur baru PHP: named arguments, constructor promotion, dan match expression.',
                'video_path' => 'videos/sample.mp4',
                'video_original_name' => '01-php-dasar.mp4',
                'video_status' => 'ready',
            ]
        );

        Lesson::firstOrCreate(
            ['course_id' => $course2->id, 'order_position' => 2],
            [
                'title' => 'Struktur Data Array dan Manipulasi Data',
                'description' => 'Bekerja dengan associative array, array functions, dan pemrosesan collection sederhana.',
                'video_path' => 'videos/sample.mp4',
                'video_original_name' => '02-php-array.mp4',
                'video_status' => 'ready',
            ]
        );

        Lesson::firstOrCreate(
            ['course_id' => $course2->id, 'order_position' => 3],
            [
                'title' => 'Konsep Object-Oriented Programming (OOP)',
                'description' => 'Class, Object, Inheritance, Visibility, dan Interface dalam ekosistem PHP modern.',
                'video_path' => null,
                'video_original_name' => null,
                'video_status' => 'ready',
            ]
        );

        // data course published 3: Tailwind CSS
        $course3 = Course::firstOrCreate(
            ['title' => 'Desain Web Responsif dengan Tailwind CSS'],
            [
                'description' => 'Membangun tampilan antarmuka web modern yang cepat, indah, dan sepenuhnya responsif di semua ukuran layar.',
                'status' => 'published',
            ]
        );

        Lesson::firstOrCreate(
            ['course_id' => $course3->id, 'order_position' => 1],
            [
                'title' => 'Setup Tailwind CSS & Fondasi Utility Classes',
                'description' => 'Memahami filosofi utility-first, konfigurasi tema, dan spacing system.',
                'video_path' => 'videos/sample.mp4',
                'video_original_name' => '01-tailwind-setup.mp4',
                'video_status' => 'ready',
            ]
        );

        Lesson::firstOrCreate(
            ['course_id' => $course3->id, 'order_position' => 2],
            [
                'title' => 'Flexbox dan Grid Layout Responsif',
                'description' => 'Menyusun layout dashboard responsif, multi-column card, dan auto-wrap grid.',
                'video_path' => 'videos/sample.mp4',
                'video_original_name' => '02-tailwind-grid.mp4',
                'video_status' => 'ready',
            ]
        );

        // data course draft: tidak boleh tampil ke student
        $courseDraft = Course::firstOrCreate(
            ['title' => 'Teknik Advanced Video Streaming (Draft)'],
            [
                'description' => 'Materi lanjutan seputar streaming video, chunking, dan enkripsi konten. Masih dalam tahap pengembangan kurikulum.',
                'status' => 'draft',
            ]
        );

        Lesson::firstOrCreate(
            ['course_id' => $courseDraft->id, 'order_position' => 1],
            [
                'title' => 'Pengenalan Video Streaming Architecture',
                'description' => 'Draft materi mengenai chunking dan byte-range request.',
                'video_path' => null,
                'video_original_name' => null,
                'video_status' => 'ready',
            ]
        );

        // daftarkan / assign kursus 1 dan 2 ke akun student demo agar siap dicoba
        $studentUser->courses()->syncWithoutDetaching([$course1->id, $course2->id]);
    }
}
