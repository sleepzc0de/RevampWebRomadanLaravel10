@extends('layouts.webromadan_backend.master_layout')

@section('css')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('webromadan/be/css/vendor/uploaders/dropzone.min.css')}}">
@endsection


@section('script_atas')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/forms/validation/validate.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/forms/selects/select2.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/editors/ckeditor/ckeditor_classic.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/uploaders/dropzone.min.js')}}"></script>
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
        ClassicEditor.create(document.querySelector('#ckeditor_classic_empty_struktur'), {
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

    // Preview image on selection
    $('#customFile').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#mainImagePreview').attr('src', e.target.result).show();
                $('#mainImagePreviewContainer').show();
            }
            reader.readAsDataURL(file);
        }
    });

    // Handle additional images preview
    const previewContainer = $('#additionalImagesPreview');
    $('#additionalImages').on('change', function() {
        previewContainer.empty();
        const files = this.files;

        if (files.length > 0) {
            $('#additionalImagesContainer').show();

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imgElem = $('<div class="col-md-3 mb-2"><img src="' + e.target.result + '" class="img-fluid img-thumbnail" style="max-height: 150px;"></div>');
                    previewContainer.append(imgElem);
                }

                reader.readAsDataURL(file);
            }
        } else {
            $('#additionalImagesContainer').hide();
        }
    });

    // URL validator for YouTube
    function isValidYoutubeUrl(url) {
        const ytRegExp = /^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/;
        return ytRegExp.test(url);
    }

    // YouTube URL validation
    $('#videoUrl').on('change', function() {
        const url = $(this).val();
        if (url && !isValidYoutubeUrl(url)) {
            $('#videoUrlError').text('Masukkan URL YouTube yang valid').show();
        } else {
            $('#videoUrlError').hide();

            // Extract video ID and show preview
            if (url) {
                const videoId = extractYoutubeId(url);
                if (videoId) {
                    $('#videoPreview').attr('src', 'https://www.youtube.com/embed/' + videoId);
                    $('#videoPreviewContainer').show();
                }
            } else {
                $('#videoPreviewContainer').hide();
            }
        }
    });

    // Extract YouTube Video ID
    function extractYoutubeId(url) {
        const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[7].length == 11) ? match[7] : false;
    }
});
</script>

<style>
    .preview-container {
        margin-top: 10px;
        display: none;
    }

    .youtube-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
    }

    .youtube-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>
@endsection

@section('content')
<!-- Form validation -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Struktur Organisasi Romadan</h5>
        @include('layouts.webromadan_backend.session_notif')
    </div>

    <form class="form-validate-jquery" action="{{route('struktur-organisasi.store')}}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="card-body">
            <div class="mb-4">
                <!-- Judul Artikel input -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Judul Stuktur <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <input maxlength="255" value="{{ old('judul') }}" type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" required placeholder="Masukkan Judul Struktur">
                        <!-- error message untuk judul -->
                        @error('judul')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <!-- /Judul Artikel input -->

                <!-- STRUKTUR -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Struktur Organisasi <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <textarea maxlength="10000" name="struktur" class="form-control @error('struktur') is-invalid @enderror" required placeholder="Struktur" id="ckeditor_classic_empty_struktur">{{ old('struktur') }}</textarea>
                        @error('struktur')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <!-- /STRUKTUR -->

                <!-- Main Image file uploader -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Gambar Utama <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <input type="file" class="form-control @error('image') is-invalid @enderror required" id="customFile" name="image">
                        <small class="form-text text-muted">Format: JPEG, JPG, PNG, SVG. Ukuran maks: 20MB</small>
                        @error('image')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror

                        <div class="preview-container" id="mainImagePreviewContainer">
                            <div class="mt-3">
                                <h6>Pratinjau Gambar Utama:</h6>
                                <img id="mainImagePreview" src="#" alt="Preview" class="img-fluid img-thumbnail" style="max-height: 200px; display: none;">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /main image file uploader -->

                <!-- Additional Images file uploader -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Gambar Tambahan</label>
                    <div class="col-lg-10">
                        <input type="file" class="form-control @error('additional_images.*') is-invalid @enderror" id="additionalImages" name="additional_images[]" multiple>
                        <small class="form-text text-muted">Format: JPEG, JPG, PNG, SVG. Ukuran maks: 20MB per gambar</small>
                        @error('additional_images.*')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror

                        <div class="preview-container" id="additionalImagesContainer">
                            <div class="mt-3">
                                <h6>Pratinjau Gambar Tambahan:</h6>
                                <div id="additionalImagesPreview" class="row"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /additional images file uploader -->

                <!-- Video URL input -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">URL Video YouTube</label>
                    <div class="col-lg-10">
                        <input type="url" class="form-control @error('video_url') is-invalid @enderror" id="videoUrl" name="video_url" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('video_url') }}">
                        <small class="form-text text-muted">Masukkan URL video YouTube (opsional)</small>
                        <div id="videoUrlError" class="invalid-feedback" style="display: none;"></div>
                        @error('video_url')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror

                        <div class="preview-container" id="videoPreviewContainer">
                            <div class="mt-3">
                                <h6>Pratinjau Video:</h6>
                                <div class="youtube-container">
                                    <iframe id="videoPreview" width="560" height="315" src="" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /video URL input -->

                <!-- Layout Type selection -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Tata Letak <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <select name="layout_type" class="form-select @error('layout_type') is-invalid @enderror" required>
                            <option value="standard" {{ old('layout_type') == 'standard' ? 'selected' : '' }}>Standar (50:50)</option>
                            <option value="wide" {{ old('layout_type') == 'wide' ? 'selected' : '' }}>Lebar (33:67)</option>
                            <option value="compact" {{ old('layout_type') == 'compact' ? 'selected' : '' }}>Kompak (67:33)</option>
                        </select>
                        <small class="form-text text-muted">Pilih tata letak untuk tampilan konten dan media</small>
                        @error('layout_type')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <!-- /layout type selection -->

            </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
            <a href="{{route('struktur-organisasi.index')}}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
            <button type="reset" class="btn btn-light ms-3" id="reset">Reset</button>
            <button type="submit" class="btn btn-primary ms-3">Submit <i class="ph-paper-plane-tilt ms-2"></i></button>
        </div>
    </form>
</div>
<!-- /form validation -->
@endsection
