<?php

namespace Tests\Feature;

use App\Models\Campus;
use App\Models\School;
use App\Models\User;
use App\Tenancy\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolOperationsTest extends TestCase
{
    use RefreshDatabase;

    private function operator(string $role = 'school_admin', bool $active = true): User
    {
        $school = School::create(['name' => 'Example School', 'slug' => 'example']);

        return User::factory()->create(['school_id' => $school->id, 'role' => $role, 'is_active' => $active]);
    }

    public function test_admin_can_enroll_student_in_own_campus_and_an_audit_record_is_saved(): void
    {
        $user = $this->operator();
        app(TenantContext::class)->set($user->school_id);
        $campus = Campus::create(['name' => 'Main', 'code' => 'MAIN']);
        app(TenantContext::class)->clear();
        $this->actingAs($user)->post('/students', ['campus_id' => $campus->id, 'admission_number' => 'ADM-001', 'first_name' => 'Amina', 'last_name' => 'Khan'])
            ->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseHas('students', ['school_id' => $user->school_id, 'campus_id' => $campus->id, 'admission_number' => 'ADM-001']);
        $this->assertDatabaseHas('audit_events', ['school_id' => $user->school_id, 'user_id' => $user->id, 'action' => 'student.created']);
    }

    public function test_teacher_cannot_change_school_branding(): void
    {
        $user = $this->operator('teacher');
        $this->actingAs($user)->patch('/settings', ['name' => 'Unauthorized'])->assertForbidden();
        $this->assertDatabaseHas('schools', ['id' => $user->school_id, 'name' => 'Example School']);
    }

    public function test_disabled_account_cannot_enter_school_workspace(): void
    {
        $user = $this->operator('school_admin', false);
        $this->actingAs($user)->get('/dashboard')->assertForbidden();
    }

    public function test_guest_is_redirected_to_sign_in(): void
    {
        $this->get('/students')->assertRedirect('/login');
    }
}
