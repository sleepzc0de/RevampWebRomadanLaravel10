/**
 * Komponen Alpine.js untuk frontend publik. Semua interaktivitas (navbar,
 * accordion FAQ, galeri media, pencarian live) didaftarkan di sini agar
 * Blade view cukup memanggil x-data="namaKomponen(...)" tanpa <script> inline.
 */
export default function registerFrontendComponents(Alpine) {
    // Navbar: efek blur saat scroll + menu mobile
    Alpine.data('siteNavbar', () => ({
        mobileOpen: false,
        scrolled: false,
        init() {
            const onScroll = () => { this.scrolled = window.scrollY > 12; };
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        },
    }));

    // Toggle mode gelap/terang — sinkron dengan class .dark di <html> yang
    // sudah di-set lebih awal oleh inline script anti-FOUC di fe_master.
    Alpine.data('themeToggle', () => ({
        dark: document.documentElement.classList.contains('dark'),
        toggle() {
            this.dark = !this.dark;
            document.documentElement.classList.toggle('dark', this.dark);
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        },
    }));

    // Tombol kembali ke atas
    Alpine.data('backToTop', () => ({
        visible: false,
        init() {
            const onScroll = () => { this.visible = window.scrollY > 480; };
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        },
        scrollTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
    }));

    // Carousel auto-slide (dipakai untuk section "Berita Terkini" di beranda)
    Alpine.data('newsCarousel', (slides = [], intervalMs = 5000) => ({
        slides,
        active: 0,
        timer: null,
        paused: false,
        start() {
            if (this.slides.length <= 1) return;
            this.timer = setInterval(() => {
                if (!this.paused) this.next();
            }, intervalMs);
        },
        next() { this.active = (this.active + 1) % this.slides.length; },
        prev() { this.active = (this.active - 1 + this.slides.length) % this.slides.length; },
        go(i) { this.active = i; },
        pause() { this.paused = true; },
        resume() { this.paused = false; },
    }));

    // Accordion FAQ (satu item terbuka pada satu waktu)
    Alpine.data('faqAccordion', () => ({
        openIndex: null,
        toggle(i) { this.openIndex = this.openIndex === i ? null : i; },
    }));

    // Galeri media (gambar/video) dengan lightbox — pengganti Slick/Swiper/Lightbox2 + jQuery
    Alpine.data('mediaGallery', (items = []) => ({
        items,
        active: 0,
        lightbox: false,
        get current() { return this.items[this.active] ?? null; },
        next() { this.active = (this.active + 1) % this.items.length; },
        prev() { this.active = (this.active - 1 + this.items.length) % this.items.length; },
        go(i) { this.active = i; },
        openLightbox(i) {
            if (this.items[i]?.type !== 'image') return;
            this.active = i;
            this.lightbox = true;
        },
    }));

    // Pencarian live (dipakai di daftar berita/warta/artikel & portal aplikasi)
    Alpine.data('liveSearch', (opts) => ({
        q: opts.initialValue || '',
        loading: false,
        _timer: null,
        init() {
            this.$watch('q', () => {
                clearTimeout(this._timer);
                if (this.q.length >= 3 || this.q.length === 0) {
                    this._timer = setTimeout(() => this.run(), 450);
                }
            });
        },
        submitNow() {
            clearTimeout(this._timer);
            this.run();
        },
        refresh() {
            this.q = '';
            this.run();
        },
        onPagerClick(e) {
            const a = e.target.closest('a[href]');
            if (!a) return;
            e.preventDefault();
            const page = new URL(a.href).searchParams.get('page');
            this.run(page);
            const top = this.$root.getBoundingClientRect().top + window.scrollY - 100;
            window.scrollTo({ top, behavior: 'smooth' });
        },
        async run(page = null) {
            this.loading = true;
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const target = document.querySelector(opts.target);

            try {
                let res;
                if (opts.json) {
                    res = await fetch(opts.url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            [opts.param]: this.q,
                            ...(page ? { page } : {}),
                        }),
                    });
                } else {
                    const body = new FormData();
                    body.append('_token', token);
                    body.append(opts.param, this.q);
                    if (opts.extra) {
                        Object.entries(opts.extra).forEach(([k, v]) => body.append(k, v));
                    }
                    res = await fetch(opts.url, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body,
                    });
                }

                if (!res.ok) throw new Error('HTTP ' + res.status);

                if (opts.responseType === 'json') {
                    const data = await res.json();
                    if (data.error) throw new Error(data.error);
                    target.innerHTML = data.html;
                    if (opts.paginationTarget && data.pagination) {
                        document.querySelector(opts.paginationTarget).innerHTML = data.pagination;
                    }
                } else {
                    target.innerHTML = await res.text();
                }

                const url = new URL(window.location);
                if (this.q) url.searchParams.set(opts.param, this.q);
                else url.searchParams.delete(opts.param);
                window.history.pushState({}, '', url);
            } catch (e) {
                target.innerHTML = '<div class="rounded-xl border border-red-200 bg-red-50 p-6 text-center text-red-700">Terjadi kesalahan saat memuat data. Silakan coba lagi.</div>';
            } finally {
                this.loading = false;
            }
        },
    }));
}
