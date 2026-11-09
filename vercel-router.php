<?php

declare(strict_types=1);

/**
 * Vercel does not execute Apache .htaccess rules. This is the single PHP
 * entrypoint for every app route beneath /QLNCKH.
 */
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

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$basePath = rtrim(getenv('APP_BASE_PATH') ?: '/QLNCKH', '/');

if ($path === $basePath) {
    $path = '/';
} elseif (str_starts_with($path, $basePath . '/')) {
    $path = substr($path, strlen($basePath));
} else {
    http_response_code(404);
    exit('Not found');
}

$routes = [
    '/' => 'index.php',
    '/index.php' => 'index.php',
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

if (preg_match('#^/(de-tai|cap-nhat)/(\d+)$#', $path, $matches)) {
    $_GET['id'] = $matches[2];
    renderView(__DIR__ . '/View/' . ($matches[1] === 'de-tai' ? 'r3_project_detail.php' : 'r3_project_update.php'));
    exit;
}

if (!isset($routes[$path])) {
    http_response_code(404);
    exit('Not found');
}

$view = __DIR__ . '/' . $routes[$path];
if ($routes[$path] === 'index.php') {
    require $view;
    exit;
}

renderView($view);
