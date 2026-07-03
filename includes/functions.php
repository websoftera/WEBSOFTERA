<?php
require_once __DIR__ . '/config.php';

function asset(string $path): string {
    $base = BASE_URL === '/' ? '' : BASE_URL;
    return $base . '/' . ltrim($path, '/');
}

function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function data_path(string $file): string {
    return DATA_DIR . '/' . basename($file);
}

function read_json(string $file, array $fallback = []): array {
    $path = data_path($file);
    if (!is_file($path)) {
        return $fallback;
    }
    $json = json_decode((string)file_get_contents($path), true);
    return is_array($json) ? $json : $fallback;
}

function write_json(string $file, array $data): void {
    file_put_contents(data_path($file), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function page_meta(string $key): array {
    $meta = read_json('content.json');
    return $meta['pages'][$key] ?? ['title' => SITE_NAME, 'description' => 'Professional IT services in Pune.'];
}

function is_admin(): bool {
    return !empty($_SESSION['is_admin']);
}

function require_admin(): void {
    if (!is_admin()) {
        header('Location: login.php');
        exit;
    }
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf']))) {
        http_response_code(403);
        exit('Invalid request token.');
    }
}

function active_nav(string $page, string $current): string {
    return $page === $current ? 'active' : '';
}

function external_url(?string $url): string {
    $url = trim((string)$url);
    if ($url === '') {
        return '';
    }
    if (!preg_match('#^https?://#i', $url)) {
        $url = 'https://' . $url;
    }
    return filter_var($url, FILTER_VALIDATE_URL) ? $url : '';
}

function website_preview_image(?string $url, int $width = 1200): string {
    $url = external_url($url);
    if ($url === '') {
        return '';
    }
    return 'https://api.microlink.io/?url=' . urlencode($url) . '&screenshot=true&embed=screenshot.url';
}
?>
