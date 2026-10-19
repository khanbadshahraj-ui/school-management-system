<?php

namespace Tests\Feature;

use App\Models\Campus;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Tenancy\TenantContext;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_school_queries_hide_other_school_records_and_reject_cross_school_campus(): void
    {
        $a = School::create(['name' => 'A', 'slug' => 'a']);
        $b = School::create(['name' => 'B', 'slug' => 'b']);
        app(TenantContext::class)->set($b->id);
        $campusB = Campus::create(['name' => 'B campus', 'code' => 'BC']);
        Student::create(['campus_id' => $campusB->id, 'admission_number' => 'B1', 'first_name' => 'Private', 'last_name' => 'Student', 'status' => 'enrolled']);
        app(TenantContext::class)->clear();
        $user = new User;
        $user->forceFill(['name' => 'A Admin', 'email' => 'a@example.test', 'password' => 'a-long-testing-password', 'school_id' => $a->id, 'role' => 'school_admin']);
        $user->save();
        $this->actingAs($user)->get('/students')->assertOk()->assertDontSee('Private');
        $this->actingAs($user)->post('/students', ['campus_id' => $campusB->id, 'admission_number' => 'A1', 'first_name' => 'Attempt', 'last_name' => 'Cross-school'])->assertSessionHasErrors('campus_id');
    }

    public function test_database_constraint_prevents_cross_school_relationship_even_without_model_scope(): void
    {
        $a = School::create(['name' => 'A', 'slug' => 'a']);
        $b = School::create(['name' => 'B', 'slug' => 'b']);
        app(TenantContext::class)->set($b->id);
        $campus = Campus::create(['name' => 'B campus', 'code' => 'BC']);
        $this->expectException(QueryException::class);
        DB::table('students')->insert(['school_id' => $a->id, 'campus_id' => $campus->id, 'admission_number' => 'A1', 'first_name' => 'Cross', 'last_name' => 'Tenant', 'status' => 'enrolled', 'created_at' => now(), 'updated_at' => now()]);
    }
}
