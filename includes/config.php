<?php
declare(strict_types=1);

session_start();

define('SITE_NAME', 'WEBSOFTERA IT SERVICES AND SOFTWARE DEVELOPMENT AND MARKETING PRIVATE LIMITED');
define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));
define('DATA_DIR', __DIR__ . '/../data');

$GLOBALS['admin_user'] = getenv('WEBSOFTERA_ADMIN_USER') ?: 'admin@websoftera.com';
$GLOBALS['admin_pass_hash'] = getenv('WEBSOFTERA_ADMIN_PASS_HASH') ?: '$2y$10$Uo5zM35qgcX1biEthl/oru4cuX.6KEJdV7sN1iGp.6f8eixfd/OSW';
?>
