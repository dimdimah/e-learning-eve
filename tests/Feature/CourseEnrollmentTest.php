<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CourseEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    private function makeStudent(): User
    {
        Permission::firstOrCreate(['name' => 'view-my-courses', 'guard_name' => 'web']);
        $role = Role::findOrCreate('student', 'web');
        $role->syncPermissions(['view-my-courses']);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $student = User::factory()->create(['email_verified_at' => now()]);
        $student->assignRole('student');

        return $student;
    }

    public function test_public_and_student_can_view_course_catalog(): void
    {
        $published = Course::factory()->create(['status' => 'published', 'title' => 'Laravel Mastery']);
        $draft = Course::factory()->create(['status' => 'draft', 'title' => 'Secret Draft']);

        $response = $this->get(route('katalog'));

        $response->assertStatus(200);
        $response->assertSee('Laravel Mastery');
        $response->assertDontSee('Secret Draft');
    }

    public function test_landing_page_displays_published_course_catalog(): void
    {
        $published = Course::factory()->create(['status' => 'published', 'title' => 'Modern PHP']);
        $draft = Course::factory()->create(['status' => 'draft', 'title' => 'Unpublished Draft']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Modern PHP');
        $response->assertDontSee('Unpublished Draft');
    }

    public function test_student_can_enroll_in_published_course(): void
    {
        $student = $this->makeStudent();
        $course = Course::factory()->create(['status' => 'published', 'title' => 'Vue 3 Dasar']);

        $response = $this->actingAs($student)->post(route('courses.enroll', $course));

        $response->assertRedirect(route('student.courses.show', $course));
        $this->assertTrue($student->hasEnrolled($course));
        $this->assertDatabaseHas('course_user', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_student_cannot_enroll_in_draft_course(): void
    {
        $student = $this->makeStudent();
        $draft = Course::factory()->create(['status' => 'draft']);

        $response = $this->actingAs($student)->post(route('courses.enroll', $draft));

        $response->assertStatus(404);
        $this->assertFalse($student->hasEnrolled($draft));
    }

    public function test_my_courses_only_displays_enrolled_courses(): void
    {
        $student = $this->makeStudent();
        $enrolledCourse = Course::factory()->create(['status' => 'published', 'title' => 'Kursus Diambil']);
        $otherCourse = Course::factory()->create(['status' => 'published', 'title' => 'Kursus Belum Diambil']);

        // student enroll di kursus 1 saja
        $student->courses()->attach($enrolledCourse->id);

        $response = $this->actingAs($student)->get(route('my.courses'));

        $response->assertStatus(200);
        $response->assertSee('Kursus Diambil');
        $response->assertDontSee('Kursus Belum Diambil');
    }

    public function test_my_courses_shows_empty_state_when_no_course_enrolled(): void
    {
        $student = $this->makeStudent();
        Course::factory()->create(['status' => 'published', 'title' => 'Materi Rahasia Python']);

        $response = $this->actingAs($student)->get(route('my.courses'));

        $response->assertStatus(200);
        $response->assertSee('Belum Ada Kursus yang Diambil');
        $response->assertDontSee('Materi Rahasia Python');
    }

    public function test_accessing_learning_room_auto_enrolls_student(): void
    {
        $student = $this->makeStudent();
        $course = Course::factory()->create(['status' => 'published']);

        $this->assertFalse($student->hasEnrolled($course));

        $response = $this->actingAs($student)->get(route('student.courses.show', $course));

        $response->assertStatus(200);
        $this->assertTrue($student->fresh()->hasEnrolled($course));
    }

    public function test_duplicate_enrollment_does_not_throw_error(): void
    {
        $student = $this->makeStudent();
        $course = Course::factory()->create(['status' => 'published']);

        // enroll pertama
        $this->actingAs($student)->post(route('courses.enroll', $course));
        // enroll kedua (seharusnya aman tanpa error duplikasi)
        $response = $this->actingAs($student)->post(route('courses.enroll', $course));

        $response->assertRedirect(route('student.courses.show', $course));
        $this->assertCount(1, $student->fresh()->courses);
    }
}
