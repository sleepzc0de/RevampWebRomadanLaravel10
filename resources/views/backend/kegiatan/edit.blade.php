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
@endsection

@section('content')

<!-- Form validation -->
					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">Edit Kegiatan</h5>
						</div>

						<form class="form-validate-jquery" action="{{route('kegiatan.update', encrypt($kegiatan->id))}}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
                            @method('PUT')
							<div class="card-body">
							
								<div class="mb-4">

                                <!-- Judul Artikel input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Judul Kegiatan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('judul') ?? $kegiatan->judul }}" type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" required placeholder="Masukkan Judul kegiatan">
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
											<input value="{{ old('tempat') ?? $kegiatan->tempat }}" type="text" name="tempat" class="form-control @error('tempat') is-invalid @enderror" required placeholder="Masukkan tempat Kegiatan">
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
											<div class="mt-3">
												<img src="{{asset('storage/romadan_gambar_web/'.$kegiatan->image)}}" alt="" width="300px">
											</div>
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
											<textarea name="isi" class="form-control @error('isi') is-invalid @enderror" required placeholder="isi kegiatan" id="ckeditor_classic_empty">{{ old('isi') ?? $kegiatan->isi }}</textarea>
										</div>
									</div>
									<!-- /kegiatan -->

                                    

									 <!-- File Kegiatan -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">File Kegiatan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file">
											<div class="mt-3">
												{{$kegiatan->file}}
											</div>

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
											<input class="form-control @error('tanggal_mulai') is-invalid @enderror required" id="tanggal_mulai" name="tanggal_mulai" type="datetime-local" value="{{ old('tanggal_mulai') ?? $kegiatan->tanggal_mulai }}">
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
											<input class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" type="datetime-local" value="{{ old('tanggal_selesai') ?? $kegiatan->tanggal_selesai }}">
											@error('tanggal_selesai')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Tanggal Selesai -->

									<!-- link Absen -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Link Absen <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('link') ?? $kegiatan->link }}" type="text" name="link" class="form-control @error('link') is-invalid @enderror" required placeholder="Masukkan Link Absen">
											<!-- error message untuk link -->
											@error('link')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /link Absen -->

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




