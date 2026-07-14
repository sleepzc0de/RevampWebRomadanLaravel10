/*
 * CMS ROMADAN V.2 — bundle backend.
 * Berisi: Alpine (chrome sidebar/topbar), delegasi dropdown aksi baris tabel,
 * CKEditor 5 terbaru (build lengkap + upload gambar), dan pemuat gambar blob
 * ter-otentikasi.
 */
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import Chart from 'chart.js/auto';

// Chart.js dibundel lokal (menggantikan CDN) — dipakai dashboard & visitor
window.Chart = Chart;

import {
    ClassicEditor,
    Alignment,
    AutoImage,
    AutoLink,
    Autoformat,
    BlockQuote,
    Bold,
    Code,
    Essentials,
    FindAndReplace,
    FontBackgroundColor,
    FontColor,
    FontFamily,
    FontSize,
    Heading,
    Highlight,
    HorizontalLine,
    Image,
    ImageCaption,
    ImageInsert,
    ImageResize,
    ImageStyle,
    ImageToolbar,
    ImageUpload,
    Indent,
    IndentBlock,
    Italic,
    Link,
    LinkImage,
    List,
    ListProperties,
    MediaEmbed,
    Paragraph,
    PasteFromOffice,
    RemoveFormat,
    SimpleUploadAdapter,
    SourceEditing,
    SpecialCharacters,
    SpecialCharactersEssentials,
    Strikethrough,
    Subscript,
    Superscript,
    Table,
    TableCaption,
    TableCellProperties,
    TableColumnResize,
    TableProperties,
    TableToolbar,
    TextTransformation,
    TodoList,
    Underline,
    WordCount,
} from 'ckeditor5';
import 'ckeditor5/ckeditor5.css';

/* ---------------------------------------------------------------------------
 * CKEditor 5 — build lengkap ala "classic build" supaya seluruh view lama
 * yang memanggil ClassicEditor.create(...) langsung dapat editor powerful
 * (tabel, gambar+upload, media embed, font, alignment, source editing, dll).
 * ------------------------------------------------------------------------- */
const editorInstances = new WeakMap();

class RomadanEditor extends ClassicEditor {
    /**
     * Idempoten: kalau elemen yang sama di-create dua kali (init per-view +
     * auto-init global), pemanggilan kedua mengembalikan promise yang sama
     * alih-alih melempar error "source element already used".
     */
    static create(element, config) {
        const el = typeof element === 'string' ? document.querySelector(element) : element;
        if (el && editorInstances.has(el)) return editorInstances.get(el);

        const promise = super.create(element, config);
        if (el) editorInstances.set(el, promise);

        return promise;
    }
}

RomadanEditor.builtinPlugins = [
    Essentials, Paragraph, Heading, Autoformat, TextTransformation, PasteFromOffice,
    Bold, Italic, Underline, Strikethrough, Subscript, Superscript, Code, RemoveFormat,
    FontFamily, FontSize, FontColor, FontBackgroundColor, Highlight, Alignment,
    List, ListProperties, TodoList, Indent, IndentBlock,
    Link, AutoLink, BlockQuote, HorizontalLine,
    SpecialCharacters, SpecialCharactersEssentials,
    Table, TableToolbar, TableProperties, TableCellProperties, TableCaption, TableColumnResize,
    Image, ImageToolbar, ImageCaption, ImageStyle, ImageResize, ImageUpload, ImageInsert,
    AutoImage, LinkImage, SimpleUploadAdapter, MediaEmbed,
    FindAndReplace, SourceEditing, WordCount,
];

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const ckUploadUrl = document.querySelector('meta[name="ck-upload-url"]')?.content ?? '';

RomadanEditor.defaultConfig = {
    licenseKey: 'GPL',
    toolbar: {
        items: [
            'undo', 'redo', '|', 'findAndReplace', '|',
            'heading', '|',
            'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor', '|',
            'bold', 'italic', 'underline', 'strikethrough', 'highlight', 'removeFormat', '|',
            'alignment', '|',
            'bulletedList', 'numberedList', 'outdent', 'indent', '|',
            'link', 'insertImage', 'mediaEmbed', 'insertTable', 'blockQuote', 'horizontalLine', 'specialCharacters', '|',
            'sourceEditing',
        ],
        shouldNotGroupWhenFull: false,
    },
    image: {
        toolbar: [
            'imageTextAlternative', 'toggleImageCaption', '|',
            'imageStyle:inline', 'imageStyle:wrapText', 'imageStyle:breakText', '|',
            'resizeImage',
        ],
    },
    table: {
        contentToolbar: [
            'tableColumn', 'tableRow', 'mergeTableCells',
            'tableProperties', 'tableCellProperties', 'toggleTableCaption',
        ],
    },
    // previewsInData: iframe embed disimpan langsung dalam konten sehingga
    // video tampil di frontend tanpa JS tambahan.
    mediaEmbed: { previewsInData: true },
    simpleUpload: {
        uploadUrl: ckUploadUrl,
        headers: { 'X-CSRF-TOKEN': csrfToken },
    },
    fontFamily: { supportAllValues: true },
    fontSize: { options: [10, 12, 'default', 16, 18, 20, 24, 28], supportAllValues: true },
    link: { addTargetToExternalLinks: true },
};

// View lama memanggil window.ClassicEditor — arahkan ke build baru.
window.ClassicEditor = RomadanEditor;
window.RomadanEditor = RomadanEditor;

// Auto-init berbasis konvensi: setiap textarea ber-id "ckeditor*" atau
// berkelas .js-rich-editor otomatis jadi editor lengkap — menutup halaman
// yang dulu lupa/salah menginisialisasi editornya (mis. edit publikasi).
// Aman terhadap init ganda berkat RomadanEditor.create yang idempoten.
document.addEventListener('DOMContentLoaded', () => {
    document
        .querySelectorAll('textarea[id^="ckeditor"], textarea.js-rich-editor')
        .forEach((el) => {
            // CKEditor menyembunyikan textarea sumber (display:none) dan baru
            // menyalin isinya saat submit. Atribut `required` pada kontrol
            // tersembunyi-kosong membuat browser MEMBLOKIR submit tanpa pesan
            // ("invalid form control is not focusable") — wajib dicabut;
            // validasi wajib-isi tetap ditegakkan server di setiap controller.
            el.removeAttribute('required');

            RomadanEditor.create(el)
                .then((editor) => {
                    // Sinkronkan isi editor ke textarea secara realtime agar
                    // nilai form selalu terkini, bukan hanya saat submit.
                    editor.model.document.on('change:data', () => {
                        el.value = editor.getData();
                    });
                })
                .catch((e) => console.error(e));
        });
});

/* ---------------------------------------------------------------------------
 * Dropdown aksi baris tabel — delegasi klik permanen (bukan sisa kompatibilitas
 * Bootstrap). Baris data-table disisipkan lewat AJAX/Alpine `x-html`, jadi
 * Alpine tidak bisa memproses `x-data`/`@click` di dalamnya; delegasi vanilla
 * pada `document` inilah cara yang benar untuk markup yang disuntik begitu.
 * Dipakai oleh komponen `datatable-actions` dan `action-button(s)`.
 * ------------------------------------------------------------------------- */
document.addEventListener('click', (e) => {
    const toggleDropdown = e.target.closest('[data-bs-toggle="dropdown"]');
    document.querySelectorAll('.dropdown-menu.show').forEach((menu) => {
        if (!toggleDropdown || !menu.parentElement.contains(toggleDropdown)) menu.classList.remove('show');
    });
    if (toggleDropdown) {
        e.preventDefault();
        toggleDropdown.parentElement.querySelector('.dropdown-menu')?.classList.toggle('show');
    }
});

document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    document.querySelectorAll('.dropdown-menu.show').forEach((m) => m.classList.remove('show'));
});

/* ---------------------------------------------------------------------------
 * Pemuat gambar blob ter-otentikasi (img[data-blob-src]) — path storage asli
 * tidak pernah tampil di HTML; gambar diambil via fetch lalu ditampilkan
 * sebagai blob URL. MutationObserver menangkap baris DataTables hasil AJAX.
 * ------------------------------------------------------------------------- */
const blobCache = new Map();

async function loadBlobImage(img) {
    const src = img.dataset.blobSrc;
    if (!src || img.dataset.blobLoaded) return;
    img.dataset.blobLoaded = '1';

    try {
        if (!blobCache.has(src)) {
            const res = await fetch(src, { credentials: 'same-origin' });
            if (!res.ok) throw new Error('blob fetch failed');
            blobCache.set(src, URL.createObjectURL(await res.blob()));
        }
        img.src = blobCache.get(src);
    } catch {
        img.dataset.blobLoaded = '';
    }
}

function scanBlobImages(root) {
    root.querySelectorAll?.('img[data-blob-src]').forEach(loadBlobImage);
}

document.addEventListener('DOMContentLoaded', () => {
    scanBlobImages(document);

    new MutationObserver((mutations) => {
        mutations.forEach((m) => {
            m.addedNodes.forEach((node) => {
                if (node.nodeType !== 1) return;
                if (node.matches?.('img[data-blob-src]')) loadBlobImage(node);
                scanBlobImages(node);
            });
        });
    }).observe(document.body, { childList: true, subtree: true });
});

/* ---------------------------------------------------------------------------
 * Alpine — tabel server-side native (pengganti jQuery DataTables)
 * Bicara dengan protokol JSON Yajra/DataTables yang sama persis
 * (draw/start/length/search/order/columns) sehingga controller tidak perlu
 * diubah sama sekali — hanya konsumen frontend-nya yang diganti.
 * ------------------------------------------------------------------------- */
Alpine.data('serverTable', ({ ajaxUrl, columns, order = [0, 'asc'], perPage = 10 }) => ({
    ajaxUrl,
    columns,
    rows: [],
    loading: true,
    search: '',
    sortCol: order[0] ?? 0,
    sortDir: order[1] ?? 'asc',
    page: 0,
    perPage,
    recordsTotal: 0,
    recordsFiltered: 0,
    draw: 0,
    extra: {},

    get totalPages() {
        return this.perPage > 0 ? Math.max(1, Math.ceil(this.recordsFiltered / this.perPage)) : 1;
    },

    get infoText() {
        if (this.recordsFiltered === 0) return 'Tidak ada data';
        const start = this.page * this.perPage + 1;
        const end = Math.min(this.recordsFiltered, start + this.perPage - 1);
        const filtered = this.recordsFiltered !== this.recordsTotal ? ` (disaring dari ${this.recordsTotal} data)` : '';

        return `Menampilkan ${start}-${end} dari ${this.recordsFiltered} data${filtered}`;
    },

    get pageNumbers() {
        const span = 2;
        const start = Math.max(0, Math.min(this.page - span, this.totalPages - (span * 2 + 1)));
        const end = Math.min(this.totalPages - 1, start + span * 2);
        const pages = [];
        for (let p = Math.max(0, start); p <= end; p++) pages.push(p);

        return pages;
    },

    // Akses nilai kolom yang bisa berupa path relasi bertitik (mis. "tipe.nama_tipe"
    // dari eager-load Eloquent) selain nama kolom datar.
    col(row, path) {
        return path.split('.').reduce((acc, key) => (acc == null ? acc : acc[key]), row);
    },

    sortBy(colIndex) {
        if (this.columns[colIndex]?.orderable === false) return;
        this.sortDir = this.sortCol === colIndex ? (this.sortDir === 'asc' ? 'desc' : 'asc') : 'asc';
        this.sortCol = colIndex;
        this.page = 0;
        this.load();
    },

    goTo(page) {
        if (page < 0 || page > this.totalPages - 1 || page === this.page) return;
        this.page = page;
        this.load();
    },

    async load() {
        this.loading = true;
        this.draw++;
        const requestDraw = this.draw;

        const params = new URLSearchParams();
        params.set('draw', requestDraw);
        params.set('start', this.page * this.perPage);
        params.set('length', this.perPage);
        params.set('search[value]', this.search);
        params.set('search[regex]', 'false');
        this.columns.forEach((col, i) => {
            params.set(`columns[${i}][data]`, col.data);
            params.set(`columns[${i}][name]`, col.name ?? col.data);
            params.set(`columns[${i}][searchable]`, col.searchable === false ? 'false' : 'true');
            params.set(`columns[${i}][orderable]`, col.orderable === false ? 'false' : 'true');
            params.set(`columns[${i}][search][value]`, '');
            params.set(`columns[${i}][search][regex]`, 'false');
        });
        params.set('order[0][column]', this.sortCol);
        params.set('order[0][dir]', this.sortDir);
        Object.entries(this.extra).forEach(([key, value]) => params.set(key, value));

        try {
            const res = await fetch(`${this.ajaxUrl}?${params.toString()}`, {
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    Accept: 'application/json',
                },
            });
            if (!res.ok) throw new Error(`serverTable: HTTP ${res.status}`);
            const json = await res.json();
            if (json.draw < requestDraw) return; // balasan basi (respons di luar urutan)

            this.rows = json.data ?? [];
            this.recordsTotal = json.recordsTotal ?? 0;
            this.recordsFiltered = json.recordsFiltered ?? 0;
        } catch (e) {
            console.error(e);
            this.rows = [];
        } finally {
            this.loading = false;
        }
    },

    init() {
        this.load();
    },
}));

/* ---------------------------------------------------------------------------
 * Alpine — preview gambar file input (pengganti ~15 salinan previewImages())
 * ------------------------------------------------------------------------- */
Alpine.data('filePreview', (initialUrl = null) => ({
    preview: initialUrl || null,

    onChange(e) {
        const file = e.target.files?.[0];
        if (!file) return;
        if (this.preview?.startsWith('blob:')) URL.revokeObjectURL(this.preview);
        this.preview = URL.createObjectURL(file);
    },
}));

/* ---------------------------------------------------------------------------
 * Alpine — preview beberapa gambar sekaligus, gambar pertama ditandai "utama"
 * (dipakai form tambah publikasi/konten dengan input file[] multiple)
 * ------------------------------------------------------------------------- */
Alpine.data('multiImagePreview', () => ({
    previews: [],

    onChange(e) {
        this.previews.forEach((p) => URL.revokeObjectURL(p.url));
        this.previews = Array.from(e.target.files || []).map((file, i) => ({
            url: URL.createObjectURL(file),
            primary: i === 0,
        }));
    },
}));

/* ---------------------------------------------------------------------------
 * Alpine — live preview URL video YouTube/Vimeo (visi-misi, layanan)
 * ------------------------------------------------------------------------- */
Alpine.data('videoUrlPreview', (initialUrl = '') => ({
    url: initialUrl || '',

    get embedHtml() {
        if (!this.url) return '';

        const yt = this.url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([\w-]{11})/);
        if (yt) {
            return `<iframe width="100%" height="315" src="https://www.youtube.com/embed/${yt[1]}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
        }

        const vimeo = this.url.match(/vimeo\.com\/(\d+)/);
        if (vimeo) {
            return `<iframe src="https://player.vimeo.com/video/${vimeo[1]}" width="100%" height="315" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>`;
        }

        return '<div class="alert alert-warning">URL video tidak valid atau tidak didukung.</div>';
    },
}));

/* ---------------------------------------------------------------------------
 * Alpine — chrome CMS (sidebar, topbar, dark mode)
 * ------------------------------------------------------------------------- */
Alpine.plugin(collapse);
Alpine.plugin(focus);

const desktopMql = window.matchMedia('(min-width: 1024px)');

Alpine.store('cms', {
    sidebarOpen: false, // drawer mobile (mobile: <lg)
    sidebarCollapsed: localStorage.getItem('cms-sidebar-collapsed') === '1', // preferensi rail desktop
    isDesktopWidth: desktopMql.matches,
    dark: localStorage.getItem('cms-theme') === 'dark',

    // Satu-satunya sumber kebenaran "apakah rail sedang diciutkan" — selalu
    // false di mobile (drawer selalu tampil penuh saat dibuka).
    get isRailCollapsed() {
        return this.sidebarCollapsed && this.isDesktopWidth;
    },

    // Satu tombol toggle, dua arti tergantung lebar layar: buka/tutup drawer
    // di mobile, ciutkan/lebarkan rail di desktop — supaya tidak ada dua
    // kontrol berbeda yang tampil sekaligus untuk fungsi yang tumpang tindih.
    toggleSidebar() {
        if (this.isDesktopWidth) {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('cms-sidebar-collapsed', this.sidebarCollapsed ? '1' : '0');
        } else {
            this.sidebarOpen = !this.sidebarOpen;
        }
    },
    expandSidebar() {
        this.sidebarCollapsed = false;
        localStorage.setItem('cms-sidebar-collapsed', '0');
    },
    toggleDark() {
        this.dark = !this.dark;
        localStorage.setItem('cms-theme', this.dark ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', this.dark);
    },
});

desktopMql.addEventListener('change', (e) => {
    Alpine.store('cms').isDesktopWidth = e.matches;
    if (e.matches) Alpine.store('cms').sidebarOpen = false; // resize ke desktop: tutup drawer mobile
});

// Terapkan tema tersimpan sedini mungkin (sebelum Alpine render)
document.documentElement.classList.toggle('dark', localStorage.getItem('cms-theme') === 'dark');

window.Alpine = Alpine;
Alpine.start();
