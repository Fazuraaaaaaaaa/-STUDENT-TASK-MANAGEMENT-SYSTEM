<?php

// Pastikan Vercel serverless request terbaca sebagai HTTPS
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = '443';
}

// Setup direktori dinamis di /tmp (satu-satunya filesystem writable di Vercel serverless)
$tmpDirs = [
    '/tmp/views',
    '/tmp/sessions',
    '/tmp/cache',
    '/tmp/logs',
];

foreach ($tmpDirs as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Inisialisasi SQLite database di /tmp
$sqliteTarget = '/tmp/database.sqlite';
$bundledDb = __DIR__ . '/../database/database.sqlite';

if (! file_exists($sqliteTarget)) {
    if (file_exists($bundledDb)) {
        @copy($bundledDb, $sqliteTarget);
    } else {
        @touch($sqliteTarget);
    }
    @chmod($sqliteTarget, 0666);
}

// Set environment variable database ke /tmp
putenv("DB_CONNECTION=sqlite");
putenv("DB_DATABASE={$sqliteTarget}");
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = $sqliteTarget;
$_SERVER['DB_CONNECTION'] = 'sqlite';
$_SERVER['DB_DATABASE'] = $sqliteTarget;

require __DIR__ . '/../public/index.php';

