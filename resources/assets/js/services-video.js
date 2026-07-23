 // ── DROPDOWN TOGGLE ──
    document.addEventListener('DOMContentLoaded', () => {
      const toggle = document.getElementById('dropdownToggle');
      const content = document.getElementById('dropdownContent');

      toggle.addEventListener('click', () => {
        toggle.classList.toggle('active');
        content.classList.toggle('active');
      });
    });

    // ── SERVICES VIDEO BACKDROP WITH ANIMATION ──
    document.addEventListener('DOMContentLoaded', () => {
      const bg = document.getElementById('services-video-bg');
      const video = document.getElementById('svc-video');
      const cards = document.querySelectorAll('#services .service-card[data-video]');

      if (!bg || !video || !cards.length) return;

      let hideTimer = null;
      let isTransitioning = false;

      cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
          if (hideTimer) clearTimeout(hideTimer);

          const src = card.dataset.video;

          // Only transition if video is actually changing
          if (video.dataset.loaded !== src) {
            isTransitioning = true;

            // Fade out current video
            bg.style.transition = 'opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
            bg.classList.remove('active');

            // Wait for fade out, then switch video
            setTimeout(() => {
              video.src = src;
              video.dataset.loaded = src;
              video.load();

              // Add transition glow effect
              bg.classList.remove('transition-glow');
              void bg.offsetWidth;
              bg.classList.add('transition-glow');

              // Fade in new video
              setTimeout(() => {
                bg.style.transition = 'opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
                bg.classList.add('active');
                video.play().catch(() => {});
                isTransitioning = false;
              }, 50);
            }, 400);
          } else {
            // Same video, just show it
            bg.classList.add('active');
            video.play().catch(() => {});
          }
        });

        card.addEventListener('mouseleave', () => {
          hideTimer = setTimeout(() => {
            if (!isTransitioning) {
              bg.style.transition = 'opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
              bg.classList.remove('active');
              setTimeout(() => {
                video.pause();
                video.currentTime = 0;
              }, 600);
            }
          }, 100);
        });
      });
    });
