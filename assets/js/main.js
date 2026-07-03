/* ============================================================
   WEBSOFTERA — Main JS v2
   ============================================================ */

'use strict';

// ---- Scroll Reveal ----
const revealItems = document.querySelectorAll('.reveal');
if ('IntersectionObserver' in window) {
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); }
    });
  }, { threshold: 0.08 });
  revealItems.forEach(el => io.observe(el));
} else {
  revealItems.forEach(el => el.classList.add('visible'));
}

// ---- Header Scroll State (collapses topbar, darkens header) ----
const headerWrap = document.getElementById('headerWrap');
const onScroll = () => headerWrap?.classList.toggle('is-scrolled', window.scrollY > 30);
onScroll();
window.addEventListener('scroll', onScroll, { passive: true });

// ---- Theme Toggle (dark / light) ----
const themeToggle = document.getElementById('themeToggle');
if (themeToggle) {
  themeToggle.addEventListener('click', () => {
    const current = document.documentElement.getAttribute('data-theme') === 'light' ? 'light' : 'dark';
    const next = current === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', next);
    try { localStorage.setItem('websoftera-theme', next); } catch (e) { /* storage unavailable, ignore */ }
    themeToggle.setAttribute('aria-label', next === 'light' ? 'Switch to dark theme' : 'Switch to light theme');
  });
}

// ---- Mobile Nav Toggle ----
const toggler = document.querySelector('.navbar-toggler');
const navPanel = document.querySelector('.main-nav-panel');
if (toggler && navPanel) {
  toggler.addEventListener('click', () => {
    const expanded = toggler.getAttribute('aria-expanded') === 'true';
    toggler.setAttribute('aria-expanded', String(!expanded));
    navPanel.classList.toggle('show');
  });
  navPanel.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', () => {
      navPanel.classList.remove('show');
      toggler.setAttribute('aria-expanded', 'false');
    });
  });
}

// ---- Marquee Duplicate (seamless loop) ----
const track = document.querySelector('.metric-track');
if (track) {
  const clone = track.innerHTML;
  track.innerHTML = clone + clone;
}

// ---- Counter Animation (homepage + ribbon counters) ----
function animateCounter(el) {
  const target  = parseInt(el.getAttribute('data-target') || el.textContent, 10) || 0;
  const duration = 2000;
  const start    = performance.now();
  const animate  = (now) => {
    const elapsed  = now - start;
    const progress = Math.min(elapsed / duration, 1);
    const ease     = 1 - Math.pow(1 - progress, 3);
    el.textContent = Math.floor(ease * target);
    if (progress < 1) { requestAnimationFrame(animate); }
    else { el.textContent = target; el.closest('.counter-card')?.classList.add('is-counted'); }
  };
  requestAnimationFrame(animate);
}

if ('IntersectionObserver' in window) {
  const countIO = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      animateCounter(entry.target);
      countIO.unobserve(entry.target);
    });
  }, { threshold: 0.15 });
  document.querySelectorAll('.counter-number').forEach(c => countIO.observe(c));
}

// ---- Hero Carousel Dots Sync ----
const heroCarousel = document.getElementById('heroImageSlider');
if (heroCarousel) {
  heroCarousel.addEventListener('slide.bs.carousel', e => {
    heroCarousel.querySelectorAll('.hero-slider-dots button').forEach((dot, i) => {
      dot.classList.toggle('active', i === e.to);
    });
  });
}

// ---- Client Card Hover Overlay ----
document.querySelectorAll('.client-browser-frame').forEach(frame => {
  const img = frame.querySelector('.client-live-frame-img');
  if (img) {
    const setLoaded = () => frame.classList.add('is-loaded');
    img.addEventListener('load', setLoaded);
    img.addEventListener('error', () => frame.classList.remove('is-loaded'));
    if (img.complete) setLoaded();
  } else {
    frame.classList.add('is-loaded');
  }
});

// ---- Service Card 3D Tilt ----
document.querySelectorAll('.service-card').forEach(card => {
  card.addEventListener('mousemove', e => {
    const rect = card.getBoundingClientRect();
    const x = e.clientX - rect.left, y = e.clientY - rect.top;
    const cx = rect.width / 2, cy = rect.height / 2;
    const rotX = ((y - cy) / cy) * 4, rotY = ((x - cx) / cx) * -4;
    card.style.transform = `translateY(-8px) rotateX(${rotX}deg) rotateY(${rotY}deg)`;
    card.style.transition = 'transform 0.1s ease';
  });
  card.addEventListener('mouseleave', () => {
    card.style.transform = '';
    card.style.transition = 'all 0.55s cubic-bezier(0.4,0,0.2,1)';
  });
});

// ---- Counter Pulse on Hover ----
document.querySelectorAll('.counter-card').forEach(card => {
  card.addEventListener('mouseenter', () => {
    const num = card.querySelector('.counter-number');
    if (num) { num.style.transform = 'scale(1.08)'; setTimeout(() => { num.style.transform = ''; }, 200); }
  });
});

// ---- Service Sticky Nav: Scrollspy Active State ----
const serviceNavLinks = document.querySelectorAll('.service-nav-link');
const serviceSections  = document.querySelectorAll('.service-showcase-section[id]');

if (serviceNavLinks.length && serviceSections.length) {
  const navIO = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const id = entry.target.id;
        serviceNavLinks.forEach(link => {
          link.classList.toggle('snl-active', link.getAttribute('href') === '#' + id);
        });
      }
    });
  }, { threshold: 0.35 });
  serviceSections.forEach(s => navIO.observe(s));

  // Smooth scroll for service anchor links
  serviceNavLinks.forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const target = document.querySelector(link.getAttribute('href'));
      if (target) {
        const offset = 120; // header + sticky nav height
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });

  // Also for page-hero-pills (service anchor pills)
  document.querySelectorAll('.page-hero-pill[href^="#"]').forEach(pill => {
    pill.addEventListener('click', e => {
      const target = document.querySelector(pill.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const offset = 120;
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });
}

// ---- Milestone Hover Animation ----
document.querySelectorAll('.milestone-item').forEach(item => {
  item.addEventListener('mouseenter', () => {
    item.querySelector('.milestone-year-badge')?.classList.add('hovered');
  });
  item.addEventListener('mouseleave', () => {
    item.querySelector('.milestone-year-badge')?.classList.remove('hovered');
  });
});

// ---- SVP Bar animation retrigger on scroll ----
const svpBars = document.querySelectorAll('.svp-bar');
if (svpBars.length && 'IntersectionObserver' in window) {
  const barIO = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.querySelectorAll('.svp-bar::after').forEach(b => {
          b.style.animationPlayState = 'running';
        });
      }
    });
  }, { threshold: 0.3 });
  document.querySelectorAll('.svc-visual-wrap').forEach(wrap => barIO.observe(wrap));
}

// ---- Smooth anchor scroll for hero pills and career links ----
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const href = a.getAttribute('href');
    if (href === '#' || href.length < 2) return;
    const target = document.querySelector(href);
    if (target) {
      e.preventDefault();
      const offset = 100;
      window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - offset, behavior: 'smooth' });
    }
  });
});

// ---- Perk cards stagger entrance ----
if ('IntersectionObserver' in window) {
  const perkIO = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const cards = entry.target.querySelectorAll('.perk-card-v2, .value-tile-v2, .trust-card, .tech-item');
        cards.forEach((card, i) => {
          setTimeout(() => card.classList.add('visible'), i * 80);
        });
        perkIO.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });
  document.querySelectorAll('.perks-grid, .value-tiles-v2, .trust-grid, .tech-grid').forEach(g => {
    g.querySelectorAll('.perk-card-v2, .value-tile-v2, .trust-card, .tech-item').forEach(c => {
      c.style.opacity = '0';
      c.style.transform = 'translateY(20px)';
      c.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
    });
    perkIO.observe(g);
  });
}

// Auto-add 'visible' styles on stagger trigger
document.addEventListener('DOMContentLoaded', () => {
  const style = document.createElement('style');
  style.textContent = `.perk-card-v2.visible,.value-tile-v2.visible,.trust-card.visible,.tech-item.visible{opacity:1!important;transform:none!important;}`;
  document.head.appendChild(style);
});
