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

<div class="row">
    <div class="col-lg-4">
            <div class="row">
                <div class="col-xl-6 col-sm-6">
                                    <div class="card bg-white text-dark" style="background-image: url({{asset('webromadan/be/images/backgrounds/panel_bg.png')}}); background-size: contain;">
                                            <div class="card-body text-justify">
                                                <div class="card-img-actions d-inline-block mb-3">
                                                    
                                                    <i class="ph-bell-simple-slash ph-2x me-3"></i> 
                                                    
                                                </div>

                                                <h2 class="mb-0">{{$data['jumlah_artikel']}}</h2>
                                                <span class="opacity-75">Jumlah Artikel</span>

                                            </div>
                                    </div>
                </div>
                <div class="col-xl-6 col-sm-6">
                                    <div class="card bg-white text-dark" style="background-image: url({{asset('webromadan/be/images/backgrounds/panel_bg.png')}}); background-size: contain;">
                                            <div class="card-body text-justify">
                                                <div class="card-img-actions d-inline-block mb-3">
                                                    
                                                    <i class="ph-bell-simple-slash ph-2x me-3"></i> 
                                                    
                                                </div>

                                                <h2 class="mb-0">{{$data['jumlah_berita']}}</h2>
                                                <span class="opacity-75">Jumlah Berita</span>

                                            </div>
                                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                                    <div class="card bg-white text-dark" style="background-image: url({{asset('webromadan/be/images/backgrounds/panel_bg.png')}}); background-size: contain;">
                                            <div class="card-body text-justify">
                                                <div class="card-img-actions d-inline-block mb-3">
                                                    
                                                    <i class="ph-bell-simple-slash ph-2x me-3"></i> 
                                                    
                                                </div>

                                                <h2 class="mb-0">{{$data['jumlah_warta']}}</h2>
                                                <span class="opacity-75">Jumlah Warta</span>

                                            </div>
                                    </div>
                </div>
                <div class="col-sm-6">
                                    <div class="card bg-white text-dark" style="background-image: url({{asset('webromadan/be/images/backgrounds/panel_bg.png')}}); background-size: contain;">
                                            <div class="card-body text-justify">
                                                <div class="card-img-actions d-inline-block mb-3">
                                                    
                                                    <i class="ph-bell-simple-slash ph-2x me-3"></i> 
                                                    
                                                </div>

                                                <h2 class="mb-0">{{$data['jumlah_kegiatan']}}</h2>
                                                <span class="opacity-75">Jumlah Kegiatan</span>

                                            </div>
                                    </div>
                </div>
            </div>
            <div class="row">
                <div class="btn-group d-flex py-1">
                    <a href="{{route('publikasi.create')}}" class="btn btn-secondary"></i>Berita</a>
                    <a href="{{route('publikasi.create')}}" class="btn btn-secondary"></i>Artikel</a>
                    <a href="{{route('publikasi.create')}}" class="btn btn-secondary"></i>Warta</a>
                </div>
                <div class="btn-group d-flex py-1">
                    <a href="{{route('kegiatan.create')}}" class="btn btn-secondary"></i>Kegiatan</a>
                    <a href="{{route('faq.create')}}" class="btn btn-secondary"></i>FAQ</a>
                    <a href="{{route('users.create')}}" class="btn btn-secondary"></i>User</a>
                </div>

            </div>
            
    </div>
    <div class="col-lg-8">
        <!-- Basic datatable -->
					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">Content Activity</h5>
						</div>
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
							<tbody>
								
							</tbody>
						</table>
					</div>
					<!-- /basic datatable -->
    </div>
   
</div>





@endsection
