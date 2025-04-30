@extends('layouts.webromadan_backend.master_layout')

@section('css')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/extensions/responsive.min.js')}}"></script>
@endsection

@section('script_atas')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/forms/selects/select2.min.js')}}"></script>
@endsection

@section('script_bawah')
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
                targets: [5]
            }],
			dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Cari :</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Cari...',
                lengthMenu: '<span class="me-3">Menampilkan :</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });

        // Basic datatable
        $('.datatable-basic').DataTable({

			columnDefs: [
				{
                    orderable: false,
                    targets: [1, 2, 3, 4, 5]
                }
			],
            processing: true,
            serverSide: true,
            ajax: "{{ route('struktur-organisasi.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'judul', name: 'judul'},
                {data: 'image_struktur', name: 'image_struktur', orderable: false, searchable: false},
                {data: 'additional_images_count', name: 'additional_images_count', orderable: false, searchable: false},
                {data: 'has_video', name: 'has_video', orderable: false, searchable: false},
                {data: 'layout_type', name: 'layout_type', orderable: false, searchable: false},
                {data: 'opsi', name: 'opsi', orderable: false, searchable: false},
            ],
            order: [[0, 'asc']]

        });


        // Alternative pagination
        $('.datatable-pagination').DataTable({
            pagingType: "simple",
            language: {
                paginate: {'next': document.dir == "rtl" ? 'Next &larr;' : 'Next &rarr;', 'previous': document.dir == "rtl" ? '&rarr; Prev' : '&larr; Prev'}
            }
        });

        // Datatable with saving state
        $('.datatable-save-state').DataTable({
            stateSave: true
        });

        // Scrollable datatable
        const table = $('.datatable-scroll-y').DataTable({
            autoWidth: true,
            scrollY: 300
        });

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

<!-- Basic datatable -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Struktur Organisasi</h5>
        @include('layouts.webromadan_backend.session_notif')
    </div>

    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div></div>
            <a href="{{ route('struktur-organisasi.create') }}" class="btn btn-primary">
                <i class="ph-plus me-1"></i>
                Tambah Struktur Organisasi
            </a>
        </div>

        <table class="table datatable-basic">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Judul</th>
                    <th width="15%">Gambar Utama</th>
                    <th width="10%">Gambar Tambahan</th>
                    <th width="10%">Video</th>
                    <th width="10%">Tata Letak</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded via AJAX -->
            </tbody>
        </table>
    </div>
</div>
<!-- /basic datatable -->
@endsection
