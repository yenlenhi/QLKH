<?php

declare(strict_types=1);

require_once __DIR__ . '/AppConfig.php';

function uploadToBlob(string $temporaryPath, string $pathname, string $contentType): string
{
    if (!is_uploaded_file($temporaryPath) && !str_starts_with($temporaryPath, sys_get_temp_dir())) {
        throw new RuntimeException('Upload source is invalid.');
    }

    $contents = file_get_contents($temporaryPath);
    if ($contents === false) {
        throw new RuntimeException('Unable to read uploaded file.');
    }

    $safePath = trim(preg_replace('#[^A-Za-z0-9._/-]+#', '-', $pathname), '/');
    $request = curl_init('https://blob.vercel-storage.com/?pathname=' . rawurlencode($safePath));
    curl_setopt_array($request, [
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS => $contents,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . requireAppEnv('BLOB_READ_WRITE_TOKEN'),
            'x-api-version: 7',
            'x-vercel-blob-access: public',
            'x-add-random-suffix: 1',
            'x-content-type: ' . $contentType,
        ],
    ]);
    $response = curl_exec($request);
    $status = (int) curl_getinfo($request, CURLINFO_RESPONSE_CODE);
    curl_close($request);

    $payload = is_string($response) ? json_decode($response, true) : null;
    if ($status < 200 || $status >= 300 || !is_array($payload) || empty($payload['url'])) {
        throw new RuntimeException('Vercel Blob upload failed.');
    }

    return $payload['url'];
}

function deleteFromBlob(?string $url): void
{
    if (!$url || !str_starts_with($url, 'https://')) {
        return;
    }

    $request = curl_init('https://blob.vercel-storage.com/delete');
    curl_setopt_array($request, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(['urls' => [$url]], JSON_THROW_ON_ERROR),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . requireAppEnv('BLOB_READ_WRITE_TOKEN'),
            'x-api-version: 7',
            'Content-Type: application/json',
        ],
    ]);
    curl_exec($request);
    curl_close($request);
}

function storeUploadedFile(array $file, string $prefix, array $allowedExtensions = []): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || empty($file['tmp_name']) || empty($file['name'])) {
        throw new RuntimeException('File upload failed.');
    }

    $size = (int) ($file['size'] ?? filesize($file['tmp_name']));
    if ($size <= 0 || $size > 20 * 1024 * 1024) {
        throw new RuntimeException('File size must be between 1 byte and 20 MB.');
    }

    $name = basename((string) $file['name']);
    $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $safeExtensions = $allowedExtensions ?: ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'zip', 'rar', 'jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!$extension || !in_array($extension, $safeExtensions, true)) {
        throw new RuntimeException('Unsupported file type.');
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']) ?: 'application/octet-stream';
    $mimeByExtension = [
        'pdf' => ['application/pdf'],
        'doc' => ['application/msword', 'application/CDFV2'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'],
        'xls' => ['application/vnd.ms-excel', 'application/CDFV2'],
        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip'],
        'csv' => ['text/csv', 'text/plain'], 'txt' => ['text/plain'],
        'zip' => ['application/zip', 'application/x-zip-compressed'],
        'rar' => ['application/vnd.rar', 'application/x-rar-compressed'],
        'jpg' => ['image/jpeg'], 'jpeg' => ['image/jpeg'], 'png' => ['image/png'],
        'gif' => ['image/gif'], 'webp' => ['image/webp'], 'svg' => ['image/svg+xml', 'text/plain'],
    ];
    if (!in_array($mime, $mimeByExtension[$extension] ?? [], true)) {
        throw new RuntimeException('The uploaded file content does not match its extension.');
    }
    return uploadToBlob($file['tmp_name'], trim($prefix, '/') . '/' . bin2hex(random_bytes(12)) . ($extension ? '.' . $extension : ''), $mime);
}
