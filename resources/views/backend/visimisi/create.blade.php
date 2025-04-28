@extends('layouts.webromadan_backend.master_layout')

@section('css')
    <script src="{{ asset('webromadan/be/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('webromadan/be/js/vendor/tables/datatables/datatables.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('webromadan/be/css/vendor/file-uploaders/dropzone.min.css') }}">
@endsection


@section('script_atas')
    <script src="{{ asset('webromadan/be/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('webromadan/be/js/vendor/forms/validation/validate.min.js') }}"></script>
    <script src="{{ asset('webromadan/be/js/vendor/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('webromadan/be/js/vendor/editors/ckeditor/ckeditor_classic.js') }}"></script>
@endsection

@section('script_bawah')
    <script src="{{ asset('webromadan/be/demo/pages/form_validation_library.js') }}"></script>
    <script src="{{ asset('webromadan/be/demo/pages/form_select2.js') }}"></script>
    <script src="{{ asset('webromadan/be/js/vendor/file-uploaders/dropzone.min.js') }}"></script>
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
                ClassicEditor.create(document.querySelector('#ckeditor_classic_empty_visi'), {
                    heading: {
                        options: [{
                                model: 'paragraph',
                                title: 'Paragraph',
                                class: 'ck-heading_paragraph'
                            },
                            {
                                model: 'heading1',
                                view: 'h1',
                                title: 'Heading 1',
                                class: 'ck-heading_heading1'
                            },
                            {
                                model: 'heading2',
                                view: 'h2',
                                title: 'Heading 2',
                                class: 'ck-heading_heading2'
                            },
                            {
                                model: 'heading3',
                                view: 'h3',
                                title: 'Heading 3',
                                class: 'ck-heading_heading3'
                            },
                            {
                                model: 'heading4',
                                view: 'h4',
                                title: 'Heading 4',
                                class: 'ck-heading_heading4'
                            },
                            {
                                model: 'heading5',
                                view: 'h5',
                                title: 'Heading 5',
                                class: 'ck-heading_heading5'
                            },
                            {
                                model: 'heading6',
                                view: 'h6',
                                title: 'Heading 6',
                                class: 'ck-heading_heading6'
                            }
                        ],
                    },
                }).catch(error => {
                    console.error(error);
                });

                // Editor with placeholder
                ClassicEditor.create(document.querySelector('#ckeditor_classic_empty_misi'), {
                    heading: {
                        options: [{
                                model: 'paragraph',
                                title: 'Paragraph',
                                class: 'ck-heading_paragraph'
                            },
                            {
                                model: 'heading1',
                                view: 'h1',
                                title: 'Heading 1',
                                class: 'ck-heading_heading1'
                            },
                            {
                                model: 'heading2',
                                view: 'h2',
                                title: 'Heading 2',
                                class: 'ck-heading_heading2'
                            },
                            {
                                model: 'heading3',
                                view: 'h3',
                                title: 'Heading 3',
                                class: 'ck-heading_heading3'
                            },
                            {
                                model: 'heading4',
                                view: 'h4',
                                title: 'Heading 4',
                                class: 'ck-heading_heading4'
                            },
                            {
                                model: 'heading5',
                                view: 'h5',
                                title: 'Heading 5',
                                class: 'ck-heading_heading5'
                            },
                            {
                                model: 'heading6',
                                view: 'h6',
                                title: 'Heading 6',
                                class: 'ck-heading_heading6'
                            }
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

            // Preview image before upload
            document.getElementById('customFile').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('mainImagePreview');
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });

            // For additional images preview
            document.getElementById('additionalImages').addEventListener('change', function(e) {
                const previewContainer = document.getElementById('additionalImagesPreview');
                previewContainer.innerHTML = '';

                Array.from(e.target.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgContainer = document.createElement('div');
                        imgContainer.className = 'mb-2 mr-2 d-inline-block';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail';
                        img.style.maxHeight = '150px';

                        imgContainer.appendChild(img);
                        previewContainer.appendChild(imgContainer);
                    }
                    reader.readAsDataURL(file);
                });
            });

            // Video URL preview
            document.getElementById('videoUrl').addEventListener('blur', function(e) {
                const url = e.target.value;
                const previewContainer = document.getElementById('videoPreview');

                if (url) {
                    let embedCode = '';

                    // YouTube URL handling
                    if (url.includes('youtube.com') || url.includes('youtu.be')) {
                        const videoId = getYoutubeId(url);
                        if (videoId) {
                            embedCode = `<iframe width="100%" height="315" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                        }
                    }
                    // Vimeo URL handling
                    else if (url.includes('vimeo.com')) {
                        const videoId = getVimeoId(url);
                        if (videoId) {
                            embedCode = `<iframe src="https://player.vimeo.com/video/${videoId}" width="100%" height="315" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>`;
                        }
                    }

                    if (embedCode) {
                        previewContainer.innerHTML = embedCode;
                    } else {
                        previewContainer.innerHTML = '<div class="alert alert-warning">URL video tidak valid atau tidak didukung.</div>';
                    }
                } else {
                    previewContainer.innerHTML = '';
                }
            });

            // Helper function to extract YouTube video ID
            function getYoutubeId(url) {
                const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
                const match = url.match(regExp);
                return (match && match[2].length === 11) ? match[2] : null;
            }

            // Helper function to extract Vimeo video ID
            function getVimeoId(url) {
                const regExp = /vimeo\.com\/([0-9]+)/;
                const match = url.match(regExp);
                return match ? match[1] : null;
            }
        });
    </script>
@endsection

@section('content')
    <!-- Form validation -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Visi Misi Romadan</h5>
            @include('layouts.webromadan_backend.session_notif')
        </div>

        <form class="form-validate-jquery" action="{{ route('visi-misi.store') }}" method="post" enctype="multipart/form-data"
            autocomplete="off">
            @csrf
            <div class="card-body">

                <div class="mb-4">
                    <!-- Judul Artikel input -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-2">Judul Visi Misi <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input value="{{ old('judul') }}" type="text" name="judul"
                                class="form-control @error('judul') is-invalid @enderror" required
                                placeholder="Masukkan Judul Visi Misi" maxlength="255">
                            <!-- error message untuk judul -->
                            @error('judul')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <!-- /Judul Artikel input -->

                    <!-- VISI -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-2">Visi <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <textarea name="visi" class="form-control @error('visi') is-invalid @enderror" required placeholder="Visi"
                                id="ckeditor_classic_empty_visi">{{ old('visi') }}</textarea>
                            @error('visi')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <!-- /VISI -->

                    <!-- MISI -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-2">Misi <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <textarea name="misi" class="form-control @error('misi') is-invalid @enderror" required placeholder="Misi"
                                id="ckeditor_classic_empty_misi">{{ old('misi') }}</textarea>
                            @error('misi')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <!-- /MISI -->

                    <!-- Main Image file uploader -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-2">Gambar Utama <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                id="customFile" name="image" required>
                            @error('image')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="mt-3">
                                <img id="mainImagePreview" style="display: none; max-height: 200px;" class="img-thumbnail">
                            </div>
                        </div>
                    </div>
                    <!-- /main image file uploader -->

                    <!-- Additional Images uploader -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-2">Gambar Tambahan</label>
                        <div class="col-lg-10">
                            <input type="file" class="form-control @error('additional_images') is-invalid @enderror"
                                id="additionalImages" name="additional_images[]" multiple>
                            <small class="form-text text-muted">Anda dapat mengunggah beberapa gambar tambahan (opsional)</small>
                            @error('additional_images')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                            @error('additional_images.*')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div id="additionalImagesPreview" class="mt-3 d-flex flex-wrap">
                                <!-- Preview images will appear here -->
                            </div>
                        </div>
                    </div>
                    <!-- /additional images uploader -->

                    <!-- Video URL input -->
<div class="row mb-3">
    <label class="col-form-label col-lg-2">URL Video (Opsional)</label>
    <div class="col-lg-10">
        <input type="url" class="form-control @error('video_url') is-invalid @enderror"
            id="videoUrl" name="video_url" value="{{ old('video_url') }}"
            placeholder="Masukkan URL video YouTube atau Vimeo">
        <small class="form-text text-muted">Video ini akan muncul di slider bersama dengan gambar-gambar</small>
        @error('video_url')
            <div class="alert alert-danger mt-2">
                {{ $message }}
            </div>
        @enderror
        <div id="videoPreview" class="mt-3">
            <!-- Video preview will appear here -->
        </div>
    </div>
</div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end">
                <a href="{{ route('visi-misi.index') }}" class="btn btn-warning"><i
                        class="ph-caret-double-left"></i>Kembali</a>
                <button type="reset" class="btn btn-light ms-3" id="reset">Reset</button>
                <button type="submit" class="btn btn-primary ms-3">Submit <i class="ph-paper-plane-tilt ms-2"></i></button>
            </div>
        </form>
    </div>
    <!-- /form validation -->
@endsection
