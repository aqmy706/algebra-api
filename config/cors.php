<?php

return [
    // Hanya laluan API yang perlukan CORS.
    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    // Asal (origin) yang dibenarkan panggil API ini.
    'allowed_origins' => [
        'https://anak2.elhumaira.com',
        'http://anak2.elhumaira.com',
        'http://localhost:5173', // pembangunan tempatan
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Tiada cookie/sesi — leaderboard awam.
    'supports_credentials' => false,
];
