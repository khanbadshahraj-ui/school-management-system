<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        return [...parent::share($request),
            'auth' => fn () => ['user' => $request->user()?->only('id', 'name', 'email', 'role')],
            'school' => fn () => $request->user()?->school?->only('id', 'name', 'primary_color', 'locale', 'timezone', 'currency'),
            'flash' => fn () => ['success' => $request->session()->get('success')],
        ];
    }
}
