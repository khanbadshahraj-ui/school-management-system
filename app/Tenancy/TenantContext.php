<?php

namespace App\Tenancy;

final class TenantContext
{
    private ?int $schoolId = null;

    public function set(int $schoolId): void
    {
        $this->schoolId = $schoolId;
    }

    public function clear(): void
    {
        $this->schoolId = null;
    }

    public function id(): int
    {
        return $this->schoolId ?? throw new \LogicException('School context is required.');
    }
}
