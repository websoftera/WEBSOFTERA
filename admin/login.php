<?php
require_once __DIR__ . '/../includes/functions.php';
if (is_admin()) {
    header('Location: dashboard.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['username'] ?? '') === $GLOBALS['admin_user'] && password_verify($_POST['password'] ?? '', $GLOBALS['admin_pass_hash'])) {
        $_SESSION['is_admin'] = true;
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Invalid username or password.';
}
?>
<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | Websoftera</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../assets/css/style.css?v=<?= filemtime(__DIR__ . '/../assets/css/style.css') ?>" rel="stylesheet">
</head>
<body class="admin-auth">
<main class="auth-wrap">
    <form class="auth-card" method="post">
        <a class="navbar-brand mb-4" href="../index.php"><img src="../assets/img/websoftera-logo.png" alt="Websoftera logo"><span>Admin</span></a>
        <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <label>Username</label>
        <input class="form-control mb-3" name="username" required>
        <label>Password</label>
        <input class="form-control mb-4" type="password" name="password" required>
        <button class="btn btn-primary w-100" type="submit"><i class="bi bi-box-arrow-in-right"></i> Login</button>
    </form>
</main>
</body>
</html>
