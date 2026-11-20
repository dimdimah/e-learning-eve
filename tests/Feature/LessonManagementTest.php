<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class LessonManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bikin user dengan role tertentu untuk test.
     */
    private function makeUser(string $role): User
    {
        $all = ['manage-courses', 'manage-users', 'manage-roles', 'view-my-courses'];
        foreach ($all as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        Role::findOrCreate($role, 'web');
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $user->assignRole($role);

        $access = [
            'admin' => $all,
            'guru' => ['manage-courses', 'view-my-courses'],
            'student' => ['view-my-courses'],
        ];
        $user->roles->first()->syncPermissions($access[$role] ?? []);

        return $user;
    }

    public function test_admin_can_view_course_detail_with_lessons(): void
    {
        $admin = $this->makeUser('admin');
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Pengenalan Laravel',
            'order_position' => 1,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.courses.show', $course));

        $response->assertStatus(200);
        $response->assertSee('Pengenalan Laravel');
        $response->assertSee('+ Tambah Lesson');
    }

    public function test_admin_can_create_lesson_with_video_file(): void
    {
        Storage::fake('local');
        $admin = $this->makeUser('admin');
        $course = Course::factory()->create();

        $file = UploadedFile::fake()->create('sample-intro.mp4', 500, 'video/mp4');

        $response = $this->actingAs($admin)->post(route('admin.courses.lessons.store', $course), [
            'title' => 'Materi Pertama',
            'description' => 'Deskripsi materi pengantar',
            'order_position' => 1,
            'video' => $file,
        ]);

        $response->assertRedirect(route('admin.courses.show', $course));
        $this->assertDatabaseHas('lessons', [
            'course_id' => $course->id,
            'title' => 'Materi Pertama',
            'order_position' => 1,
            'video_original_name' => 'sample-intro.mp4',
            'video_status' => 'ready',
        ]);

        $lesson = Lesson::where('title', 'Materi Pertama')->first();
        $this->assertNotNull($lesson->video_path);
        Storage::disk('local')->assertExists($lesson->video_path);
    }

    public function test_admin_can_update_lesson_and_change_order(): void
    {
        $admin = $this->makeUser('admin');
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Judul Lama',
            'order_position' => 1,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.courses.lessons.update', [$course, $lesson]), [
            'title' => 'Judul Baru Diperbarui',
            'description' => 'Deskripsi baru',
            'order_position' => 5,
        ]);

        $response->assertRedirect(route('admin.courses.show', $course));
        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'title' => 'Judul Baru Diperbarui',
            'order_position' => 5,
        ]);
    }

    public function test_admin_can_reorder_lessons_using_move_up_and_down(): void
    {
        $admin = $this->makeUser('admin');
        $course = Course::factory()->create();

        $lesson1 = Lesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Lesson 1',
            'order_position' => 1,
        ]);
        $lesson2 = Lesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Lesson 2',
            'order_position' => 2,
        ]);

        // geser lesson 1 ke bawah
        $response = $this->actingAs($admin)->patch(route('admin.courses.lessons.move-down', [$course, $lesson1]));
        $response->assertRedirect(route('admin.courses.show', $course));

        $lesson1->refresh();
        $lesson2->refresh();

        $this->assertEquals(2, $lesson1->order_position);
        $this->assertEquals(1, $lesson2->order_position);

        // geser lesson 1 kembali ke atas
        $this->actingAs($admin)->patch(route('admin.courses.lessons.move-up', [$course, $lesson1]));
        $lesson1->refresh();
        $lesson2->refresh();

        $this->assertEquals(1, $lesson1->order_position);
        $this->assertEquals(2, $lesson2->order_position);
    }

    public function test_admin_can_delete_lesson(): void
    {
        Storage::fake('local');
        $admin = $this->makeUser('admin');
        $course = Course::factory()->create();

        $file = UploadedFile::fake()->create('delete-me.mp4', 200, 'video/mp4');
        $path = $file->store('videos', 'local');

        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Akan Dihapus',
            'video_path' => $path,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.courses.lessons.destroy', [$course, $lesson]));

        $response->assertRedirect(route('admin.courses.show', $course));
        $this->assertDatabaseMissing('lessons', ['id' => $lesson->id]);
        Storage::disk('local')->assertMissing($path);
    }

    public function test_admin_can_upload_video_async(): void
    {
        Storage::fake('local');
        $admin = $this->makeUser('admin');
        $course = Course::factory()->create();

        $file = UploadedFile::fake()->create('async-upload.mp4', 1000, 'video/mp4');

        $response = $this->actingAs($admin)->postJson(route('admin.courses.lessons.upload-async', $course), [
            'video' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'video_path', 'original_name']);
        $this->assertEquals('ready', $response->json('status'));
        Storage::disk('local')->assertExists($response->json('video_path'));
    }

    public function test_student_cannot_access_admin_lesson_routes(): void
    {
        $student = $this->makeUser('student');
        $course = Course::factory()->create();

        $response = $this->actingAs($student)->get(route('admin.courses.lessons.create', $course));
        $response->assertStatus(403);
    }

    public function test_student_can_view_published_course_detail_and_lessons(): void
    {
        $student = $this->makeUser('student');
        $course = Course::factory()->create(['status' => 'published']);
        $lesson1 = Lesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Materi Pertama Student',
            'order_position' => 1,
        ]);
        $lesson2 = Lesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Materi Kedua Student',
            'order_position' => 2,
        ]);

        $response = $this->actingAs($student)->get(route('student.courses.show', $course));

        $response->assertStatus(200);
        $response->assertSee('Materi Pertama Student');
        $response->assertSee('Materi Kedua Student');
    }

    public function test_student_cannot_view_draft_course_detail(): void
    {
        $student = $this->makeUser('student');
        $course = Course::factory()->create(['status' => 'draft']);

        $response = $this->actingAs($student)->get(route('student.courses.show', $course));

        $response->assertStatus(404);
    }

    public function test_student_can_stream_video_of_published_course_lesson(): void
    {
        Storage::fake('local');
        $student = $this->makeUser('student');
        $course = Course::factory()->create(['status' => 'published']);

        $file = UploadedFile::fake()->create('published-video.mp4', 500, 'video/mp4');
        $path = $file->store('videos', 'local');

        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'video_path' => $path,
            'video_status' => 'ready',
        ]);

        $response = $this->actingAs($student)->get(route('lessons.video', $lesson));

        $response->assertStatus(200);
    }

    public function test_student_cannot_stream_video_of_draft_course_lesson(): void
    {
        Storage::fake('local');
        $student = $this->makeUser('student');
        $course = Course::factory()->create(['status' => 'draft']);

        $file = UploadedFile::fake()->create('draft-video.mp4', 500, 'video/mp4');
        $path = $file->store('videos', 'local');

        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'video_path' => $path,
            'video_status' => 'ready',
        ]);

        $response = $this->actingAs($student)->get(route('lessons.video', $lesson));

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_stream_video(): void
    {
        $course = Course::factory()->create(['status' => 'published']);
        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'video_path' => 'videos/sample.mp4',
        ]);

        $response = $this->get(route('lessons.video', $lesson));

        $response->assertRedirect(route('login'));
    }
}
