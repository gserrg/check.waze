<?php

return [
    'name' => env('APP_NAME', 'check'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'Europe/Moscow',
    'locale' => 'ru',
    'fallback_locale' => 'en',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',


    'log' => env('APP_LOG', 'single'),
    'log_level' => env('APP_LOG_LEVEL', 'debug'),


    'providers' => [
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        App\RouteServiceProvider::class,
    ],


    'aliases' => [],
];
