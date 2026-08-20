<?php
require_once __DIR__ . '/includes/functions.php';
$currentPage = 'home';
$meta = page_meta('home');
$content = read_json('content.json');
$companyStats = $content['company_stats'] ?? [];
$services = read_json('services.json');
$clients = read_json('clients.json');

$clientGroups = [];
$allClients   = [];
foreach ($clients as $client) {
    $category = trim($client['category'] ?? '');
    $name     = trim($client['name'] ?? '');
    if ($category === '' || $name === '') continue;
    $clientCard = [
        'category'      => $category,
        'name'          => $name,
        'logo'          => trim($client['logo'] ?? ''),
        'website_url'   => external_url($client['website_url'] ?? ''),
        'website_image' => trim($client['website_image'] ?? '')
    ];
    $allClients[] = $clientCard;
    $clientGroups[$category][] = $clientCard;
}
$clientShowcaseGroups = $allClients === []
    ? $clientGroups
    : array_merge(['All Clients' => $allClients], $clientGroups);

$heroImages = array_values(array_filter(read_json('hero-images.json'), static function ($item) {
    return trim($item['image'] ?? '') !== '';
}));
if ($heroImages === []) {
    $heroImages = [['title' => 'Websoftera', 'image' => 'hero-work-preview.png', 'alt' => 'Websoftera project preview']];
}

include __DIR__ . '/includes/header.php';
?>

<!-- ============================================================
     HERO
     ============================================================ -->
<section class="hero">
  <div class="hero-shine"></div>
  <div class="container">
    <div class="row align-items-center g-5">

      <!-- Left Column -->
      <div class="col-lg-6 reveal">
        <span class="eyebrow">IT Strategy · Software · Growth Marketing</span>
        <h1 class="hero-title">
          <span class="hero-title-static">Your Business Solution for</span>
          <span class="hero-title-dynamic" aria-label="Digital Growth, Custom Software, Smart Web Apps, SEO Ready Sites">
            <span class="hero-title-word">Digital Growth</span>
            <span class="hero-title-word">Custom Software</span>
            <span class="hero-title-word">Smart Web Apps</span>
            <span class="hero-title-word">SEO Ready Sites</span>
          </span>
        </h1>
        <p class="lead"><?= e($content['hero_subtitle']) ?></p>
        <div class="hero-actions">
          <a href="contact.php" class="btn btn-primary btn-lg"><i class="bi bi-lightning-charge"></i> Start a Project</a>
          <a href="services.php" class="btn btn-outline-light btn-lg"><i class="bi bi-layers"></i> Explore Expertise</a>
        </div>
        
      </div>

      <!-- Right Column — Orbit Animation -->
      <div class="col-lg-6 reveal delay-1">
        <div class="motion-stage" aria-label="Animated Websoftera technology expertise graphic">
          <div class="orbit orbit-one"><span><i class="bi bi-code-slash"></i></span></div>
          <div class="orbit orbit-two"><span><i class="bi bi-phone"></i></span></div>
          <div class="orbit orbit-three"><span><i class="bi bi-megaphone"></i></span></div>
          <div class="orbit orbit-four"><span><i class="bi bi-braces"></i></span></div>
          <div class="orbit orbit-five"><span><i class="bi bi-terminal"></i></span></div>
          <div class="orbit orbit-six"><span><i class="bi bi-cpu"></i></span></div>
          <div id="heroImageSlider" class="carousel slide hero-image-slider" data-bs-ride="carousel" data-bs-interval="3200">
            <div class="carousel-inner">
              <?php foreach ($heroImages as $i => $slide):
                $src = preg_match('#^https?://#i', $slide['image'])
                    ? $slide['image']
                    : asset('assets/img/hero/' . $slide['image']);
              ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                  <img src="<?= e($src) ?>" alt="<?= e($slide['alt'] ?? $slide['title'] ?? 'Websoftera project') ?>">
                  <?php if (trim($slide['title'] ?? '') !== ''): ?>
                    <span class="hero-slide-caption"><?= e($slide['title']) ?></span>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
            <?php if (count($heroImages) > 1): ?>
              <div class="hero-slider-dots">
                <?php foreach ($heroImages as $i => $slide): ?>
                  <button type="button" data-bs-target="#heroImageSlider" data-bs-slide-to="<?= $i ?>"
                    class="<?= $i === 0 ? 'active' : '' ?>" aria-label="Show hero image <?= $i + 1 ?>"></button>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ============================================================
     SERVICES TICKER STRIP
     ============================================================ -->
<section class="metric-strip">
  <div class="metric-track" aria-hidden="true">
    <?php foreach ($services as $service): ?>
      <span><i class="bi <?= e($service['icon']) ?>"></i><?= e($service['title']) ?></span>
    <?php endforeach; ?>
  </div>
</section>

<!-- ============================================================
     ABOUT (HOME)
     ============================================================ -->
<section class="section home-about">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 reveal">
        <span class="eyebrow">About Websoftera</span>
        <h2 class="section-heading">We Design Technology That Makes Businesses Look Sharper And Work Smarter</h2>
        <p><?= e($content['about_intro']) ?></p>
        <div class="about-proof">
          <div><strong>01</strong><span>Brand-first UI strategy</span></div>
          <div><strong>02</strong><span>Fast, scalable development</span></div>
          <div><strong>03</strong><span>SEO and ads-ready pages</span></div>
          <div><strong>04</strong><span>Long-term support included</span></div>
        </div>
        <a href="about.php" class="btn btn-primary mt-4">Explore Company</a>
      </div>
      <div class="col-lg-6 reveal delay-1">
        <div class="about-visual">
          <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=1000&q=82" alt="Professional IT team building software">
          <div class="about-orbit one"><i class="bi bi-code-square"></i></div>
          <div class="about-orbit two"><i class="bi bi-bar-chart-line"></i></div>
          <div class="about-orbit three"><i class="bi bi-phone"></i></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     COUNTERS
     ============================================================ -->
<section class="stats-ribbon-sec home-stats-ribbon">
  <div class="container">
    <div class="stats-ribbon reveal">
      <div class="stats-ribbon-grid">
        <div class="stats-ribbon-item">
          <div class="counter-icon-wrap"><i class="bi bi-briefcase"></i></div>
          <div class="counter-number-wrap">
            <span class="counter-number" data-target="<?= (int) preg_replace('/[^0-9]/', '', $companyStats['projects'] ?? '150') ?>">0</span><span class="counter-suffix">+</span>
          </div>
          <span>Projects Completed</span>
        </div>
        <div class="stats-ribbon-item">
          <div class="counter-icon-wrap"><i class="bi bi-people"></i></div>
          <div class="counter-number-wrap">
            <span class="counter-number" data-target="<?= (int) preg_replace('/[^0-9]/', '', $companyStats['clients'] ?? '80') ?>">0</span><span class="counter-suffix">+</span>
          </div>
          <span>Clients Served</span>
        </div>
        <div class="stats-ribbon-item">
          <div class="counter-icon-wrap"><i class="bi bi-emoji-smile"></i></div>
          <div class="counter-number-wrap">
            <span class="counter-number" data-target="<?= (int) preg_replace('/[^0-9]/', '', $companyStats['satisfaction'] ?? '97') ?>">0</span><span class="counter-suffix">%</span>
          </div>
          <span>Client Satisfaction</span>
        </div>
        <div class="stats-ribbon-item">
          <div class="counter-icon-wrap"><i class="bi bi-headset"></i></div>
          <div class="counter-number-wrap">
            <span class="counter-number" data-target="<?= (int) explode('/', $companyStats['support'] ?? '24')[0] ?>">0</span><span class="counter-suffix">/7</span>
          </div>
          <span>Technical Support</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     DIRECTOR'S MESSAGE
     ============================================================ -->
<section class="section director-message-section">
  <div class="director-message-glow"></div>
  <div class="container">
    <div class="director-message-card reveal">
      <div class="director-message-content">
        <span class="eyebrow">A Message From Our Directors</span>
        <i class="bi bi-quote director-quote-mark" aria-hidden="true"></i>
        <h2>Two Leaders.<br>One Vision.</h2>
        <p>We believe technology is more than code — it is about understanding people, solving real problems, and creating lasting value.</p>
        <h3 class="director-principles-title">Our Principles</h3>
        <div class="director-principles" aria-label="Our leadership principles">
          <span><i class="bi bi-check2-circle"></i> Client-first thinking</span>
          <span><i class="bi bi-check2-circle"></i> Quality without compromise</span>
          <span><i class="bi bi-check2-circle"></i> Innovation with purpose</span>
          <span><i class="bi bi-check2-circle"></i> Partnerships built to last</span>
        </div>
      </div>

      <div class="director-profiles-grid">
        <article class="director-profile-card">
          <div class="director-photo-wrap">
            <img src="<?= asset('assets/img/directors/akash-raje.webp') ?>" alt="Akash Raje, Director at Websoftera">
            <span class="director-photo-label"><i class="bi bi-patch-check-fill"></i> Director</span>
          </div>
          <div class="director-profile-copy">
            <div class="director-identity"><strong>Akash Raje</strong><span>Director, Websoftera</span></div>
            <blockquote>“Technology should solve real problems, create real value, & help businesses move forward. At Websoftera, we build with purpose, quality, & a long-term vision.”</blockquote>
          </div>
        </article>
        <article class="director-profile-card">
          <div class="director-photo-wrap">
            <img src="<?= asset('assets/img/directors/priyanka-akshay-raje.webp') ?>" alt="Mrs. Priyanka Akshay Raje, Director at Websoftera">
            <span class="director-photo-label"><i class="bi bi-patch-check-fill"></i> Director</span>
          </div>
          <div class="director-profile-copy">
            <div class="director-identity"><strong>Mrs. Priyanka Akshay Raje</strong><span>Director, Websoftera</span></div>
            <blockquote>“Great businesses are built on trust, people, and consistency. We strive to create meaningful digital solutions and lasting relationships with every client.”</blockquote>
          </div>
        </article>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     SERVICES SHOWCASE
     ============================================================ -->
<section class="section services-showcase">
  <div class="container">
    <div class="section-title reveal">
      <span class="eyebrow">What We Build</span>
      <h2>Technology Services Designed Like Premium Products</h2>
      <p>Every solution is planned around brand trust, performance, conversion, and long-term maintainability. Clean visuals in front, disciplined engineering behind.</p>
    </div>
    <div class="row g-4">
      <?php foreach (array_slice($services, 0, 6) as $index => $service): ?>
        <div class="col-md-6 col-lg-4 reveal delay-<?= $index % 3 ?>">
          <article class="service-card service-premium">
            <span class="service-number"><?= str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
            <div class="icon-box"><i class="bi <?= e($service['icon']) ?>"></i></div>
            <h3><?= e($service['title']) ?></h3>
            <p><?= e($service['description']) ?></p>
            <div class="service-tags">
              <?php foreach (array_slice($service['features'] ?? [], 0, 3) as $feature): ?>
                <span><?= e($feature) ?></span>
              <?php endforeach; ?>
            </div>
            <a class="service-link" href="services.php">Explore service <i class="bi bi-arrow-right"></i></a>
          </article>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================================
     GALLERY
     ============================================================ -->
<section class="section gallery-section">
  <div class="container">
    <div class="section-title reveal">
      <span class="eyebrow">Digital Work Gallery</span>
      <h2>Interfaces, Campaigns, Dashboards, And Systems With A Premium Finish</h2>
      <p>A glimpse of the digital environments we craft for websites, software products, business automation, and marketing campaigns.</p>
    </div>
    <div class="gallery-grid">
      <article class="gallery-item large reveal">
        <img src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=1200&q=82" alt="Website UI design preview">
        <div><span>Website Design</span><strong>Conversion-led landing pages</strong></div>
      </article>
      <article class="gallery-item reveal delay-1">
        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=900&q=82" alt="Business dashboard analytics">
        <div><span>Dashboards</span><strong>Data-rich admin panels</strong></div>
      </article>
      <article class="gallery-item reveal delay-2">
        <img src="https://images.unsplash.com/photo-1551650975-87deedd944c3?auto=format&fit=crop&w=900&q=82" alt="Mobile application interface">
        <div><span>Mobile Apps</span><strong>Elegant app experiences</strong></div>
      </article>
      <article class="gallery-item wide reveal">
        <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=1200&q=82" alt="Digital marketing planning session">
        <div><span>Growth Marketing</span><strong>Campaign-ready digital journeys</strong></div>
      </article>
    </div>
  </div>
</section>

<!-- ============================================================
     WHY WEBSOFTERA
     ============================================================ -->
<section class="section soft-band">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 reveal">
        <div class="media-stack">
          <img class="rounded-media" src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=900&q=80" alt="IT consulting discussion">
          <div class="media-badge"><i class="bi bi-stars"></i> Premium design, practical engineering</div>
        </div>
      </div>
      <div class="col-lg-6 reveal delay-1">
        <span class="eyebrow">Why Websoftera</span>
        <h2 class="section-heading">A Refined Technology Partner For Growth-Minded Teams</h2>
        <p><?= e($content['about_intro']) ?></p>
        <div class="check-list">
          <span><i class="bi bi-check2-circle"></i> Elegant, original UI aligned with your brand identity</span>
          <span><i class="bi bi-check2-circle"></i> Clean code structure for fast pages and easy maintenance</span>
          <span><i class="bi bi-check2-circle"></i> SEO and Google Ads-ready messaging on every key page</span>
          <span><i class="bi bi-check2-circle"></i> Long-term support and improvements after launch</span>
        </div>
        <a href="about.php" class="btn btn-primary mt-4">Know More</a>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     CLIENTS
     ============================================================ -->
<section class="section client-section">
  <div class="container">
    <div class="section-title reveal">
      <span class="eyebrow">Our Clients</span>
      <h2>Trusted By Businesses Building Their Digital Presence</h2>
      <p>We support organizations across services, education, import-export, sports, and growing local brands with reliable digital execution.</p>
    </div>

    <div class="client-tabs-showcase reveal">
      <style>
        <?php $si = 0; foreach ($clientShowcaseGroups as $cat => $cls): ?>
          .client-tabs-showcase:has(#client-tab-<?= $si ?>:checked) label[for="client-tab-<?= $si ?>"] {
            background: linear-gradient(135deg, #F05A24 0%, #FFAA3A 100%) !important;
            color: #ffffff !important; border-color: transparent !important;
            box-shadow: 0 4px 20px rgba(240,90,36,0.38) !important;
          }
          .client-tabs-showcase:has(#client-tab-<?= $si ?>:checked) #client-grid-<?= $si ?> { display: grid !important; }
        <?php $si++; endforeach; ?>
      </style>

      <?php $ci = 0; foreach ($clientShowcaseGroups as $cat => $cls): ?>
        <input class="client-tab-radio" type="radio" name="client-categories" id="client-tab-<?= $ci ?>" <?= $ci === 0 ? 'checked' : '' ?> style="display:none;">
      <?php $ci++; endforeach; ?>

      <div class="client-tabs-bar" role="tablist" aria-label="Client categories">
        <?php $ci = 0; foreach ($clientShowcaseGroups as $cat => $logos): ?>
          <label class="client-tab-label" for="client-tab-<?= $ci ?>" role="tab"><?= e($cat) ?></label>
        <?php $ci++; endforeach; ?>
      </div>

      <div class="client-panels">
        <?php $ci = 0; foreach ($clientShowcaseGroups as $cat => $clientsInCat):
          $chunks = array_chunk($clientsInCat, 3);
        ?>
          <div class="client-grid-panel" id="client-grid-<?= $ci ?>" style="display:none;">
            <div id="clientSlider-<?= $ci ?>" class="carousel slide client-three-slider" data-bs-ride="carousel">
              <div class="carousel-inner">
                <?php foreach ($chunks as $si => $triple): ?>
                  <div class="carousel-item <?= $si === 0 ? 'active' : '' ?>">
                    <div class="row g-4 justify-content-center px-lg-4">
                      <?php foreach ($triple as $client):
                        $siteUrl = $client['website_url'] ?? '';
                        $webImg  = $client['website_image'] ?? '';
                      ?>
                        <div class="col-lg-4 col-md-6 d-flex">
                          <article class="client-card w-100 p-3 d-flex flex-column">
                            <div class="client-img-wrap mb-3 position-relative">
                              <?php if (!empty($webImg)): ?>
                                <img class="client-preview-img" src="<?= e(strpos($webImg,'http')===0 ? $webImg : asset('assets/img/clients/'.$webImg)) ?>" alt="<?= e($client['name']) ?> website" loading="lazy">
                              <?php elseif (!empty($siteUrl)): ?>
                                <img class="client-preview-img" src="<?= e(website_preview_image($siteUrl, 1024)) ?>" alt="<?= e($client['name']) ?> website" loading="lazy">
                              <?php else: ?>
                                <div class="d-flex flex-column align-items-center justify-content-center w-100 h-100" style="background:var(--bg-highlight);aspect-ratio:16/10;">
                                  <?php if (!empty($client['logo'])): ?>
                                    <img src="<?= e(strpos($client['logo'],'http')===0 ? $client['logo'] : asset('assets/img/clients/'.$client['logo'])) ?>" alt="<?= e($client['name']) ?>" style="max-height:50px;object-fit:contain;">
                                  <?php endif; ?>
                                  <span class="badge-soft mt-2"><i class="bi bi-patch-check"></i> Project Done</span>
                                </div>
                              <?php endif; ?>
                              <?php if (!empty($siteUrl)): ?>
                                <a class="client-card-overlay position-absolute w-100 h-100 top-0 start-0 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0"
                                   href="<?= e($siteUrl) ?>" target="_blank" rel="noopener"
                                   style="border-radius:var(--radius);transition:opacity .3s ease;z-index:2;"
                                   aria-label="Visit <?= e($client['name']) ?>">
                                  <span class="btn btn-sm btn-light fw-bold"><i class="bi bi-box-arrow-up-right me-1"></i>Visit Site</span>
                                </a>
                              <?php endif; ?>
                            </div>
                            <div class="client-card-meta mt-auto">
                              <?php if (!empty($client['logo'])): ?>
                                <span class="client-logo-badge">
                                  <img src="<?= e(strpos($client['logo'],'http')===0 ? $client['logo'] : asset('assets/img/clients/'.$client['logo'])) ?>" alt="<?= e($client['name']) ?> logo">
                                </span>
                              <?php endif; ?>
                              <h4 class="client-card-title fw-bold mb-0"><?= e($client['name']) ?></h4>
                            </div>
                          </article>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
              <?php if (count($chunks) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#clientSlider-<?= $ci ?>" data-bs-slide="prev"><span class="bi bi-arrow-left"></span></button>
                <button class="carousel-control-next" type="button" data-bs-target="#clientSlider-<?= $ci ?>" data-bs-slide="next"><span class="bi bi-arrow-right"></span></button>
              <?php endif; ?>
            </div>
          </div>
        <?php $ci++; endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     TESTIMONIALS
     ============================================================ -->
<section class="section testimonial-section">
  <div class="container">
    <div class="section-title reveal">
      <span class="eyebrow">Trusted By Clients</span>
      <h2>Results That Clients Can Feel</h2>
    </div>
    <?php
    $testimonials = read_json('testimonials.json');
    $chunked = array_chunk($testimonials, 2);
    ?>
    <div id="testimonialSlider" class="carousel slide reveal" data-bs-ride="carousel" style="padding: 0 30px;">
      <div class="carousel-inner">
        <?php foreach ($chunked as $idx => $pair): ?>
          <div class="carousel-item <?= $idx === 0 ? 'active' : '' ?>">
            <div class="row g-4 justify-content-center">
              <?php foreach ($pair as $item): ?>
                <div class="col-md-6 d-flex">
                  <div class="testimonial-slide w-100">
                    <i class="bi bi-quote"></i>
                    <p><?= e($item['quote']) ?></p>
                    <strong><?= e($item['name']) ?></strong>
                    <span><?= e($item['role']) ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#testimonialSlider" data-bs-slide="prev"><span class="bi bi-arrow-left"></span></button>
      <button class="carousel-control-next" type="button" data-bs-target="#testimonialSlider" data-bs-slide="next"><span class="bi bi-arrow-right"></span></button>
    </div>
  </div>
</section>

<!-- ============================================================
     TEAM RECOGNITION
     ============================================================ -->
<section class="section people-progress-section" id="people-progress">
  <div class="container">
    <div class="recognition-filter reveal" role="group" aria-label="Filter team highlights">
      <button class="recognition-filter-btn active" type="button" data-recognition-filter="all" aria-pressed="true">All</button>
      <button class="recognition-filter-btn" type="button" data-recognition-filter="onboarding" aria-pressed="false">New Onboarding</button>
      <button class="recognition-filter-btn" type="button" data-recognition-filter="intern" aria-pressed="false">Intern Of The Month</button>
    </div>

    <div class="recognition-viewer reveal">
      <div class="recognition-thumbnails" role="tablist" aria-label="Team recognition images">
        <button class="recognition-thumbnail active" type="button" role="tab" aria-selected="true" data-recognition-category="onboarding" data-recognition-src="assets/img/team-highlights/team-onboarding.webp" data-recognition-alt="Websoftera team recognition">
          <img src="assets/img/team-highlights/team-onboarding.webp" alt="" loading="lazy">
        </button>
        <button class="recognition-thumbnail" type="button" role="tab" aria-selected="false" data-recognition-category="intern" data-recognition-src="assets/img/team-highlights/parth-inamdar.webp" data-recognition-alt="Parth Inamdar, Best Intern of the Month">
          <img src="assets/img/team-highlights/parth-inamdar.webp" alt="" loading="lazy">
        </button>
        <button class="recognition-thumbnail" type="button" role="tab" aria-selected="false" data-recognition-category="intern" data-recognition-src="assets/img/team-highlights/intern-highlight.webp" data-recognition-alt="Websoftera Intern of the Month">
          <img src="assets/img/team-highlights/intern-highlight.webp" alt="" loading="lazy">
        </button>
        <button class="recognition-thumbnail" type="button" role="tab" aria-selected="false" data-recognition-category="intern" data-recognition-src="assets/img/team-highlights/darshna-jagatiya.webp" data-recognition-alt="Darshna Jagatiya, Star Intern of the Month">
          <img src="assets/img/team-highlights/darshna-jagatiya.webp" alt="" loading="lazy">
        </button>
      </div>
      <figure class="recognition-stage">
        <span class="recognition-stage-mark" aria-hidden="true"><i class="bi bi-stars"></i></span>
        <img id="recognitionStageImage" src="assets/img/team-highlights/team-onboarding.webp" alt="Websoftera team recognition">
      </figure>
    </div>
  </div>
</section>

<!-- ============================================================
     CTA BAND
     ============================================================ -->
<section class="cta-band">
  <div class="container d-lg-flex align-items-center justify-content-between gap-4">
    <div>
      <span class="eyebrow">Request Free Consultation</span>
      <h2>Ready To Start A Project With Us?</h2>
      <p>Transform your vision into reality. Our team will help you choose a practical, impactful, and scalable next step.</p>
    </div>
    <a href="contact.php" class="btn btn-primary btn-lg flex-shrink-0 mt-3 mt-lg-0"><i class="bi bi-calendar2-check"></i> Book Consultation</a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
