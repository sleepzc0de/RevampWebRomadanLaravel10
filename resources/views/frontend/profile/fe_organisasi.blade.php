@extends('layouts.webromadan_frontend.fe_master')

@section('title', 'Struktur Organisasi — Biro Manajemen BMN dan Pengadaan')

@section('content')
<section class="bg-white py-16 sm:py-20 dark:bg-navy-950">
    <div class="fe-container">
        <div class="text-center">
            <span class="fe-eyebrow justify-center">Profil</span>
            <h1 class="mt-3 text-3xl font-extrabold text-navy-800 sm:text-4xl dark:text-white">Struktur Organisasi</h1>
        </div>

        @if (session('error'))
            <div class="mx-auto mt-8 max-w-2xl rounded-xl border border-red-200 bg-red-50 p-4 text-center text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        @if ($jabatanRoot)
            {{-- Layar kecil: daftar vertikal bertingkat (tanpa scroll horizontal) --}}
            <div class="mt-12 space-y-3 md:hidden">
                @include('frontend.profile._struktur-jabatan-node-mobile', ['node' => $jabatanRoot, 'byParent' => $jabatanByParent])
            </div>
        @else
            <x-fe.empty-state class="mt-12" icon="fa-sitemap" title="Belum ada data" text="Data struktur organisasi belum tersedia, silakan hubungi administrator." />
        @endif
    </div>

    @if ($jabatanRoot)
        {{-- Layar sedang ke atas: bagan pohon horizontal, diskalakan otomatis
             supaya seluruh bagan muat dalam satu layar tanpa scroll. Dibiarkan
             lebih lebar dari fe-container (bukan teks bacaan) supaya ruang
             yang tersedia lebih besar & skala tidak terlalu kecil. --}}
        <div class="mt-12 hidden px-4 md:block">
            <div id="org-tree-viewport" class="relative mx-auto max-w-[1800px] overflow-hidden">
                <ul id="org-tree" class="org-tree min-w-max">
                    @include('frontend.profile._struktur-jabatan-node', ['node' => $jabatanRoot, 'byParent' => $jabatanByParent])
                </ul>
            </div>
        </div>
    @endif
</section>

@push('scripts')
<script>
    // Bagan bisa lebih besar dari layar — skalakan seluruh pohon (bukan
    // scroll) supaya semuanya kelihatan sekaligus dalam satu layar.
    (function () {
        function fitOrgTree() {
            const viewport = document.getElementById('org-tree-viewport');
            const tree = document.getElementById('org-tree');
            if (!viewport || !tree) return;

            tree.style.transform = 'none';
            tree.style.marginLeft = '0px';
            viewport.style.height = 'auto';

            const naturalWidth = tree.scrollWidth;
            const naturalHeight = tree.scrollHeight;
            const availableWidth = viewport.clientWidth;
            const availableHeight = Math.max(window.innerHeight * 0.7, 340);

            const scale = Math.min(1, availableWidth / naturalWidth, availableHeight / naturalHeight);
            const offsetX = Math.max(0, (availableWidth - naturalWidth * scale) / 2);

            tree.style.transformOrigin = 'top left';
            tree.style.transform = 'scale(' + scale + ')';
            tree.style.marginLeft = offsetX + 'px';
            viewport.style.height = Math.ceil(naturalHeight * scale) + 'px';
        }

        let resizeTimer;
        function scheduleFit() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(fitOrgTree, 150);
        }

        document.addEventListener('DOMContentLoaded', fitOrgTree);
        window.addEventListener('resize', scheduleFit);
    })();
</script>
@endpush
@endsection
