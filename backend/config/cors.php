<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'], // Atau spesifikkan: ['GET', 'POST', 'PUT', 'DELETE']
    'allowed_origins' => ['http://localhost:5173', 'http://127.0.0.1:5173'], // Ganti dengan URL frontend Vue.js kamu
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Atau spesifikkan header yang kamu gunakan, misal: ['Content-Type', 'Authorization']
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false, // Set ke true jika pakai cookie atau kredensial lain
];