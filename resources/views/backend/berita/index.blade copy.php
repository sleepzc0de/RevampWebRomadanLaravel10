@extends('layouts.webromadan_backend.master_layout')

@section('css')
@endsection


@section('script_atas')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/extensions/responsive.min.js')}}"></script>
@endsection

@section('script_bawah')
{{-- <script src="{{asset('webromadan/be/demo/pages/datatables_extension_responsive.js')}}"></script> --}}

{{-- <script src="https://cdn.datatables.net/plug-ins/1.13.1/sorting/datetime-moment.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script> --}}

<script>
    /* ------------------------------------------------------------------------------
 *
 *  # Responsive extension for Datatables
 *
 *  Demo JS code for datatable_responsive.html page
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

const DatatableResponsive = function() {


    //
    // Setup module components
    //

    // Basic Datatable examples
    const _componentDatatableResponsive = function() {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend( $.fn.dataTable.defaults, {
            autoWidth: false,
            responsive: true,
            columnDefs: [{ 
                // orderable: false,
                // targets: [ 6 ]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Cari Berita',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });

        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        // Basic responsive configuration
        $('.datatable-responsive').DataTable({
            autoWidth: true,
            // scrollY: 200,
            // scrollX: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('berita.index') }}",
            columns: [
            { data:'DT_RowIndex', name:'DT_RowIndex', width:'10px',orderable:false,searchable:false},
            {data: 'judul',name:'judul'},
            {data: 'sub_judul',name:'sub_judul'},
            {data: 'image_berita',name:'image_berita',orderable:false, searchable:false},
            {data: 'kategori.nama_kategori',name:'kategori.nama_kategori',orderable:false,searchable:false},
            {data: 'status.nama_status',name:'status.nama_status',orderable:false,searchable:false},
            {data: 'penulis',name:'penulis'},
            {data: 'created_at',name:'created_at'},
            {data: 'opsi',name:'opsi',orderable:false,searchable:false},
            ],
            order: [[0, 'asc']],
        });


    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentDatatableResponsive();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    DatatableResponsive.init();
});
</script>
@endsection

@section('content')
<!-- Basic responsive configuration -->
					<div class="card">
                        <div class="card-header text-center">
                          <h1>List Berita Romadan</h1>
                           @include('layouts.webromadan_backend.session_notif')
						</div>
						<div class="card-header">
                          
                                    <a href="{{route('berita.create')}}"><button type="button" class="btn btn-flat-purple btn-labeled btn-labeled-start rounded-pill">
                                        <span class="btn-labeled-icon bg-purple text-white rounded-pill">
                                            <i class="ph-check-square-offset"></i>
                                        </span>
                                        Tambah Data
                                    </button></a>

                                    <a href="{{route('berita.sampah')}}"><button type="button" class="btn btn-flat-pink btn-labeled btn-labeled-start rounded-pill">
                                        <span class="btn-labeled-icon bg-pink text-white rounded-pill">
                                            <i class="ph-check-square-offset"></i>
                                        </span>
                                        Daftar Berita Terhapus
                                    </button></a>
                            
						</div>

						<table class="table datatable-responsive nowrap w-100">
							<thead>
								<tr>
									<th>#</th>
                                    <th>Judul</th>
									<th>Sub Judul</th>
									<th>Gambar</th>
									<th>Kategori</th>
									<th>Status</th>
                                    <th>Penulis</th>
                                    <th>Tanggal Berita</th>
                                    <th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<!-- /basic responsive configuration -->
@endsection
