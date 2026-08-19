<?php
require_once __DIR__ . '/includes/functions.php';
$currentPage = 'contact';
$meta = page_meta('contact');
$settings = read_json('settings.json');
$content  = read_json('content.json');
$cp       = $content['contact_page'] ?? [];
$faqs     = read_json('faqs.json');
$trust    = read_json('trust.json');
$success  = false;
$error    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lead = [
        'name'       => trim($_POST['name']    ?? ''),
        'email'      => trim($_POST['email']   ?? ''),
        'phone'      => trim($_POST['phone']   ?? ''),
        'service'    => trim($_POST['service'] ?? ''),
        'message'    => trim($_POST['message'] ?? ''),
        'created_at' => date('Y-m-d H:i:s'),
    ];

    if ($lead['name'] === '' || $lead['message'] === '') {
        $error = 'Please enter your name and project requirements.';
    } elseif (!filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!valid_indian_mobile($lead['phone'])) {
        $error = 'Please enter a valid 10-digit Indian mobile number.';
    } elseif (append_json('messages.json', $lead)) {
        send_contact_notification($lead);
        header('Location: contact.php?submitted=1');
        exit;
    } else {
        $error = 'We could not save your message. Please try again.';
    }
}
$success = isset($_GET['submitted']) && $_GET['submitted'] === '1';
include __DIR__ . '/includes/header.php';

$waPhone   = preg_replace('/[^0-9]/', '', $settings['phone'] ?? '');
$waMessage = $cp['whatsapp_message'] ?? 'Hi, I have a project inquiry for Websoftera.';
?>

<!-- PAGE HERO -->
<section class="page-hero">
  <div class="container reveal">
    <span class="eyebrow">Let's Talk</span>
    <h1>Request A Free Consultation For Your Next IT Project</h1>
    <p><?= e($cp['intro'] ?? "Share what you're working on. Our team will come back with a practical recommendation, not a sales pitch.") ?></p>
  </div>
</section>

<!-- MAIN CONTACT SECTION -->
<section class="contact-split-section">
  <div class="container">
    <div class="contact-split-grid">

      <!-- FORM PANEL -->
      <div class="reveal">
        <div class="contact-form-card">
          <h2>Send Us A Message</h2>
          <p><?= e($cp['form_subtitle'] ?? "We respond within one business day.") ?></p>

          <?php if ($success): ?>
            <div class="success-alert">
              <i class="bi bi-check-circle-fill"></i>
              <div>
                <strong>Thank you for contacting us!</strong><br>
                Message received. We'll be in touch with you shortly.
              </div>
            </div>
          <?php else: ?>
            <?php if ($error !== ''): ?>
              <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

          <form method="post" action="contact.php" novalidate>
            <div class="form-row-2">
              <div class="float-label">
                <input type="text" name="name" id="f-name" placeholder=" " value="<?= e($_POST['name'] ?? '') ?>" required>
                <label for="f-name">Your Full Name *</label>
              </div>
              <div class="float-label">
                <input type="email" name="email" id="f-email" placeholder=" " value="<?= e($_POST['email'] ?? '') ?>" maxlength="254" required>
                <label for="f-email">Email Address *</label>
              </div>
            </div>
            <div class="form-row-2">
              <div class="float-label">
                <input type="tel" name="phone" id="f-phone" placeholder=" " value="<?= e($_POST['phone'] ?? '') ?>" inputmode="numeric" pattern="(?:\+91[ -]?)?[6-9][0-9]{9}" maxlength="14" title="Enter a valid 10-digit Indian mobile number" required>
                <label for="f-phone">Phone Number *</label>
              </div>
              <div class="float-label">
                <select name="service" id="f-service">
                  <option value="">Select a service</option>
                  <option>Website Development</option>
                  <option>WordPress Website</option>
                  <option>Mobile App Development</option>
                  <option>Digital Marketing</option>
                  <option>ERP / Software Solution</option>
                  <option>IT Training</option>
                  <option>Something else</option>
                </select>
                <label for="f-service">Service Interest</label>
              </div>
            </div>
            <div class="float-label">
              <textarea name="message" id="f-message" placeholder=" " required><?= e($_POST['message'] ?? '') ?></textarea>
              <label for="f-message">Tell us about your project *</label>
            </div>
            <button class="btn btn-primary btn-lg w-100" type="submit">
              <i class="bi bi-send"></i> Send Message
            </button>
            <p style="font-size:.78rem;color:var(--text-muted);margin-top:14px;text-align:center;">
              <i class="bi bi-lock"></i> Your information is kept private and never shared.
            </p>
          </form>
          <?php endif; ?>
        </div>
      </div>

      <!-- INFO PANEL -->
      <div class="contact-info-panel reveal delay-1">
        <div class="contact-info-box">
          <div class="cib-header">
            <h3>Get In Touch Directly</h3>
            <p>Pick the channel that suits you — we're reachable on all of them.</p>
          </div>
          <div class="contact-detail-list">
            <div class="contact-detail-row">
              <div class="cd-icon"><i class="bi bi-geo-alt-fill"></i></div>
              <div class="cd-text">
                <h5>Our Office</h5>
                <p><?= e($settings['address']) ?></p>
              </div>
            </div>
            <div class="contact-detail-row">
              <div class="cd-icon"><i class="bi bi-envelope-fill"></i></div>
              <div class="cd-text">
                <h5>Email Us</h5>
                <p><a href="mailto:<?= e($settings['email']) ?>"><?= e($settings['email']) ?></a></p>
              </div>
            </div>
            <div class="contact-detail-row">
              <div class="cd-icon"><i class="bi bi-telephone-fill"></i></div>
              <div class="cd-text">
                <h5>Call Us</h5>
                <p><a href="tel:<?= e(preg_replace('/[^+\d]/', '', $settings['phone'])) ?>"><?= e($settings['phone']) ?></a></p>
                <?php if (!empty($settings['phone_alt'])): ?>
                  <p><a href="tel:<?= e(preg_replace('/[^+\d]/', '', $settings['phone_alt'])) ?>"><?= e($settings['phone_alt']) ?></a></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="contact-detail-row">
              <div class="cd-icon"><i class="bi bi-clock-fill"></i></div>
              <div class="cd-text">
                <h5>Business Hours</h5>
                <p><?= e($cp['business_hours'] ?? 'Mon – Sat: 9:30 AM – 7:00 PM IST') ?></p>
              </div>
            </div>
          </div>

          <!-- WhatsApp -->
          <a class="whatsapp-cta" href="https://wa.me/<?= e($waPhone) ?>?text=<?= rawurlencode($waMessage) ?>" target="_blank" rel="noopener">
            <i class="bi bi-whatsapp"></i>
            Message Us On WhatsApp — Get A Reply Faster
          </a>
        </div>

        <!-- Response promise -->
        <div class="response-badge">
          <div class="rb-icon"><i class="bi bi-lightning-charge-fill"></i></div>
          <div class="rb-text">
            <strong>Average Response: <?= e($cp['response_time'] ?? 'Under 4 Hours') ?></strong>
            <span><?= e($cp['response_note'] ?? 'Mon–Sat business hours. WhatsApp even faster.') ?></span>
          </div>
        </div>

        <!-- Social links -->
        <div style="margin-top:16px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:22px 24px;">
          <p style="font-size:.82rem;font-weight:600;color:var(--text-muted);margin-bottom:14px;text-transform:uppercase;letter-spacing:.08em;">Find Us Online</p>
          <div class="socials" style="gap:10px;">
            <?php if (!empty($settings['facebook'])): ?>
              <a href="<?= e($settings['facebook']) ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <?php endif; ?>
            <?php if (!empty($settings['instagram'])): ?>
              <a href="<?= e($settings['instagram']) ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <?php endif; ?>
            <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
            <a href="#" aria-label="Twitter / X"><i class="bi bi-twitter-x"></i></a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- TRUST SIGNALS -->
<?php if ($trust): ?>
<section class="trust-section">
  <div class="container">
    <div class="trust-grid reveal">
      <?php foreach ($trust as $t): ?>
        <div class="trust-card">
          <div class="tc-icon"><i class="bi <?= e($t['icon']) ?>"></i></div>
          <div class="tc-text">
            <strong><?= e($t['title']) ?></strong>
            <span><?= e($t['description']) ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- FAQ SECTION -->
<?php if ($faqs): ?>
<section class="faq-section">
  <div class="container">
    <div class="section-title reveal">
      <span class="eyebrow">Frequently Asked</span>
      <h2>Common Questions Before Getting Started</h2>
    </div>
    <div class="faq-wrap reveal">
      <?php foreach ($faqs as $fi => $faq): ?>
        <div class="faq-item">
          <input type="checkbox" class="faq-toggle" id="faq-<?= $fi ?>">
          <label class="faq-question" for="faq-<?= $fi ?>">
            <?= e($faq['question']) ?>
            <i class="bi bi-chevron-down faq-arrow"></i>
          </label>
          <div class="faq-body">
            <div class="faq-body-inner"><?= e($faq['answer']) ?></div>
          </div>
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
      <span class="eyebrow">Start The Conversation</span>
      <h2>Your Next Digital Step Starts Here</h2>
      <p>Send us a message or call us today. First consultation is always free and commitment-free.</p>
    </div>
    <a href="tel:<?= e(preg_replace('/[^+\d]/', '', $settings['phone'])) ?>" class="btn btn-primary btn-lg flex-shrink-0 mt-3 mt-lg-0">
      <i class="bi bi-telephone-fill"></i> Call Us Now
    </a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
