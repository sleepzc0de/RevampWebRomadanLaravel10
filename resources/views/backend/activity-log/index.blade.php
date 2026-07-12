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

    const table = $('.datatable-activity-log').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('activity-log.index') }}",
            data: function (d) {
                d.log_name = $('#filter-log-name').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '10px', orderable: false, searchable: false },
            { data: 'waktu', name: 'created_at' },
            { data: 'modul', name: 'log_name' },
            { data: 'event', name: 'event' },
            { data: 'deskripsi', name: 'description' },
            { data: 'pengguna', name: 'causer.name' },
            { data: 'detail', name: 'detail', orderable: false, searchable: false },
        ],
        order: [[1, 'desc']],
    });

    $('#filter-log-name').on('change', function () {
        table.draw();
    });

    $(document).on('click', '.activity-detail-btn', function () {
        const properties = $(this).data('properties');
        const subject = $(this).data('subject') || '-';
        $('#activity-detail-subject').text(subject);
        $('#activity-detail-json').text(JSON.stringify(properties, null, 2));
        const modal = new bootstrap.Modal(document.getElementById('activityDetailModal'));
        modal.show();
    });
});
</script>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0">Log Aktivitas</h5>
        <div class="d-flex align-items-center gap-2">
            <select id="filter-log-name" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Modul</option>
                @foreach($logNames as $logName)
                    <option value="{{ $logName }}">{{ ucwords(str_replace('_', ' ', $logName)) }}</option>
                @endforeach
            </select>
            <form action="{{ route('activity-log.clean') }}" method="post" onsubmit="return confirm('Hapus semua log lebih dari 365 hari?');">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="ph-broom"></i> Bersihkan Log Lama
                </button>
            </form>
        </div>
    </div>
    @include('layouts.webromadan_backend.session_notif')

    <table class="table datatable-activity-log table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Waktu</th>
                <th>Modul</th>
                <th>Event</th>
                <th>Deskripsi</th>
                <th>Pengguna</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Modal Detail Aktivitas -->
<div class="modal fade" id="activityDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Perubahan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-2">Subjek: <span id="activity-detail-subject"></span></p>
                <pre id="activity-detail-json" class="bg-light p-3 rounded" style="white-space: pre-wrap; word-break: break-word;"></pre>
            </div>
        </div>
    </div>
</div>
@endsection
