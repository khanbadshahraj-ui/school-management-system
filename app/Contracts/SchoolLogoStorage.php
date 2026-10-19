<?php

namespace App\Contracts;

interface SchoolLogoStorage
{
    /** @return array{content: string, mime_type: string, content_hash: string}|null */
    public function get(int $schoolId): ?array;

    public function put(int $schoolId, string $bytes, string $mimeType): void;
}
