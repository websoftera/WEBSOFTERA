<?php
require_once __DIR__ . '/includes/functions.php';
$currentPage = 'about';
$meta = page_meta('about');
$content = read_json('content.json');
$stats   = $content['company_stats'] ?? [];
$ap      = $content['about_page'] ?? [];
$milestones   = read_json('milestones.json');
$values       = read_json('values.json');
$cultureFacts = read_json('culture_facts.json');
include __DIR__ . '/includes/header.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
  <div class="container reveal">
    <span class="eyebrow">Who We Are</span>
    <h1>Technology, Design, And Marketing — All Under One Roof</h1>
    <p>Websoftera is a Pune-based IT company helping businesses build sharper digital presence, cleaner internal systems, and more effective growth marketing. We have been doing this since <?= e($ap['founded_year'] ?? '2020') ?>.</p>
  </div>
</section>

<!-- STATS RIBBON -->
<section class="stats-ribbon-sec">
  <div class="container">
    <div class="stats-ribbon reveal">
      <div class="stats-ribbon-grid">
        <div class="stats-ribbon-item">
          <div class="counter-icon-wrap"><i class="bi bi-briefcase"></i></div>
          <div class="counter-number-wrap">
            <span class="counter-number" data-target="<?= (int) preg_replace('/[^0-9]/', '', $stats['projects'] ?? '150') ?>">0</span><span class="counter-suffix">+</span>
          </div>
          <span>Projects Completed</span>
        </div>
        <div class="stats-ribbon-item">
          <div class="counter-icon-wrap"><i class="bi bi-people"></i></div>
          <div class="counter-number-wrap">
            <span class="counter-number" data-target="<?= (int) preg_replace('/[^0-9]/', '', $stats['clients'] ?? '80') ?>">0</span><span class="counter-suffix">+</span>
          </div>
          <span>Clients Served</span>
        </div>
        <div class="stats-ribbon-item">
          <div class="counter-icon-wrap"><i class="bi bi-emoji-smile"></i></div>
          <div class="counter-number-wrap">
            <span class="counter-number" data-target="<?= (int) preg_replace('/[^0-9]/', '', $stats['satisfaction'] ?? '97') ?>">0</span><span class="counter-suffix">%</span>
          </div>
          <span>Client Satisfaction</span>
        </div>
        <div class="stats-ribbon-item">
          <div class="counter-icon-wrap"><i class="bi bi-headset"></i></div>
          <div class="counter-number-wrap">
            <span class="counter-number" data-target="<?= (int) explode('/', $stats['support'] ?? '24')[0] ?>">0</span><span class="counter-suffix">/7</span>
          </div>
          <span>Technical Support</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- OUR STORY -->
<section class="about-story-section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-5 reveal">
        <div class="story-image-col">
          <div class="story-img-frame">
            <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=900&q=80" alt="Websoftera team working">
          </div>
          <div class="story-img-badge">
            <i class="bi bi-award-fill"></i>
            <div>
              <strong>Est. <?= e($ap['founded_year'] ?? '2020') ?></strong>
              <span>Pune, Maharashtra</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-7 reveal delay-1">
        <div class="story-text-col">
          <span class="eyebrow">Our Story</span>
          <h2 class="section-heading"><?= e($ap['story_heading'] ?? 'From A Small Pune Studio To A Multi-Service IT Partner') ?></h2>
          <p><?= e($ap['story_text'] ?? '') ?></p>

          <?php if (!empty($ap['story_quote'])): ?>
            <div class="story-pull-quote">
              <p>"<?= e($ap['story_quote']) ?>"</p>
            </div>
          <?php endif; ?>

          <p><?= e($ap['story_text2'] ?? '') ?></p>
          <a href="contact.php" class="btn btn-primary mt-4">Work With Us</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- MILESTONE TIMELINE -->
<?php if ($milestones): ?>
<section class="milestones-section">
  <div class="container">
    <div class="section-title reveal">
      <span class="eyebrow">Our Journey</span>
      <h2>Key Milestones Since <?= e($ap['founded_year'] ?? '2020') ?></h2>
    </div>
    <div class="milestones-grid reveal">
      <?php foreach ($milestones as $m): ?>
        <div class="milestone-item">
          <div class="milestone-year-badge"><?= e($m['year']) ?></div>
          <h4><?= e($m['title']) ?></h4>
          <p><?= e($m['description']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- MISSION & VISION -->
<section class="mission-vision-section">
  <div class="container">
    <div class="section-title reveal">
      <span class="eyebrow">What We Stand For</span>
      <h2>Mission, Vision, And How We Work</h2>
    </div>

    <!-- Mission -->
    <div class="mission-block reveal" style="margin-bottom: 16px;">
      <div class="mission-visual-side">
        <div class="mission-icon-bg"><i class="bi bi-bullseye"></i></div>
        <h3>Our Mission</h3>
        <p><?= e($ap['mission_tagline'] ?? '') ?></p>
      </div>
      <div class="mission-text-side">
        <h4>What We Aim To Do</h4>
        <p><?= e($content['mission'] ?? '') ?></p>
        <div class="check-list mt-4">
          <span><i class="bi bi-check2-circle"></i> Deliver technology that works first try</span>
          <span><i class="bi bi-check2-circle"></i> Communicate with full transparency — no black boxes</span>
          <span><i class="bi bi-check2-circle"></i> Support clients long after the project goes live</span>
        </div>
      </div>
    </div>

    <!-- Vision -->
    <div class="mission-block reveal">
      <div class="mission-text-side">
        <h4>Where We're Headed</h4>
        <p><?= e($content['vision'] ?? '') ?></p>
        <div class="check-list mt-4">
          <span><i class="bi bi-check2-circle"></i> Building deeper engineering and marketing capabilities every year</span>
          <span><i class="bi bi-check2-circle"></i> Expanding to serve international markets with India-quality delivery</span>
          <span><i class="bi bi-check2-circle"></i> Training the next generation of IT professionals in Pune</span>
        </div>
      </div>
      <div class="mission-visual-side" style="background: linear-gradient(135deg, #1C3A6E 0%, #0A2540 100%);">
        <div class="mission-icon-bg"><i class="bi bi-compass"></i></div>
        <h3>Our Vision</h3>
        <p><?= e($ap['vision_tagline'] ?? '') ?></p>
      </div>
    </div>

  </div>
</section>

<!-- VALUES -->
<?php if ($values): ?>
<section class="values-section-v2">
  <div class="container">
    <div class="section-title reveal">
      <span class="eyebrow">Our Values</span>
      <h2>Four Principles That Guide Every Project</h2>
      <p>These aren't aspirational statements on a wall. They're the standards we hold each engagement to — regardless of project size.</p>
    </div>
    <div class="value-tiles-v2 reveal">
      <?php foreach ($values as $v): ?>
        <div class="value-tile-v2">
          <div class="vt-icon"><i class="bi <?= e($v['icon']) ?>"></i></div>
          <h3><?= e($v['title']) ?></h3>
          <p><?= e($v['description']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CULTURE SECTION -->
<section class="culture-section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 reveal">
        <div class="culture-mosaic">
          <div class="culture-photo main">
            <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=900&q=80" alt="Websoftera team collaboration">
          </div>
          <div class="culture-photo">
            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=600&q=80" alt="Team planning">
          </div>
          <div class="culture-photo">
            <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=600&q=80" alt="Team meeting">
          </div>
        </div>
      </div>
      <div class="col-lg-6 reveal delay-1">
        <div class="culture-copy">
          <span class="eyebrow">Our Culture</span>
          <h2><?= e($ap['culture_heading'] ?? 'A Team That Takes Quality Personally') ?></h2>
          <p><?= e($ap['culture_text'] ?? '') ?></p>
          <p><?= e($ap['culture_text2'] ?? '') ?></p>
          <?php if ($cultureFacts): ?>
            <div class="culture-facts">
              <?php foreach ($cultureFacts as $fact): ?>
                <div class="culture-fact"><i class="bi <?= e($fact['icon']) ?>"></i> <?= e($fact['text']) ?></div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA BAND -->
<section class="cta-band">
  <div class="container d-lg-flex align-items-center justify-content-between gap-4">
    <div>
      <span class="eyebrow">Let's Build Together</span>
      <h2>Ready To Start A Conversation?</h2>
      <p>Tell us what you're trying to build, fix, or grow. We'll tell you how we can help — and what it'll take to get there.</p>
    </div>
    <a href="contact.php" class="btn btn-primary btn-lg flex-shrink-0 mt-3 mt-lg-0">
      <i class="bi bi-calendar2-check"></i> Book Free Consultation
    </a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
