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

						<form class="form-validate-jquery" action="{{route('faq.update', encrypt($faq->id))}}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
                            @method('PUT')
							<div class="card-body">
							
								<div class="mb-4">

                                <!-- FAQ JUDUL -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Pertanyaan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('faq_judul') ?? $faq->faq_judul }}" type="text" name="faq_judul" class="form-control @error('judul') is-invalid @enderror" required placeholder="Masukkan Pertanyaan FAQ">
											<!-- error message untuk judul -->
											@error('faq_judul')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /FAQ JUDUL -->

                                    <!-- JAWABAN FAQ -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Jawaban <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<textarea name="faq_isi" class="form-control @error('faq_isi') is-invalid @enderror" required placeholder="Jawaban FAQ">{{ old('faq_isi') ?? $faq->faq_isi }}</textarea>
										</div>
									</div>
									<!-- /JAWABAN FAQ -->

                                     <!-- FAQ KATEGORI -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Kategori FAQ <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<select id="kategori" name="kategori" class="form-control form-control-select2 select" @error('kategori') is-invalid @enderror required>
												<option>--PILIH--</option>
												@foreach ($kategori as $item)
												<option value="{{ $item->id_kategori }}" {{ old('kategori',$faq->kategori) == $item->id_kategori ? 'selected' : ''}}>{{$loop->iteration." - ".$item->nama_kategori}}</option>
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
									<!-- /FAQ KATEGORI -->

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




