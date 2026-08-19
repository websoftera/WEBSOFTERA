<?php require_admin(); ?>
<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($adminTitle ?? 'Admin Dashboard') ?> | Websoftera</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../assets/css/style.css?v=<?= filemtime(__DIR__ . '/../assets/css/style.css') ?>" rel="stylesheet">
</head>
<body class="admin-body">
<?php
$currentScript = basename($_SERVER['SCRIPT_NAME']);
$currentType   = $_GET['type'] ?? ($currentScript === 'messages.php' ? 'contact' : '');
function admin_nav_active(string $script, string $type, string $matchScript, string $matchType = ''): string {
    global $currentScript, $currentType;
    if ($currentScript !== $matchScript) return '';
    if ($matchType !== '' && $currentType !== $matchType) return '';
    return 'active-link';
}
?>
<nav class="admin-nav">
    <a class="navbar-brand" href="dashboard.php"><img src="../assets/img/websoftera-logo.png" alt="Websoftera logo"><span>Admin</span></a>
    <div>
        <a href="../index.php" target="_blank"><i class="bi bi-box-arrow-up-right"></i> View Site</a>
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</nav>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <a href="dashboard.php" class="<?= admin_nav_active('dashboard.php', '', 'dashboard.php') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="content.php" class="<?= admin_nav_active('content.php', '', 'content.php') ?>"><i class="bi bi-file-earmark-text"></i> Website Content</a>
        <a href="manage.php?type=services" class="<?= admin_nav_active('manage.php', 'services', 'manage.php', 'services') ?>"><i class="bi bi-grid"></i> Services</a>
        <a href="manage.php?type=process_steps" class="<?= admin_nav_active('manage.php', 'process_steps', 'manage.php', 'process_steps') ?>"><i class="bi bi-signpost-split"></i> Delivery Process</a>
        <a href="manage.php?type=tech_stack" class="<?= admin_nav_active('manage.php', 'tech_stack', 'manage.php', 'tech_stack') ?>"><i class="bi bi-cpu"></i> Tech Stack</a>
        <a href="manage.php?type=clients" class="<?= admin_nav_active('manage.php', 'clients', 'manage.php', 'clients') ?>"><i class="bi bi-window-stack"></i> Client Websites</a>
        <a href="manage.php?type=hero_images" class="<?= admin_nav_active('manage.php', 'hero_images', 'manage.php', 'hero_images') ?>"><i class="bi bi-images"></i> Hero Slider</a>
        <a href="manage.php?type=milestones" class="<?= admin_nav_active('manage.php', 'milestones', 'manage.php', 'milestones') ?>"><i class="bi bi-flag"></i> Milestones</a>
        <a href="manage.php?type=values" class="<?= admin_nav_active('manage.php', 'values', 'manage.php', 'values') ?>"><i class="bi bi-gem"></i> Company Values</a>
        <a href="manage.php?type=culture_facts" class="<?= admin_nav_active('manage.php', 'culture_facts', 'manage.php', 'culture_facts') ?>"><i class="bi bi-people"></i> Culture Facts</a>
        <a href="manage.php?type=careers" class="<?= admin_nav_active('manage.php', 'careers', 'manage.php', 'careers') ?>"><i class="bi bi-briefcase"></i> Job Openings</a>
        <a href="manage.php?type=internships" class="<?= admin_nav_active('manage.php', 'internships', 'manage.php', 'internships') ?>"><i class="bi bi-mortarboard"></i> Internships</a>
        <a href="manage.php?type=perks" class="<?= admin_nav_active('manage.php', 'perks', 'manage.php', 'perks') ?>"><i class="bi bi-gift"></i> Career Perks</a>
        <a href="manage.php?type=apply_steps" class="<?= admin_nav_active('manage.php', 'apply_steps', 'manage.php', 'apply_steps') ?>"><i class="bi bi-list-ol"></i> Application Steps</a>
        <a href="manage.php?type=testimonials" class="<?= admin_nav_active('manage.php', 'testimonials', 'manage.php', 'testimonials') ?>"><i class="bi bi-chat-quote"></i> Testimonials</a>
        <a href="manage.php?type=faqs" class="<?= admin_nav_active('manage.php', 'faqs', 'manage.php', 'faqs') ?>"><i class="bi bi-question-circle"></i> FAQs</a>
        <a href="manage.php?type=trust" class="<?= admin_nav_active('manage.php', 'trust', 'manage.php', 'trust') ?>"><i class="bi bi-shield-check"></i> Trust Signals</a>
        <a href="messages.php?type=contact" class="<?= admin_nav_active('messages.php', 'contact', 'messages.php', 'contact') ?>"><i class="bi bi-inbox"></i> Contact Messages</a>
        <a href="messages.php?type=quotes" class="<?= admin_nav_active('messages.php', 'quotes', 'messages.php', 'quotes') ?>"><i class="bi bi-file-earmark-text"></i> Quote Requests</a>
    </aside>
    <main class="admin-main">
