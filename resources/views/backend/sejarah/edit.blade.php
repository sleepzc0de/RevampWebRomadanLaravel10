@extends('layouts.webromadan_backend.master_layout')

@section('css')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<style>
    .media-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }
    .media-item {
        position: relative;
        width: 150px;
        height: 150px;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
    }
    .media-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .media-item-existing {
        position: relative;
        width: 150px;
        margin-bottom: 10px;
        margin-right: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
        display: inline-block;
    }
    .media-item-existing img {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }
    .media-item-existing .caption {
        padding: 5px;
        background: #f9f9f9;
        font-size: 12px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .media-checkbox {
        position: absolute;
        top: 5px;
        right: 5px;
        z-index: 10;
    }
    .media-checkbox .form-check-input {
        background-color: rgba(255, 255, 255, 0.8);
        border-color: rgba(0, 0, 0, 0.5);
    }
    .media-checkbox .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .video-item {
        background: #f5f5f5;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .video-item-existing {
        background: #f5f5f5;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
        position: relative;
    }
    .video-item-existing .icon {
        margin-right: 10px;
        font-size: 24px;
        color: #dc3545;
    }
    .video-item i {
        margin-right: 10px;
        font-size: 24px;
        color: #dc3545;
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
 *  # CKEditor Classic editor
 *  Demo JS code for editor_ckeditor_classic.html page
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
        ClassicEditor.create(document.querySelector('#ckeditor_classic_empty_sejarah'), {
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
            // Enhanced toolbar with more formatting options
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'link',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'outdent',
                    'indent',
                    '|',
                    'blockQuote',
                    'insertTable',
                    'undo',
                    'redo'
                ]
            },
            // Configure tables
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells'
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

    // Check all media items by default
    $('.media-checkbox input').prop('checked', true);

    // Image preview for multiple images
    $('#images').on('change', function() {
        const files = Array.from(this.files);
        const previewContainer = $('.image-preview-container');

        previewContainer.html(''); // Clear previous previews

        files.forEach(file => {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewContainer.append(`
                    <div class="media-item">
                        <img src="${e.target.result}" alt="${file.name}" title="${file.name}">
                    </div>
                `);
            };

            reader.readAsDataURL(file);
        });
    });

    // Handle "Select All" checkbox for media
    $('#select-all-media').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.media-checkbox input').prop('checked', isChecked);
    });

    // Add more video URL fields
    let videoCount = $('.video-url-group').length;

    $('#add-video-url').on('click', function(e) {
        e.preventDefault();
        videoCount++;

        $('#video-urls-container').append(`
            <div class="video-url-group mb-2">
                <div class="input-group">
                    <input type="url" name="video_urls[]" class="form-control" placeholder="Masukkan URL video (YouTube, Vimeo, dll)">
                    <button type="button" class="btn btn-danger remove-video">
                        <i class="ph-trash"></i>
                    </button>
                </div>
            </div>
        `);
    });

    // Remove video URL field
    $(document).on('click', '.remove-video', function() {
        $(this).closest('.video-url-group').remove();
    });
});
</script>
@endsection

@section('content')

<!-- Form validation -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Sejarah</h5>
        @include('layouts.webromadan_backend.session_notif')
    </div>

    <form class="form-validate-jquery" action="{{route('sejarah.update', encrypt($sejarah->id))}}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="mb-4">

                 <!-- Judul Artikel input -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Judul Sejarah <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <input maxlength="255" value="{{ old('judul') ?? $sejarah->judul }}" type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" required placeholder="Masukkan Judul Sejarah">
                        <!-- error message untuk judul -->
                        @error('judul')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <!-- /Judul Artikel input -->

                <!-- SEJARAH -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Sejarah <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <textarea maxlength="10000" name="sejarah" class="form-control @error('sejarah') is-invalid @enderror" required placeholder="Sejarah" id="ckeditor_classic_empty_sejarah">{{ old('sejarah') ?? $sejarah->sejarah }}</textarea>
                        @error('sejarah')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <!-- /SEJARAH -->

                <!-- Existing Media (Images and Videos) -->
                @if(isset($sejarah->media) && $sejarah->media)
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Media Saat Ini</label>
                    <div class="col-lg-10">
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="select-all-media" checked>
                            <label class="form-check-label" for="select-all-media">Pilih Semua</label>
                        </div>

                        <div class="existing-media-container">
                            @php
                                $mediaItems = json_decode($sejarah->media, true) ?? [];
                            @endphp

                            @foreach($mediaItems as $index => $item)
                                @if($item['type'] === 'image')
                                    <div class="media-item-existing">
                                        <div class="media-checkbox">
                                            <input type="checkbox" class="form-check-input" name="keep_media[]" value="{{ $index }}" checked>
                                        </div>
                                        <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $item['path']]) }}" alt="{{ $item['original_name'] ?? 'Image' }}" style="background:#eef1f4;">
                                        <div class="caption">{{ $item['original_name'] ?? 'Image' }}</div>
                                    </div>
                                @elseif($item['type'] === 'video')
                                    <div class="video-item-existing">
                                        <div class="media-checkbox">
                                            <input type="checkbox" class="form-check-input" name="keep_media[]" value="{{ $index }}" checked>
                                        </div>
                                        <i class="ph-video icon"></i>
                                        <span class="video-url">{{ $item['url'] }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="form-text text-muted">Centang kotak di sebelah item media untuk menyimpannya. Item yang tidak dicentang akan dihapus.</div>
                    </div>
                </div>
                @endif

                <!-- Main Image (Legacy) -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Gambar Utama</label>
                    <div class="col-lg-10">
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                        <div class="form-text text-muted">Format: JPEG, PNG, JPG, SVG. Ukuran maksimal: 20MB</div>
                        @if($sejarah->image)
                        <div class="mt-3">
                            <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $sejarah->image]) }}" alt="" class="img-fluid" style="max-width: 300px; max-height: 200px; background:#eef1f4;">
                        </div>
                        @endif
                        @error('image')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <!-- /Main Image -->

                <!-- Multiple Images -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Tambah Gambar Baru</label>
                    <div class="col-lg-10">
                        <input type="file" class="form-control @error('images.*') is-invalid @enderror" id="images" name="images[]" multiple>
                        <div class="form-text text-muted">Pilih beberapa file gambar. Format: JPEG, PNG, JPG, SVG. Ukuran maksimal: 20MB per gambar</div>
                        <div class="image-preview-container media-preview-container">
                            <!-- Image previews will be added here -->
                        </div>
                        @error('images.*')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <!-- /Multiple Images -->

                <!-- Video URLs -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Tambah URL Video Baru</label>
                    <div class="col-lg-10">
                        <div id="video-urls-container">
                            <div class="video-url-group mb-2">
                                <div class="input-group">
                                    <input type="url" name="video_urls[]" class="form-control" placeholder="Masukkan URL video (YouTube, Vimeo, dll)">
                                    <button type="button" class="btn btn-danger remove-video">
                                        <i class="ph-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-video-url" class="btn btn-light btn-sm mt-2">
                            <i class="ph-plus me-1"></i> Tambah URL Video
                        </button>
                        <div class="form-text text-muted">Masukkan URL video dari YouTube, Vimeo, atau platform video lainnya.</div>
                        @error('video_urls.*')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <!-- /Video URLs -->

            </div>

        </div>

        <div class="card-footer d-flex justify-content-end">
            <a href="{{ route('sejarah.index') }}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
            <button type="reset" class="btn btn-dark ms-3">Reset</button>
            <button type="submit" class="btn btn-primary ms-3">Update <i class="ph-paper-plane-tilt ms-2"></i></button>
        </div>
    </form>
</div>
<!-- /form validation -->
@endsection
