<?php

namespace App\Console\Commands;

use App\Models\Campus;
use App\Models\Department;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Tenancy\TenantContext;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BootstrapDemo extends Command
{
    protected $signature = 'school:bootstrap-demo';

    protected $description = 'Provision the demo owner once from protected runtime configuration';

    public function handle(): int
    {
        if (! config('deployment.bootstrap.enabled')) {
            $this->info('Demo bootstrap disabled; existing data preserved.');

            return self::SUCCESS;
        }
        if (config('deployment.profile') !== 'demo' || ! config('deployment.demo_mode')) {
            $this->error('Demo bootstrap is restricted to the demo profile.');

            return self::FAILURE;
        }
        $email = config('deployment.bootstrap.email');
        $name = config('deployment.bootstrap.school_name');
        $slug = config('deployment.bootstrap.school_slug');
        if (Validator::make(compact('email', 'name', 'slug'), ['email' => 'required|email|max:255', 'name' => 'required|string|max:150', 'slug' => 'required|alpha_dash|max:100'])->fails()) {
            $this->error('Demo bootstrap identity settings are invalid.');

            return self::FAILURE;
        }
        try {
            DB::transaction(function () use ($email, $name, $slug) {
                DB::select('select pg_advisory_xact_lock(19370002)');
                $existing = User::where('email', $email)->first();
                if ($existing) {
                    if ($existing->school?->slug !== $slug) {
                        throw new \RuntimeException('Bootstrap identity conflict');
                    }

                    return;
                }
                $hash = (string) config('deployment.bootstrap.password_hash');
                if (! in_array(password_get_info($hash)['algoName'], ['bcrypt', 'argon2id', 'argon2i'], true)) {
                    throw new \RuntimeException('A valid protected password hash is required');
                }
                $school = School::firstOrCreate(['slug' => $slug], ['name' => $name]);
                $user = new User;
                $user->forceFill(['name' => 'Demo School Owner', 'email' => $email, 'password' => $hash, 'school_id' => $school->id, 'role' => 'school_admin', 'is_active' => true]);
                $user->save();
                if (config('deployment.bootstrap.seed')) {
                    $context = app(TenantContext::class);
                    $context->set($school->id);
                    try {
                        $campus = Campus::firstOrCreate(['code' => 'MAIN'], ['name' => 'Demonstration Campus']);
                        Department::firstOrCreate(['code' => 'ACADEMIC'], ['name' => 'Academics']);
                        foreach (['Amina', 'Bilal', 'Daniyal', 'Farah', 'Hassan', 'Iqra', 'Maryam', 'Omar', 'Sara', 'Zain'] as $index => $first) {
                            Student::firstOrCreate(['admission_number' => sprintf('DEMO-%03d', $index + 1)], ['campus_id' => $campus->id, 'first_name' => $first, 'last_name' => 'Example', 'status' => 'enrolled']);
                        }
                    } finally {
                        $context->clear();
                    }
                }
            });
        } catch (\Throwable) {
            $this->error('Demo bootstrap failed safely. Check protected identity configuration; credentials are never logged.');

            return self::FAILURE;
        }
        $this->info('Demo owner available; existing accounts and data were not overwritten.');

        return self::SUCCESS;
    }
}
