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
        ClassicEditor.create(document.querySelector('#ckeditor_classic_empty_kegiatan'), {
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
							<h5 class="mb-0">Kegiatan Romadan</h5>
                            @include('layouts.webromadan_backend.session_notif')
						</div>

						<form class="form-validate-jquery" action="{{route('kegiatan.store')}}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
							<div class="card-body">
							
								<div class="mb-4">

									<!-- Judul Artikel input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Judul Kegiatan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('judul') }}" type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" required placeholder="Masukkan Judul kegiatan">
											<!-- error message untuk judul -->
											@error('judul')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Judul Artikel input -->

									<!-- Tempat Kegiatan -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Tempat Kegiatan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('tempat') }}" type="text" name="tempat" class="form-control @error('tempat') is-invalid @enderror" required placeholder="Masukkan tempat Kegiatan">
											<!-- error message untuk tempat -->
											@error('tempat')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Tempat Kegiatan -->

									<!-- Image file uploader -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Gambar Kegiatan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
											@error('image')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /image file uploader -->

									<!-- kegiatan -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Kegiatan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<textarea name="isi" class="form-control @error('isi') is-invalid @enderror" required placeholder="isi kegiatan" id="ckeditor_classic_empty_kegiatan">{{ old('isi') }}</textarea>
										</div>
									</div>
									<!-- /kegiatan -->

                                    

									 <!-- File Kegiatan -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">File Kegiatan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input type="file" class="form-control @error('file') is-invalid @enderror required" id="file" name="file">
											@error('file')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /File Kegiatan -->

									<!-- Tanggal Mulai -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Tanggal Mulai <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input class="form-control @error('tanggal_mulai') is-invalid @enderror required" id="tanggal_mulai" name="tanggal_mulai" type="datetime-local">
											@error('tanggal_mulai')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Tanggal Mulai -->

									<!-- Tanggal Selesai -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Tanggal Selesai</label>
										<div class="col-lg-10">
											<input class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" type="datetime-local">
											@error('tanggal_selesai')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Tanggal Selesai -->
                                    
									

								</div>

						</div>

						<div class="card-footer d-flex justify-content-end">
							<a href="{{route('kegiatan.index')}}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
							<button type="reset" class="btn btn-light ms-3" id="reset">Reset</button>
							<button type="submit" class="btn btn-primary ms-3">Submit <i class="ph-paper-plane-tilt ms-2"></i></button>
						</div>
							</form>
					</div>
<!-- /form validation -->
@endsection




