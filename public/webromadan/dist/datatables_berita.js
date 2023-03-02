
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
            serverSide: true,
            processing: true,
            ajax: {
                'url':$('#table-berita').val()

            },
            columns:[
                {
                    data:'DT_RowIndex', name:'DT_RowIndex', width:'10px',orderable:false,searchable:false
                },
                {
                    data: 'judul',name:'judul'
                },
                 {
                    data: 'sub_judul',name:'sub_judul'
                },
                {
                    data: 'image_berita',name:'image_berita',orderable:false, searchable:false,
                },
                 {
                    data: 'kategori.nama_kategori',name:'kategori.nama_kategori',orderable:false,searchable:false
                },
                 {
                    data: 'status.nama_status',name:'status.nama_status',orderable:false,searchable:false, 
                },
                 {
                    data: 'opsi',name:'opsi',orderable:false,searchable:false
                },
            ],
            ordering:true,
            autoWidth: false,
            columnDefs: [{ 
                orderable: false,
                width: 50,
                targets: [ 6 ]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                processing: '<div class="text-dark"><span class="sr-only">Loading...</span></div>',
                search: '<div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Cari Data Berita',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });

        // Basic datatable
        $('.datatable-basic').DataTable();

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