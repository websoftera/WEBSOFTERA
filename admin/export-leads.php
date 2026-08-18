<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$messages = read_json('messages.json');
$filename = 'websoftera-leads-' . date('Y-m-d') . '.csv';

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-store, no-cache, must-revalidate');

$output = fopen('php://output', 'w');
fwrite($output, "\xEF\xBB\xBF");
fputcsv($output, ['Name', 'Email', 'Phone', 'Service Interest', 'Message', 'Received At']);

foreach ($messages as $message) {
    fputcsv($output, [
        $message['name'] ?? '',
        $message['email'] ?? '',
        $message['phone'] ?? '',
        $message['service'] ?? '',
        $message['message'] ?? '',
        $message['created_at'] ?? '',
    ]);
}

fclose($output);
exit;
