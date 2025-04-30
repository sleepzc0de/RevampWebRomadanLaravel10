@extends('layouts.webromadan_backend.master_layout')

@section('css')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('webromadan/be/css/vendor/uploaders/dropzone.min.css')}}">
<!-- Add Sortable.js library -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
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
    $('#image').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#mainImagePreview').attr('src', e.target.result);
                $('#currentImageContainer').hide();
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
        }
    });

    // Handle image deletion
    $('.remove-image-checkbox').on('change', function() {
        const imgContainer = $(this).closest('.existing-image');
        if (this.checked) {
            imgContainer.addClass('opacity-50');
        } else {
            imgContainer.removeClass('opacity-50');
        }
    });

    // URL validator for YouTube
    function isValidYoutubeUrl(url) {
        if (!url) return true; // Allow empty
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
        if (!url) return null;
        const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?\/\s]{11})/i;
        const match = url.match(regExp);
        return (match && match[7].length == 11) ? match[7] : false;
    }

    // Initialize video preview if URL already exists
    const initialUrl = $('#videoUrl').val();
    if (initialUrl) {
        const videoId = extractYoutubeId(initialUrl);
        if (videoId) {
            $('#videoPreview').attr('src', 'https://www.youtube.com/embed/' + videoId);
            $('#videoPreviewContainer').show();
        }
    }

    // Initialize Sortable for reordering images
    const sortableContainer = document.getElementById('sortableImages');
    if (sortableContainer) {
        console.log('Initializing Sortable');
        const sortable = new Sortable(sortableContainer, {
            animation: 150,
            ghostClass: 'bg-light',
            handle: '.drag-handle',
            onEnd: function(evt) {
                console.log('Sorting ended');
                // Update sort order inputs
                $('#sortableImages .existing-image').each(function(index) {
                    const newOrder = index + 1;
                    $(this).find('input[name^="image_order"]').val(newOrder);
                    $(this).find('.order-number').text(newOrder);
                    console.log('Updated image ' + $(this).find('input[name^="image_order"]').attr('name') + ' to order ' + newOrder);
                });
            }
        });
    } else {
        console.warn('Sortable container not found');
    }
});
</script>

<style>
    .preview-container {
        margin-top: 10px;
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

    .existing-image {
        position: relative;
        margin-bottom: 15px;
        transition: opacity 0.3s;
    }

    .existing-image.opacity-50 {
        opacity: 0.5;
    }

    .image-controls {
        margin-top: 8px;
    }

    #sortableImages .existing-image {
        cursor: move;
    }

    .drag-handle {
        cursor: grab;
        display: inline-block;
        margin-right: 5px;
        background-color: #007bff;
        color: white;
        padding: 3px 8px;
        border-radius: 3px;
    }

    .drag-handle:active {
        cursor: grabbing;
    }

    .drag-handle i {
        margin-right: 2px;
    }
</style>
@endsection

@section('content')

<!-- Form validation -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Struktur Organisasi</h5>
        @include('layouts.webromadan_backend.session_notif')
    </div>

    <form class="form-validate-jquery" action="{{route('struktur-organisasi.update', encrypt($struktur->id))}}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="mb-4">

                <!-- Judul Artikel input -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Judul Struktur <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <input maxlength="255" value="{{ old('judul') ?? $struktur->judul }}" type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" required placeholder="Masukkan Judul Artikel">
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
                    <label class="col-form-label col-lg-2">Struktur <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <textarea maxlength="10000" name="struktur" class="form-control @error('struktur') is-invalid @enderror" required placeholder="struktur" id="ckeditor_classic_empty_struktur">{{ old('struktur') ?? $struktur->struktur }}</textarea>
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
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                        <small class="form-text text-muted">Format: JPEG, JPG, PNG, SVG. Ukuran maks: 20MB</small>

                        <div class="preview-container" id="currentImageContainer">
                            <div class="mt-3">
                                <h6>Gambar Utama Saat Ini:</h6>
                                <img src="{{asset('storage/romadan_gambar_web/'.$struktur->image)}}" alt="" class="img-fluid img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>

                        <div class="preview-container" id="mainImagePreviewContainer" style="display:none;">
                            <div class="mt-3">
                                <h6>Pratinjau Gambar Utama Baru:</h6>
                                <img id="mainImagePreview" src="#" alt="Preview" class="img-fluid img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>

                        @error('image')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <!-- /main image file uploader -->

                <!-- Existing additional images -->
                @if($struktur->additionalImages->count() > 0)
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Gambar Tambahan Saat Ini</label>
                    <div class="col-lg-10">
                        <div class="preview-container">
                            <h6>Klik dan tahan untuk menyeret gambar dan mengubah urutan</h6>
                            <div id="sortableImages" class="row">
                                @foreach($struktur->additionalImages->sortBy('sort_order') as $image)
                                <div class="col-md-3 existing-image">
                                    <div class="card">
                                        <img src="{{ asset('storage/romadan_gambar_web/' . $image->image_path) }}" class="img-fluid card-img-top" alt="Image {{ $loop->iteration }}">
                                        <div class="card-body p-2">
                                            <div class="image-controls">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input remove-image-checkbox" name="remove_images[]" value="{{ $image->id }}" id="remove_image_{{ $image->id }}">
                                                    <label class="form-check-label" for="remove_image_{{ $image->id }}">Hapus</label>
                                                </div>
                                                <input type="hidden" name="image_order[{{ $image->id }}]" value="{{ $image->sort_order }}">
                                                <span class="drag-handle badge bg-primary"><i class="ph-arrows-up-down"></i> Tarik</span>
                                                <span class="badge bg-secondary order-number">{{ $image->sort_order }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Additional Images file uploader -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Tambah Gambar Baru</label>
                    <div class="col-lg-10">
                        <input type="file" class="form-control @error('additional_images.*') is-invalid @enderror" id="additionalImages" name="additional_images[]" multiple>
                        <small class="form-text text-muted">Format: JPEG, JPG, PNG, SVG. Ukuran maks: 20MB per gambar</small>
                        @error('additional_images.*')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror

                        <div class="preview-container" id="additionalImagesContainer" style="display:none;">
                            <div class="mt-3">
                                <h6>Pratinjau Gambar Tambahan Baru:</h6>
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
                        <input type="url" class="form-control @error('video_url') is-invalid @enderror" id="videoUrl" name="video_url" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('video_url') ?? $struktur->video_url }}">
                        <small class="form-text text-muted">Masukkan URL video YouTube (opsional)</small>
                        <div id="videoUrlError" class="invalid-feedback" style="display: none;"></div>
                        @error('video_url')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror

                        <div class="preview-container" id="videoPreviewContainer" style="{{ !empty($struktur->video_url) ? '' : 'display:none;' }}">
                            <div class="mt-3">
                                <h6>Pratinjau Video:</h6>
                                <div class="youtube-container">
                                    <iframe id="videoPreview" width="560" height="315" src="{{ !empty($struktur->video_url) ? 'https://www.youtube.com/embed/'.$struktur->getYoutubeIdAttribute() : '' }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
                            <option value="standard" {{ (old('layout_type') ?? $struktur->layout_type) == 'standard' ? 'selected' : '' }}>Standar (50:50)</option>
                            <option value="wide" {{ (old('layout_type') ?? $struktur->layout_type) == 'wide' ? 'selected' : '' }}>Lebar (33:67)</option>
                            <option value="compact" {{ (old('layout_type') ?? $struktur->layout_type) == 'compact' ? 'selected' : '' }}>Kompak (67:33)</option>
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
            <a href="{{ route('struktur-organisasi.index') }}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
            <button type="reset" class="btn btn-dark ms-3">Reset</button>
            <button type="submit" class="btn btn-primary ms-3">Update <i class="ph-paper-plane-tilt ms-2"></i></button>
        </div>
    </form>
</div>
<!-- /form validation -->
@endsection
