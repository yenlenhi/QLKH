<?php

declare(strict_types=1);

require_once __DIR__ . '/AppConfig.php';

function ablyRequest(string $method, string $path, ?array $body = null): array
{
    $key = requireAppEnv('ABLY_API_KEY');
    if (!str_contains($key, ':')) {
        throw new RuntimeException('ABLY_API_KEY is malformed.');
    }

    $request = curl_init('https://main.realtime.ably.net' . $path);
    curl_setopt_array($request, [
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD => $key,
        CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    ]);
    if ($body !== null) {
        curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($body, JSON_THROW_ON_ERROR));
    }
    $response = curl_exec($request);
    $status = (int) curl_getinfo($request, CURLINFO_RESPONSE_CODE);
    curl_close($request);
    $payload = is_string($response) ? json_decode($response, true) : null;
    if ($status < 200 || $status >= 300 || !is_array($payload)) {
        throw new RuntimeException('Ably request failed.');
    }

    return $payload;
}

function issueAblyTokenRequest(int $userId): array
{
    $keyName = explode(':', requireAppEnv('ABLY_API_KEY'), 2)[0];
    $channel = 'notifications:user:' . $userId;
    return ablyRequest('POST', '/keys/' . rawurlencode($keyName) . '/requestToken', [
        'keyName' => $keyName,
        'timestamp' => (int) floor(microtime(true) * 1000),
        'clientId' => 'user-' . $userId,
        'ttl' => 3_600_000,
        'capability' => json_encode([$channel => ['subscribe']], JSON_THROW_ON_ERROR),
    ]);
}

function publishNotification(int $userId, array $message): void
{
    if (appEnv('ABLY_API_KEY') === null || $userId <= 0) {
        return;
    }

    ablyRequest('POST', '/channels/' . rawurlencode('notifications:user:' . $userId) . '/messages', [[
        'name' => 'notification',
        'data' => json_encode($message, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
    ]]);
}
