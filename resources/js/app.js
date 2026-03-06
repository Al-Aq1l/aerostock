import './bootstrap';

// ── Page Fade-in ─────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity 0.25s ease';
    requestAnimationFrame(() => { document.body.style.opacity = '1'; });

    // Auto-dismiss flash messages
    const flashes = document.querySelectorAll('.flash-msg');
    flashes.forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.4s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }, 4000);
    });

    // ESC closes modals globally
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active')
                .forEach(m => m.classList.remove('active'));
        }
    });
});
