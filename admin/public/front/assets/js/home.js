/* JustGoom — Homepage banner carousel */
document.addEventListener('DOMContentLoaded', function() {
  var carousel = document.getElementById('bannerCarousel');
  if (!carousel) return;

  var slides = carousel.querySelectorAll('.banner-slide');
  var dotsContainer = carousel.querySelector('.banner-dots');
  var prevBtn = carousel.querySelector('.banner-nav.prev');
  var nextBtn = carousel.querySelector('.banner-nav.next');
  var current = 0;
  var timer;

  slides.forEach(function(_, i) {
    var dot = document.createElement('button');
    dot.type = 'button';
    dot.className = 'banner-dot' + (i === 0 ? ' active' : '');
    dot.setAttribute('aria-label', 'Go to banner ' + (i + 1));
    dot.addEventListener('click', function() { goTo(i); });
    dotsContainer.appendChild(dot);
  });

  var dots = dotsContainer.querySelectorAll('.banner-dot');

  function goTo(index) {
    slides[current].classList.remove('active');
    dots[current].classList.remove('active');
    current = (index + slides.length) % slides.length;
    slides[current].classList.add('active');
    dots[current].classList.add('active');
  }

  function next() { goTo(current + 1); }
  function prev() { goTo(current - 1); }

  function startAuto() {
    clearInterval(timer);
    timer = setInterval(next, 6000);
  }

  if (prevBtn) prevBtn.addEventListener('click', function() { prev(); startAuto(); });
  if (nextBtn) nextBtn.addEventListener('click', function() { next(); startAuto(); });

  startAuto();
});
