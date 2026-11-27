// Landing RUBACA — mesin scroll.
//
// Scroll tetap native (tidak dibajak). Setiap "adegan" adalah blok tinggi dengan
// panggung sticky di dalamnya. Skrip ini hanya mengubah posisi scroll menjadi angka
// 0–1 dan menuliskannya sebagai custom property CSS; semua gerak (zoom, geser,
// kata muncul satu per satu) dihitung di CSS lewat calc().
//
//   data-scene            blok tinggi berisi satu panggung sticky
//   data-night="a b"      nilai malam (0 terang … 1 navy) di awal dan akhir adegan
//   data-r="a b; c d"     rentang progres adegan → --r0/--e0, --r1/--e1 (linear / eased)
//   data-words            teks dipecah per kata, muncul mengikuti --r0
//   data-hide             before | after | both: sembunyikan di luar rentang
//   data-track, data-book deret buku mendatar, yang di tengah membesar

const home = document.getElementById('rubaca-home');
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

if (home && !reduceMotion.matches) {
    start(home);
}

function start(home) {
    const root = document.documentElement;
    const clamp = (v, a = 0, b = 1) => Math.min(b, Math.max(a, v));
    const ease = (t) => t * t * (3 - 2 * t);
    const lerp = (a, b, t) => a + (b - a) * t;

    root.classList.add('scrub');

    // Pecah teks menjadi kata-kata.
    home.querySelectorAll('[data-words]').forEach((el) => {
        const words = el.textContent.trim().split(/\s+/);
        el.textContent = '';
        words.forEach((word, i) => {
            const span = document.createElement('span');
            span.className = 'rb-w';
            span.style.setProperty('--i', i);
            span.textContent = word;
            el.append(span);
            if (i < words.length - 1) el.append(' ');
        });
        el.style.setProperty('--n', words.length);
    });

    const scenes = [...home.querySelectorAll('[data-scene]')].map((el) => {
        const books = [...el.querySelectorAll('[data-book]')];
        return {
            el,
            night: (el.dataset.night || '0 0').split(/\s+/).map(Number),
            items: [...el.querySelectorAll('[data-r]')].map((node) => ({
                node,
                ranges: node.dataset.r.split(';').map((r) => r.trim().split(/\s+/).map(Number)),
                hide: node.dataset.hide || '',
                last: [],
                hidden: null,
            })),
            track: el.querySelector('[data-track]'),
            books,
            top: 0,
            height: 0,
            span: 1,
            shift: 0,
            pitch: 1,
            cur: null,
        };
    });

    // Warna latar: pagi → senja → navy.
    const stops = [
        [0, [239, 239, 234]],
        [0.5, [142, 162, 192]],
        [1, [14, 33, 64]],
    ];
    const bgAt = (night) => {
        for (let i = 1; i < stops.length; i++) {
            if (night <= stops[i][0]) {
                const [a, ca] = stops[i - 1];
                const [b, cb] = stops[i];
                const t = (night - a) / (b - a);
                return ca.map((c, k) => Math.round(lerp(c, cb[k], t)));
            }
        }
        return stops[stops.length - 1][1];
    };

    let vh = window.innerHeight;
    let docHeight = document.documentElement.scrollHeight;
    let raf = 0;
    let tone = 'day';
    let lastNight = -1;
    let lastRead = -1;

    const measure = () => {
        const stage = home.querySelector('.rb-stage');
        vh = stage ? stage.offsetHeight : window.innerHeight;
        docHeight = document.documentElement.scrollHeight;
        scenes.forEach((s) => {
            const rect = s.el.getBoundingClientRect();
            s.top = rect.top + window.scrollY;
            s.height = rect.height;
            s.span = Math.max(1, s.height - vh);
            if (s.books.length) {
                const first = s.books[0].offsetLeft;
                s.shift = s.books[s.books.length - 1].offsetLeft - first;
                s.pitch = s.books[1] ? s.books[1].offsetLeft - first : 1;
            }
        });
    };

    const applyScene = (s, p) => {
        s.items.forEach((item) => {
            item.ranges.forEach(([a, b], i) => {
                const t = clamp((p - a) / (b - a));
                if (Math.abs(t - (item.last[i] ?? -1)) > 0.0005) {
                    item.last[i] = t;
                    item.node.style.setProperty(`--r${i}`, t.toFixed(4));
                    item.node.style.setProperty(`--e${i}`, ease(t).toFixed(4));
                }
            });
            if (item.hide) {
                const first = item.last[0] ?? 0;
                const last = item.last[item.last.length - 1] ?? 0;
                const hidden = (item.hide !== 'after' && first <= 0.001) || (item.hide !== 'before' && last >= 0.999);
                if (hidden !== item.hidden) {
                    item.hidden = hidden;
                    item.node.style.visibility = hidden ? 'hidden' : '';
                }
            }
        });

        if (s.track) {
            const k = clamp((p - 0.06) / 0.86);
            s.track.style.setProperty('--tx', `${(-k * s.shift).toFixed(1)}px`);
            s.books.forEach((book, i) => {
                const offset = Math.abs(i * s.pitch - k * s.shift);
                const f = 1 - clamp(offset / s.pitch);
                book.style.setProperty('--f', f.toFixed(3));
            });
        }
    };

    const nightAt = (y) => {
        const c = y + vh / 2;
        const first = scenes[0];
        const last = scenes[scenes.length - 1];
        if (c <= first.top) return first.night[0];
        for (const s of scenes) {
            if (c <= s.top + s.height) return lerp(s.night[0], s.night[1], clamp((c - s.top) / s.height));
        }
        return last.night[1];
    };

    const frame = () => {
        raf = 0;
        const y = window.scrollY;
        let moving = false;

        scenes.forEach((s) => {
            const target = (y - s.top) / s.span;
            const near = y > s.top - vh * 1.3 && y < s.top + s.height + vh * 0.3;
            if (s.cur === null || !near) {
                s.cur = target;
            } else if (Math.abs(target - s.cur) > 0.0004) {
                s.cur += (target - s.cur) * 0.2; // sedikit peredam supaya gerak terasa lembut
                moving = true;
            } else {
                s.cur = target;
            }
            if (near || s.dirty !== false) {
                applyScene(s, s.cur);
                s.dirty = near;
            }
        });

        const night = nightAt(y);
        if (Math.abs(night - lastNight) > 0.002) {
            lastNight = night;
            const [r, g, b] = bgAt(night);
            root.style.setProperty('--bg', `rgb(${r} ${g} ${b})`);
            root.style.setProperty('--glow', clamp((night - 0.55) / 0.4).toFixed(3));
            if (tone === 'day' && night >= 0.62) tone = 'night';
            else if (tone === 'night' && night <= 0.58) tone = 'day';
            root.dataset.tone = tone;
        }

        const read = clamp(y / Math.max(1, docHeight - window.innerHeight));
        if (Math.abs(read - lastRead) > 0.001) {
            lastRead = read;
            root.style.setProperty('--read', read.toFixed(4));
        }

        if (moving) raf = requestAnimationFrame(frame);
    };

    const schedule = () => {
        if (!raf) raf = requestAnimationFrame(frame);
    };

    const remeasure = () => {
        measure();
        scenes.forEach((s) => (s.dirty = true));
        schedule();
    };

    measure();
    frame();

    window.addEventListener('scroll', schedule, { passive: true });
    window.addEventListener('resize', remeasure);
    window.addEventListener('load', remeasure);
    window.addEventListener('pageshow', remeasure);
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(remeasure);
    if ('ResizeObserver' in window) new ResizeObserver(remeasure).observe(home);
}
