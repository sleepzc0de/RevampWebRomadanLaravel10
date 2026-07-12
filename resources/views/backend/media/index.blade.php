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
            searchPlaceholder: 'Cari nama file...',
            lengthMenu: '<span class="me-3">Tampilkan:</span> _MENU_',
            paginate: { first: 'First', last: 'Last', next: '&rarr;', previous: '&larr;' },
        },
    });

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    const table = $('.datatable-media').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('media.index') }}",
            data: function (d) {
                d.type = $('#filter-type').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '10px', orderable: false, searchable: false },
            { data: 'preview', name: 'preview', orderable: false, searchable: false, width: '70px' },
            { data: 'filename', name: 'filename' },
            { data: 'ukuran', name: 'size' },
            { data: 'digunakan', name: 'digunakan', orderable: false, searchable: false },
            { data: 'waktu', name: 'created_at' },
            { data: 'opsi', name: 'opsi', orderable: false, searchable: false },
        ],
        order: [[5, 'desc']],
    });

    $('#filter-type').on('change', function () {
        table.draw();
    });

    $(document).on('click', '.media-delete-btn', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#deleteMediaName').text(name);
        $('#deleteMediaForm').attr('action', "{{ route('media.destroy', 'REPLACE_ID') }}".replace('REPLACE_ID', id));
        const modal = new bootstrap.Modal(document.getElementById('deleteMediaModal'));
        modal.show();
    });
});
</script>
@endsection

@section('content')
<style>
    .rm-kpi { border: 0; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 10px rgba(16,32,46,.06); }
    .rm-kpi .rm-ico { width: 46px; height: 46px; border-radius: 12px; display:flex; align-items:center; justify-content:center; font-size: 1.35rem; }
    .rm-kpi h2 { font-weight: 800; letter-spacing: -.02em; }
    .rm-accent { height: 4px; }
</style>

<div class="row g-3 mb-1">
    @php
        $cards = [
            ['label' => 'Total File', 'value' => $kpi['total'], 'icon' => 'ph-folders', 'c' => '#0f5fae'],
            ['label' => 'Gambar', 'value' => $kpi['images'], 'icon' => 'ph-image', 'c' => '#12a150'],
            ['label' => 'Dokumen', 'value' => $kpi['documents'], 'icon' => 'ph-file-text', 'c' => '#e8a400'],
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
    <div class="col-xl-3 col-sm-6">
        <div class="card rm-kpi h-100">
            <div class="rm-accent" style="background: #7a4dd1;"></div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="mb-0">{{ $kpi['total_size'] }}</h2>
                        <span class="text-muted">Total Ukuran</span>
                    </div>
                    <div class="rm-ico" style="background: #7a4dd11a; color: #7a4dd1;">
                        <i class="ph-hard-drives"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0">Media Library</h5>
        <div class="d-flex align-items-center gap-2">
            <select id="filter-type" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Tipe</option>
                <option value="image">Gambar</option>
            </select>
            <form action="{{ route('media.sync') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="ph-arrows-clockwise"></i> Sinkronkan Sekarang
                </button>
            </form>
        </div>
    </div>
    @include('layouts.webromadan_backend.session_notif')

    <table class="table datatable-media table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Preview</th>
                <th>Nama File</th>
                <th>Ukuran</th>
                <th>Status</th>
                <th>Diunggah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Modal konfirmasi hapus -->
<div class="modal fade" id="deleteMediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Hapus File</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteMediaForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Yakin ingin menghapus <strong id="deleteMediaName"></strong>? Tindakan ini tidak bisa dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
