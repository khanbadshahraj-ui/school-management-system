<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\DB;

class CheckProduction extends Command
{
    protected $signature = 'school:check-production';

    protected $description = 'Validate secure runtime configuration for demo or production';

    public function handle(): int
    {
        $profile = config('deployment.profile');
        $key = (string) config('app.key');
        $key = str_starts_with($key, 'base64:') ? base64_decode(substr($key, 7), true) : $key;
        $checks = [
            'APP_ENV must be production' => app()->environment('production'),
            'DEPLOYMENT_PROFILE must be demo or production' => in_array($profile, ['demo', 'production'], true),
            'APP_KEY must be a valid encryption key' => is_string($key) && Encrypter::supported($key, config('app.cipher')),
            'APP_DEBUG must be false' => ! config('app.debug'),
            'APP_URL must be an HTTPS origin' => filter_var(config('app.url'), FILTER_VALIDATE_URL) && parse_url(config('app.url'), PHP_URL_SCHEME) === 'https',
            'PostgreSQL is required' => config('database.default') === 'pgsql',
            'Secure encrypted sessions are required' => config('session.secure') && config('session.encrypt'),
        ];
        try {
            foreach (['pgsql', 'pgsql_direct'] as $name) {
                $checks['Encrypted PostgreSQL connection required: '.$name] = in_array(DB::connection($name)->getConfig('sslmode'), ['require', 'verify-ca', 'verify-full'], true);
            }
            $runtime = DB::connection('pgsql')->getConfig();
            $direct = DB::connection('pgsql_direct')->getConfig();
            $checks['Runtime and migration endpoints must identify the same database'] = str_replace('-pooler.', '.', $runtime['host']) === $direct['host'] && $runtime['database'] === $direct['database'];
        } catch (\Throwable) {
            $checks['Database configuration must be valid'] = false;
        }
        if ($profile === 'demo') {
            $checks += [
                'DEMO_MODE must be enabled' => (bool) config('deployment.demo_mode'),
                'Demo sessions and cache must use the database' => config('session.driver') === 'database' && config('cache.default') === 'database',
                'Demo jobs must run synchronously' => config('queue.default') === 'sync',
                'Demo logos must use durable database storage' => config('deployment.logo_storage') === 'database',
                'Bulk uploads and external integrations must remain disabled' => ! config('deployment.bulk_uploads_enabled') && ! config('deployment.external_messages_enabled') && ! config('deployment.live_payments_enabled'),
                'Demo outbound email must use the non-sending array transport' => config('mail.default') === 'array',
            ];
        } elseif ($profile === 'production') {
            $checks += [
                'DEMO_MODE must be disabled for production' => ! config('deployment.demo_mode'),
                'Production sessions and queues must use Redis' => config('session.driver') === 'redis' && config('queue.default') === 'redis',
                'Enabled bulk uploads require durable object storage' => ! config('deployment.bulk_uploads_enabled') || (config('filesystems.default') === 's3' && filled(config('filesystems.disks.s3.bucket'))),
            ];
        }
        foreach ($checks as $message => $passed) {
            if (! $passed) {
                $this->error($message);
            }
        }
        if (in_array(false, array_map(fn ($value) => (bool) $value, $checks), true)) {
            return self::FAILURE;
        }
        $this->info('Runtime configuration passed.');

        return self::SUCCESS;
    }
}
