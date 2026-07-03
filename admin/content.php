<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
verify_csrf();
$saved = false;
$content = read_json('content.json');

// Ensure nested groups exist even on older content.json structures
$content['about_page']   = $content['about_page']   ?? [];
$content['contact_page'] = $content['contact_page'] ?? [];
$content['career_page']  = $content['career_page']  ?? [];
$content['company_stats'] = $content['company_stats'] ?? [];

$statFields = ['projects', 'clients', 'countries', 'years', 'team_members'];
$aboutFields = ['founded_year', 'story_heading', 'story_text', 'story_quote', 'story_text2', 'mission_tagline', 'vision_tagline', 'culture_heading', 'culture_text', 'culture_text2'];
$contactFields = ['intro', 'form_subtitle', 'response_time', 'response_note', 'business_hours', 'whatsapp_message'];
$careerFields = ['intro', 'culture_heading', 'culture_text', 'culture_text2', 'whatsapp_message'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($content['pages'] as $key => $page) {
        $content['pages'][$key]['title'] = trim($_POST['meta_title'][$key] ?? $page['title']);
        $content['pages'][$key]['description'] = trim($_POST['meta_description'][$key] ?? $page['description']);
    }
    foreach (['hero_title', 'hero_subtitle', 'about_intro', 'mission', 'vision', 'ads_note'] as $field) {
        $content[$field] = trim($_POST[$field] ?? ($content[$field] ?? ''));
    }
    foreach ($statFields as $field) {
        $content['company_stats'][$field] = trim($_POST['company_stats'][$field] ?? ($content['company_stats'][$field] ?? ''));
    }
    foreach ($aboutFields as $field) {
        $content['about_page'][$field] = trim($_POST['about_page'][$field] ?? ($content['about_page'][$field] ?? ''));
    }
    foreach ($contactFields as $field) {
        $content['contact_page'][$field] = trim($_POST['contact_page'][$field] ?? ($content['contact_page'][$field] ?? ''));
    }
    foreach ($careerFields as $field) {
        $content['career_page'][$field] = trim($_POST['career_page'][$field] ?? ($content['career_page'][$field] ?? ''));
    }
    write_json('content.json', $content);
    $saved = true;
}
$adminTitle = 'Website Content';
include __DIR__ . '/admin-header.php';

function field_label(string $key): string {
    return ucwords(str_replace('_', ' ', $key));
}
function field_rows(string $key): int {
    return in_array($key, ['story_text', 'story_text2', 'culture_text', 'culture_text2', 'intro'], true) ? 3 : 2;
}
?>
<div class="admin-title"><div><span class="eyebrow">Edit</span><h1>Website content and SEO</h1></div></div>
<?php if ($saved): ?><div class="alert alert-success"><i class="bi bi-check-circle me-1"></i> Content saved. Your live website now reflects these changes.</div><?php endif; ?>

<form class="admin-panel" method="post">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

    <h2>Page SEO</h2>
    <?php foreach ($content['pages'] as $key => $page): ?>
        <div class="seo-row">
            <h3><?= e(ucfirst($key)) ?> Page</h3>
            <label>Meta Title</label>
            <input class="form-control" name="meta_title[<?= e($key) ?>]" value="<?= e($page['title']) ?>">
            <label>Meta Description</label>
            <textarea class="form-control" name="meta_description[<?= e($key) ?>]" rows="2"><?= e($page['description']) ?></textarea>
        </div>
    <?php endforeach; ?>

    <h2 class="mt-4">Home Page Content</h2>
    <?php foreach (['hero_title', 'hero_subtitle', 'about_intro', 'mission', 'vision', 'ads_note'] as $field): ?>
        <label><?= e(field_label($field)) ?></label>
        <textarea class="form-control mb-3" name="<?= e($field) ?>" rows="<?= field_rows($field) ?>"><?= e($content[$field] ?? '') ?></textarea>
    <?php endforeach; ?>

    <h2 class="mt-4">Company-Wide Stats</h2>
    <p class="text-muted" style="font-size:.85rem;margin-top:-10px;margin-bottom:18px;">These numbers appear across the homepage, About page, and Career page (hero stats, counters, stats ribbon, team size).</p>
    <div class="row g-3 mb-3">
        <?php foreach ($statFields as $field): ?>
            <div class="col-md-2">
                <label><?= e(field_label($field)) ?></label>
                <input class="form-control" name="company_stats[<?= e($field) ?>]" value="<?= e($content['company_stats'][$field] ?? '') ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <h2 class="mt-4">About Page Content</h2>
    <p class="text-muted" style="font-size:.85rem;margin-top:-10px;margin-bottom:18px;">Story section, mission/vision taglines, and culture section text shown on the About page.</p>
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <label>Founded Year</label>
            <input class="form-control" name="about_page[founded_year]" value="<?= e($content['about_page']['founded_year'] ?? '') ?>">
        </div>
    </div>
    <?php foreach (['story_heading', 'story_text', 'story_quote', 'story_text2', 'mission_tagline', 'vision_tagline', 'culture_heading', 'culture_text', 'culture_text2'] as $field): ?>
        <label><?= e(field_label($field)) ?></label>
        <textarea class="form-control mb-3" name="about_page[<?= e($field) ?>]" rows="<?= field_rows($field) ?>"><?= e($content['about_page'][$field] ?? '') ?></textarea>
    <?php endforeach; ?>

    <h2 class="mt-4">Contact Page Content</h2>
    <div class="row g-3 mb-3">
        <?php foreach (['response_time', 'business_hours'] as $field): ?>
            <div class="col-md-6">
                <label><?= e(field_label($field)) ?></label>
                <input class="form-control" name="contact_page[<?= e($field) ?>]" value="<?= e($content['contact_page'][$field] ?? '') ?>">
            </div>
        <?php endforeach; ?>
    </div>
    <?php foreach (['intro', 'form_subtitle', 'response_note', 'whatsapp_message'] as $field): ?>
        <label><?= e(field_label($field)) ?></label>
        <textarea class="form-control mb-3" name="contact_page[<?= e($field) ?>]" rows="<?= field_rows($field) ?>"><?= e($content['contact_page'][$field] ?? '') ?></textarea>
    <?php endforeach; ?>

    <h2 class="mt-4">Career Page Content</h2>
    <?php foreach (['intro', 'culture_heading', 'culture_text', 'culture_text2', 'whatsapp_message'] as $field): ?>
        <label><?= e(field_label($field)) ?></label>
        <textarea class="form-control mb-3" name="career_page[<?= e($field) ?>]" rows="<?= field_rows($field) ?>"><?= e($content['career_page'][$field] ?? '') ?></textarea>
    <?php endforeach; ?>

    <button class="btn btn-primary mt-2"><i class="bi bi-check2"></i> Save Content</button>
</form>
<?php include __DIR__ . '/admin-footer.php'; ?>
