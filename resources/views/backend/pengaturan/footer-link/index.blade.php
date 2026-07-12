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
        columnDefs: [{ orderable: false, width: 100, targets: [0] }],
        dom: '<"datatable-header"f<"ms-sm-auto"B><"ms-sm-auto"l>><"datatable-scroll"t><"datatable-footer"ip>',
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

    $('.datatable-basic').DataTable({
        autoWidth: true,
        scrollY: 200,
        scrollX: true,
        processing: true,
        serverSide: true,
        ajax: "{{ route('footer-link.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '2px', orderable: false, searchable: false },
            { data: 'label', name: 'label' },
            { data: 'url', name: 'url' },
            { data: 'sort_order', name: 'sort_order' },
            { data: 'status', name: 'is_active', orderable: false, searchable: false },
            { data: 'opsi', name: 'opsi', orderable: false, searchable: false },
        ],
        order: [[3, 'asc']],
        buttons: {
            dom: { button: { className: '' } },
            buttons: [
                {
                    extend: 'colvis',
                    text: '<i class="ph-squares-four"></i>',
                    className: 'btn btn-outline-info dropdown-toggle',
                    collectionLayout: 'fixed four-column'
                }
            ]
        },
    });
});
</script>
@endsection

@section('content')
<!-- Basic datatable -->
<div class="card">
    <div class="card-header text-center">
        <h1>Tautan Footer</h1>
        @include('layouts.webromadan_backend.session_notif')
    </div>
    <div class="card-header">
        <p class="text-muted mb-2">Daftar tautan yang tampil pada bagian "Tautan" di footer seluruh halaman frontend. Urutkan dengan angka pada kolom Urutan (semakin kecil semakin awal tampil).</p>
        <a href="{{route('footer-link.create')}}"><button type="button" class="btn btn-flat-purple btn-labeled btn-labeled-start rounded-pill">
            <span class="btn-labeled-icon bg-purple text-white rounded-pill">
                <i class="ph-check-square-offset"></i>
            </span>
            Tambah Tautan
        </button></a>
    </div>

    <table class="table datatable-basic table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Label</th>
                <th>URL</th>
                <th>Urutan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<!-- /basic datatable -->
@endsection
