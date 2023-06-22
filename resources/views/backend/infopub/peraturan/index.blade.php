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
            ajax: "{{ route('peraturan.index') }}",
            columns: [
            { data:'DT_RowIndex', name:'DT_RowIndex', width:'2px',orderable:false,searchable:false},
            {data: 'nomor_peraturan',name:'nomor_peraturan'},
            {data: 'judul_peraturan',name:'judul_peraturan'},
            {data: 'file_peraturan',name:'file_peraturan',orderable:false, searchable:false},
            {data: 'kategori.nama_kategori',name:'kategori.nama_kategori',orderable:false,searchable:false},
            {data: 'jenis_peraturan.nama_jenis_peraturan',name:'jenis_peraturan.nama_jenis_peraturan',orderable:false,searchable:false},
            {data: 'tanggal_penetapan',name:'tanggal_penetapan'},
            {data: 'tanggal_berlaku',name:'tanggal_berlaku'},
            {data: 'status_peraturan.nama_peraturan_status',name:'status_peraturan.nama_peraturan_status',orderable:false,searchable:false},
            {data: 'slug',name:'slug'},
            {data: 'opsi',name:'opsi',orderable:false,searchable:false},
            

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
<!-- Basic datatable -->

					<div class="card">
                        <div class="card-header text-center">
                          <h1>Peraturan</h1>
                           @include('layouts.webromadan_backend.session_notif')
						</div>
                        <div class="card-header">
                          
                                    <a href="{{route('peraturan.create')}}"><button type="button" class="btn btn-flat-purple btn-labeled btn-labeled-start rounded-pill">
                                        <span class="btn-labeled-icon bg-purple text-white rounded-pill">
                                            <i class="ph-check-square-offset"></i>
                                        </span>
                                        Tambah Peraturan
                                    </button></a>
                            
						</div>
                        
						<table class="table datatable-basic table-hover table-striped">
							<thead>
								<tr>
									<th>#</th>
                                    <th>Nomor Peraturan</th>
                                    <th>Judul Peraturan</th>
                                    <th>File Peraturan</th>
                                    <th>Kategori Peraturan</th>
                                    <th>Jenis Peraturan</th>
                                    <th>Tanggal Penetapan</th>
                                    <th>Tanggal Berlaku</th>
                                    <th>Status Peraturan</th>
                                    <th>Slug</th>
                                    <th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<!-- /basic datatable -->



@endsection
