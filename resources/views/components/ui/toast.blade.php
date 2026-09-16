@props(['position' => 'bottom'])

<div
    id="kivu-toast-root"
    aria-live="polite"
    aria-atomic="true"
    class="pointer-events-none fixed inset-x-0 z-[100] flex flex-col items-center gap-2 px-4 {{ $position === 'top' ? 'top-4' : 'bottom-4' }} sm:inset-x-auto sm:right-5 sm:items-end"></div>

<script>
    (function () {
        if (window.__kivuToastBound) return;
        window.__kivuToastBound = true;

        var tones = {
            success: 'border-l-kivu-success text-kivu-text',
            error: 'border-l-kivu-danger text-kivu-text',
            info: 'border-l-kivu-info text-kivu-text',
        };
        var icons = { success: 'circle-check', error: 'alert-circle', info: 'info' };

        function icon(name, cls) {
            var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('viewBox', '0 0 24 24');
            svg.setAttribute('fill', 'none');
            svg.setAttribute('stroke', 'currentColor');
            svg.setAttribute('stroke-width', '2');
            svg.setAttribute('stroke-linecap', 'round');
            svg.setAttribute('stroke-linejoin', 'round');
            svg.setAttribute('class', cls);
            var paths = {
                'circle-check': ['M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Z', 'm9 12 2 2 4-4'],
                'alert-circle': ['M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Z', 'M12 8v4', 'M12 16h.01'],
                'info': ['M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Z', 'M12 16v-4', 'M12 8h.01'],
            }[name] || [];
            paths.forEach(function (d) {
                var p = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                p.setAttribute('d', d);
                svg.appendChild(p);
            });
            return svg;
        }

        window.kivuToast = function (message, type) {
            if (!message) return;
            var root = document.getElementById('kivu-toast-root');
            if (!root) return;

            type = tones[type] ? type : 'success';

            var el = document.createElement('div');
            el.className = 'kivu-toast pointer-events-auto flex w-full max-w-sm items-center gap-3 rounded-kivu border border-kivu-border border-l-4 bg-kivu-surface p-3.5 shadow-theme-lg ' + tones[type];
            el.setAttribute('role', 'status');

            var badge = document.createElement('span');
            badge.className = 'flex h-7 w-7 shrink-0 items-center justify-center rounded-full ' + (type === 'error' ? 'bg-kivu-danger-soft text-kivu-danger' : type === 'info' ? 'bg-kivu-info-soft text-kivu-info' : 'bg-kivu-success-soft text-kivu-success');
            badge.appendChild(icon(icons[type], 'h-4 w-4'));
            el.appendChild(badge);

            var text = document.createElement('p');
            text.className = 'flex-1 text-sm font-medium';
            text.textContent = message;
            el.appendChild(text);

            var close = document.createElement('button');
            close.type = 'button';
            close.className = 'rounded p-1 text-kivu-text-muted transition hover:text-kivu-text';
            close.setAttribute('aria-label', 'Tutup notifikasi');
            close.textContent = '\u00d7';
            close.style.fontSize = '18px';
            close.style.lineHeight = '1';
            close.addEventListener('click', function () { el.remove(); });
            el.appendChild(close);

            root.appendChild(el);

            setTimeout(function () {
                el.style.transition = 'opacity .2s ease, transform .2s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateY(8px)';
                setTimeout(function () { el.remove(); }, 220);
            }, 4000);
        };

        window.addEventListener('toast', function (event) {
            var detail = event.detail;
            var payload = Array.isArray(detail) ? detail[0] : detail;
            if (!payload) return;
            window.kivuToast(payload.message || payload[0] || '', payload.type || 'success');
        });
    })();
</script>
