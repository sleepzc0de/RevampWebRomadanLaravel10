@extends('layouts.webromadan_backend.master_layout')

@section('css')
@endsection

@section('script_atas')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/extensions/responsive.min.js')}}"></script>
@endsection

@section('script_bawah')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (!$().DataTable) {
        console.warn('Warning - datatables.min.js is not loaded.');
        return;
    }

    $.extend($.fn.dataTable.defaults, {
        autoWidth: false,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"datatable-header"f<"ms-sm-auto"l>><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span class="me-3">Cari Data:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
            searchPlaceholder: 'Cari...',
            lengthMenu: '<span class="me-3">Tampilkan:</span> _MENU_',
            paginate: { first: 'First', last: 'Last', next: '&rarr;', previous: '&larr;' },
        },
    });

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    const table = $('.datatable-visitor').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('visitors.index') }}",
            data: function (d) {
                d.device_type = $('#filter-device-type').val();
                d.show_bot = $('#filter-show-bot').is(':checked') ? 1 : 0;
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '10px', orderable: false, searchable: false },
            { data: 'waktu', name: 'created_at' },
            { data: 'ip_address', name: 'ip_address' },
            { data: 'url', name: 'url' },
            { data: 'perangkat', name: 'browser', orderable: false },
            { data: 'referrer', name: 'referrer' },
            { data: 'status', name: 'is_bot', orderable: false, searchable: false },
        ],
        order: [[1, 'desc']],
    });

    $('#filter-device-type, #filter-show-bot').on('change', function () {
        table.draw();
    });
});
</script>

{{-- ===== Chart.js — tren kunjungan 7 hari ===== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        if (typeof Chart === 'undefined') return;
        Chart.defaults.font.family = "'Plus Jakarta Sans','Inter',sans-serif";
        Chart.defaults.color = '#5b6b7d';

        const trend = @json($dailyTrend);

        const ctxTrend = document.getElementById('rmVisitorTrend');
        if (ctxTrend) {
            const g = ctxTrend.getContext('2d').createLinearGradient(0, 0, 0, 220);
            g.addColorStop(0, 'rgba(15,95,174,.28)');
            g.addColorStop(1, 'rgba(15,95,174,0)');
            new Chart(ctxTrend, {
                type: 'line',
                data: { labels: trend.labels, datasets: [{
                    label: 'Kunjungan', data: trend.data,
                    borderColor: '#0f5fae', backgroundColor: g, borderWidth: 3,
                    fill: true, tension: .4, pointBackgroundColor: '#0f5fae',
                    pointRadius: 4, pointHoverRadius: 6,
                }]},
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#eef2f6' } }, x: { grid: { display: false } } }
                }
            });
        }
    })();
</script>
@endsection

@section('content')
<style>
    .rm-kpi { border: 0; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 10px rgba(16,32,46,.06); transition: transform .2s ease, box-shadow .2s ease; }
    .rm-kpi:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(15,95,174,.14); }
    .rm-kpi .rm-ico { width: 46px; height: 46px; border-radius: 12px; display:flex; align-items:center; justify-content:center; font-size: 1.35rem; }
    .rm-kpi h2 { font-weight: 800; letter-spacing: -.02em; }
    .rm-accent { height: 4px; }
    .rm-list-item { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px dashed #eef2f6; }
    .rm-list-item:last-child { border-bottom:0; }
    .rm-rank { width:26px;height:26px;border-radius:8px;background:#eef4fc;color:#0f5fae;font-weight:700;display:flex;align-items:center;justify-content:center;font-size:.8rem; flex:none;}
</style>

{{-- ===== KPI utama ===== --}}
<div class="row g-3 mb-1">
    @php
        $cards = [
            ['label' => 'Total Kunjungan', 'value' => $kpi['total'], 'icon' => 'ph-users-three', 'c' => '#0f5fae'],
            ['label' => 'Kunjungan Hari Ini', 'value' => $kpi['today'], 'icon' => 'ph-calendar-check', 'c' => '#12a150'],
            ['label' => 'Pengunjung Unik Hari Ini', 'value' => $kpi['unique_today'], 'icon' => 'ph-fingerprint', 'c' => '#7a4dd1'],
            ['label' => 'Terdeteksi Bot', 'value' => $kpi['bots'], 'icon' => 'ph-robot', 'c' => '#e8a400'],
        ];
    @endphp
    @foreach($cards as $card)
    <div class="col-xl-3 col-sm-6">
        <div class="card rm-kpi h-100">
            <div class="rm-accent" style="background: {{ $card['c'] }};"></div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="mb-0">{{ number_format($card['value'], 0, ',', '.') }}</h2>
                        <span class="text-muted">{{ $card['label'] }}</span>
                    </div>
                    <div class="rm-ico" style="background: {{ $card['c'] }}1a; color: {{ $card['c'] }};">
                        <i class="{{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
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
                    <div class="rm-list-item">
                        <div class="rm-rank">{{ $loop->iteration }}</div>
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
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0">Log Pengunjung</h5>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="form-check">
                <input type="checkbox" id="filter-show-bot" class="form-check-input">
                <label for="filter-show-bot" class="form-check-label">Tampilkan Bot</label>
            </div>
            <select id="filter-device-type" class="form-select form-select-sm" style="width:auto;">
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
        </div>
    </div>
    @include('layouts.webromadan_backend.session_notif')

    <table class="table datatable-visitor table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Waktu</th>
                <th>IP Address</th>
                <th>Halaman</th>
                <th>Perangkat / Browser</th>
                <th>Referrer</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
@endsection
