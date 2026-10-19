<?php

namespace Tests\Unit;

use App\Tenancy\TenantContext;
use PHPUnit\Framework\TestCase;

class TenantContextTest extends TestCase
{
    public function test_missing_context_fails_closed(): void
    {
        $this->expectException(\LogicException::class);
        (new TenantContext)->id();
    }

    public function test_cleared_context_cannot_leak_into_next_operation(): void
    {
        $context = new TenantContext;
        $context->set(7);
        $this->assertSame(7, $context->id());
        $context->clear();
        $this->expectException(\LogicException::class);
        $context->id();
    }
}
