<?php
require_once __DIR__ . '/../includes/functions.php';
$adminTitle = 'Messages';
$messages = array_reverse(read_json('messages.json'));
include __DIR__ . '/admin-header.php';
?>
<div class="admin-title"><div><span class="eyebrow">Leads</span><h1>Contact messages</h1></div></div>
<section class="admin-panel">
    <?php if (!$messages): ?>
        <p class="text-muted mb-0">No messages yet.</p>
    <?php endif; ?>
    <?php foreach ($messages as $message): ?>
        <div class="message-row">
            <div>
                <strong><?= e($message['name']) ?></strong>
                <span><?= e($message['created_at'] ?? '') ?></span>
            </div>
            <p><?= e($message['message']) ?></p>
            <small><?= e($message['email']) ?> <?= e($message['phone']) ?></small>
        </div>
    <?php endforeach; ?>
</section>
<?php include __DIR__ . '/admin-footer.php'; ?>
