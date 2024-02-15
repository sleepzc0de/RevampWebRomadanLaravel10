@extends('layouts.webromadan_backend.master_layout')

@section('css')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/datatables.min.js')}}"></script>
@endsection


@section('script_atas')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/forms/validation/validate.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/forms/selects/select2.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/editors/ckeditor/ckeditor_classic.js')}}"></script>

@endsection

@section('script_bawah')
<script src="{{asset('webromadan/be/demo/pages/form_validation_library.js')}}"></script>
<script src="{{asset('webromadan/be/demo/pages/form_select2.js')}}"></script>
{{-- <script src="{{asset('webromadan/be/demo/pages/editor_ckeditor_classic.js')}}"></script> --}}
<script>
	/* ------------------------------------------------------------------------------
 *
 *  # CKEditor Classic editor
 *
 *  Demo JS code for editor_ckeditor_classic.html page
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

const CKEditorClassic = function() {


    //
    // Setup module components
    //

    // CKEditor
    const _componentCKEditorClassic = function() {
        if (typeof ClassicEditor == 'undefined') {
            console.warn('Warning - ckeditor_classic.js is not loaded.');
            return;
        }

        // Editor with placeholder
        ClassicEditor.create(document.querySelector('#ckeditor_classic_empty'), {
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                    { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                    { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                ],
            },
			
            
        }).catch(error => {
            console.error(error);
        });


    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentCKEditorClassic();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    CKEditorClassic.init();
});
</script>
@endsection

@section('content')
<!-- Form validation -->
					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">Tambah Medsos Romadan</h5>
                            @include('layouts.webromadan_backend.session_notif')
						</div>

						<form class="form-validate-jquery" action="{{route('medsos.store')}}" method="post" autocomplete="off">
							@csrf
							<div class="card-body">
							
								<div class="mb-4">

									<!-- Nama Medsos -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Nama Medsos <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('nama_medsos') }}" type="text" name="nama_medsos" class="form-control @error('nama_medsos') is-invalid @enderror" required placeholder="Masukkan Nama Medsos">
											<!-- error message untuk nama_medsos -->
											@error('nama_medsos')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Nama Medsos -->

                                    <!-- Link Medsos -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Link Medsos <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('link_medsos') }}" type="text" name="link_medsos" class="form-control @error('link_medsos') is-invalid @enderror" required placeholder="Masukkan Link Medsos">
											<!-- error message untuk link_medsos -->
											@error('link_medsos')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Link Medsos -->


                                     <!-- Logo Medsos -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Logo Medsos <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('logo_medsos') }}" type="text" name="logo_medsos" class="form-control @error('logo_medsos') is-invalid @enderror" required placeholder="Masukkan Nama Logo Medsos dari Fontawesome, contoh:fa-brands fa-facebook	
											">
											<!-- error message untuk logo_medsos -->
											@error('logo_medsos')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Logo Medsos -->

								</div>

						</div>

						<div class="card-footer d-flex justify-content-end">
                            <a href="{{route('medsos.index')}}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
							<button type="reset" class="btn btn-light ms-3" id="reset">Reset</button>
							<button type="submit" class="btn btn-primary ms-3 ">Submit <i class="ph-paper-plane-tilt ms-2"></i></button>
						</div>
							</form>
					</div>
<!-- /form validation -->
@endsection




