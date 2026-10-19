<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthTest extends TestCase
{
    public function test_liveness_does_not_require_the_database(): void
    {
        $this->get('/up')->assertOk();
    }

    public function test_login_page_renders(): void
    {
        $this->get('/login')->assertOk();
    }
}
