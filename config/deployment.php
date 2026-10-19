<?php

$profile = env('DEPLOYMENT_PROFILE', 'production');

return [
    'profile' => $profile,
    'demo_mode' => env('DEMO_MODE', false),
    'logo_storage' => 'database',
    'bulk_uploads_enabled' => env('BULK_UPLOADS_ENABLED', $profile !== 'demo'),
    'external_messages_enabled' => env('EXTERNAL_MESSAGES_ENABLED', false),
    'live_payments_enabled' => env('LIVE_PAYMENTS_ENABLED', false),
    'bootstrap' => [
        'enabled' => env('DEMO_BOOTSTRAP_ENABLED', false),
        'email' => env('DEMO_OWNER_EMAIL'),
        'password_hash' => env('DEMO_OWNER_PASSWORD_HASH'),
        'school_name' => env('DEMO_SCHOOL_NAME', 'Northstar Demonstration School'),
        'school_slug' => env('DEMO_SCHOOL_SLUG', 'northstar-demo'),
        'seed' => env('DEMO_SEED', false),
    ],
];
