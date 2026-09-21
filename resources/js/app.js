import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import intersect from '@alpinejs/intersect';

Alpine.plugin(collapse);
Alpine.plugin(intersect);

/* ---------------------------------------------------------------------
 | Header — the sliding highlight, scroll progress and the mobile menu.
 --------------------------------------------------------------------- */
Alpine.data('siteHeader', () => ({
    open: false,
    panel: null,
    solid: false,
    progress: 0,
    ind: { left: 0, width: 0 },
    side: null,
    closeTimer: null,

    init() {
        this.onScroll();
        this.$nextTick(() => this.settle());
        window.addEventListener('resize', () => this.settle());
        document.fonts?.ready.then(() => this.settle());

        // Lock the page behind the mobile menu.
        this.$watch('open', (isOpen) => {
            document.body.style.overflow = isOpen ? 'hidden' : '';
        });
    },

    onScroll() {
        const y = window.scrollY;
        if (y > 48) this.solid = true;
        else if (y < 16) this.solid = false;

        const scrollable = document.documentElement.scrollHeight - window.innerHeight;
        this.progress = scrollable > 0 ? Math.min(y / scrollable, 1) : 0;
    },

    // Slide the highlight to a link and open (or close) its panel. `side` says
    // which of the two navs the link sits in.
    hover(el, key, side = null) {
        this.keep();
        this.side = side;
        this.moveTo(el);
        this.panel = key;
    },

    moveTo(el) {
        this.ind = el ? { left: el.offsetLeft + 2, width: el.offsetWidth - 4 } : { left: 0, width: 0 };
    },

    // Rest the highlight on the current page's link while nothing is hovered.
    settle() {
        if (this.panel) return;

        const current = this.$refs.current ?? null;
        this.side = current?.dataset.side ?? null;
        this.moveTo(current);
    },

    keep() {
        clearTimeout(this.closeTimer);
    },

    leave() {
        this.closeTimer = setTimeout(() => {
            this.panel = null;
            this.settle();
        }, 160);
    },
}));

/* ---------------------------------------------------------------------
 | Count-up for stat figures
 --------------------------------------------------------------------- */
Alpine.data('counter', (target = 0, duration = 1500) => ({
    value: 0,
    done: false,

    start() {
        if (this.done) return;
        this.done = true;

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            this.value = target;
            return;
        }

        const startedAt = performance.now();
        const step = (now) => {
            const progress = Math.min((now - startedAt) / duration, 1);
            // easeOutExpo
            const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            this.value = Math.round(eased * target);
            if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    },
}));

window.Alpine = Alpine;
Alpine.start();

/* ---------------------------------------------------------------------
 | Reveal on scroll
 --------------------------------------------------------------------- */
const revealables = () => document.querySelectorAll('.reveal:not(.is-visible)');

if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0.06 },
    );

    const observeAll = () => revealables().forEach((el) => observer.observe(el));

    document.addEventListener('DOMContentLoaded', observeAll);
    document.addEventListener('livewire:navigated', observeAll);
} else {
    document.addEventListener('DOMContentLoaded', () =>
        revealables().forEach((el) => el.classList.add('is-visible')),
    );
}

/* ---------------------------------------------------------------------
 | Headline word reveal
 |
 | Each word is wrapped in an overflow-hidden box and slides up from below
 | on a stagger, so headings wipe into place instead of fading. The splitter
 | walks text nodes only, so inline markup (the accent <span>) survives.
 --------------------------------------------------------------------- */
const splitIntoWords = (root) => {
    if (root.dataset.split === 'done') return;

    const walk = (node) => {
        [...node.childNodes].forEach((child) => {
            if (child.nodeType === Node.TEXT_NODE) {
                if (!child.textContent.trim()) return;

                const frag = document.createDocumentFragment();
                child.textContent.split(/(\s+)/).forEach((part) => {
                    if (!part.trim()) {
                        frag.appendChild(document.createTextNode(part));
                        return;
                    }
                    const box = document.createElement('span');
                    box.className = 'word';
                    const inner = document.createElement('span');
                    inner.className = 'word-inner';
                    inner.textContent = part;
                    box.appendChild(inner);
                    frag.appendChild(box);
                });
                child.replaceWith(frag);
            } else if (child.nodeType === Node.ELEMENT_NODE && !child.classList.contains('word')) {
                walk(child);
            }
        });
    };

    walk(root);
    root.querySelectorAll('.word-inner').forEach((el, i) => el.style.setProperty('--i', i));
    root.dataset.split = 'done';
};

const prefersReducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const initSplitHeadings = () => {
    if (prefersReducedMotion()) return;
    document.querySelectorAll('[data-split]').forEach(splitIntoWords);
};

document.addEventListener('DOMContentLoaded', initSplitHeadings);

/* ---------------------------------------------------------------------
 | Cursor spotlight
 |
 | One delegated listener for the whole page rather than one per card; it
 | only writes two custom properties, so the highlight itself is pure CSS.
 --------------------------------------------------------------------- */
document.addEventListener(
    'pointermove',
    (event) => {
        const card = event.target.closest?.('.spotlight');
        if (!card) return;

        const rect = card.getBoundingClientRect();
        card.style.setProperty('--mx', `${event.clientX - rect.left}px`);
        card.style.setProperty('--my', `${event.clientY - rect.top}px`);
    },
    { passive: true },
);
