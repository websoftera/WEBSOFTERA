<?php
require_once __DIR__ . '/includes/functions.php';
$currentPage = 'career';
$meta = page_meta('career');
$settings    = read_json('settings.json');
$content     = read_json('content.json');
$stats       = $content['company_stats'] ?? [];
$cp          = $content['career_page'] ?? [];
$careers     = read_json('careers.json');
$internships = read_json('internships.json');
$perks       = read_json('perks.json');
$applySteps  = read_json('apply_steps.json');
include __DIR__ . '/includes/header.php';

$email     = $settings['email'] ?? 'info@websoftera.com';
$waPhone   = preg_replace('/[^0-9]/', '', $settings['phone'] ?? '');
$waMessage = $cp['whatsapp_message'] ?? "Hi, I'd like to apply for a role at Websoftera.";
?>

<!-- PAGE HERO -->
<section class="page-hero">
  <div class="container reveal">
    <span class="eyebrow">Join Our Team</span>
    <h1>Build Real Digital Products With A Team That Cares About Quality</h1>
    <p><?= e($cp['intro'] ?? "Websoftera is growing — and we're looking for developers, marketers, and designers to join our team.") ?></p>
    <div class="page-hero-pills">
      <a href="#job-openings" class="page-hero-pill"><i class="bi bi-briefcase"></i> Full-Time Roles</a>
      <a href="#internships" class="page-hero-pill"><i class="bi bi-mortarboard"></i> Internships</a>
      <a href="#apply-steps" class="page-hero-pill"><i class="bi bi-send"></i> How To Apply</a>
    </div>
  </div>
</section>



<!-- PERKS & BENEFITS -->
<?php if ($perks): ?>
<section class="perks-section">
  <div class="container">
    <div class="section-title reveal">
      <span class="eyebrow">What You Get</span>
      <h2>Perks, Benefits, And What Working Here Looks Like</h2>
    </div>
    <div class="perks-grid reveal">
      <?php foreach ($perks as $p): ?>
        <div class="perk-card-v2">
          <div class="perk-icon"><i class="bi <?= e($p['icon']) ?>"></i></div>
          <div class="perk-text">
            <h4><?= e($p['title']) ?></h4>
            <p><?= e($p['description']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- JOB OPENINGS -->
<section class="jobs-section" id="job-openings">
  <div class="container">
    <div class="section-title reveal">
      <span class="eyebrow">Full-Time Openings</span>
      <h2>Current Job Opportunities</h2>
      <p>We hire for attitude and capability — not pedigree. If you can show great work, we want to talk.</p>
    </div>
    <?php if (!$careers): ?>
      <p class="text-muted text-center">No open positions right now — check back soon, or send an open application below.</p>
    <?php endif; ?>
    <div class="row g-4">
      <?php foreach ($careers as $ji => $job):
        $isOpen = strtolower(trim($job['status'] ?? '')) === 'open';
        $reqs   = $job['requirements'] ?? [];
        $nice   = $job['nice_to_have'] ?? [];
      ?>
        <div class="col-lg-6 reveal delay-<?= $ji % 2 ?>">
          <div class="job-card-v2">
            <div class="jcv2-header">
              <div class="jcv2-header-top">
                <div>
                  <?php if ($isOpen): ?>
                    <span class="job-open-dot">Actively Hiring</span>
                  <?php else: ?>
                    <span class="job-closed-dot"><?= e($job['status']) ?></span>
                  <?php endif; ?>
                  <h3 class="jcv2-title mt-2"><?= e($job['title']) ?></h3>
                </div>
                <?php if ($isOpen): ?>
                  <a href="#apply-steps" class="btn btn-primary btn-sm flex-shrink-0">Apply Now</a>
                <?php endif; ?>
              </div>
              <div class="jcv2-tags">
                <span class="j-tag"><i class="bi bi-briefcase"></i><?= e($job['type']) ?></span>
                <span class="j-tag"><i class="bi bi-geo-alt"></i><?= e($job['location']) ?></span>
                <span class="j-tag"><i class="bi bi-clock"></i>Full Time</span>
              </div>
            </div>
            <div class="jcv2-body">
              <p class="jcv2-desc"><?= e($job['description']) ?></p>
              <?php if ($reqs): ?>
                <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);margin-bottom:12px;">What We're Looking For</div>
                <div class="jcv2-requirements">
                  <?php foreach ($reqs as $req): ?>
                    <div class="jcv2-req"><i class="bi bi-check2-circle"></i><?= e($req) ?></div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              <?php if ($nice): ?>
                <div style="margin-top:16px;">
                  <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);margin-bottom:10px;">Nice To Have</div>
                  <?php foreach ($nice as $n): ?>
                    <div class="jcv2-req" style="color:var(--text-muted);"><i class="bi bi-star" style="color:var(--accent);"></i><?= e($n) ?></div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="jcv2-footer">
              <div class="jcv2-footer-info">
                <i class="bi bi-send"></i> Apply via email or WhatsApp — link below
              </div>
              <?php if ($isOpen): ?>
                <a href="mailto:<?= e($email) ?>?subject=<?= e(rawurlencode('Application: ' . $job['title'])) ?>" class="btn btn-outline-primary btn-sm">Email Resume</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- INTERNSHIPS -->
<section class="internships-section" id="internships">
  <div class="container">
    <div class="section-title reveal">
      <span class="eyebrow">Internship Programme</span>
      <h2>Learn On Live Projects — Not Dummy Exercises</h2>
      <p>Our internships are structured for students and fresh graduates who want real IT experience — not just a certificate to put on their CV.</p>
    </div>
    <div class="row g-4">
      <?php foreach ($internships as $ii => $intern):
        $isOpen = strtolower(trim($intern['status'] ?? '')) === 'open';
        $reqs   = $intern['requirements'] ?? [];
      ?>
        <div class="col-lg-6 reveal delay-<?= $ii % 2 ?>">
          <div class="job-card-v2">
            <div class="jcv2-header">
              <div class="jcv2-header-top">
                <div>
                  <?php if ($isOpen): ?>
                    <span class="job-open-dot">Accepting Applications</span>
                  <?php else: ?>
                    <span class="job-closed-dot"><?= e($intern['status']) ?></span>
                  <?php endif; ?>
                  <h3 class="jcv2-title mt-2"><?= e($intern['title']) ?></h3>
                </div>
                <?php if ($isOpen): ?>
                  <a href="#apply-steps" class="btn btn-outline-primary btn-sm flex-shrink-0">Apply</a>
                <?php endif; ?>
              </div>
              <div class="jcv2-tags">
                <span class="j-tag"><i class="bi bi-clock"></i><?= e($intern['duration']) ?></span>
                <span class="j-tag"><i class="bi bi-geo-alt"></i><?= e($intern['location']) ?></span>
                <span class="j-tag"><i class="bi bi-mortarboard"></i>Internship</span>
              </div>
            </div>
            <div class="jcv2-body">
              <p class="jcv2-desc"><?= e($intern['description']) ?></p>
              <?php if ($reqs): ?>
                <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);margin-bottom:12px;">Basic Requirements</div>
                <div class="jcv2-requirements">
                  <?php foreach ($reqs as $req): ?>
                    <div class="jcv2-req"><i class="bi bi-check2-circle"></i><?= e($req) ?></div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="jcv2-footer">
              <div class="jcv2-footer-info">
                <i class="bi bi-award"></i> Certificate provided on successful completion
              </div>
              <?php if ($isOpen): ?>
                <a href="mailto:<?= e($email) ?>?subject=<?= e(rawurlencode('Internship Application: ' . $intern['title'])) ?>" class="btn btn-outline-primary btn-sm">Apply Now</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- APPLICATION PROCESS -->
<?php if ($applySteps): ?>
<section class="apply-steps-section" id="apply-steps">
  <div class="container">
    <div class="section-title reveal">
      <span class="eyebrow">How To Apply</span>
      <h2>Our Hiring Process Is Simple And Respectful Of Your Time</h2>
      <p>No endless rounds. No ghosting. Here's exactly what happens after you apply.</p>
    </div>
    <div class="apply-steps-grid reveal">
      <?php foreach ($applySteps as $i => $s): ?>
        <div class="apply-step">
          <div class="as-num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></div>
          <h4><?= e($s['title']) ?></h4>
          <p><?= e($s['description']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Apply CTA inline -->
    <div class="text-center mt-5 reveal">
      <p style="font-size:1rem;color:var(--text-dim);margin-bottom:20px;">Ready to apply? Send your resume directly.</p>
      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="mailto:<?= e($email) ?>?subject=<?= e(rawurlencode('Job Application — Websoftera')) ?>" class="btn btn-primary btn-lg">
          <i class="bi bi-envelope"></i> Email Your Resume
        </a>
        <a href="https://wa.me/<?= e($waPhone) ?>?text=<?= rawurlencode($waMessage) ?>" class="btn btn-outline-light btn-lg" target="_blank" rel="noopener">
          <i class="bi bi-whatsapp"></i> WhatsApp Us
        </a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CTA BAND -->
<section class="cta-band">
  <div class="container d-lg-flex align-items-center justify-content-between gap-4">
    <div>
      <span class="eyebrow">Don't See Your Role?</span>
      <h2>Send Us Your Profile Anyway</h2>
      <p>We're always open to meeting strong candidates, even when there isn't an active listing. If you're good, we'll find a way to work together.</p>
    </div>
    <a href="mailto:<?= e($email) ?>?subject=<?= e(rawurlencode('Open Application — Websoftera')) ?>" class="btn btn-primary btn-lg flex-shrink-0 mt-3 mt-lg-0">
      <i class="bi bi-send"></i> Send Open Application
    </a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
