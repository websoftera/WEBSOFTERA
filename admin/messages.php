<?php
require_once __DIR__ . '/../includes/functions.php';
$adminTitle = 'Messages';
$messages = array_reverse(read_json('messages.json'));
$services = array_values(array_unique(array_filter(array_map(static fn($message) => trim((string)($message['service'] ?? '')), $messages))));
sort($services);
include __DIR__ . '/admin-header.php';
?>
<div class="admin-title">
    <div><span class="eyebrow">Leads</span><h1>Contact messages</h1></div>
    <a class="btn btn-primary" href="export-leads.php"><i class="bi bi-file-earmark-spreadsheet"></i> Export all leads</a>
</div>
<section class="admin-panel leads-panel">
    <div class="leads-toolbar">
        <div class="leads-search">
            <i class="bi bi-search"></i>
            <input class="form-control" type="search" id="lead-search" placeholder="Search name, email, phone or message" aria-label="Search leads">
        </div>
        <select class="form-select" id="lead-service" aria-label="Filter by service">
            <option value="">All services</option>
            <?php foreach ($services as $service): ?>
                <option value="<?= e(strtolower($service)) ?>"><?= e($service) ?></option>
            <?php endforeach; ?>
        </select>
        <select class="form-select" id="lead-date" aria-label="Filter by date">
            <option value="all">All dates</option>
            <option value="today">Today</option>
            <option value="7">Last 7 days</option>
            <option value="30">Last 30 days</option>
        </select>
        <button class="btn btn-outline-primary" type="button" id="lead-reset"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
    </div>
    <div class="leads-summary"><strong id="lead-count"><?= count($messages) ?></strong> lead<?= count($messages) === 1 ? '' : 's' ?></div>
    <?php if (!$messages): ?>
        <p class="text-muted mb-0">No messages yet.</p>
    <?php else: ?>
        <div class="leads-table-wrap">
            <table class="leads-table">
                <thead>
                    <tr>
                        <th>Lead</th>
                        <th>Contact</th>
                        <th>Service</th>
                        <th>Message</th>
                        <th>Received</th>
                    </tr>
                </thead>
                <tbody id="leads-body">
                <?php foreach ($messages as $message):
                    $createdAt = (string)($message['created_at'] ?? '');
                    $timestamp = strtotime($createdAt) ?: 0;
                    $searchable = strtolower(implode(' ', [
                        $message['name'] ?? '', $message['email'] ?? '', $message['phone'] ?? '',
                        $message['service'] ?? '', $message['message'] ?? '', $createdAt,
                    ]));
                ?>
                    <tr class="lead-row" data-search="<?= e($searchable) ?>" data-service="<?= e(strtolower((string)($message['service'] ?? ''))) ?>" data-timestamp="<?= $timestamp ?>">
                        <td><strong><?= e($message['name'] ?? '') ?></strong></td>
                        <td>
                            <a href="mailto:<?= e($message['email'] ?? '') ?>"><?= e($message['email'] ?? '') ?></a>
                            <?php if (!empty($message['phone'])): ?><a href="tel:<?= e(preg_replace('/[^+\d]/', '', $message['phone'])) ?>"><?= e($message['phone']) ?></a><?php endif; ?>
                        </td>
                        <td><span class="lead-service-badge"><?= e(!empty($message['service']) ? $message['service'] : 'Not specified') ?></span></td>
                        <td><div class="lead-message" title="<?= e($message['message'] ?? '') ?>"><?= e($message['message'] ?? '') ?></div></td>
                        <td><time datetime="<?= e($createdAt) ?>"><?= $timestamp ? e(date('d M Y', $timestamp)) . '<small>' . e(date('h:i A', $timestamp)) . '</small>' : '—' ?></time></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="leads-empty-filter" id="leads-empty-filter"><i class="bi bi-search"></i><strong>No matching leads</strong><span>Try changing your search or filters.</span></div>
    <?php endif; ?>
</section>
<script>
(() => {
    const rows = [...document.querySelectorAll('.lead-row')];
    if (!rows.length) return;
    const search = document.getElementById('lead-search');
    const service = document.getElementById('lead-service');
    const date = document.getElementById('lead-date');
    const count = document.getElementById('lead-count');
    const empty = document.getElementById('leads-empty-filter');
    const filter = () => {
        const term = search.value.trim().toLowerCase();
        const selectedService = service.value;
        const period = date.value;
        const now = new Date();
        const todayStart = new Date(now.getFullYear(), now.getMonth(), now.getDate()).getTime() / 1000;
        let visible = 0;
        rows.forEach(row => {
            const timestamp = Number(row.dataset.timestamp);
            const matchesSearch = !term || row.dataset.search.includes(term);
            const matchesService = !selectedService || row.dataset.service === selectedService;
            let matchesDate = true;
            if (period === 'today') matchesDate = timestamp >= todayStart;
            if (period === '7' || period === '30') matchesDate = timestamp >= (Date.now() / 1000) - (Number(period) * 86400);
            const show = matchesSearch && matchesService && matchesDate;
            row.hidden = !show;
            if (show) visible++;
        });
        count.textContent = visible;
        empty.classList.toggle('show', visible === 0);
    };
    search.addEventListener('input', filter);
    service.addEventListener('change', filter);
    date.addEventListener('change', filter);
    document.getElementById('lead-reset').addEventListener('click', () => {
        search.value = ''; service.value = ''; date.value = 'all'; filter(); search.focus();
    });
})();
</script>
<?php include __DIR__ . '/admin-footer.php'; ?>
