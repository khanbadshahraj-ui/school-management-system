<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DemoBootstrapTest extends TestCase
{
    use RefreshDatabase;

    private function demo(): void
    {
        config(['deployment.profile' => 'demo', 'deployment.demo_mode' => true, 'deployment.bootstrap.enabled' => true, 'deployment.bootstrap.email' => 'owner@example.test', 'deployment.bootstrap.password_hash' => Hash::make('CI-only-example-password'), 'deployment.bootstrap.school_name' => 'Demo School', 'deployment.bootstrap.school_slug' => 'demo-school', 'deployment.bootstrap.seed' => true]);
    }

    public function test_bootstrap_is_repeatable_and_preserves_password_and_school_changes(): void
    {
        $this->demo();
        $this->artisan('school:bootstrap-demo')->assertSuccessful();
        $owner = User::where('email', 'owner@example.test')->firstOrFail();
        $owner->school->update(['name' => 'Renamed School']);
        $owner->forceFill(['password' => Hash::make('Changed-owner-password')])->save();
        $hash = $owner->password;
        $this->artisan('school:bootstrap-demo')->assertSuccessful();
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('schools', 1);
        $this->assertDatabaseCount('students', 10);
        $this->assertSame($hash, $owner->fresh()->password);
        $this->assertSame('Renamed School', $owner->school->fresh()->name);
    }

    public function test_existing_identity_in_another_school_is_not_promoted_or_changed(): void
    {
        $this->demo();
        $school = School::create(['name' => 'Other School', 'slug' => 'other']);
        $owner = User::factory()->create(['email' => 'owner@example.test', 'school_id' => $school->id, 'role' => 'teacher']);
        $this->artisan('school:bootstrap-demo')->assertFailed();
        $this->assertSame('teacher', $owner->fresh()->role);
        $this->assertDatabaseCount('schools', 1);
    }

    public function test_bootstrap_refuses_production_profile(): void
    {
        $this->demo();
        config(['deployment.profile' => 'production']);
        $this->artisan('school:bootstrap-demo')->assertFailed();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_readiness_works_without_redis(): void
    {
        config(['session.driver' => 'array', 'cache.default' => 'array', 'queue.default' => 'sync', 'database.redis.default.host' => 'unreachable.invalid']);
        $this->get('/ready')->assertOk();
    }
}
