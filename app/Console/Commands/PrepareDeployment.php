<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Sleep;

class PrepareDeployment extends Command
{
    protected $signature = 'school:prepare-deployment';

    protected $description = 'Validate, migrate under a direct PostgreSQL lock, and bootstrap safely';

    public function handle(): int
    {
        if ($this->call('school:check-production') !== self::SUCCESS) {
            return self::FAILURE;
        }
        $connection = DB::connection('pgsql_direct');
        $locked = false;
        try {
            if (str_contains((string) $connection->getConfig('host'), '-pooler.')) {
                $this->error('MIGRATION_DATABASE_URL must use the direct Neon endpoint.');

                return self::FAILURE;
            }
            for ($attempt = 1; $attempt <= 6; $attempt++) {
                try {
                    $result = $connection->selectOne('select pg_try_advisory_lock(19370001) as acquired');
                    if (in_array($result->acquired, [true, 1, '1', 't'], true)) {
                        $locked = true;
                        break;
                    }
                } catch (\Throwable) {
                    DB::purge('pgsql_direct');
                    $connection = DB::connection('pgsql_direct');
                }
                if ($attempt < 6) {
                    Sleep::for(2)->seconds();
                }
            }
            if (! $locked) {
                $this->error('Database is unavailable or migration lock is busy. Deployment stopped safely.');

                return self::FAILURE;
            }
            if ($this->call('migrate', ['--database' => 'pgsql_direct', '--force' => true, '--no-interaction' => true]) !== self::SUCCESS) {
                return self::FAILURE;
            }
            if (config('deployment.profile') === 'demo' && $this->call('school:bootstrap-demo', ['--no-interaction' => true]) !== self::SUCCESS) {
                return self::FAILURE;
            }
            $this->info('Database preparation completed.');

            return self::SUCCESS;
        } catch (\Throwable) {
            $this->error('Database preparation failed. Connection details are intentionally omitted.');

            return self::FAILURE;
        } finally {
            if ($locked) {
                try {
                    $connection->select('select pg_advisory_unlock(19370001)');
                } catch (\Throwable) { /* Connection closure also releases the lock. */
                }
            }
        }
    }
}
