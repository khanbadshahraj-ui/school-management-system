<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('primary_color', 7)->default('#2563eb');
            $t->string('locale', 5)->default('en');
            $t->string('timezone')->default('Asia/Karachi');
            $t->string('currency', 3)->default('PKR');
            $t->timestamps();
        });
        Schema::table('users', function (Blueprint $t) {
            $t->foreignId('school_id')->nullable()->constrained()->restrictOnDelete();
            $t->string('role')->default('teacher');
            $t->boolean('is_active')->default(true);
        });
        foreach (['campuses', 'departments'] as $table) {
            Schema::create($table, function (Blueprint $t) {
                $t->id();
                $t->foreignId('school_id')->constrained()->restrictOnDelete();
                $t->string('name');
                $t->string('code', 30);
                $t->timestamps();
                $t->unique(['school_id', 'code']);
                $t->unique(['school_id', 'id']);
            });
        }
        Schema::create('students', function (Blueprint $t) {
            $t->id();
            $t->foreignId('school_id')->constrained()->restrictOnDelete();
            $t->unsignedBigInteger('campus_id');
            $t->string('admission_number', 50);
            $t->string('first_name', 100);
            $t->string('last_name', 100);
            $t->string('status')->default('enrolled');
            $t->timestamps();
            $t->unique(['school_id', 'admission_number']);
            $t->unique(['school_id', 'id']);
            $t->foreign(['school_id', 'campus_id'])->references(['school_id', 'id'])->on('campuses')->restrictOnDelete();
            $t->index(['school_id', 'created_at']);
        });
        Schema::create('audit_events', function (Blueprint $t) {
            $t->id();
            $t->foreignId('school_id')->constrained()->restrictOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('action');
            $t->unsignedBigInteger('subject_id')->nullable();
            $t->timestampTz('created_at');
            $t->index(['school_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_events');
        Schema::dropIfExists('students');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('campuses');
        Schema::table('users', function (Blueprint $t) {
            $t->dropConstrainedForeignId('school_id');
            $t->dropColumn(['role', 'is_active']);
        });
        Schema::dropIfExists('schools');
    }
};
