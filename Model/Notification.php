<?php

declare(strict_types=1);

require_once __DIR__ . '/Ably.php';

function createNotification(PDO $pdo, int $userId, int $projectId, string $title, string $content, string $type): int
{
    $statement = $pdo->prepare(
        'INSERT INTO tbl_notifications (user_id, project_id, title, content, type, is_read, created_at) '
        . 'VALUES (?, ?, ?, ?, ?, FALSE, NOW()) RETURNING id'
    );
    $statement->execute([$userId, $projectId, $title, $content, $type]);
    return (int) $statement->fetchColumn();
}

function publishNotificationSafely(int $userId, int $id, int $projectId, string $title, string $type): void
{
    try {
        publishNotification($userId, ['id' => $id, 'project_id' => $projectId, 'title' => $title, 'type' => $type]);
    } catch (Throwable $error) {
        error_log('Ably publish failed: ' . $error->getMessage());
    }
}
