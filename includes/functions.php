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
    file_put_contents(data_path($file), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
}

function append_json(string $file, array $entry): bool {
    $path = data_path($file);
    $handle = fopen($path, 'c+');
    if ($handle === false || !flock($handle, LOCK_EX)) {
        if (is_resource($handle)) fclose($handle);
        return false;
    }

    $contents = stream_get_contents($handle);
    $data = json_decode($contents ?: '[]', true);
    if (!is_array($data)) $data = [];
    $data[] = $entry;
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    rewind($handle);
    $written = $json !== false && ftruncate($handle, 0) && fwrite($handle, $json) !== false;
    fflush($handle);
    flock($handle, LOCK_UN);
    fclose($handle);
    return $written;
}

function send_contact_notification(array $lead): bool {
    $safeName = trim(preg_replace('/[\r\n]+/', ' ', (string)($lead['name'] ?? '')));
    $subject = 'New website lead from ' . ($safeName ?: 'Contact form');
    $body = "A new contact form entry was received.\n\n"
        . "Name: " . $lead['name'] . "\n"
        . "Email: " . $lead['email'] . "\n"
        . "Phone: " . $lead['phone'] . "\n"
        . "Service: " . $lead['service'] . "\n"
        . "Message:\n" . $lead['message'] . "\n\n"
        . "Received: " . $lead['created_at'] . "\n";

    $smtp = $GLOBALS['smtp_config'] ?? [];
    $from = filter_var($smtp['username'] ?? '', FILTER_VALIDATE_EMAIL) ?: '';
    $host = preg_replace('/[^a-z0-9.-]/i', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
    $from = $from ?: 'no-reply@' . $host;
    $headers = ['Content-Type: text/plain; charset=UTF-8', 'From: Websoftera Website Leads <' . $from . '>'];
    if (filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) {
        $headers[] = 'Reply-To: ' . $lead['email'];
    }

    if (!empty($smtp['host']) && !empty($smtp['username']) && !empty($smtp['password'])) {
        return smtp_send_mail($smtp, CONTACT_NOTIFICATION_EMAIL, $from, $subject, $body, $headers);
    }
    return @mail(CONTACT_NOTIFICATION_EMAIL, $subject, $body, implode("\r\n", $headers));
}

function smtp_read_response($socket): string {
    $response = '';
    while (($line = fgets($socket, 515)) !== false) {
        $response .= $line;
        if (strlen($line) < 4 || $line[3] === ' ') break;
    }
    return $response;
}

function smtp_command($socket, string $command, array $expectedCodes): bool {
    if (fwrite($socket, $command . "\r\n") === false) return false;
    $response = smtp_read_response($socket);
    return in_array((int)substr($response, 0, 3), $expectedCodes, true);
}

function smtp_send_mail(array $config, string $to, string $from, string $subject, string $body, array $headers): bool {
    $smtpHost = preg_replace('/[^a-z0-9.-]/i', '', (string)$config['host']);
    $smtpPort = (int)($config['port'] ?? 587);
    $socket = @stream_socket_client('tcp://' . $smtpHost . ':' . $smtpPort, $errorNumber, $errorMessage, 15);
    if ($socket === false) {
        error_log('Contact SMTP connection failed: ' . $errorMessage);
        return false;
    }
    stream_set_timeout($socket, 15);

    $ok = in_array((int)substr(smtp_read_response($socket), 0, 3), [220], true)
        && smtp_command($socket, 'EHLO websoftera.com', [250]);

    if ($ok && strtolower((string)($config['encryption'] ?? 'tls')) === 'tls') {
        $ok = smtp_command($socket, 'STARTTLS', [220])
            && stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)
            && smtp_command($socket, 'EHLO websoftera.com', [250]);
    }

    $ok = $ok
        && smtp_command($socket, 'AUTH LOGIN', [334])
        && smtp_command($socket, base64_encode((string)$config['username']), [334])
        && smtp_command($socket, base64_encode(str_replace(' ', '', (string)$config['password'])), [235])
        && smtp_command($socket, 'MAIL FROM:<' . $from . '>', [250])
        && smtp_command($socket, 'RCPT TO:<' . $to . '>', [250, 251])
        && smtp_command($socket, 'DATA', [354]);

    if ($ok) {
        $messageHeaders = array_merge([
            'To: ' . $to,
            'Subject: ' . $subject,
            'Date: ' . date(DATE_RFC2822),
            'MIME-Version: 1.0',
        ], $headers);
        $payload = implode("\r\n", $messageHeaders) . "\r\n\r\n" . str_replace("\n.", "\n..", str_replace(["\r\n", "\r"], "\n", $body));
        $ok = smtp_command($socket, $payload . "\r\n.", [250]);
    }

    smtp_command($socket, 'QUIT', [221]);
    fclose($socket);
    if (!$ok) error_log('Contact SMTP delivery failed.');
    return $ok;
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
