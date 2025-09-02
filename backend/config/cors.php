<?php

return [

    'paths' => [
        'api/*',
        'login',
        'register',
        'broadcasting/auth',
        'stranke*',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:5173'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Authorization', 'Content-Type', 'Accept', 'X-Requested-With'],


    // 🔥 Izpostavi Authorization header, da ga frontend vidi v response-u
    'exposed_headers' => ['Authorization'],

    'max_age' => 0,

    // ❌ Cookieji niso uporabljeni, zato naj bo false
    'supports_credentials' => false,
];
