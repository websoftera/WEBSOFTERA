<?php
$currentPage = $currentPage ?? 'home';
$meta = $meta ?? page_meta($currentPage);
$settings = read_json('settings.json');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($meta['title']) ?></title>
  <meta name="description" content="<?= e($meta['description']) ?>">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?= e($settings['canonical'] ?? 'https://websoftera.com/') ?>">
  <link rel="preconnect" href="https://cdn.jsdelivr.net">
  <link rel="preconnect" href="https://images.unsplash.com">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="<?= asset('assets/css/style.css') ?>?v=<?= filemtime(__DIR__ . '/../assets/css/style.css') ?>" rel="stylesheet">
  <script>
    // Apply saved theme before paint to avoid flash of wrong theme
    (function () {
      try {
        var saved = localStorage.getItem('websoftera-theme');
        var theme = saved === 'light' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', theme);
      } catch (e) { document.documentElement.setAttribute('data-theme', 'dark'); }
    })();
  </script>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<div class="header-wrap" id="headerWrap">

  <!-- TOPBAR -->
  <div class="topbar" id="siteTopbar">
    <div class="container topbar-shell">
      <div class="topbar-left">
        <a href="mailto:<?= e($settings['email']) ?>"><i class="bi bi-envelope"></i><?= e($settings['email']) ?></a>
        <a href="tel:<?= e(preg_replace('/[^+\d]/', '', $settings['phone'])) ?>"><i class="bi bi-telephone"></i><?= e($settings['phone']) ?></a>
        <span class="topbar-hours d-none d-lg-inline-flex"><i class="bi bi-clock"></i>Mon&ndash;Sat: 9:30 AM &ndash; 7:00 PM</span>
      </div>
      <div class="topbar-right">
        <div class="topbar-socials">
          <?php if (!empty($settings['facebook'])): ?>
            <a href="<?= e($settings['facebook']) ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <?php endif; ?>
          <?php if (!empty($settings['instagram'])): ?>
            <a href="<?= e($settings['instagram']) ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <?php endif; ?>
          <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
          <a href="#" aria-label="Twitter / X"><i class="bi bi-twitter-x"></i></a>
        </div>
      </div>
    </div>
  </div>

  <!-- MAIN HEADER -->
  <header class="site-header" id="siteHeader">
    <nav class="navbar navbar-expand-lg">
      <div class="container header-shell">

        <!-- Logo (left) -->
        <a class="navbar-brand" href="<?= asset('index.php') ?>">
          <img class="brand-logo" src="<?= asset('assets/img/websoftera-logo.png') ?>" alt="Websoftera logo">
        </a>

        <!-- Mobile toggler -->
        <button class="navbar-toggler" type="button" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Right-aligned group: nav + theme toggle + CTA -->
        <div class="header-right-group">
          <div class="collapse navbar-collapse main-nav-panel" id="mainNav">
            <ul class="navbar-nav">
              <li class="nav-item"><a class="nav-link <?= active_nav('home',    $currentPage) ?>" href="<?= asset('index.php') ?>">Home</a></li>
              <li class="nav-item"><a class="nav-link <?= active_nav('about',   $currentPage) ?>" href="<?= asset('about.php') ?>">About Us</a></li>
              <li class="nav-item"><a class="nav-link <?= active_nav('services',$currentPage) ?>" href="<?= asset('services.php') ?>">Services</a></li>
              <li class="nav-item"><a class="nav-link <?= active_nav('career',  $currentPage) ?>" href="<?= asset('career.php') ?>">Career</a></li>
              <li class="nav-item"><a class="nav-link <?= active_nav('contact', $currentPage) ?>" href="<?= asset('contact.php') ?>">Contact Us</a></li>
            </ul>
          </div>

          <button type="button" class="theme-toggle-btn" id="themeToggle" aria-label="Switch between dark and light theme">
            <i class="bi bi-sun-fill theme-icon theme-icon-sun"></i>
            <i class="bi bi-moon-stars-fill theme-icon theme-icon-moon"></i>
          </button>
        </div>

      </div>
    </nav>
  </header>

</div><!-- /.header-wrap -->

<main id="main">
