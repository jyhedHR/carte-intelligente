// ── HERO SLIDER ──
document.addEventListener('DOMContentLoaded', () => {
  const slides = document.querySelectorAll('.slide');
  const dotsContainer = document.getElementById('slider-dots');
  const prevArrow = document.getElementById('prev-arrow');
  const nextArrow = document.getElementById('next-arrow');
  const slider = document.getElementById('hero-slider');

  if (!slides.length || !dotsContainer) return;

  let currentSlide = 0;

  // Create dots
  slides.forEach((_, index) => {
    const dot = document.createElement('div');
    dot.classList.add('dot');
    if (index === 0) dot.classList.add('active');
    dot.addEventListener('click', () => goToSlide(index));
    dotsContainer.appendChild(dot);
  });

  const dots = document.querySelectorAll('.dot');

  function goToSlide(n) {
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));
    slides[n].classList.add('active');
    dots[n].classList.add('active');
    currentSlide = n;
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    goToSlide(currentSlide);
  }

  function prevSlide() {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    goToSlide(currentSlide);
  }

  if (nextArrow) nextArrow.addEventListener('click', nextSlide);
  if (prevArrow) prevArrow.addEventListener('click', prevSlide);

  let slideInterval = setInterval(nextSlide, 1800);

  if (slider) {
    slider.addEventListener('mouseenter', () => clearInterval(slideInterval));
    slider.addEventListener('mouseleave', () => {
      slideInterval = setInterval(nextSlide, 1500);
    });
  }
});
