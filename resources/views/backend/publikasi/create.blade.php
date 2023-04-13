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
							<h5 class="mb-0">Tambah Publikasi</h5>
                            @include('layouts.webromadan_backend.session_notif')
						</div>

						<form class="form-validate-jquery" action="{{route('publikasi.store')}}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
							<div class="card-body">
							
								<div class="mb-4">

									<!-- Judul publikasi input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Judul publikasi <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('judul') }}" type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" required placeholder="Masukkan Judul publikasi">
											<!-- error message untuk judul -->
											@error('judul')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Judul publikasi input -->

                                    <!-- Sub Judul publikasi input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Sub Judul publikasi <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('sub_judul') }}" type="text" name="sub_judul" class="form-control @error('sub_judul') is-invalid @enderror" required placeholder="Masukkan Sub Judul publikasi">
											<!-- error message untuk judul -->
												@error('sub_judul')
												<div class="alert alert-danger mt-2">
													{{ $message }}
												</div>
												@enderror
										</div>
									</div>
									<!-- /Sub Judul publikasi input -->

									<!-- Tipe Publikasi -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Tipe Publikasi <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<select value="{{ old('tipe') }}" name="tipe" class="form-control form-control-select2 select" @error('tipe') is-invalid @enderror required>
												<option>--PILIH--</option>
												@foreach ($tipe as $item)
												<option value="{{ $item->id_tipe }}" {{ old('tipe') == $item->id_tipe ? 'selected' : null}}>{{$loop->iteration." - ".$item->nama_tipe}}</option>
												@endforeach
													
												
											</select>

											<!-- error message untuk judul -->
											@error('tipe')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Tipe Publikasi -->

                                    <!-- Kategori publikasi -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Kategori publikasi <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<select value="{{ old('kategori') }}" name="kategori" class="form-control form-control-select2 select" @error('kategori') is-invalid @enderror required>
												<option>--PILIH--</option>
												@foreach ($kategori as $item)
												<option value="{{ $item->id_kategori }}" {{ old('kategori') == $item->id_kategori ? 'selected' : null}}>{{$loop->iteration." - ".$item->nama_kategori}}</option>
												@endforeach
													
												
											</select>

											<!-- error message untuk judul -->
											@error('kategori')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Kategori publikasi -->

                                    <!-- Image file uploader -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Gambar publikasi <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input type="file" class="form-control @error('image') is-invalid @enderror required" id="customFile" name="image">
											@error('image')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /image file uploader -->
                                    
									<!-- Isi publikasi Input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Isi publikasi <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											{{-- <textarea rows="5" cols="5" name="isi" class="form-control @error('isi') is-invalid @enderror" required placeholder="Isi publikasi">{{ old('isi') }}</textarea> --}}

											<textarea name="isi" class="form-control @error('isi') is-invalid @enderror" required placeholder="Isi publikasi" id="ckeditor_classic_empty">{{ old('isi') }}</textarea>
										</div>
									</div>
									<!-- /Isi publikasi Input -->

								</div>

						</div>

						<div class="card-footer d-flex justify-content-end">
							<a href="{{route('publikasi.index') }}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
							<button type="reset" class="btn btn-light ms-3" id="reset">Reset</button>
							<button type="submit" class="btn btn-primary ms-3">Submit <i class="ph-paper-plane-tilt ms-2"></i></button>
						</div>
							</form>
					</div>
<!-- /form validation -->
@endsection




