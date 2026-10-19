<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        return [...parent::share($request),
            'auth' => fn () => ['user' => $request->user()?->only('id', 'name', 'email', 'role')],
            'school' => fn () => $request->user()?->school?->only('id', 'name', 'primary_color', 'locale', 'timezone', 'currency'),
            'demo' => fn () => (bool) config('deployment.demo_mode'),
            'logoUrl' => function () use ($request) {
                if (! $request->user()) {
                    return null;
                }
                $hash = DB::table('school_logos')->where('school_id', $request->user()->school_id)->value('content_hash');

                return $hash ? '/school/logo?v='.$hash : null;
            },
            'flash' => fn () => ['success' => $request->session()->get('success')],
        ];
    }
}
