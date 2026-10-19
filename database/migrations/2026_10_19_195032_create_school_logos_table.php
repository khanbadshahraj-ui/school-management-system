<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_logos', function (Blueprint $table) {
            $table->foreignId('school_id')->primary()->constrained()->cascadeOnDelete();
            $table->string('mime_type', 32);
            $table->string('content_hash', 64);
            $table->text('content_base64');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_logos');
    }
};
