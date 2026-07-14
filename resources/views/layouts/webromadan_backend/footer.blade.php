<footer x-data="{ show: false }" @scroll.window="show = window.scrollY > 320"
    class="relative flex h-12 shrink-0 flex-wrap items-center justify-between gap-2 border-t border-slate-200 px-4 text-xs text-slate-500 sm:px-6 dark:border-white/10 dark:text-slate-400">
    <span>CMS Romadan <span class="font-bold text-gold-500">V.2</span></span>
    <span>&copy; {{ date('Y') }} <a href="http://www.romadan.kemenkeu.go.id/" class="font-semibold no-underline">Biro Manajemen BMN dan Pengadaan</a> · Powered by Auliya Putra Azhari</span>

    <button type="button" x-show="show" x-cloak x-transition.opacity
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="fixed bottom-5 right-5 z-40 flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-lg transition hover:scale-105 hover:text-brand-700 active:scale-95 dark:border-white/10 dark:bg-navy-900 dark:text-slate-300 dark:hover:text-brand-400"
        aria-label="Kembali ke atas" title="Kembali ke atas">
        <i class="ph-arrow-up"></i>
    </button>
</footer>
