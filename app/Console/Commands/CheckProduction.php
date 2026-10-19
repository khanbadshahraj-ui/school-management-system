<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckProduction extends Command
{
    protected $signature = 'school:check-production';

    protected $description = 'Fail before release if essential production settings are unsafe';

    public function handle(): int
    {
        if (! app()->environment('production')) {
            $this->error('APP_ENV must be production for this release.');

            return self::FAILURE;
        }
        $checks = [
            'APP_KEY must be configured' => filled(config('app.key')),
            'APP_DEBUG must be false' => ! config('app.debug'),
            'APP_URL must use HTTPS' => str_starts_with(config('app.url'), 'https://'),
            'PostgreSQL is required' => config('database.default') === 'pgsql',
            'Redis sessions are required' => config('session.driver') === 'redis',
            'Secure cookies are required' => (bool) config('session.secure'),
            'S3-compatible durable storage is required' => config('filesystems.default') === 's3' && filled(config('filesystems.disks.s3.bucket')),
            'Redis queues are required' => config('queue.default') === 'redis',
        ];
        foreach ($checks as $message => $passed) {
            if (! $passed) {
                $this->error($message);
            }
        }

        return in_array(false, $checks, true) ? self::FAILURE : self::SUCCESS;
    }
}
