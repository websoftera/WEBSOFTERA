<?php
require_once __DIR__ . '/includes/functions.php';
$currentPage = 'services';
$meta = page_meta('services');
$services     = read_json('services.json');
$techStack    = read_json('tech_stack.json');
$processSteps = read_json('process_steps.json');
include __DIR__ . '/includes/header.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
  <div class="container reveal">
    <span class="eyebrow">What We Build</span>
    <h1>IT Services Engineered For Growth-Focused Businesses</h1>
    <p>From clean, conversion-led websites to full ERP automation — every Websoftera service is designed to produce measurable business results, not just deliverables.</p>
    <div class="page-hero-pills">
      <?php foreach ($services as $i => $svc): ?>
        <a href="#service-<?= $i + 1 ?>" class="page-hero-pill">
          <i class="bi <?= e($svc['icon']) ?>"></i>
          <?= e($svc['title']) ?>
        </a>
      <?php endforeach; ?>
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
          <a href="contact.php" class="btn btn-outline-light">Request Quote <i class="bi bi-arrow-right"></i></a>
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
                <div class="svp-hero-bar"></div>
                <div class="svp-line l80"></div>
                <div class="svp-line l60"></div>
                <div class="svp-line l90" style="animation-delay:.3s"></div>
                <div class="svp-line l45" style="animation-delay:.5s"></div>
                <div class="svp-cards">
                  <div class="svp-card-block"></div>
                  <div class="svp-card-block" style="animation-delay:.4s"></div>
                  <div class="svp-card-block" style="animation-delay:.8s"></div>
                </div>
              </div>
            </div>

          <?php elseif ($visual === 'phone'): ?>
            <div class="svp-phone">
              <div class="svp-phone-notch"></div>
              <div class="svp-phone-screen">
                <div class="svp-phone-header"></div>
                <div class="svp-phone-line"></div>
                <div class="svp-phone-line lp70"></div>
                <div class="svp-phone-line lp50"></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:8px;">
                  <div class="svp-card-block" style="height:40px;border-radius:8px;"></div>
                  <div class="svp-card-block" style="height:40px;border-radius:8px;animation-delay:.4s"></div>
                </div>
                <div class="svp-phone-btn"></div>
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

<?php include __DIR__ . '/includes/footer.php'; ?>
