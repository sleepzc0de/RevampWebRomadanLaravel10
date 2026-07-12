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
<script src="{{asset('webromadan/be/demo/pages/editor_ckeditor_classic.js')}}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var inputTanggal = document.getElementById('created_at');

        // Ambil hari ini dengan format yang sesuai
        var now = new Date();
        var year = now.getFullYear();
        var month = ('0' + (now.getMonth() + 1)).slice(-2);
        var day = ('0' + now.getDate()).slice(-2);
        var hours = ('0' + now.getHours()).slice(-2);
        var minutes = ('0' + now.getMinutes()).slice(-2);

        // Set nilai maksimum ke hari ini pada jam dan menit saat ini
        var maxDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
        inputTanggal.setAttribute('max', maxDateTime);
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // New images preview functionality
        const newImagesInput = document.getElementById('new_images');
        const previewContainer = document.getElementById('preview-container');

        newImagesInput.addEventListener('change', function() {
            previewContainer.innerHTML = ''; // Clear previous previews

            if (this.files) {
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const previewDiv = document.createElement('div');
                            previewDiv.className = 'position-relative';

                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'img-thumbnail';
                            img.style.width = '150px';
                            img.style.height = '150px';
                            img.style.objectFit = 'cover';

                            previewDiv.appendChild(img);
                            previewContainer.appendChild(previewDiv);
                        };

                        reader.readAsDataURL(file);
                    }
                }
            }
        });

        // Prevent deleting the primary image
        const deleteCheckboxes = document.querySelectorAll('.delete-image-checkbox');
        deleteCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const imageId = this.value;
                const primaryRadio = document.getElementById(`primary_${imageId}`);

                // If this is the primary image
                if (!primaryRadio && this.checked) {
                    alert('Anda tidak dapat menghapus gambar utama. Silakan pilih gambar lain sebagai utama terlebih dahulu.');
                    this.checked = false;
                }
            });
        });
    });
    </script>
@endsection

@section('content')

<!-- Form validation -->
<div class="card">
	<div class="card-header">
		<h5 class="mb-0">Edit publikasi</h5>
	</div>

	<form class="form-validate-jquery" action="{{route('publikasi.update', encrypt($publikasi->id))}}" method="post" enctype="multipart/form-data" autocomplete="off">
		@csrf
		@method('PUT')
		<div class="card-body">

			<div class="mb-4">

				<!-- Judul publikasi input -->
				<div class="row mb-3">
					<label class="col-form-label col-lg-2">Judul publikasi <span class="text-danger">*</span></label>
					<div class="col-lg-10">
						<input maxlength="255" value="{{ old('judul') ?? $publikasi->judul }}" type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" required placeholder="Masukkan Judul publikasi">
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
						<input maxlength="255" value="{{ old('sub_judul') ?? $publikasi->sub_judul }}" type="text" name="sub_judul" class="form-control @error('sub_judul') is-invalid @enderror" required placeholder="Masukkan Sub Judul publikasi">
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
						<select id="tipe" name="tipe" class="form-control form-control-select2 select" @error('tipe') is-invalid @enderror required>
							<option>--PILIH--</option>
							@foreach ($tipe as $item)
							<option value="{{ $item->id_tipe }}" {{ old('tipe',$publikasi->tipe) == $item->id_tipe ? 'selected' : ''}}>{{$loop->iteration." - ".$item->nama_tipe}}</option>
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
						<select id="kategori" name="kategori" class="form-control form-control-select2 select" @error('kategori') is-invalid @enderror required>
							<option>--PILIH--</option>
							@foreach ($kategori as $item)
							<option value="{{ $item->id_kategori }}" {{ old('kategori',$publikasi->kategori) == $item->id_kategori ? 'selected' : ''}}>{{$loop->iteration." - ".$item->nama_kategori}}</option>
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

			<!-- Image gallery and management -->
<div class="row mb-3">
    <label class="col-form-label col-lg-2">Gambar publikasi <span class="text-danger">*</span></label>
    <div class="col-lg-10">
        <!-- Current images gallery -->
        <div class="mb-3">
            <h6>Gambar saat ini:</h6>
            <div class="d-flex flex-wrap gap-3 mb-3" id="current-images">
                @foreach($publikasi->images as $image)
                <div class="position-relative" id="image-container-{{ $image->id }}">
                    <img src="{{ asset('storage/romadan_gambar_web/'.$image->image_path) }}"
                         alt="" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">

                    <div class="position-absolute top-0 start-0 d-flex">
                        @if($image->is_primary)
                        <span class="badge bg-primary">Utama</span>
                        @else
                        <div class="form-check form-check-inline mt-1 ms-1">
                            <input class="form-check-input" type="radio" name="primary_image"
                                   id="primary_{{ $image->id }}" value="{{ $image->id }}">
                            <label class="form-check-label text-white small" for="primary_{{ $image->id }}">
                                Set Utama
                            </label>
                        </div>
                        @endif
                    </div>

                    <div class="position-absolute bottom-0 end-0">
                        <div class="form-check form-check-inline mb-1 me-1">
                            <input class="form-check-input delete-image-checkbox" type="checkbox"
                                   name="delete_images[]" id="delete_{{ $image->id }}" value="{{ $image->id }}">
                            <label class="form-check-label text-danger small" for="delete_{{ $image->id }}">
                                Hapus
                            </label>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($publikasi->images->count() < 1)
            <div class="alert alert-warning">
                Tidak ada gambar tersedia. Silakan unggah minimal satu gambar.
            </div>
            @endif
        </div>

        <!-- Upload new images -->
        <div>
            <h6>Tambah gambar baru:</h6>
            <input type="file" class="form-control @error('new_images') is-invalid @enderror"
                   id="new_images" name="new_images[]" multiple accept="image/jpeg,image/png,image/jpg,image/svg">
            <small class="text-muted">Anda dapat memilih beberapa gambar sekaligus.</small>
            <div id="preview-container" class="d-flex flex-wrap gap-2 mt-2"></div>

            @error('new_images')
            <div class="alert alert-danger mt-2">
                {{ $message }}
            </div>
            @enderror
            @error('new_images.*')
            <div class="alert alert-danger mt-2">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
</div>
<!-- /Image gallery and management -->

                 <!-- File publikasi -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">File Publikasi <span class="text-danger"></span></label>
										<div class="col-lg-10">
											<input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file">
											<div class="mt-3">
                                                <a target="_blank" href="{{asset('storage/romadan_file_web/'.$publikasi->file)}}">{{$publikasi->file}}</a>
											</div>

											@error('file')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /File publikasi -->

				<!-- Isi publikasi Input -->
				<div class="row mb-3">
					<label class="col-form-label col-lg-2">Isi publikasi <span class="text-danger">*</span></label>
					<div class="col-lg-10">
						{{-- <textarea rows="5" cols="5" name="isi" class="form-control @error('isi') is-invalid @enderror" required placeholder="Isi publikasi">{{old('isi')?? $publikasi->isi}}</textarea> --}}
						<textarea maxlength="25000" name="isi" class="form-control @error('isi') is-invalid @enderror" required placeholder="Isi publikasi" id="ckeditor_classic_empty">{{ old('isi') ?? $publikasi->isi }}</textarea>
					</div>
				</div>
				<!-- /Isi publikasi Input -->

                <!-- Embedded Media URL Input -->
<div class="row mb-3">
    <label class="col-form-label col-lg-2">URL Media (Video/Image) <span class="text-danger"></span></label>
    <div class="col-lg-10">
        <input type="url" name="embedded_media" value="{{ old('embedded_media') ?? $publikasi->embedded_media }}" class="form-control @error('embedded_media') is-invalid @enderror" placeholder="https://youtube.com/watch?v=example or image URL">
        <small class="text-muted">Masukkan URL YouTube, Vimeo, atau gambar yang ingin ditampilkan</small>

        @if($publikasi->embedded_media)
        <div class="mt-3">
            <h6>Preview Media:</h6>
            <div class="mb-2">
                {!! $publikasi->getEmbeddedMediaHtml() !!}
            </div>
            <p class="text-muted">URL saat ini: {{ $publikasi->embedded_media }}</p>
        </div>
        @endif

        @error('embedded_media')
        <div class="alert alert-danger mt-2">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
<!-- /Embedded Media URL Input -->

				<!-- Status Warta -->
				<div class="row mb-3">
					<label class="col-form-label col-lg-2">Status publikasi <span class="text-danger">*</span></label>
					<div class="col-lg-10">
						<select id="status" name="status" class="form-control form-control-select2 select" @error('status') is-invalid @enderror required>
							<option>--PILIH--</option>
							@foreach ($status as $item)
							<option value="{{ $item->nama_status }}" {{ old('status',$publikasi->status) == $item->nama_status ? 'selected' : ''}}>{{$loop->iteration." - ".$item->nama_status}}</option>
							@endforeach


						</select>

						<!-- error message untuk judul -->
						@error('status')
						<div class="alert alert-danger mt-2">
							{{ $message }}
						</div>
						@enderror
					</div>
				</div>
				<!-- /Status Warta -->

				<!-- Jadwalkan Publikasi -->
				<div class="row mb-3" id="wrap-published-at" style="display:none;">
					<label class="col-form-label col-lg-2">Waktu Terbit Terjadwal <span class="text-danger">*</span></label>
					<div class="col-lg-10">
						<input class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', $publikasi->published_at ? Carbon\Carbon::parse($publikasi->published_at)->format('Y-m-d\TH:i') : '') }}">
						<small class="text-muted">Publikasi akan otomatis berstatus "published" saat waktu ini tercapai.</small>
						@error('published_at')
						<div class="alert alert-danger mt-2">
							{{ $message }}
						</div>
						@enderror
					</div>
				</div>
				<!-- /Jadwalkan Publikasi -->
				<script>
					document.addEventListener('DOMContentLoaded', function () {
						const statusSelect = document.getElementById('status');
						const wrap = document.getElementById('wrap-published-at');
						if (!statusSelect || !wrap) return;
						const toggle = function () {
							wrap.style.display = statusSelect.value === 'scheduled' ? '' : 'none';
						};
						statusSelect.addEventListener('change', toggle);
						toggle();
					});
				</script>

				<!-- Tanggal Terbit -->
				<div class="row mb-3">
					<label class="col-form-label col-lg-2">Tanggal Terbit <span class="text-danger">*</span></label>
					<div class="col-lg-10">
						<input class="form-control @error('created_at') is-invalid @enderror required" id="created_at" name="created_at" type="datetime-local" value="{{ old('created_at', $publikasi->created_at ? Carbon\Carbon::parse($publikasi->created_at)->format('Y-m-d\TH:i') : '') }}">
						@error('created_at')
						<div class="alert alert-danger mt-2">
							{{ $message }}
						</div>
						@enderror
					</div>
				</div>
				<!-- /Tanggal Terbit -->








			</div>

		</div>

		<div class="card-footer d-flex justify-content-end">
			<a href="{{ URL::previous() }}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
			<a href="{{route('publikasi.index')}}"><button class="btn btn-light ms-3">Batal</button></a>
			<button type="submit" class="btn btn-primary ms-3">Update <i class="ph-paper-plane-tilt ms-2"></i></button>
		</div>
	</form>
</div>
<!-- /form validation -->
@endsection
