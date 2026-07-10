@extends('layouts.webromadan_backend.master_layout')

@section('css')
@endsection


@section('script_atas')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/extensions/responsive.min.js')}}"></script>
@endsection

@section('script_bawah')
{{-- <script src="{{asset('webromadan/be/demo/pages/datatables_basic.js')}}"></script> --}}

<script>
    /* ------------------------------------------------------------------------------
 *
 *  # Basic datatables
 *
 *  Demo JS code for datatable_basic.html page
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

const DatatableBasic = function() {


    //
    // Setup module components
    //

    // Basic Datatable examples
    const _componentDatatableBasic = function() {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend( $.fn.dataTable.defaults, {
            autoWidth: false,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            columnDefs: [{
                orderable: false,
                width: 100,
                targets: [0]
            }],
            dom: '<"datatable-header"f<"ms-sm-auto"B><"ms-sm-auto"l>><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Cari Data:</span> <div class=" form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Cari...',
                lengthMenu: '<span class="me-3">Tampilkan:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' },

            },
        });

        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Basic datatable
        $('.datatable-basic').DataTable({
            autoWidth: true,
            scrollY: 200,
            scrollX: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('home') }}",
            columns: [
			{data: 'created_at',name:'created_at'},
			{data: 'tipe.nama_tipe', name:'tipe.nama_tipe', orderable:false,searchable:false},
			{data: 'judul',name:'judul'},
			{data: 'penulis',name:'penulis'},
            {data: 'pengedit',name:'pengedit'},


            // { data:'DT_RowIndex', name:'DT_RowIndex', width:'10px',orderable:false,searchable:false},
            //  {data: 'judul',name:'judul'},
            // {data: 'sub_judul',name:'sub_judul'},
            // {data: 'image_publikasi',name:'image_publikasi',orderable:false, searchable:false},
            // {data: 'tipe.nama_tipe', name:'tipe.nama_tipe', orderable:false,searchable:false},
            // {data: 'kategori.nama_kategori',name:'kategori.nama_kategori',orderable:false,searchable:false},
            // {data: 'status.nama_status',name:'status.nama_status',orderable:false,searchable:false},
            // {data: 'penulis',name:'penulis'},
            // {data: 'pengedit',name:'pengedit'},
            // {data: 'created_at',name:'created_at'},
            // {data: 'opsi',name:'opsi',orderable:false,searchable:false},

            // {data: 'action', name: 'action', orderable: false, searchable:false},
            ],
            order: [[0, 'asc']],
            buttons: {
                dom:{
                    button: {
                        className: ''
                    },
                },
                buttons: [
                    {
                        extend: 'excelHtml5',
                        className: 'btn btn-outline-success',
                        text: '<i class="far fa-file-excel me-2"></i> Excel',
                        exportOptions: {
                            columns: ':visible',

                        }
                    },
                    // {
                    //     extend: 'pdfHtml5',
                    //     className: 'btn btn-outline-danger',
                    //     text: '<i class="far fa-file-pdf me-2"></i> Pdf',
                    //     exportOptions: {
                    //         columns: [0, 1, 2, 5]
                    //     }
                    // },
                    {
                        extend: 'colvis',
                        text: '<i class="ph-squares-four"></i>',
                        className: 'btn btn-outline-info dropdown-toggle',
                        collectionLayout: 'fixed four-column'
                    }
                ]
            },
        });


        // Scrollable datatable
        // const table = $('.datatable-scroll-y').DataTable({
        //     autoWidth: true,
        //     scrollY: 300
        // });

        // Resize scrollable table when sidebar width changes
        $('.sidebar-control').on('click', function() {
            table.columns.adjust().draw();
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentDatatableBasic();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    DatatableBasic.init();
});
</script>


@endsection


@section('content')

<style>
    .rm-kpi { border: 0; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 10px rgba(16,32,46,.06); transition: transform .2s ease, box-shadow .2s ease; }
    .rm-kpi:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(15,95,174,.14); }
    .rm-kpi .rm-ico { width: 46px; height: 46px; border-radius: 12px; display:flex; align-items:center; justify-content:center; font-size: 1.35rem; }
    .rm-kpi h2 { font-weight: 800; letter-spacing: -.02em; }
    .rm-accent { height: 4px; }
    .rm-chip { border: 1px solid #e6ebf1; border-radius: 12px; padding: 12px 14px; display:flex; align-items:center; gap:10px; background:#fff; transition: box-shadow .2s ease; }
    .rm-chip:hover { box-shadow: 0 6px 16px rgba(16,32,46,.08); }
    .rm-list-item { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px dashed #eef2f6; }
    .rm-list-item:last-child { border-bottom:0; }
    .rm-rank { width:26px;height:26px;border-radius:8px;background:#eef4fc;color:#0f5fae;font-weight:700;display:flex;align-items:center;justify-content:center;font-size:.8rem; flex:none;}
    .rm-badge { font-size:.7rem;font-weight:600;padding:3px 9px;border-radius:999px; }
    /* Ikon di dalam chip/kpi selalu center & tidak gepeng */
    .rm-ico i { line-height:1; }
    /* Tombol Aksi Cepat modern */
    .rm-actions { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .rm-action { display:flex; align-items:center; gap:11px; padding:12px; border:1px solid #e9eef4; border-radius:13px; background:#fff; text-decoration:none; color:#16202e; transition:transform .18s ease, box-shadow .22s ease, border-color .18s ease; }
    .rm-action:hover { transform:translateY(-3px); box-shadow:0 10px 22px rgba(16,32,46,.12); border-color:var(--ac,#0f5fae); }
    .rm-action-ico { flex:none; width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; color:var(--ac,#0f5fae); background:color-mix(in srgb, var(--ac,#0f5fae) 12%, #fff); }
    .rm-action:hover .rm-action-ico { background:var(--ac,#0f5fae); color:#fff; }
    .rm-action .lbl { font-weight:700; font-size:.92rem; line-height:1.15; }
    .rm-action .sub { color:#8592a3; font-size:.72rem; }
    @media (max-width: 400px) { .rm-actions { grid-template-columns:1fr; } }
</style>

{{-- ===== KPI utama ===== --}}
<div class="row g-3 mb-1">
    @php
        $cards = [
            ['label' => 'Total Publikasi', 'value' => $kpi['publikasi_total'], 'icon' => 'ph-newspaper', 'c' => '#0f5fae'],
            ['label' => 'Published',       'value' => $kpi['published'],       'icon' => 'ph-check-circle', 'c' => '#12a150'],
            ['label' => 'Draft',           'value' => $kpi['draft'],           'icon' => 'ph-pencil-simple-line', 'c' => '#e8a400'],
            ['label' => 'Total Views',     'value' => $kpi['views_total'],     'icon' => 'ph-eye', 'c' => '#7a4dd1'],
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
                    <div class="rm-list-item">
                        <span class="rm-rank">{{ $i + 1 }}</span>
                        <div class="flex-fill text-truncate">
                            <div class="fw-semibold text-truncate">{{ $p->judul }}</div>
                            <small class="text-muted">{{ ucfirst($p->nama_tipe ?? '-') }}</small>
                        </div>
                        <span class="text-primary fw-semibold"><i class="ph-eye me-1"></i>{{ number_format($p->views, 0, ',', '.') }}</span>
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
                    <div class="rm-list-item">
                        <div class="flex-fill text-truncate">
                            <div class="fw-semibold text-truncate">{{ $p->judul }}</div>
                            <small class="text-muted">{{ ucfirst($p->nama_tipe ?? '-') }} &middot; {{ \Carbon\Carbon::parse($p->created_at)->locale('id')->isoFormat('D MMM Y') }}</small>
                        </div>
                        <span class="rm-badge {{ $pub ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }}">
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
                    ['Peraturan', $kpi['peraturan'], 'ph-scroll', '#0f5fae'],
                    ['Aplikasi', $kpi['aplikasi'], 'ph-squares-four', '#12a150'],
                    ['FAQ', $kpi['faq'], 'ph-question', '#7a4dd1'],
                    ['Layanan', $kpi['layanan'], 'ph-headset', '#e8a400'],
                    ['Users', $kpi['users'], 'ph-users-three', '#0b7285'],
                    ['Sampah', $kpi['trashed'], 'ph-trash', '#c0392b'],
                ];
            @endphp
            @foreach($chips as [$label, $val, $icon, $c])
            <div class="col-sm-4 col-6">
                <div class="rm-chip">
                    <div class="rm-ico" style="background: {{ $c }}1a; color: {{ $c }}; width:38px;height:38px;border-radius:10px;font-size:1.05rem;">
                        <i class="{{ $icon }}"></i>
                    </div>
                    <div><div class="fw-bold" style="font-size:1.1rem;">{{ number_format($val, 0, ',', '.') }}</div><small class="text-muted">{{ $label }}</small></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @if(auth()->user()->hasRole(['ADMINISTRATOR', 'REDAKTUR', 'EDITOR','HUMAS']))
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Aksi Cepat</h5></div>
            <div class="card-body">
                <div class="rm-actions">
                    <a href="{{route('publikasi.create')}}" class="rm-action" style="--ac:#0f5fae">
                        <span class="rm-action-ico"><i class="ph-plus-circle"></i></span>
                        <span><span class="lbl d-block">Publikasi</span><span class="sub">Tambah konten</span></span>
                    </a>
                    <a href="{{route('faq.create')}}" class="rm-action" style="--ac:#7a4dd1">
                        <span class="rm-action-ico"><i class="ph-question"></i></span>
                        <span><span class="lbl d-block">FAQ</span><span class="sub">Tanya jawab</span></span>
                    </a>
                    @role('ADMINISTRATOR')
                    <a href="{{route('users.create')}}" class="rm-action" style="--ac:#0b7285">
                        <span class="rm-action-ico"><i class="ph-user-plus"></i></span>
                        <span><span class="lbl d-block">User</span><span class="sub">Kelola akun</span></span>
                    </a>
                    <a href="{{route('backups.index')}}" class="rm-action" style="--ac:#5b6b7d">
                        <span class="rm-action-ico"><i class="ph-database"></i></span>
                        <span><span class="lbl d-block">Backup</span><span class="sub">Cadangan data</span></span>
                    </a>
                    @endrole
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- ===== Content Activity (datatable — tetap seperti semula) ===== --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Content Activity</h5></div>
            <table class="table datatable-basic">
                <thead>
                    <tr>
                        <th>TANGGAL BUAT</th>
                        <th>TIPE</th>
                        <th>JUDUL</th>
                        <th>PEMBUAT</th>
                        <th>PENGEDIT</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===== Chart.js ===== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        if (typeof Chart === 'undefined') return;
        Chart.defaults.font.family = "'Plus Jakarta Sans','Inter',sans-serif";
        Chart.defaults.color = '#5b6b7d';

        const trend = @json($monthly);
        const byTipe = @json($byTipe);

        const ctxTrend = document.getElementById('rmTrend');
        if (ctxTrend) {
            const g = ctxTrend.getContext('2d').createLinearGradient(0, 0, 0, 220);
            g.addColorStop(0, 'rgba(15,95,174,.28)');
            g.addColorStop(1, 'rgba(15,95,174,0)');
            new Chart(ctxTrend, {
                type: 'line',
                data: { labels: trend.labels, datasets: [{
                    label: 'Publikasi', data: trend.data,
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

        const ctxTipe = document.getElementById('rmTipe');
        if (ctxTipe) {
            new Chart(ctxTipe, {
                type: 'doughnut',
                data: { labels: Object.keys(byTipe), datasets: [{
                    data: Object.values(byTipe),
                    backgroundColor: ['#0f5fae', '#f5b800', '#12a150'],
                    borderWidth: 0, hoverOffset: 6,
                }]},
                options: { responsive: true, maintainAspectRatio: false, cutout: '62%',
                    plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 14 } } } }
            });
        }
    })();
</script>

@endsection
