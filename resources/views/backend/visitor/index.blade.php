@extends('layouts.webromadan_backend.master_layout')

@section('content')
{{-- ===== KPI utama ===== --}}
<div class="row g-3 mb-1">
    @php
        $cards = [
            ['label' => 'Total Kunjungan', 'value' => $kpi['total'], 'icon' => 'ph-users-three', 'c' => 'var(--color-brand-500)'],
            ['label' => 'Kunjungan Hari Ini', 'value' => $kpi['today'], 'icon' => 'ph-calendar-check', 'c' => '#059669'],
            ['label' => 'Pengunjung Unik Hari Ini', 'value' => $kpi['unique_today'], 'icon' => 'ph-fingerprint', 'c' => '#64748b'],
            ['label' => 'Terdeteksi Bot', 'value' => $kpi['bots'], 'icon' => 'ph-robot', 'c' => '#d69e00'],
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

{{-- ===== Chart tren + halaman terpopuler ===== --}}
<div class="row g-3 mb-1">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Tren Kunjungan (7 hari terakhir)</h5>
                <span class="badge bg-primary bg-opacity-10 text-primary">Harian</span>
            </div>
            <div class="card-body">
                <div style="position:relative; height:280px;"><canvas id="rmVisitorTrend"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Halaman Terpopuler (30 hari)</h5>
            </div>
            <div class="card-body">
                @forelse($topPages as $url => $count)
                    @php $label = $url === '/' ? '/' : '/'.ltrim($url, '/'); @endphp
                    <div class="cms-row-item">
                        <div class="cms-row-rank">{{ $loop->iteration }}</div>
                        <div class="flex-fill text-truncate" title="{{ $label }}">{{ $label }}</div>
                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ number_format($count, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-muted mb-0">Belum ada data kunjungan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ===== Tabel detail kunjungan ===== --}}
<x-data-table
    title="Log Pengunjung"
    :ajax="route('visitors.index')"
    search-placeholder="Cari kunjungan..."
    :order="[1, 'desc']"
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Waktu', 'data' => 'waktu', 'name' => 'created_at'],
        ['label' => 'IP Address', 'data' => 'ip_address'],
        ['label' => 'Halaman', 'data' => 'url'],
        ['label' => 'Perangkat / Browser', 'data' => 'perangkat', 'name' => 'browser', 'orderable' => false],
        ['label' => 'Referrer', 'data' => 'referrer'],
        ['label' => 'Status', 'data' => 'status', 'name' => 'is_bot', 'orderable' => false, 'searchable' => false, 'raw' => true],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>

    <x-slot:headerActions>
        <label class="form-check flex items-center gap-1.5">
            <input type="checkbox" class="form-check-input" x-model="extra.show_bot" @change="page = 0; load()">
            <span class="form-check-label">Tampilkan Bot</span>
        </label>
        <select class="form-select form-select-sm" style="width:auto;" x-model="extra.device_type" @change="page = 0; load()">
            <option value="">Semua Perangkat</option>
            <option value="Desktop">Desktop</option>
            <option value="Mobile">Mobile</option>
            <option value="Tablet">Tablet</option>
        </select>
        <a href="{{ route('visitors.export') }}" class="btn btn-sm btn-outline-success">
            <i class="ph-file-xls"></i> Ekspor Excel
        </a>
        <form action="{{ route('visitors.clean') }}" method="post" onsubmit="return confirm('Hapus semua data pengunjung lebih dari 90 hari?');">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="ph-broom"></i> Bersihkan Data Lama
            </button>
        </form>
    </x-slot:headerActions>
</x-data-table>

{{-- ===== Chart.js — tren kunjungan 7 hari (garis tipis, tanpa gradient) =====
     Menunggu DOMContentLoaded karena backend.js (window.Chart) dimuat sebagai
     <script type="module"> yang selalu ditangguhkan (defer). --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Chart === 'undefined') return;
        const darkMode = document.documentElement.classList.contains('dark');
        Chart.defaults.font.family = "'Inter',sans-serif";
        Chart.defaults.color = darkMode ? '#94a3b8' : '#64748b';

        const trend = @json($dailyTrend);
        const gridColor = darkMode ? 'rgba(255,255,255,.06)' : '#f1f5f9';

        const ctxTrend = document.getElementById('rmVisitorTrend');
        if (ctxTrend) {
            new Chart(ctxTrend, {
                type: 'line',
                data: { labels: trend.labels, datasets: [{
                    label: 'Kunjungan', data: trend.data,
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
    });
</script>
@endsection
