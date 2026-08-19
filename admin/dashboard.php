<?php
require_once __DIR__ . '/../includes/functions.php';
$adminTitle = 'Dashboard';
$services     = read_json('services.json');
$clients      = read_json('clients.json');
$careers      = read_json('careers.json');
$internships  = read_json('internships.json');
$testimonials = read_json('testimonials.json');
$messages     = read_json('messages.json');
$quoteMessages = read_json('quote_messages.json');
$milestones   = read_json('milestones.json');
$faqs         = read_json('faqs.json');
include __DIR__ . '/admin-header.php';
?>
<div class="admin-title">
    <div>
        <span class="eyebrow">Overview</span>
        <h1>Website dashboard</h1>
    </div>
</div>
<div class="row g-4">
    <?php foreach ([
        ['Services', count($services), 'bi-grid'],
        ['Client Websites', count($clients), 'bi-window-stack'],
        ['Job Openings', count($careers), 'bi-briefcase'],
        ['Internships', count($internships), 'bi-mortarboard'],
        ['Testimonials', count($testimonials), 'bi-chat-quote'],
        ['Milestones', count($milestones), 'bi-flag'],
        ['FAQs', count($faqs), 'bi-question-circle'],
        ['Contact Messages', count($messages), 'bi-inbox'],
        ['Quote Requests', count($quoteMessages), 'bi-file-earmark-text'],
    ] as $card): ?>
        <div class="col-md-6 col-xl-3">
            <div class="admin-stat">
                <i class="bi <?= e($card[2]) ?>"></i>
                <span><?= e($card[0]) ?></span>
                <strong><?= e((string)$card[1]) ?></strong>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<section class="admin-panel mt-4">
    <h2>Quick actions</h2>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-primary" href="content.php"><i class="bi bi-file-earmark-text"></i> Update Website Content & SEO</a>
        <a class="btn btn-outline-primary" href="manage.php?type=services">Manage Services</a>
        <a class="btn btn-outline-primary" href="manage.php?type=clients">Manage Client Websites</a>
        <a class="btn btn-outline-primary" href="manage.php?type=hero_images">Manage Hero Slider</a>
        <a class="btn btn-outline-primary" href="manage.php?type=careers">Manage Jobs</a>
        <a class="btn btn-outline-primary" href="manage.php?type=internships">Manage Internships</a>
        <a class="btn btn-outline-primary" href="manage.php?type=testimonials">Manage Testimonials</a>
        <a class="btn btn-outline-primary" href="messages.php?type=contact">Contact Messages</a>
        <a class="btn btn-outline-primary" href="messages.php?type=quotes">Quote Requests</a>
    </div>
</section>

<section class="admin-panel mt-4">
    <h2>About Page Sections</h2>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-primary" href="manage.php?type=milestones">Milestones</a>
        <a class="btn btn-outline-primary" href="manage.php?type=values">Company Values</a>
        <a class="btn btn-outline-primary" href="manage.php?type=culture_facts">Culture Facts</a>
    </div>
</section>

<section class="admin-panel mt-4">
    <h2>Services Page Sections</h2>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-primary" href="manage.php?type=tech_stack">Technology Stack</a>
        <a class="btn btn-outline-primary" href="manage.php?type=process_steps">Delivery Process</a>
    </div>
</section>

<section class="admin-panel mt-4">
    <h2>Contact Page Sections</h2>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-primary" href="manage.php?type=faqs">FAQs</a>
        <a class="btn btn-outline-primary" href="manage.php?type=trust">Trust Signals</a>
    </div>
</section>

<section class="admin-panel mt-4">
    <h2>Career Page Sections</h2>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-primary" href="manage.php?type=perks">Career Perks</a>
        <a class="btn btn-outline-primary" href="manage.php?type=apply_steps">Application Steps</a>
    </div>
</section>

<?php include __DIR__ . '/admin-footer.php'; ?>
