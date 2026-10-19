<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DemoDeploymentTest extends TestCase
{
    private function demo(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        config(['app.url' => 'https://school.example.test', 'app.debug' => false, 'app.key' => 'base64:'.base64_encode(str_repeat('a', 32)),
            'deployment.profile' => 'demo', 'deployment.demo_mode' => true, 'deployment.logo_storage' => 'database', 'deployment.bulk_uploads_enabled' => false, 'deployment.external_messages_enabled' => false, 'deployment.live_payments_enabled' => false,
            'session.driver' => 'database', 'session.secure' => true, 'session.encrypt' => true, 'cache.default' => 'database', 'queue.default' => 'sync', 'mail.default' => 'array']);
        foreach (['pgsql', 'pgsql_direct'] as $connection) {
            DB::purge($connection);
            config(['database.connections.'.$connection.'.url' => null, 'database.connections.'.$connection.'.host' => 'db.example.test', 'database.connections.'.$connection.'.database' => 'school', 'database.connections.'.$connection.'.sslmode' => 'require']);
        }
    }

    public function test_secure_demo_does_not_require_redis_or_object_storage(): void
    {
        $this->demo();
        $this->artisan('school:check-production')->assertSuccessful();
    }

    public function test_insecure_session_is_rejected(): void
    {
        $this->demo();
        config(['session.secure' => false]);
        $this->artisan('school:check-production')->assertFailed();
    }

    public function test_migration_endpoint_for_another_database_is_rejected(): void
    {
        $this->demo();
        config(['database.connections.pgsql_direct.database' => 'different']);
        $this->artisan('school:check-production')->assertFailed();
    }

    public function test_demo_configuration_cannot_be_used_as_production(): void
    {
        $this->demo();
        config(['deployment.profile' => 'production']);
        $this->artisan('school:check-production')->assertFailed();
    }
}
