<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CourseManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bikin user dengan role tertentu untuk test.
     */
    private function makeUser(string $role): User
    {
        // permission dibuat dulu karena dipakai middleware route, aksesnya sama seperti seeder
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

        // kasih akses default sesuai role-nya
        $access = [
            'admin' => $all,
            'guru' => ['manage-courses', 'view-my-courses'],
            'student' => ['view-my-courses'],
        ];
        $user->roles->first()->syncPermissions($access[$role] ?? []);

        return $user;
    }

    public function test_admin_can_view_course_list(): void
    {
        $admin = $this->makeUser('admin');

        $response = $this->actingAs($admin)->get('/admin/courses');

        $response->assertStatus(200);
    }

    public function test_student_cannot_access_course_pages(): void
    {
        $student = $this->makeUser('student');

        $response = $this->actingAs($student)->get('/admin/courses');

        $response->assertStatus(403);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/courses');

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_is_different_for_admin_and_student(): void
    {
        $admin = $this->makeUser('admin');
        $student = $this->makeUser('student');

        $this->actingAs($admin)->get('/dashboard')->assertViewIs('admin.dashboard');
        $this->actingAs($student)->get('/dashboard')->assertViewIs('dashboard');
    }

    public function test_admin_can_create_course(): void
    {
        $admin = $this->makeUser('admin');

        $response = $this->actingAs($admin)->post('/admin/courses', [
            'title' => 'Belajar Laravel dari Nol',
            'description' => 'Course untuk pemula',
            'status' => 'published',
        ]);

        $response->assertRedirect('/admin/courses');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('courses', [
            'title' => 'Belajar Laravel dari Nol',
            'status' => 'published',
        ]);
    }

    public function test_create_course_requires_title(): void
    {
        $admin = $this->makeUser('admin');

        $response = $this->actingAs($admin)
            ->from('/admin/courses/create')
            ->post('/admin/courses', [
                'description' => 'Deskripsi saja tanpa judul',
                'status' => 'draft',
            ]);

        $response->assertRedirect('/admin/courses/create');
        $response->assertSessionHasErrors('title');
    }

    public function test_admin_can_update_course(): void
    {
        $admin = $this->makeUser('admin');
        $course = Course::factory()->create();

        $response = $this->actingAs($admin)->put('/admin/courses/'.$course->id, [
            'title' => 'Judul Baru',
            'description' => 'Deskripsi baru',
            'status' => 'draft',
        ]);

        $response->assertRedirect('/admin/courses');
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Judul Baru',
        ]);
    }

    public function test_admin_can_delete_course(): void
    {
        $admin = $this->makeUser('admin');
        $course = Course::factory()->create();

        $response = $this->actingAs($admin)->delete('/admin/courses/'.$course->id);

        $response->assertRedirect('/admin/courses');
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    public function test_guru_can_view_course_list(): void
    {
        $guru = $this->makeUser('guru');

        $response = $this->actingAs($guru)->get('/admin/courses');

        $response->assertStatus(200);
    }

    public function test_guru_cannot_access_user_management(): void
    {
        $guru = $this->makeUser('guru');

        $response = $this->actingAs($guru)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_guru_uses_admin_dashboard(): void
    {
        $guru = $this->makeUser('guru');

        $this->actingAs($guru)->get('/dashboard')->assertViewIs('admin.dashboard');
    }

    public function test_student_can_view_my_courses(): void
    {
        $student = $this->makeUser('student');
        Course::factory()->create(['status' => 'published']);

        $response = $this->actingAs($student)->get('/my-courses');

        $response->assertStatus(200);
        $response->assertSee('Kursus Saya');
    }

    public function test_my_courses_only_shows_published(): void
    {
        $student = $this->makeUser('student');
        $published = Course::factory()->create(['title' => 'Course Aktif', 'status' => 'published']);
        $draft = Course::factory()->create(['title' => 'Course Rahasia', 'status' => 'draft']);

        // siswa mengambil kursus
        $student->courses()->attach([$published->id, $draft->id]);

        $response = $this->actingAs($student)->get('/my-courses');

        $response->assertSee('Course Aktif');
        $response->assertDontSee('Course Rahasia');
    }

    public function test_student_sees_my_courses_menu_in_sidebar(): void
    {
        $student = $this->makeUser('student');

        $response = $this->actingAs($student)->get('/dashboard');

        $response->assertSee('Kursus Saya');
        $response->assertDontSee('Kelola User');
    }

    public function test_guru_sees_course_menu_not_user_menu(): void
    {
        $guru = $this->makeUser('guru');

        $response = $this->actingAs($guru)->get('/dashboard');

        $response->assertSee('Kelola Course');
        $response->assertDontSee('Kelola User');
        $response->assertDontSee('Kelola Role');
    }

    public function test_revoking_permission_blocks_course_access(): void
    {
        $guru = $this->makeUser('guru');

        // cabut akses kelola course dari role guru
        $guru->roles->first()->revokePermissionTo('manage-courses');
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->actingAs($guru)->get('/admin/courses')->assertForbidden();
    }

    public function test_admin_can_toggle_permission_from_role_page(): void
    {
        $admin = $this->makeUser('admin');
        $guru = Role::findOrCreate('guru', 'web');
        $manageUsers = Permission::findOrCreate('manage-users', 'web');

        // admin aktifkan akses manage-users untuk role guru lewat centang di halaman daftar
        $this->actingAs($admin)->patch('/admin/roles/'.$guru->id.'/permissions', [
            'permission_ids' => [$manageUsers->id],
        ])->assertRedirect('/admin/roles');

        $this->assertTrue($guru->fresh()->hasPermissionTo('manage-users'));
    }

    public function test_unchecking_all_permissions_revokes_access(): void
    {
        $admin = $this->makeUser('admin');
        $guru = Role::findOrCreate('guru', 'web');
        $guru->syncPermissions(['manage-courses']);
        $this->assertTrue($guru->hasPermissionTo('manage-courses'));

        // admin klik "Kosongkan" di halaman daftar role, jadi tidak ada centang terkirim
        $this->actingAs($admin)->patch('/admin/roles/'.$guru->id.'/permissions')->assertRedirect('/admin/roles');

        $this->assertFalse($guru->fresh()->hasPermissionTo('manage-courses'));
    }
}
