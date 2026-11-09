<?php

declare(strict_types=1);

function renderView(string $view): void
{
    $workingDirectory = getcwd();
    chdir(dirname($view));

    try {
        require $view;
    } finally {
        chdir($workingDirectory);
    }
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$hasProjectPrefix = false;

if ($path === '/QLNCKH') {
    $hasProjectPrefix = true;
    $path = '/';
} elseif (str_starts_with($path, '/QLNCKH/')) {
    $hasProjectPrefix = true;
    $path = substr($path, strlen('/QLNCKH'));
}

$file = realpath(__DIR__ . $path);

if (
    $path !== '/'
    && $file !== false
    && str_starts_with($file, __DIR__ . DIRECTORY_SEPARATOR)
    && is_file($file)
) {
    if ($hasProjectPrefix) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            require $file;
        } else {
            $mimeTypes = [
                'css' => 'text/css',
                'js' => 'text/javascript',
                'svg' => 'image/svg+xml',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'woff2' => 'font/woff2',
            ];
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            header('Content-Type: ' . ($mimeTypes[$extension] ?? mime_content_type($file)));
            readfile($file);
        }

        return true;
    }

    return false;
}

$routes = [
    '/dashboard' => 'View/r1_home.php',
    '/dang-ky-de-tai' => 'View/r1_register_project.php',
    '/quan-ly-nhiem-vu' => 'View/r1_overview.php',
    '/dang-ky-tai-khoan' => 'View/r1_register_account.php',
    '/phan-quyen' => 'View/r1_permission.php',
    '/huong-dan' => 'View/r1_guide_list.php',
    '/quan-ly-nguoi-dung' => 'View/r1_users.php',
    '/cau-hinh-he-thong' => 'View/r1_system_config.php',
    '/thung-rac' => 'View/r1_trash.php',
    '/lich-su' => 'View/r1_leadership_activity_log.php',
    '/noi-dung-nhiem-vu' => 'View/r1_project_detail.php',
    '/trang-chu' => 'View/r3_home.php',
    '/nhiem-vu-cua-toi' => 'View/r3_my_projects.php',
    '/dang-ky-de-tai-nckh' => 'View/r3_project_register.php',
    '/nhiem-vu-de-xuat-thuc-hien' => 'View/r3_proposed_projects.php',
    '/ly-lich-khoa-hoc' => 'View/r3_scientific_cv.php',
    '/chi-tiet-de-tai' => 'View/r3_project_detail.php',
    '/cap-nhat-de-tai' => 'View/r3_project_update.php',
];

if (preg_match('#^/de-tai/(\d+)$#', $path, $matches)) {
    $_GET['id'] = $matches[1];
    renderView(__DIR__ . '/View/r3_project_detail.php');
    return true;
}

if (preg_match('#^/cap-nhat/(\d+)$#', $path, $matches)) {
    $_GET['id'] = $matches[1];
    renderView(__DIR__ . '/View/r3_project_update.php');
    return true;
}

if (isset($routes[$path])) {
    renderView(__DIR__ . '/' . $routes[$path]);
    return true;
}

require __DIR__ . '/index.php';
