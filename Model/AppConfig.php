<?php

declare(strict_types=1);

function appEnv(string $name, ?string $default = null): ?string
{
    $value = getenv($name);
    return $value === false || $value === '' ? $default : $value;
}

function appBasePath(): string
{
    return rtrim(appEnv('APP_BASE_PATH', '/QLNCKH') ?: '/QLNCKH', '/');
}

function requireAppEnv(string $name): string
{
    $value = appEnv($name);
    if ($value === null) {
        throw new RuntimeException("Missing required environment variable: {$name}");
    }

    return $value;
}
