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
						<input value="{{ old('judul') ?? $publikasi->judul }}" type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" required placeholder="Masukkan Judul publikasi">
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
						<input value="{{ old('sub_judul') ?? $publikasi->sub_judul }}" type="text" name="sub_judul" class="form-control @error('sub_judul') is-invalid @enderror" required placeholder="Masukkan Sub Judul publikasi">
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

				<!-- Image file uploader -->
				<div class="row mb-3">
					<label class="col-form-label col-lg-2">Gambar publikasi <span class="text-danger">*</span></label>
					<div class="col-lg-10">
						<input type="file" class="form-control @error('image') is-invalid @enderror" id="customFile" name="image">
						<div class="mt-3">
							<img src="{{asset('storage/romadan_gambar_web/'.$publikasi->image)}}" alt="" width="300px">
						</div>


						@error('image')
						<div class="alert alert-danger mt-2">
							{{ $message }}
						</div>
						@enderror
					</div>
				</div>
				<!-- /image file uploader -->

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
						<textarea name="isi" class="form-control @error('isi') is-invalid @enderror" required placeholder="Isi publikasi" id="ckeditor_classic_empty">{{ old('isi') ?? $publikasi->isi }}</textarea>
					</div>
				</div>
				<!-- /Isi publikasi Input -->

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

				<!-- Tanggal Terbit -->
				<div class="row mb-3">
					<label class="col-form-label col-lg-2">Tanggal Terbit <span class="text-danger">*</span></label>
					<div class="col-lg-10">
						<input class="form-control @error('created_at') is-invalid @enderror required" id="created_at" name="created_at" type="datetime-local" value="{{ old('created_at') ?? $publikasi->created_at }}">
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
