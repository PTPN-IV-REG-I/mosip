// Alpine.js via CDN is loaded in layout, but we init any extra Alpine plugins here
import './bootstrap';

// ── Ripple effect on buttons ──────────────────────────────────
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-primary');
    if (!btn) return;
    const circle = document.createElement('span');
    const rect   = btn.getBoundingClientRect();
    const size   = Math.max(rect.width, rect.height);
    circle.style.cssText = `
        width:${size}px; height:${size}px;
        left:${e.clientX - rect.left - size/2}px;
        top:${e.clientY - rect.top  - size/2}px;
    `;
    circle.classList.add('ripple-effect');
    btn.appendChild(circle);
    setTimeout(() => circle.remove(), 600);
});

// ── Smooth scroll ─────────────────────────────────────────────
document.documentElement.style.scrollBehavior = 'smooth';

// ── Page transition and micro-interactions ────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const main = document.getElementById('main-content');
    if (main) {
        main.animate(
            [
                { opacity: 0, transform: 'translateY(14px)' },
                { opacity: 1, transform: 'translateY(0)' },
            ],
            {
                duration: 360,
                easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
                fill: 'both',
            }
        );
    }

    document.querySelectorAll('tbody tr').forEach((row, index) => {
        row.style.animationDelay = `${index * 35}ms`;
    });
});
