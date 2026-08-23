(function () {
  'use strict';

  const menuButton = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.main-nav');

  if (menuButton && nav) {
    const closeMenu = () => {
      nav.classList.remove('is-open');
      document.body.classList.remove('menu-open');
      menuButton.setAttribute('aria-expanded', 'false');
      menuButton.setAttribute('aria-label', 'Open menu');
    };

    menuButton.addEventListener('click', () => {
      const opening = !nav.classList.contains('is-open');
      nav.classList.toggle('is-open', opening);
      document.body.classList.toggle('menu-open', opening);
      menuButton.setAttribute('aria-expanded', String(opening));
      menuButton.setAttribute('aria-label', opening ? 'Close menu' : 'Open menu');
    });

    nav.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') closeMenu();
    });
  }

  const slider = document.querySelector('[data-slider], .feast-native-slider');
  if (slider) {
    const slides = Array.from(slider.querySelectorAll('[data-slide], .hero-slide'));
    const dots = Array.from(document.querySelectorAll('[data-slide-to]'));
    const nextButton = document.querySelector('[data-slide-next]');
    const prevButton = document.querySelector('[data-slide-prev]');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let current = 0;
    let timer;

    const show = (index) => {
      current = (index + slides.length) % slides.length;
      slides.forEach((slide, position) => {
        const active = position === current;
        slide.classList.toggle('is-active', active);
        slide.setAttribute('aria-hidden', String(!active));
      });
      dots.forEach((dot, position) => {
        const active = position === current;
        dot.classList.toggle('is-active', active);
        if (active) dot.setAttribute('aria-current', 'true');
        else dot.removeAttribute('aria-current');
      });
    };

    const stop = () => {
      window.clearInterval(timer);
      timer = undefined;
    };
    const start = () => {
      stop();
      if (!reducedMotion && slides.length > 1) {
        timer = window.setInterval(() => show(current + 1), 9000);
      }
    };
    const restart = () => { stop(); start(); };

    dots.forEach((dot) => dot.addEventListener('click', () => { show(Number(dot.dataset.slideTo)); restart(); }));
    if (nextButton) nextButton.addEventListener('click', () => { show(current + 1); restart(); });
    if (prevButton) prevButton.addEventListener('click', () => { show(current - 1); restart(); });
    slider.parentElement.addEventListener('mouseenter', stop);
    slider.parentElement.addEventListener('mouseleave', start);
    slider.parentElement.addEventListener('focusin', stop);
    slider.parentElement.addEventListener('focusout', start);
    start();
  }

  document.querySelectorAll('[data-bundle]').forEach((link) => {
    link.addEventListener('click', () => {
      const field = document.querySelector('#message');
      if (field && !field.value) field.value = 'I am interested in the ' + link.dataset.bundle + ' catering option.';
    });
  });
})();
