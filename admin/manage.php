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
<style>
.editor-card {
    transition: border-color 0.2s, box-shadow 0.2s;
}
.editor-card:hover {
    border-color: var(--primary) !important;
    box-shadow: 0 4px 12px rgba(255, 122, 0, 0.05);
}
.editor-card-header {
    user-select: none;
}
.editor-card-header h2 {
    margin-bottom: 0 !important;
    font-size: 1.05rem !important;
}
.editor-card.collapsed {
    padding-bottom: 16px;
}
.card-title-text {
    font-weight: 700;
}
</style>

<div class="admin-title"><div><span class="eyebrow">Manage</span><h1><?= e($config['title']) ?></h1></div></div>
<?php if ($saved): ?><div class="alert alert-success"><i class="bi bi-check-circle me-1"></i> Changes saved. Your live website now reflects this content.</div><?php endif; ?>
<form class="admin-panel" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <?php foreach ($items as $index => $item): 
        $isLast = ($index === count($items) - 1);
        $cardTitle = $item[$config['primary']] ?? '';
    ?>
        <div class="editor-card <?= $isLast ? 'is-new' : 'collapsed' ?>" data-index="<?= $index ?>">
            <div class="editor-card-header d-flex align-items-center justify-content-between cursor-pointer" onclick="toggleCard(<?= $index ?>)">
                <h2>
                    <i class="bi bi-grip-vertical text-muted"></i> 
                    <?= $index + 1 ?>. 
                    <span class="card-title-text"><?= e($cardTitle ?: 'New item') ?></span>
                    <?php if (!$isLast): ?>
                        <?php
                        $badgeText = '';
                        $badgeType = '';
                        if ($type === 'careers' || $type === 'internships') {
                            $badgeText = $item['status'] ?? '';
                            $badgeType = $item['type'] ?? $item['duration'] ?? '';
                        }
                        ?>
                        <?php if ($badgeText): ?>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle ms-2" style="font-size: 0.72rem;"><?= e($badgeText) ?></span>
                        <?php endif; ?>
                        <?php if ($badgeType): ?>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1" style="font-size: 0.72rem;"><?= e($badgeType) ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                </h2>
                <div class="d-flex align-items-center gap-2">
                    <?php if (!$isLast): ?>
                        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2 btn-edit" onclick="event.stopPropagation(); toggleCard(<?= $index ?>)">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 btn-delete" onclick="event.stopPropagation(); deleteCard(<?= $index ?>)">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    <?php else: ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle">Add New</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="editor-card-body mt-3" style="<?= $isLast ? '' : 'display: none;' ?>">
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
        </div>
    <?php endforeach; ?>
    <button class="btn btn-primary mt-3"><i class="bi bi-check2"></i> Save <?= e($config['title']) ?></button>
</form>

<script>
function toggleCard(index) {
    const card = document.querySelector(`.editor-card[data-index="${index}"]`);
    if (!card) return;
    const body = card.querySelector('.editor-card-body');
    const isCollapsed = card.classList.contains('collapsed');
    
    if (isCollapsed) {
        card.classList.remove('collapsed');
        body.style.display = 'block';
    } else {
        card.classList.add('collapsed');
        body.style.display = 'none';
    }
}

function deleteCard(index) {
    if (confirm("Are you sure you want to delete this item? This will save and reload the page.")) {
        const card = document.querySelector(`.editor-card[data-index="${index}"]`);
        if (card) {
            const primaryInput = card.querySelector(`[name="items[${index}][<?= $config['primary'] ?>]"]`);
            if (primaryInput) {
                primaryInput.value = '';
                card.closest('form').submit();
            }
        }
    }
}
</script>
<?php include __DIR__ . '/admin-footer.php'; ?>
