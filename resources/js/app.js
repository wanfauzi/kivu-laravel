import Chart from 'chart.js/auto';

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

// Delegated password reveal toggle.
document.addEventListener('click', function (event) {
    const btn = event.target.closest('[data-password-toggle]');
    if (!btn) return;

    const input = document.getElementById(btn.getAttribute('data-password-toggle'));
    if (!input) return;

    const willShow = input.type === 'password';
    input.type = willShow ? 'text' : 'password';

    btn.querySelectorAll('[data-icon-eye]').forEach((el) => el.classList.toggle('hidden', willShow));
    btn.querySelectorAll('[data-icon-eye-off]').forEach((el) => el.classList.toggle('hidden', !willShow));
    btn.setAttribute('aria-label', willShow ? 'Sembunyikan password' : 'Tampilkan password');
});

// Close native <details> dropdowns when clicking outside or on a link.
document.addEventListener('click', function (event) {
    const inside = event.target.closest('[data-kivu-dropdown]');

    document.querySelectorAll('[data-kivu-dropdown][open]').forEach((details) => {
        if (details !== inside || event.target.closest('a, button')) {
            details.removeAttribute('open');
        }
    });
});

// Close dropdowns on Escape.
document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape') return;

    document.querySelectorAll('[data-kivu-dropdown][open]').forEach((details) => details.removeAttribute('open'));
});

// Shared Chart.js factory, theme-aware via the KIVU CSS tokens.
function token(name, fallback) {
    const value = getComputedStyle(document.documentElement).getPropertyValue(name).trim();
    return value || fallback;
}

function renderKivuChart(canvas) {
    if (!canvas || canvas.dataset.chartReady === '1') return;

    let payload;
    try {
        payload = JSON.parse(canvas.dataset.chart);
    } catch (error) {
        return;
    }

    const primary = token('--kivu-primary', '#2563eb');
    const success = token('--kivu-success', '#16a34a');
    const info = token('--kivu-info', '#0284c7');
    const muted = token('--kivu-text-muted', '#64748b');
    const border = token('--kivu-border', '#e2e8f0');

    const palette = [primary, success, info, '#f59e0b', '#8b5cf6', '#ef4444'];

    const datasets = (payload.series || []).map((serie, index) => ({
        label: serie.label,
        data: serie.data || [],
        borderColor: serie.color || palette[index % palette.length],
        backgroundColor: serie.fill
            ? 'color-mix(in oklab, ' + (serie.color || palette[index % palette.length]) + ' 18%, transparent)'
            : 'transparent',
        fill: Boolean(serie.fill),
        tension: 0.35,
        borderWidth: 2,
        pointRadius: 0,
        pointHoverRadius: 4,
    }));

    canvas.__kivuChart = new Chart(canvas, {
        type: payload.type || 'line',
        data: { labels: payload.labels || [], datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    display: (payload.series || []).length > 1,
                    labels: { color: muted, boxWidth: 10, boxHeight: 10, usePointStyle: true, font: { size: 11 } },
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: false,
                },
            },
            scales: {
                x: { grid: { display: false }, border: { color: border }, ticks: { color: muted, font: { size: 11 } } },
                y: {
                    beginAtZero: true,
                    grid: { color: border },
                    border: { display: false },
                    ticks: {
                        color: muted,
                        font: { size: 11 },
                        callback: (value) => (payload.compact ? compactNumber(value) : value),
                    },
                },
            },
        },
    });

    canvas.dataset.chartReady = '1';
}

function compactNumber(value) {
    const number = Number(value);
    if (number >= 1000000) return (number / 1000000).toFixed(1) + 'jt';
    if (number >= 1000) return (number / 1000).toFixed(0) + 'rb';
    return String(number);
}

function initKivuCharts() {
    document.querySelectorAll('canvas[data-chart]').forEach(renderKivuChart);
}

document.addEventListener('DOMContentLoaded', initKivuCharts);
document.addEventListener('livewire:navigated', initKivuCharts);
if (document.readyState !== 'loading') initKivuCharts();

