<?php

namespace Tests\Feature;

use App\Contracts\SchoolLogoStorage;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SchoolLogoTest extends TestCase
{
    use RefreshDatabase;

    private function owner(string $slug, string $role = 'school_admin'): User
    {
        $school = School::create(['name' => $slug, 'slug' => $slug]);

        return User::factory()->create(['school_id' => $school->id, 'role' => $role, 'is_active' => true]);
    }

    private function image(): string
    {
        return base64_decode('iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAAFklEQVR4nGOQSzlBEmIY1TCqYfhqAADGmEoQckuN7wAAAABJRU5ErkJggg==');
    }

    public function test_admin_logo_is_stored_in_database_and_retrieved_after_storage_service_is_recreated(): void
    {
        $owner = $this->owner('alpha');
        $this->actingAs($owner)->post('/school/logo', ['logo' => UploadedFile::fake()->createWithContent('logo.png', $this->image())])->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseHas('school_logos', ['school_id' => $owner->school_id, 'content_hash' => hash('sha256', $this->image())]);
        $this->assertDatabaseHas('audit_events', ['school_id' => $owner->school_id, 'action' => 'school.logo.updated']);
        $this->app->forgetInstance(SchoolLogoStorage::class);
        $this->get('/school/logo')->assertOk()->assertContent($this->image())->assertHeader('Content-Type', 'image/png');
    }

    public function test_logo_endpoint_ignores_another_school_id(): void
    {
        $a = $this->owner('alpha');
        $b = $this->owner('beta');
        app(SchoolLogoStorage::class)->put($a->school_id, $this->image(), 'image/png');
        $this->actingAs($b)->get('/school/logo?school_id='.$a->school_id)->assertNotFound();
    }

    public function test_teacher_cannot_replace_logo(): void
    {
        $teacher = $this->owner('alpha', 'teacher');
        $this->actingAs($teacher)->post('/school/logo', ['logo' => UploadedFile::fake()->createWithContent('logo.png', $this->image())])->assertForbidden();
        $this->assertDatabaseCount('school_logos', 0);
    }

    public function test_svg_upload_is_rejected(): void
    {
        $owner = $this->owner('alpha');
        $this->actingAs($owner)->post('/school/logo', ['logo' => UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"/>')])->assertSessionHasErrors('logo');
        $this->assertDatabaseCount('school_logos', 0);
    }
}
