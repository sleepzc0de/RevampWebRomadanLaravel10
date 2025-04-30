@extends('layouts.webromadan_backend.master_layout')

@section('css')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<style>
    .image-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }
    .image-preview {
        position: relative;
        width: 150px;
        height: 150px;
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
    }
    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(255,0,0,0.7);
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        text-align: center;
        line-height: 20px;
        cursor: pointer;
    }
</style>
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
            ClassicEditor.create(document.querySelector('#ckeditor_classic_empty_layanan'), {
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
                toolbar: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'link',
                    'bulletedList',
                    'numberedList',
                    'blockQuote',
                    'insertTable',
                    'undo',
                    'redo'
                ]
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

        // Preview additional images when selected
        document.getElementById('additionalImages').addEventListener('change', function(event) {
            const container = document.getElementById('additionalImagesPreview');
            container.innerHTML = '';

            for (const file of event.target.files) {
                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'image-preview';

                        const img = document.createElement('img');
                        img.src = e.target.result;

                        div.appendChild(img);
                        container.appendChild(div);
                    }

                    reader.readAsDataURL(file);
                }
            }
        });
    });
</script>
@endsection

@section('content')
<!-- Form validation -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Layanan Romadan</h5>
                            @include('layouts.webromadan_backend.session_notif')
                        </div>

                        <form class="form-validate-jquery" action="{{route('layanan.store')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="card-body">

                                <div class="mb-4">

                                    <!-- Judul Artikel input -->
                                    <div class="row mb-3">
                                        <label class="col-form-label col-lg-2">Judul layanan <span class="text-danger">*</span></label>
                                        <div class="col-lg-10">
                                            <input maxlength="255" value="{{ old('judul') }}" type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" required placeholder="Masukkan Judul layanan">
                                            <!-- error message untuk judul -->
                                            @error('judul')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- /Judul Artikel input -->

                                    <!-- layanan -->
                                    <div class="row mb-3">
                                        <label class="col-form-label col-lg-2">Layanan <span class="text-danger">*</span></label>
                                        <div class="col-lg-10">
                                            <textarea maxlength="5000" name="layanan" class="form-control @error('layanan') is-invalid @enderror" required placeholder="Layanan" id="ckeditor_classic_empty_layanan">{{ old('layanan') }}</textarea>
                                            @error('layanan')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <small class="text-muted">Maksimal 5000 karakter</small>
                                        </div>
                                    </div>
                                    <!-- /layanan -->

                                    <!-- Main Image file uploader -->
                                    <div class="row mb-3">
                                        <label class="col-form-label col-lg-2">Gambar Utama <span class="text-danger">*</span></label>
                                        <div class="col-lg-10">
                                            <input type="file" class="form-control @error('image') is-invalid @enderror required" id="customFile" name="image">
                                            @error('image')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <small class="text-muted">Maksimal 20MB (JPEG, PNG, JPG, SVG)</small>
                                        </div>
                                    </div>
                                    <!-- /main image file uploader -->

                                    <!-- Additional Images uploader -->
                                    <div class="row mb-3">
                                        <label class="col-form-label col-lg-2">Gambar Tambahan</label>
                                        <div class="col-lg-10">
                                            <input type="file" class="form-control @error('additional_images.*') is-invalid @enderror" id="additionalImages" name="additional_images[]" multiple>
                                            @error('additional_images.*')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <small class="text-muted">Anda dapat memilih beberapa gambar. Maksimal 20MB per gambar (JPEG, PNG, JPG, SVG)</small>

                                            <!-- Preview for additional images -->
                                            <div id="additionalImagesPreview" class="image-preview-container"></div>
                                        </div>
                                    </div>
                                    <!-- /additional images uploader -->

                                    <!-- Video URL input -->
                                    <div class="row mb-3">
                                        <label class="col-form-label col-lg-2">URL Video</label>
                                        <div class="col-lg-10">
                                            <input type="url" class="form-control @error('video_url') is-invalid @enderror" name="video_url" placeholder="Contoh: https://www.youtube.com/watch?v=AbCdEfGhIjK" value="{{ old('video_url') }}">
                                            @error('video_url')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <small class="text-muted">Masukkan URL video dari platform seperti YouTube, Vimeo, dll.</small>
                                        </div>
                                    </div>
                                    <!-- /video URL input -->

                                </div>

                        </div>

                        <div class="card-footer d-flex justify-content-end">
                            <a href="{{route('layanan.index')}}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
                            <button type="reset" class="btn btn-light ms-3" id="reset">Reset</button>
                            <button type="submit" class="btn btn-primary ms-3">Submit <i class="ph-paper-plane-tilt ms-2"></i></button>
                        </div>
                            </form>
                    </div>
<!-- /form validation -->
@endsection
