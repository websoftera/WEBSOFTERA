<?php
require_once __DIR__ . '/includes/functions.php';
$currentPage = 'services';
$meta = page_meta('services');
$services     = read_json('services.json');
$techStack    = read_json('tech_stack.json');
$processSteps = read_json('process_steps.json');
$quoteSuccess = isset($_GET['quote']) && $_GET['quote'] === 'success';
$quoteError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_type'] ?? '') === 'quote') {
  $quoteLead = [
    'name'       => trim($_POST['name'] ?? ''),
    'email'      => trim($_POST['email'] ?? ''),
    'phone'      => trim($_POST['phone'] ?? ''),
    'service'    => trim($_POST['service'] ?? ''),
    'message'    => trim($_POST['message'] ?? ''),
    'source'     => 'Service quote request',
    'created_at' => date('Y-m-d H:i:s'),
  ];
  if ($quoteLead['name'] === '' || $quoteLead['service'] === '' || $quoteLead['message'] === '') {
    $quoteError = 'Please complete all required fields.';
  } elseif (!filter_var($quoteLead['email'], FILTER_VALIDATE_EMAIL)) {
    $quoteError = 'Please enter a valid email address.';
  } elseif (!valid_indian_mobile($quoteLead['phone'])) {
    $quoteError = 'Please enter a valid 10-digit Indian mobile number.';
  } else {
    $emailLead = $quoteLead;
    $emailLead['message'] = "Quote request\n\n" . $quoteLead['message'];
    if (append_json('quote_messages.json', $quoteLead)) {
      send_contact_notification($emailLead);
      header('Location: services.php?quote=success');
      exit;
    }
    $quoteError = 'We could not save your request. Please try again.';
  }
}
include __DIR__ . '/includes/header.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
  <div class="container page-hero-grid reveal">
    <div class="page-hero-copy">
      <span class="eyebrow">What We Build</span>
      <h1>IT Services Engineered For Growth-Focused Businesses</h1>
      <p>From clean, conversion-led websites to full ERP automation — every Websoftera service is designed to produce measurable business results, not just deliverables.</p>
      <div class="page-hero-pills">
        <?php foreach ($services as $i => $svc): ?>
          <a href="#service-<?= $i + 1 ?>" class="page-hero-pill"><i class="bi <?= e($svc['icon']) ?>"></i><?= e($svc['title']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="page-hero-visual" aria-label="Connected technology services">
      <div class="phv-ring one"></div><div class="phv-ring two"></div>
      <div class="phv-core"><i class="bi bi-layers"></i><strong>Digital Solutions</strong><span>Built to scale</span></div>
      <div class="phv-chip chip-one"><i class="bi bi-window-stack"></i><span>Web</span></div>
      <div class="phv-chip chip-two"><i class="bi bi-phone"></i><span>Mobile</span></div>
      <div class="phv-chip chip-three"><i class="bi bi-diagram-3"></i><span>ERP</span></div>
    </div>
  </div>
</section>

<!-- STICKY SERVICE NAV -->
<nav class="service-sticky-nav" aria-label="Services navigation">
  <div class="container-fluid">
    <div class="service-nav-scroll">
      <?php foreach ($services as $i => $svc): ?>
        <a href="#service-<?= $i + 1 ?>" class="service-nav-link">
          <i class="bi <?= e($svc['icon']) ?>"></i>
          <?= e($svc['title']) ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</nav>

<!-- SERVICE SHOWCASE SECTIONS -->
<?php foreach ($services as $i => $svc):
  $isAlt  = ($i % 2 !== 0);
  $num    = str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT);
  $visual = $svc['visual'] ?? 'browser';
  $reqs   = $svc['reqs'] ?? [];
  $stats  = $svc['stats'] ?? [];
?>
<section class="service-showcase-section <?= $isAlt ? 'alt' : '' ?>" id="service-<?= $i + 1 ?>">
  <div class="svc-bg-num"><?= $num ?></div>
  <div class="container">
    <div class="row align-items-center g-5 <?= $isAlt ? 'flex-lg-row-reverse' : '' ?>">

      <!-- Info -->
      <div class="col-lg-6 reveal">
        <div class="svc-icon-orb"><i class="bi <?= e($svc['icon']) ?>"></i></div>
        <?php if (!empty($svc['eyebrow'])): ?><span class="svc-eyebrow"><?= e($svc['eyebrow']) ?></span><?php endif; ?>
        <h2 class="svc-heading"><?= e($svc['title']) ?></h2>
        <p class="svc-para"><?= e($svc['description']) ?></p>

        <?php if ($reqs): ?>
          <div class="feature-checklist">
            <?php foreach ($reqs as $req): ?>
              <div class="fc-item"><i class="bi bi-check2-circle"></i><?= e($req) ?></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if ($stats): ?>
          <div class="svc-mini-stats">
            <?php foreach ($stats as $stat):
              $parts = array_map('trim', explode('|', $stat, 2));
              $value = $parts[0] ?? '';
              $label = $parts[1] ?? '';
            ?>
              <div class="svc-stat"><strong><?= e($value) ?></strong><span><?= e($label) ?></span></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="d-flex gap-3 flex-wrap">
          <a href="contact.php" class="btn btn-primary"><i class="bi bi-lightning-charge"></i> Get This Service</a>
          <button type="button" class="btn btn-outline-light js-quote-open" data-service="<?= e($svc['title']) ?>">Request Quote <i class="bi bi-arrow-right"></i></button>
        </div>
      </div>

      <!-- Visual -->
      <div class="col-lg-6 reveal delay-1">
        <div class="svc-visual-wrap">
          <div class="svc-visual-glow"></div>

          <?php if ($visual === 'browser'): ?>
            <div class="svp-browser">
              <div class="svp-topbar">
                <span class="svp-dot r"></span><span class="svp-dot y"></span><span class="svp-dot g"></span>
                <div class="svp-url-bar"></div>
              </div>
              <div class="svp-body">
                <span class="svp-preview-label"><?= e($svc['eyebrow'] ?? 'Digital Experience') ?></span>
                <h3><?= e($svc['title']) ?></h3>
                <p><?= e($svc['description']) ?></p>
                <div class="svp-cards">
                  <?php foreach (array_slice($svc['features'] ?? $reqs, 0, 3) as $feature): ?>
                    <div class="svp-card-block"><i class="bi bi-check2"></i><span><?= e($feature) ?></span></div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>

          <?php elseif ($visual === 'phone'): ?>
            <div class="svp-phone">
              <div class="svp-phone-notch"></div>
              <div class="svp-phone-screen">
                <div class="svp-phone-header"><i class="bi bi-phone"></i> Websoftera App</div>
                <strong class="svp-phone-title">Built for every screen</strong>
                <p class="svp-phone-copy">Fast, intuitive mobile experiences for your customers.</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:8px;">
                  <div class="svp-phone-feature"><i class="bi bi-android2"></i> Android</div>
                  <div class="svp-phone-feature"><i class="bi bi-apple"></i> iOS</div>
                </div>
                <div class="svp-phone-btn">Get Started</div>
              </div>
            </div>

          <?php elseif ($visual === 'analytics'): ?>
            <div class="svp-analytics">
              <div class="svp-chart-card">
                <div class="svp-chart-top">
                  <div>
                    <div style="font-size:.72rem;color:var(--text-muted);font-weight:600;margin-bottom:4px;">CAMPAIGN LEADS</div>
                    <div class="svp-chart-num">2,847</div>
                  </div>
                  <span class="svp-chart-change">↑ 34.2% this month</span>
                </div>
                <div class="svp-bars">
                  <div class="svp-bar"></div><div class="svp-bar"></div><div class="svp-bar"></div>
                  <div class="svp-bar"></div><div class="svp-bar"></div><div class="svp-bar"></div>
                </div>
                <div class="svp-metrics-row">
                  <div class="svp-metric-chip"><strong>4.2%</strong><span>CTR</span></div>
                  <div class="svp-metric-chip"><strong>₹48</strong><span>CPC</span></div>
                  <div class="svp-metric-chip"><strong>3.1×</strong><span>ROAS</span></div>
                </div>
              </div>
            </div>

          <?php elseif ($visual === 'code'): ?>
            <div class="svp-code">
              <div class="svp-code-top">
                <span class="svp-dot r"></span><span class="svp-dot y"></span><span class="svp-dot g"></span>
                <span style="margin-left:10px;font-size:.74rem;color:var(--text-muted);">app.js — Websoftera Training</span>
              </div>
              <div class="svp-code-body">
                <div class="svp-code-line"><span class="cm">// Full-Stack Project Workshop</span></div>
                <div class="svp-code-line"><span class="kw">const</span> <span class="fn">express</span> = require(<span class="str">'express'</span>);</div>
                <div class="svp-code-line"><span class="kw">const</span> app = express();</div>
                <div class="svp-code-line">&nbsp;</div>
                <div class="svp-code-line">app.<span class="fn">get</span>(<span class="str">'/api/students'</span>, <span class="kw">async</span> (req, res) => {</div>
                <div class="svp-code-line">&nbsp;&nbsp;<span class="kw">const</span> data = <span class="kw">await</span> <span class="fn">db.query</span>(<span class="str">'SELECT * FROM students'</span>);</div>
                <div class="svp-code-line">&nbsp;&nbsp;res.<span class="fn">json</span>({ success: <span class="kw">true</span>, data });</div>
                <div class="svp-code-line">});<span class="svp-code-cursor"></span></div>
              </div>
            </div>

          <?php elseif ($visual === 'erp'): ?>
            <div class="svp-erp">
              <div class="svp-erp-header">
                <span class="svp-erp-title"><i class="bi bi-grid-3x3-gap" style="margin-right:6px;"></i>ERP Control Panel</span>
                <span style="font-size:.74rem;color:#48C75A;font-weight:700;">● Live</span>
              </div>
              <div class="svp-erp-body">
                <div class="svp-module-grid">
                  <div class="svp-module"><i class="bi bi-box-seam"></i><span>Inventory</span><strong>2,341</strong></div>
                  <div class="svp-module"><i class="bi bi-people"></i><span>HR / Payroll</span><strong>48</strong></div>
                  <div class="svp-module"><i class="bi bi-graph-up"></i><span>Revenue</span><strong>₹12L</strong></div>
                </div>
                <div class="svp-progress-list mt-3">
                  <div>
                    <span>Purchase Orders Processed</span>
                    <div class="svp-progress-bar"><div class="svp-progress-fill" style="width:78%"></div></div>
                  </div>
                  <div>
                    <span>Dispatch Completed This Week</span>
                    <div class="svp-progress-bar"><div class="svp-progress-fill" style="width:64%;animation-delay:.3s"></div></div>
                  </div>
                  <div>
                    <span>Invoice Reconciliation</span>
                    <div class="svp-progress-bar"><div class="svp-progress-fill" style="width:91%;animation-delay:.6s"></div></div>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>

        </div>
      </div>

    </div>
  </div>
</section>
<?php endforeach; ?>

<!-- TECHNOLOGY STACK -->
<?php if ($techStack): ?>
<section class="section tech-stack-section">
  <div class="container">
    <div class="section-title reveal">
      <span class="eyebrow">Technology Stack</span>
      <h2>Tools And Technologies We Work With</h2>
      <p>We choose technologies based on your requirements — not hype. Every stack is practical, maintainable, and built for the long term.</p>
    </div>
    <div class="tech-grid reveal">
      <?php foreach ($techStack as $t): ?>
        <div class="tech-item reveal"><i class="bi <?= e($t['icon']) ?>"></i><span><?= e($t['name']) ?></span></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- HOW WE DELIVER -->
<?php if ($processSteps): ?>
<section class="section" style="background:var(--bg);">
  <div class="container">
    <div class="section-title reveal">
      <span class="eyebrow">Our Delivery Process</span>
      <h2>How Every Websoftera Project Gets Done</h2>
      <p>A clear, four-stage process that keeps your project on schedule, on brief, and on budget — every time.</p>
    </div>
    <div class="process-h-track reveal">
      <?php foreach ($processSteps as $i => $step): ?>
        <div class="process-h-step">
          <div class="ph-step-num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></div>
          <h4><?= e($step['title']) ?></h4>
          <p><?= e($step['description']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CTA BAND -->
<section class="cta-band">
  <div class="container d-lg-flex align-items-center justify-content-between gap-4">
    <div>
      <span class="eyebrow">Request Free Consultation</span>
      <h2>Ready To Build Something That Works?</h2>
      <p>Tell us your requirement. We'll suggest the right service, timeline, and budget — with no sales pressure.</p>
    </div>
    <a href="contact.php" class="btn btn-primary btn-lg flex-shrink-0 mt-3 mt-lg-0">
      <i class="bi bi-calendar2-check"></i> Book Consultation
    </a>
  </div>
</section>

<!-- REQUEST QUOTE MODAL -->
<div class="quote-modal<?= ($quoteSuccess || $quoteError !== '') ? ' open' : '' ?>" id="quote-modal" aria-hidden="<?= ($quoteSuccess || $quoteError !== '') ? 'false' : 'true' ?>">
  <div class="quote-modal-backdrop" data-quote-close></div>
  <div class="quote-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="quote-modal-title">
    <button type="button" class="quote-modal-close" data-quote-close aria-label="Close quote form"><i class="bi bi-x-lg"></i></button>
    <?php if ($quoteSuccess): ?>
      <div class="quote-success">
        <div class="quote-success-icon"><i class="bi bi-check2"></i></div>
        <span class="eyebrow">Request Received</span>
        <h2 id="quote-modal-title">Thank You For Your Interest</h2>
        <p>Your quote request has been received. Our team will review your requirement and contact you shortly.</p>
        <button type="button" class="btn btn-primary" data-quote-close>Continue Exploring</button>
      </div>
    <?php else: ?>
      <div class="quote-modal-heading">
        <span class="eyebrow">Free Project Estimate</span>
        <h2 id="quote-modal-title">Request A Quote</h2>
        <p>Tell us what you need. We’ll respond with the right approach, timeline, and estimated budget.</p>
      </div>
      <?php if ($quoteError !== ''): ?><div class="alert alert-danger"><?= e($quoteError) ?></div><?php endif; ?>
      <form method="post" action="services.php" class="quote-form">
        <input type="hidden" name="form_type" value="quote">
        <div class="quote-form-grid">
          <label><span>Full Name *</span><input class="form-control" type="text" name="name" value="<?= e($_POST['name'] ?? '') ?>" required></label>
          <label><span>Email Address *</span><input class="form-control" type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" maxlength="254" required></label>
          <label><span>Phone Number *</span><input class="form-control" type="tel" name="phone" value="<?= e($_POST['phone'] ?? '') ?>" inputmode="numeric" pattern="(?:\+91[ -]?)?[6-9][0-9]{9}" maxlength="14" title="Enter a valid 10-digit Indian mobile number" required></label>
          <label><span>Service Required *</span>
            <select class="form-select" name="service" id="quote-service" required>
              <option value="">Select a service</option>
              <?php foreach ($services as $service): ?>
                <option value="<?= e($service['title']) ?>"<?= ($_POST['service'] ?? '') === $service['title'] ? ' selected' : '' ?>><?= e($service['title']) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
        </div>
        <label><span>Project Requirements *</span><textarea class="form-control" name="message" rows="4" placeholder="Briefly describe your project, goals, and preferred timeline." required><?= e($_POST['message'] ?? '') ?></textarea></label>
        <button class="btn btn-primary btn-lg w-100" type="submit"><i class="bi bi-send"></i> Submit Quote Request</button>
      </form>
    <?php endif; ?>
  </div>
</div>

<script>
(() => {
  const modal = document.getElementById('quote-modal');
  const serviceSelect = document.getElementById('quote-service');
  const openModal = service => {
    if (serviceSelect && service) {
      serviceSelect.value = service;
      serviceSelect.dispatchEvent(new Event('change', { bubbles: true }));
    }
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('quote-modal-active');
    setTimeout(() => modal.querySelector('input, button, select, textarea')?.focus(), 50);
  };
  const closeModal = () => {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('quote-modal-active');
    if (location.search.includes('quote=success')) history.replaceState({}, '', 'services.php');
  };
  document.querySelectorAll('.js-quote-open').forEach(button => button.addEventListener('click', () => openModal(button.dataset.service)));
  document.querySelectorAll('[data-quote-close]').forEach(button => button.addEventListener('click', closeModal));
  document.addEventListener('keydown', event => { if (event.key === 'Escape' && modal.classList.contains('open')) closeModal(); });
  if (modal.classList.contains('open')) document.body.classList.add('quote-modal-active');
})();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
