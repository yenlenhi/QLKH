<?php

declare(strict_types=1);

require_once __DIR__ . '/AppConfig.php';

try {
    $databaseUrl = requireAppEnv('DATABASE_URL');
    $scheme = (string) parse_url($databaseUrl, PHP_URL_SCHEME);
    if (!in_array($scheme, ['postgres', 'postgresql'], true)) {
        throw new RuntimeException('DATABASE_URL must use the PostgreSQL scheme.');
    }
    if (!in_array('pgsql', PDO::getAvailableDrivers(), true)) {
        throw new RuntimeException('The PHP runtime is missing pdo_pgsql.');
    }

    $parts = parse_url($databaseUrl);
    if (!is_array($parts) || empty($parts['host']) || empty($parts['path'])) {
        throw new RuntimeException('DATABASE_URL is malformed.');
    }
    parse_str($parts['query'] ?? '', $query);
    $dsnParts = [
        'host=' . $parts['host'],
        'port=' . ($parts['port'] ?? 5432),
        'dbname=' . ltrim($parts['path'], '/'),
        'sslmode=' . ($query['sslmode'] ?? 'require'),
    ];
    if (isset($query['options'])) {
        $dsnParts[] = 'options=' . $query['options'];
    }

    $pdo = new PDO('pgsql:' . implode(';', $dsnParts), urldecode($parts['user'] ?? ''), urldecode($parts['pass'] ?? ''), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    error_log('Database connection failed: ' . $e->getMessage());
    die(json_encode(['status' => 'error', 'message' => 'Không thể kết nối cơ sở dữ liệu.'], JSON_UNESCAPED_UNICODE));
}
