<?php

namespace App\Console\Commands;

use App\Models\School;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BootstrapSchool extends Command
{
    protected $signature = 'school:bootstrap {email} {--name=} {--slug=}';

    protected $description = 'Create a school and owner through a protected interactive terminal';

    public function handle(): int
    {
        $email = $this->argument('email');
        $name = $this->option('name');
        $slug = $this->option('slug');
        $password = $this->secret('New owner password (minimum 14 characters; not logged)');
        $validator = Validator::make(compact('email', 'name', 'slug', 'password'), ['email' => 'required|email|unique:users,email', 'name' => 'required|string|max:150', 'slug' => 'required|alpha_dash|unique:schools,slug', 'password' => 'required|string|min:14']);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }
        DB::transaction(function () use ($email, $name, $slug, $password) {
            $school = School::create(['name' => $name, 'slug' => $slug]);
            $user = new User;
            $user->forceFill(['name' => 'School Administrator', 'email' => $email, 'password' => $password, 'school_id' => $school->id, 'role' => 'school_admin', 'is_active' => true]);
            $user->save();
        });
        $this->info('School and owner created. No sample students were inserted.');

        return self::SUCCESS;
    }
}
