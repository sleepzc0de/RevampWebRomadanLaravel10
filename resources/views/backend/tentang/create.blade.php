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
        ClassicEditor.create(document.querySelector('#ckeditor_classic_empty_tentang'), {
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

            // Enable number list and numbered headings
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },

            // Preserve number formatting
            htmlSupport: {
                allow: [
                    {
                        name: /^(ol|li|ul)$/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }
                ]
            }

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview images before upload
    function previewImages(input, previewContainer) {
        const container = document.getElementById(previewContainer);
        container.innerHTML = '';

        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.className = 'mt-2 mr-2 d-inline-block position-relative';
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" style="max-width: 150px; max-height: 150px; margin: 5px;" class="border rounded">
                    `;
                    container.appendChild(preview);
                }

                reader.readAsDataURL(file);
            });
        }
    }

    // Set up image preview for main image
    const mainImageInput = document.getElementById('customFile');
    if (mainImageInput) {
        mainImageInput.addEventListener('change', function() {
            previewImages(this, 'main-image-preview');
        });
    }

    // Set up image preview for additional images
    const additionalImagesInput = document.getElementById('additionalImages');
    if (additionalImagesInput) {
        additionalImagesInput.addEventListener('change', function() {
            previewImages(this, 'additional-images-preview');
        });
    }
});
</script>
@endsection

@section('content')
<!-- Form validation -->
					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">Tentang Romadan</h5>
                            @include('layouts.webromadan_backend.session_notif')
						</div>

						<form class="form-validate-jquery" action="{{route('tentang.store')}}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
							<div class="card-body">

								<div class="mb-4">

									<!-- Judul Artikel input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Judul Tentang <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input maxlength="255" value="{{ old('judul') }}" type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" required placeholder="Masukkan Judul tentang">
											<!-- error message untuk judul -->
											@error('judul')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Judul Artikel input -->

									<!-- tentang -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Tentang <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<textarea maxlength="1000" name="tentang" class="form-control @error('tentang') is-invalid @enderror" required placeholder="Tentang" id="ckeditor_classic_empty_tentang">{{ old('tentang') }}</textarea>
                                            @error('tentang')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /tentang -->

                                    <!-- Main Image file uploader -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Gambar Utama <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input type="file" class="form-control @error('image') is-invalid @enderror required" id="customFile" name="image">
											<small class="form-text text-muted">Format: JPG, JPEG, PNG, SVG. Maksimal: 20MB</small>
                                            <div id="main-image-preview" class="mt-2"></div>
											@error('image')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /main image file uploader -->

                                    <!-- Additional Images file uploader -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Gambar Tambahan</label>
										<div class="col-lg-10">
											<input type="file" class="form-control @error('additional_images.*') is-invalid @enderror" id="additionalImages" name="additional_images[]" multiple>
											<small class="form-text text-muted">Format: JPG, JPEG, PNG, SVG. Maksimal: 20MB per file. Anda dapat memilih beberapa file.</small>
                                            <div id="additional-images-preview" class="mt-2"></div>
											@error('additional_images.*')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /additional image file uploader -->

                                    <!-- Video URL input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">URL Video</label>
										<div class="col-lg-10">
											<input value="{{ old('video_url') }}" type="url" name="video_url" class="form-control @error('video_url') is-invalid @enderror" placeholder="Contoh: https://www.youtube.com/watch?v=example">
											<small class="form-text text-muted">Masukkan URL video dari platform seperti YouTube atau Vimeo.</small>
											@error('video_url')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Video URL input -->

								</div>

						</div>

						<div class="card-footer d-flex justify-content-end">
							<a href="{{route('tentang.index')}}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
							<button type="reset" class="btn btn-light ms-3" id="reset">Reset</button>
							<button type="submit" class="btn btn-primary ms-3">Submit <i class="ph-paper-plane-tilt ms-2"></i></button>
						</div>
							</form>
					</div>
<!-- /form validation -->
@endsection
