<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
verify_csrf();
$type = $_GET['type'] ?? 'services';

/*
 * Generic CRUD config per content type.
 * fields       — all editable fields, in display order
 * list_fields  — fields stored as newline-separated arrays (rendered as <textarea>, one item per line)
 * help         — optional per-field helper text shown under the input
 */
$allowed = [
    'services' => [
        'file' => 'services.json', 'title' => 'Services', 'primary' => 'title',
        'fields' => ['title', 'icon', 'eyebrow', 'description', 'visual', 'features', 'stats', 'reqs'],
        'list_fields' => ['features', 'stats', 'reqs'],
        'help' => [
            'icon'   => 'Bootstrap Icons class name, e.g. bi-window-stack. Browse icons at icons.getbootstrap.com',
            'visual' => 'Mockup style shown on the Services page. Use one of: browser, phone, analytics, code, erp',
            'stats'  => 'One stat per line, format: Value|Label — e.g. 150+|Sites Built',
            'reqs'   => 'One feature/requirement per line — shown as a checklist on the Services page.',
        ],
    ],
    'careers' => [
        'file' => 'careers.json', 'title' => 'Job Openings', 'primary' => 'title',
        'fields' => ['title', 'type', 'location', 'status', 'description', 'requirements', 'nice_to_have'],
        'list_fields' => ['requirements', 'nice_to_have'],
        'help' => [
            'status'       => 'Type "Open" to show the pulsing "Actively Hiring" badge, or any other word (e.g. Closed) for a neutral badge.',
            'requirements' => 'One requirement per line.',
            'nice_to_have' => 'One nice-to-have skill per line (optional).',
        ],
    ],
    'internships' => [
        'file' => 'internships.json', 'title' => 'Internships', 'primary' => 'title',
        'fields' => ['title', 'duration', 'location', 'status', 'description', 'requirements'],
        'list_fields' => ['requirements'],
        'help' => ['status' => 'Type "Open" to show the "Accepting Applications" badge.'],
    ],
    'clients' => [
        'file' => 'clients.json', 'title' => 'Client Websites', 'primary' => 'name',
        'fields' => ['category', 'name', 'logo', 'website_url', 'website_image'],
        'list_fields' => [],
    ],
    'hero_images' => [
        'file' => 'hero-images.json', 'title' => 'Hero Slider Images', 'primary' => 'title',
        'fields' => ['title', 'image', 'alt'],
        'list_fields' => [],
    ],
    'testimonials' => [
        'file' => 'testimonials.json', 'title' => 'Testimonials', 'primary' => 'name',
        'fields' => ['name', 'role', 'quote'],
        'list_fields' => [],
    ],
    'milestones' => [
        'file' => 'milestones.json', 'title' => 'Company Milestones', 'primary' => 'title',
        'fields' => ['year', 'title', 'description'],
        'list_fields' => [],
        'help' => ['year' => 'Shown inside the round timeline badge, e.g. 2018'],
    ],
    'values' => [
        'file' => 'values.json', 'title' => 'Company Values', 'primary' => 'title',
        'fields' => ['icon', 'title', 'description'],
        'list_fields' => [],
        'help' => ['icon' => 'Bootstrap Icons class, e.g. bi-palette2'],
    ],
    'culture_facts' => [
        'file' => 'culture_facts.json', 'title' => 'Culture Facts (About page)', 'primary' => 'text',
        'fields' => ['icon', 'text'],
        'list_fields' => [],
        'help' => ['icon' => 'Bootstrap Icons class, e.g. bi-geo-alt'],
    ],
    'perks' => [
        'file' => 'perks.json', 'title' => 'Career Perks & Benefits', 'primary' => 'title',
        'fields' => ['icon', 'title', 'description'],
        'list_fields' => [],
        'help' => ['icon' => 'Bootstrap Icons class, e.g. bi-laptop'],
    ],
    'apply_steps' => [
        'file' => 'apply_steps.json', 'title' => 'Application Process Steps', 'primary' => 'title',
        'fields' => ['title', 'description'],
        'list_fields' => [],
        'help' => ['title' => 'Step number is automatic, based on order below.'],
    ],
    'process_steps' => [
        'file' => 'process_steps.json', 'title' => 'Delivery Process Steps (Services page)', 'primary' => 'title',
        'fields' => ['title', 'description'],
        'list_fields' => [],
        'help' => ['title' => 'Step number is automatic, based on order below.'],
    ],
    'tech_stack' => [
        'file' => 'tech_stack.json', 'title' => 'Technology Stack (Services page)', 'primary' => 'name',
        'fields' => ['icon', 'name'],
        'list_fields' => [],
        'help' => ['icon' => 'Bootstrap Icons class, e.g. bi-filetype-php'],
    ],
    'faqs' => [
        'file' => 'faqs.json', 'title' => 'FAQs (Contact page)', 'primary' => 'question',
        'fields' => ['question', 'answer'],
        'list_fields' => [],
    ],
    'trust' => [
        'file' => 'trust.json', 'title' => 'Trust Signals (Contact page)', 'primary' => 'title',
        'fields' => ['icon', 'title', 'description'],
        'list_fields' => [],
        'help' => ['icon' => 'Bootstrap Icons class, e.g. bi-clock-history'],
    ],
];

if (!isset($allowed[$type])) {
    $type = 'services';
}
$config       = $allowed[$type];
$listFields   = $config['list_fields'] ?? [];
$helpText     = $config['help'] ?? [];
$items        = read_json($config['file']);
$saved        = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newItems = [];
    $posted = $_POST['items'] ?? [];
    foreach ($posted as $index => $row) {
        if (trim($row[$config['primary']] ?? '') === '') {
            continue;
        }
        $item = [];
        foreach ($config['fields'] as $field) {
            if (in_array($field, $listFields, true)) {
                $list = array_filter(array_map('trim', explode("\n", $row[$field] ?? '')));
                $item[$field] = array_values($list);
            } else {
                $val = trim($row[$field] ?? '');

                // Handle file uploads for image-managed sections.
                if (($type === 'clients' && in_array($field, ['logo', 'website_image'], true)) || ($type === 'hero_images' && $field === 'image')) {
                    $fileKey = "upload_{$field}_{$index}";
                    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                        $filename = time() . '_' . basename($_FILES[$fileKey]['name']);
                        $uploadDir = $type === 'hero_images' ? '/assets/img/hero/' : '/assets/img/clients/';
                        $dest = dirname(__DIR__) . $uploadDir . $filename;
                        if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $dest)) {
                            $val = $filename;
                        }
                    }
                }

                $item[$field] = $val;
            }
        }
        $newItems[] = $item;
    }
    write_json($config['file'], $newItems);
    $items = $newItems;
    $saved = true;
}
$items[] = array_fill_keys($config['fields'], '');
$adminTitle = $config['title'];
include __DIR__ . '/admin-header.php';
?>
<div class="admin-title"><div><span class="eyebrow">Manage</span><h1><?= e($config['title']) ?></h1></div></div>
<?php if ($saved): ?><div class="alert alert-success"><i class="bi bi-check-circle me-1"></i> Changes saved. Your live website now reflects this content.</div><?php endif; ?>
<form class="admin-panel" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <?php foreach ($items as $index => $item): ?>
        <div class="editor-card">
            <h2><i class="bi bi-grip-vertical text-muted"></i> <?= $index + 1 ?>. <?= e($item[$config['primary']] ?: 'New item') ?></h2>
            <div class="row g-3">
                <?php foreach ($config['fields'] as $field):
                    $isLong  = in_array($field, array_merge(['description'], $listFields), true);
                    $isImage = ($type === 'clients' && in_array($field, ['logo', 'website_image'], true)) || ($type === 'hero_images' && $field === 'image');
                ?>
                    <div class="<?= $isLong ? 'col-12' : 'col-md-6' ?>">
                        <label><?= e(ucwords(str_replace('_', ' ', $field))) ?></label>
                        <?php if (in_array($field, $listFields, true)): ?>
                            <textarea class="form-control" rows="4" name="items[<?= $index ?>][<?= e($field) ?>]"><?= e(is_array($item[$field] ?? null) ? implode("\n", $item[$field]) : ($item[$field] ?? '')) ?></textarea>
                        <?php elseif ($field === 'description'): ?>
                            <textarea class="form-control" rows="3" name="items[<?= $index ?>][<?= e($field) ?>]"><?= e($item[$field] ?? '') ?></textarea>
                        <?php elseif ($isImage): ?>
                            <input type="hidden" name="items[<?= $index ?>][<?= e($field) ?>]" value="<?= e($item[$field] ?? '') ?>">
                            <input type="file" class="form-control" name="upload_<?= e($field) ?>_<?= $index ?>">
                            <?php if (!empty($item[$field])): ?>
                                <small class="text-success d-block mt-1">Current file: <strong><?= e($item[$field]) ?></strong></small>
                            <?php else: ?>
                                <small class="text-muted d-block mt-1">No file uploaded yet.</small>
                            <?php endif; ?>
                        <?php else: ?>
                            <input class="form-control" name="items[<?= $index ?>][<?= e($field) ?>]" value="<?= e($item[$field] ?? '') ?>">
                        <?php endif; ?>
                        <?php if (!empty($helpText[$field])): ?>
                            <small class="text-muted d-block mt-1"><?= e($helpText[$field]) ?></small>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <button class="btn btn-primary"><i class="bi bi-check2"></i> Save <?= e($config['title']) ?></button>
</form>
<?php include __DIR__ . '/admin-footer.php'; ?>
