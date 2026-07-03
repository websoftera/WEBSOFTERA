</main>

<footer class="footer">
  <div class="container">
    <div class="footer-shell">

      <!-- Brand & tagline -->
      <div class="footer-brand-panel">
        <a class="footer-brand" href="<?= asset('index.php') ?>">
          <img class="footer-logo" src="<?= asset('assets/img/websoftera-logo.png') ?>" alt="Websoftera logo">
        </a>
        <p>Classy websites, scalable software, and growth-focused digital marketing — built for businesses that take their brand seriously.</p>
        <div class="socials mt-4">
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

      <!-- Links -->
      <div class="footer-link-panel">
        <div>
          <h3>Company</h3>
          <a href="<?= asset('about.php') ?>">About Us</a>
          <a href="<?= asset('services.php') ?>">Services</a>
          <a href="<?= asset('career.php') ?>">Career</a>
          <a href="<?= asset('contact.php') ?>">Contact</a>
        </div>
        <div>
          <h3>Solutions</h3>
          <a href="<?= asset('services.php') ?>">Web Development</a>
          <a href="<?= asset('services.php') ?>">Mobile Apps</a>
          <a href="<?= asset('services.php') ?>">ERP Solutions</a>
          <a href="<?= asset('services.php') ?>">Digital Marketing</a>
          <a href="<?= asset('services.php') ?>">IT Training</a>
        </div>
        <div class="footer-contact-block">
          <h3>Get In Touch</h3>
          <p><i class="bi bi-geo-alt"></i><span><?= e($settings['address']) ?></span></p>
          <p><i class="bi bi-envelope"></i><span><a href="mailto:<?= e($settings['email']) ?>"><?= e($settings['email']) ?></a></span></p>
          <p><i class="bi bi-telephone"></i><span><?= e($settings['phone']) ?></span></p>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      <span>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. All rights reserved.</span>
      <span style="color: var(--text-muted); font-size: 0.8rem;">Designed &amp; Built by Websoftera</span>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('assets/js/main.js') ?>?v=<?= filemtime(__DIR__ . '/../assets/js/main.js') ?>"></script>
</body>
</html>
