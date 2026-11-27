import Alpine from 'alpinejs';
import './reader.js';

window.Alpine = Alpine;

Alpine.data('passwordVisibility', () => ({
    showPassword: false,
    submitting: false,
}));

Alpine.data('passwordRegistration', () => ({
    password: '',
    passwordConfirmation: '',
    showPassword: false,
    showConfirmation: false,
    submitting: false,
    get hasMinLength() {
        return this.password.length >= 8;
    },
    get hasUppercase() {
        return /[A-Z]/.test(this.password);
    },
    get hasNumber() {
        return /[0-9]/.test(this.password);
    },
    get isPasswordValid() {
        return this.hasMinLength && this.hasUppercase && this.hasNumber;
    },
    get passwordsMatch() {
        return this.password.length > 0 && this.password === this.passwordConfirmation;
    },
    get isValid() {
        return this.isPasswordValid && this.passwordsMatch;
    },
}));

Alpine.data('catalogSearch', () => ({
    q: '',
    category: '',
    results: [],
    loading: false,
    open: false,
    timer: null,
    async search() {
        clearTimeout(this.timer);
        this.timer = setTimeout(async () => {
            if (!this.q.trim()) {
                this.results = [];
                this.loading = false;
                return;
            }

            this.loading = true;
            this.open = true;
            try {
                const params = new URLSearchParams({ q: this.q, category: this.category });
                const response = await fetch(`/katalog/cari?${params}`, {
                    headers: { 'Accept': 'application/json' },
                });
                if (!response.ok) throw new Error('Search failed');
                this.results = await response.json();
            } catch (_) {
                this.results = [];
            } finally {
                this.loading = false;
            }
        }, 220);
    },
}));

Alpine.start();

// Scroll reveal is re-mounted after client-side public navigation. Keep observers
// scoped and disconnect the previous set before binding the newly swapped content.
let revealObserver = null;
const setupScrollReveal = () => {
    revealObserver?.disconnect();
    const scope = document.querySelector('#main-content') || document;
    const sectionTargets = [...scope.querySelectorAll('[data-reveal]')];
    const staggerTargets = [];
    scope.querySelectorAll('[data-reveal-stagger]').forEach((group) => {
        [...group.children].forEach((element, index) => {
            element.style.setProperty('--reveal-delay', `${Math.min(index * 85, 340)}ms`);
            staggerTargets.push(element);
        });
    });
    const targets = [...new Set([...sectionTargets, ...staggerTargets])];
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduceMotion || !('IntersectionObserver' in window)) {
        targets.forEach((element) => element.classList.add('is-visible'));
        return;
    }
    targets.forEach((element) => element.classList.add('reveal-on-scroll'));
    revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -36px 0px' });
    targets.forEach((element) => revealObserver.observe(element));
};

setupScrollReveal();


// Public routes are swapped in-place to avoid full document reloads. Auth, admin,
// reader, POST forms, external links and downloads retain normal browser navigation.
const publicMain = () => document.querySelector('#main-content');
const isPublicPath = (path) => path === '/' || path === '/katalog' || path === '/e-book' || path.startsWith('/katalog/');
const navProgress = document.getElementById('navigation-progress');
let navigationBusy = false;
const mountPublicPage = async (url, { push = true, scroll = true } = {}) => {
    if (navigationBusy) return;
    navigationBusy = true;
    navProgress?.classList.add('is-active');
    navProgress?.setAttribute('aria-hidden', 'false');
    try {
        const response = await fetch(url.href, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } });
        if (!response.ok || !response.headers.get('content-type')?.includes('text/html')) throw new Error('Navigation response is not an HTML page');
        const markup = await response.text();
        const parsed = new DOMParser().parseFromString(markup, 'text/html');
        const oldMain = publicMain();
        const nextMain = parsed.querySelector('#main-content');
        if (!oldMain || !nextMain || parsed.querySelector('.tb-admin')) throw new Error('Page is outside public navigation scope');
        rubacaMotionCleanup?.();
        rubacaMotionCleanup = null;
        Alpine.destroyTree(oldMain);
        oldMain.innerHTML = nextMain.innerHTML;
        document.title = parsed.title;
        if (push) history.pushState({ rubacaPublic: true }, '', url.href);
        Alpine.initTree(oldMain);
        setupScrollReveal();
        initializeRubacaHomeMotion();
        if (scroll) window.scrollTo({ top: 0, behavior: 'smooth' });
        if (url.hash) {
            requestAnimationFrame(() => document.querySelector(url.hash)?.scrollIntoView({ behavior: 'smooth', block: 'start' }));
        }
    } catch (error) {
        if (push) window.location.assign(url.href);
    } finally {
        navigationBusy = false;
        navProgress?.classList.remove('is-active');
        navProgress?.setAttribute('aria-hidden', 'true');
    }
};

document.addEventListener('click', (event) => {
    const target = event.target instanceof Element ? event.target : null;
    const link = target?.closest('a[href]');
    if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
    if (link.target && link.target !== '_self' || link.hasAttribute('download')) return;
    const url = new URL(link.href, window.location.href);
    if (url.origin !== window.location.origin || !isPublicPath(url.pathname)) return;
    const current = new URL(window.location.href);
    if (url.pathname === current.pathname && url.search === current.search) return; // hash-only anchors scroll natively
    event.preventDefault();
    mountPublicPage(url);
});
window.addEventListener('popstate', () => {
    const url = new URL(window.location.href);
    if (isPublicPath(url.pathname)) mountPublicPage(url, { push: false, scroll: true });
});

// Motion: hero parallax, stat counters, cover tilt, header shadow.
let rubacaMotionCleanup = null;
function initializeRubacaHomeMotion() {
    rubacaMotionCleanup?.();
    rubacaMotionCleanup = null;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    const undo = [];
    const hero = document.querySelector('.rb-hero');
    if (hero) {
        let busy = false;
        const onScroll = () => {
            if (busy) return;
            busy = true;
            requestAnimationFrame(() => { hero.style.setProperty('--py', `${Math.min(window.scrollY * 0.18, 90)}px`); busy = false; });
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        undo.push(() => window.removeEventListener('scroll', onScroll));
    }
    const counters = document.querySelectorAll('[data-count]');
    if (counters.length && 'IntersectionObserver' in window) {
        const format = new Intl.NumberFormat('id-ID');
        const observer = new IntersectionObserver((entries, obs) => entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            obs.unobserve(entry.target);
            const end = Number(entry.target.dataset.count) || 0;
            const start = performance.now();
            const tick = (now) => {
                const p = Math.min((now - start) / 1200, 1);
                entry.target.textContent = format.format(Math.round(end * (1 - Math.pow(1 - p, 3))));
                if (p < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        }), { threshold: 0.4 });
        counters.forEach((el) => { el.textContent = '0'; observer.observe(el); });
        undo.push(() => observer.disconnect());
    }
    if (window.matchMedia('(hover: hover)').matches) {
        const cover = (e) => e.target.closest?.('.rb-card')?.querySelector('.rb-cover');
        const move = (e) => {
            const el = cover(e);
            if (!el) return;
            const r = el.getBoundingClientRect();
            el.style.setProperty('--ry', `${((e.clientX - r.left) / r.width - 0.5) * 14}deg`);
            el.style.setProperty('--rx', `${-((e.clientY - r.top) / r.height - 0.5) * 14}deg`);
        };
        const leave = (e) => { const el = cover(e); el?.style.removeProperty('--rx'); el?.style.removeProperty('--ry'); };
        document.addEventListener('pointermove', move);
        document.addEventListener('pointerout', leave);
        undo.push(() => { document.removeEventListener('pointermove', move); document.removeEventListener('pointerout', leave); });
    }
    rubacaMotionCleanup = () => undo.forEach((fn) => fn());
}
initializeRubacaHomeMotion();

const siteHeader = document.querySelector('.public-header');
const syncHeader = () => siteHeader?.classList.toggle('is-scrolled', window.scrollY > 8);
window.addEventListener('scroll', syncHeader, { passive: true });
syncHeader();
