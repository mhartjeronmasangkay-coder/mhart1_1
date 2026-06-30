<?php

return [
    'paths' => ['api/*', 'graphql'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:4200',
        'http://localhost:40385',
        'http://127.0.0.1:4200',
        'http://127.0.0.1:40385',
        'http://127.0.0.1:38399',
    ],
    'allowed_origins_patterns' => [
        '#^http://localhost:[0-9]+$#',
        '#^http://127\.0\.0\.1:[0-9]+$#',
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];