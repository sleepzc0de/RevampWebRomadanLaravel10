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
							<h5 class="mb-0">Edit List Informasi Publik</h5>
						</div>

						<form class="form-validate-jquery" action="{{route('informasi-publik.update', encrypt($infopub->id))}}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
                            @method('PUT')
							<div class="card-body">
							
								<div class="mb-4">

                                <!-- Judul Artikel input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Judul List Informasi Publik <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('judul_list_informasi') ?? $infopub->judul_list_informasi }}" type="text" name="judul_list_informasi" class="form-control @error('judul_list_informasi') is-invalid @enderror" required placeholder="Masukkan Judul kegiatan">
											<!-- error message untuk judul_list_informasi -->
											@error('judul_list_informasi')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Judul Artikel input -->

									<!-- kegiatan -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Isi Informasi Publik <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<textarea name="isi_list_informasi" class="form-control @error('isi_list_informasi') is-invalid @enderror" required placeholder="Isikan List Informasi" id="ckeditor_classic_empty">{{ old('isi_list_informasi') ?? $infopub->isi_list_informasi }}</textarea>
										</div>
									</div>
									<!-- /kegiatan -->

									<!-- link Absen -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Link List Informasi <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('link_list_informasi') ?? $infopub->link_list_informasi }}" type="text" name="link_list_informasi" class="form-control @error('link_list_informasi') is-invalid @enderror" required placeholder="Masukkan Link List Informasi">
											<!-- error message untuk link_list_informasi -->
											@error('link_list_informasi')
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




