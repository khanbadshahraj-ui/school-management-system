<?php

namespace App\Services;

use App\Contracts\SchoolLogoStorage;
use Illuminate\Support\Facades\DB;

class DatabaseSchoolLogoStorage implements SchoolLogoStorage
{
    public function get(int $schoolId): ?array
    {
        $logo = DB::table('school_logos')->where('school_id', $schoolId)->first();
        if (! $logo) {
            return null;
        }
        $content = base64_decode($logo->content_base64, true);
        if ($content === false) {
            return null;
        }

        return ['content' => $content, 'mime_type' => $logo->mime_type, 'content_hash' => $logo->content_hash];
    }

    public function put(int $schoolId, string $bytes, string $mimeType): void
    {
        if (strlen($bytes) > 131072 || ! in_array($mimeType, ['image/png', 'image/jpeg'], true)) {
            throw new \InvalidArgumentException('Logo storage only accepts bounded PNG/JPEG content.');
        }
        DB::table('school_logos')->upsert([['school_id' => $schoolId, 'mime_type' => $mimeType, 'content_hash' => hash('sha256', $bytes), 'content_base64' => base64_encode($bytes), 'created_at' => now(), 'updated_at' => now()]], ['school_id'], ['mime_type', 'content_hash', 'content_base64', 'updated_at']);
    }
}
