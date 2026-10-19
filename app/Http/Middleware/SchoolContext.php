<?php

namespace App\Http\Middleware;

use App\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;

class SchoolContext
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user()?->school_id && $request->user()?->is_active, 403);
        abort_unless(in_array($request->user()->role, ['school_admin', 'admissions', 'teacher']), 403);
        $context = app(TenantContext::class);
        $context->set((int) $request->user()->school_id);
        try {
            return $next($request);
        } finally {
            $context->clear();
        }
    }
}
