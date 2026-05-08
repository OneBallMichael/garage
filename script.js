// ── SCROLL-TRIGGERED FADE-IN ──
// Selects elements that should animate in when scrolled into view,
// sets their initial hidden state, and attaches an IntersectionObserver.

const fadeSelectors = [
  '.about-heading',
  '.about-text',
  '.about-frame',
  '.menu-item',
  '.review-card'
].join(', ');

const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity  = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  },
  { threshold: 0.1 }
);

document.querySelectorAll(fadeSelectors).forEach((el) => {
  el.style.opacity   = '0';
  el.style.transform = 'translateY(20px)';
  el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
  observer.observe(el);
});

// ── PROMO TOAST LOGIC ──
document.addEventListener('DOMContentLoaded', () => {
  fetch('api.php')
    .then(res => res.json())
    .then(data => {
      if (data && data.title && data.title.trim() !== '') {
        const toast = document.getElementById('promo-toast');
        const titleEl = document.getElementById('promo-title');
        const descEl = document.getElementById('promo-desc');
        const timeEl = document.getElementById('promo-time');

        titleEl.textContent = data.title;
        descEl.textContent = data.description;
        
        if (data.end_time && data.end_time.trim() !== '') {
          timeEl.textContent = `Ważne: ${data.end_time}`;
          timeEl.style.display = 'block';
        } else {
          timeEl.style.display = 'none';
        }

        const dismissed = localStorage.getItem('promo_dismissed');
        // Pokaż, jeśli tytuł się zmienił (to nowa promocja)
        if (dismissed !== data.title) {
          setTimeout(() => {
            toast.classList.remove('hidden');
          }, 1500);
        }
        
        document.getElementById('promo-close').addEventListener('click', () => {
          toast.classList.add('hidden');
          localStorage.setItem('promo_dismissed', data.title);
        });
      }
    })
    .catch(err => console.error('Błąd pobierania promocji:', err));
});
