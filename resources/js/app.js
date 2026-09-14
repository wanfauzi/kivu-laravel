(function () {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Reveal-on-scroll
    const reveals = document.querySelectorAll('.reveal');
    if (reveals.length > 0 && !reduceMotion && 'IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        reveals.forEach((el) => io.observe(el));
    } else {
        reveals.forEach((el) => el.classList.add('is-visible'));
    }

    // Count-up statistics
    const counters = document.querySelectorAll('[data-count]');
    if (counters.length > 0 && !reduceMotion && 'IntersectionObserver' in window) {
        const anim = (el) => {
            const target = parseInt(el.dataset.count, 10) || 0;
            const duration = 1200;
            const start = performance.now();
            const step = (now) => {
                const p = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.round(target * eased).toLocaleString('id-ID');
                if (p < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        };
        const cio = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    anim(entry.target);
                    cio.unobserve(entry.target);
                }
            });
        }, { threshold: 0.4 });
        counters.forEach((el) => cio.observe(el));
    } else {
        counters.forEach((el) => {
            el.textContent = (parseInt(el.dataset.count, 10) || 0).toLocaleString('id-ID');
        });
    }
})();