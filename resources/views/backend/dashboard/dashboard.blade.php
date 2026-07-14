@extends('layouts.webromadan_backend.master_layout')

@section('content')

<div class="mb-5 flex flex-wrap items-end justify-between gap-2">
    <div>
        <h1 class="mb-1 text-xl font-bold">Dashboard</h1>
        <p class="m-0 text-sm text-slate-500 dark:text-slate-400">Ringkasan aktivitas konten CMS Romadan.</p>
    </div>
    <span class="font-mono text-xs text-slate-400 dark:text-slate-500">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
</div>

{{-- ===== KPI utama ===== --}}
<div class="row g-3 mb-1">
    @php
        $cards = [
            ['label' => 'Total Publikasi', 'value' => $kpi['publikasi_total'], 'icon' => 'ph-newspaper', 'c' => 'var(--color-brand-500)'],
            ['label' => 'Published',       'value' => $kpi['published'],       'icon' => 'ph-check-circle', 'c' => '#059669'],
            ['label' => 'Draft',           'value' => $kpi['draft'],           'icon' => 'ph-pencil-simple-line', 'c' => '#d69e00'],
            ['label' => 'Total Views',     'value' => $kpi['views_total'],     'icon' => 'ph-eye', 'c' => '#64748b'],
        ];
    @endphp
    @foreach($cards as $card)
    <div class="col-xl-3 col-sm-6">
        <div class="cms-stat h-full" style="border-inline-start-color: {{ $card['c'] }};">
            <i class="{{ $card['icon'] }} cms-stat-icon"></i>
            <span class="cms-stat-label">{{ $card['label'] }}</span>
            <span class="cms-stat-value">{{ number_format($card['value'], 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach
</div>

{{-- ===== Chart tren + komposisi tipe ===== --}}
<div class="row g-3 mb-1">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Tren Publikasi (6 bulan terakhir)</h5>
                <span class="badge bg-primary bg-opacity-10 text-primary">Bulanan</span>
            </div>
            <div class="card-body">
                {{-- wrapper tinggi-tetap wajib untuk Chart.js responsive + maintainAspectRatio:false --}}
                <div style="position:relative; height:300px;"><canvas id="rmTrend"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Komposisi Tipe</h5></div>
            <div class="card-body">
                <div style="position:relative; height:260px;"><canvas id="rmTipe"></canvas></div>
            </div>
        </div>
    </div>
</div>

{{-- ===== Top viewed + terbaru ===== --}}
<div class="row g-3 mb-1">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Terpopuler (berdasarkan views)</h5></div>
            <div class="card-body">
                @forelse($topViewed as $i => $p)
                    <div class="cms-row-item">
                        <span class="cms-row-rank">{{ $i + 1 }}</span>
                        <div class="flex-fill text-truncate">
                            <div class="fw-semibold text-truncate">{{ $p->judul }}</div>
                            <small class="text-muted">{{ ucfirst($p->nama_tipe ?? '-') }}</small>
                        </div>
                        <span class="d-inline-flex align-items-center gap-1 font-mono text-xs font-semibold text-slate-500 dark:text-slate-400"><i class="ph-eye"></i>{{ number_format($p->views, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-muted mb-0">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Publikasi Terbaru</h5></div>
            <div class="card-body">
                @forelse($recent as $p)
                    @php $pub = strtolower($p->status) === 'published'; @endphp
                    <div class="cms-row-item">
                        <div class="flex-fill text-truncate">
                            <div class="fw-semibold text-truncate">{{ $p->judul }}</div>
                            <small class="text-muted">{{ ucfirst($p->nama_tipe ?? '-') }} &middot; {{ \Carbon\Carbon::parse($p->created_at)->locale('id')->isoFormat('D MMM Y') }}</small>
                        </div>
                        <span class="badge {{ $pub ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }}">
                            {{ $pub ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted mb-0">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ===== Statistik sekunder + aksi cepat ===== --}}
<div class="row g-3 mb-1">
    <div class="col-lg-8">
        <div class="row g-2">
            @php
                $chips = [
                    ['Peraturan', $kpi['peraturan'], 'ph-scroll'],
                    ['Aplikasi', $kpi['aplikasi'], 'ph-squares-four'],
                    ['FAQ', $kpi['faq'], 'ph-question'],
                    ['Layanan', $kpi['layanan'], 'ph-headset'],
                    ['Users', $kpi['users'], 'ph-users-three'],
                    ['Sampah', $kpi['trashed'], 'ph-trash'],
                ];
            @endphp
            @foreach($chips as [$label, $val, $icon])
            <div class="col-sm-4 col-6">
                <div class="cms-chip">
                    <i class="{{ $icon }} text-lg text-slate-400 dark:text-slate-500"></i>
                    <div><span class="cms-chip-value d-block">{{ number_format($val, 0, ',', '.') }}</span><small class="text-muted">{{ $label }}</small></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @if(auth()->user()->hasRole(['ADMINISTRATOR', 'REDAKTUR', 'EDITOR','HUMAS']))
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Aksi Cepat</h5></div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{route('publikasi.create')}}" class="cms-quick-link">
                    <i class="ph-plus-circle"></i>
                    <span><span class="lbl">Publikasi Baru</span><span class="sub">Tambah konten berita/warta/artikel</span></span>
                </a>
                <a href="{{route('faq.create')}}" class="cms-quick-link">
                    <i class="ph-question"></i>
                    <span><span class="lbl">FAQ Baru</span><span class="sub">Tambah tanya jawab</span></span>
                </a>
                @role('ADMINISTRATOR')
                <a href="{{route('users.create')}}" class="cms-quick-link">
                    <i class="ph-user-plus"></i>
                    <span><span class="lbl">Tambah User</span><span class="sub">Kelola akun tim</span></span>
                </a>
                <a href="{{route('backups.index')}}" class="cms-quick-link">
                    <i class="ph-database"></i>
                    <span><span class="lbl">Backup</span><span class="sub">Cadangan data sistem</span></span>
                </a>
                <a href="{{route('activity-log.index')}}" class="cms-quick-link">
                    <i class="ph-clock-counter-clockwise"></i>
                    <span><span class="lbl">Log Aktivitas</span><span class="sub">Audit trail perubahan</span></span>
                </a>
                @endrole
            </div>
        </div>
    </div>
    @endif
</div>

{{-- ===== Content Activity ===== --}}
<x-data-table
    title="Content Activity"
    :ajax="route('home')"
    :order="[0, 'asc']"
    :columns="[
        ['label' => 'Tanggal Buat', 'data' => 'created_at'],
        ['label' => 'Tipe', 'data' => 'tipe.nama_tipe', 'name' => 'tipe.nama_tipe', 'orderable' => false, 'searchable' => false],
        ['label' => 'Judul', 'data' => 'judul'],
        ['label' => 'Views', 'data' => 'views'],
        ['label' => 'Pembuat', 'data' => 'penulis'],
        ['label' => 'Pengedit', 'data' => 'pengedit'],
    ]"
/>

{{-- ===== Chart.js — garis tipis datar, tanpa gradient glow =====
     Menunggu DOMContentLoaded: backend.js (yang mengeset window.Chart) dimuat
     sebagai <script type="module">, yang selalu ditangguhkan (defer) dan baru
     jalan SETELAH parsing HTML selesai — kalau kode ini dijalankan langsung
     (inline, tanpa menunggu event), window.Chart belum ada dan chart gagal
     tampil tanpa error yang terlihat. --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Chart === 'undefined') return;
        const darkMode = document.documentElement.classList.contains('dark');
        Chart.defaults.font.family = "'Inter',sans-serif";
        Chart.defaults.color = darkMode ? '#94a3b8' : '#64748b';

        const trend = @json($monthly);
        const byTipe = @json($byTipe);
        const gridColor = darkMode ? 'rgba(255,255,255,.06)' : '#f1f5f9';

        const ctxTrend = document.getElementById('rmTrend');
        if (ctxTrend) {
            new Chart(ctxTrend, {
                type: 'line',
                data: { labels: trend.labels, datasets: [{
                    label: 'Publikasi', data: trend.data,
                    borderColor: '#0f5fae', backgroundColor: 'transparent', borderWidth: 2,
                    fill: false, tension: .25, pointBackgroundColor: '#0f5fae',
                    pointRadius: 3, pointHoverRadius: 5,
                }]},
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: gridColor } }, x: { grid: { display: false } } }
                }
            });
        }

        const ctxTipe = document.getElementById('rmTipe');
        if (ctxTipe) {
            new Chart(ctxTipe, {
                type: 'doughnut',
                data: { labels: Object.keys(byTipe), datasets: [{
                    data: Object.values(byTipe),
                    backgroundColor: ['#0f5fae', '#94a3b8', '#f5b800'],
                    borderWidth: 0, hoverOffset: 4,
                }]},
                options: { responsive: true, maintainAspectRatio: false, cutout: '68%',
                    plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 6, padding: 14 } } } }
            });
        }
    });
</script>

@endsection
