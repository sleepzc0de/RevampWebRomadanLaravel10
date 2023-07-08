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
                orderable: false,
                width: 200,
                targets: [ 0 ]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Type to filter...',
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
            ajax: "{{ route('users.index') }}",
            columns: [
            { data:'DT_RowIndex', name:'DT_RowIndex', width:'10px',orderable:false,searchable:false},
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role_name', name: 'role_name' },
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
                          <h1>LIST USER WEBROMADAN</h1>
                           @include('layouts.webromadan_backend.session_notif')
						</div>
						<div class="card-header">
                          
                                    <a href="{{route('users.create')}}"><button type="button" class="btn btn-flat-purple btn-labeled btn-labeled-start rounded-pill">
                                        <span class="btn-labeled-icon bg-purple text-white rounded-pill">
                                            <i class="ph-check-square-offset"></i>
                                        </span>
                                        Tambah Data
                                    </button></a>
                            
						</div>

						<table class="table datatable-responsive">
							<thead>
								<tr>
									<th>#</th>
									<th>NAMA</th>
									<th>EMAIL</th>
                                    <th>ROLE</th>
									<th>AKSI</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<!-- /basic responsive configuration -->
@endsection
