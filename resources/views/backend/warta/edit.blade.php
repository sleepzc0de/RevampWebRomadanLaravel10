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
							<h5 class="mb-0">Edit Warta</h5>
						</div>

						<form class="form-validate-jquery" action="{{route('warta.update', encrypt($warta->id))}}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
                            @method('PUT')
							<div class="card-body">
							
								<div class="mb-4">

									<!-- Judul warta input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Judul Warta <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('judul') ?? $warta->judul }}" type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" required placeholder="Masukkan Judul warta">
											<!-- error message untuk judul -->
											@error('judul')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Judul warta input -->

                                    <!-- Sub Judul warta input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Sub Judul Warta <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('sub_judul') ?? $warta->sub_judul }}" type="text" name="sub_judul" class="form-control @error('sub_judul') is-invalid @enderror" required placeholder="Masukkan Sub Judul warta">
											<!-- error message untuk judul -->
												@error('sub_judul')
												<div class="alert alert-danger mt-2">
													{{ $message }}
												</div>
												@enderror
										</div>
									</div>
									<!-- /Sub Judul warta input -->

                                    <!-- Kategori warta -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Kategori Warta <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<select id="kategori" name="kategori" class="form-control form-control-select2 select" @error('kategori') is-invalid @enderror required>
												<option>--PILIH--</option>
												@foreach ($kategori as $item)
												<option value="{{ $item->id_kategori }}" {{ old('kategori',$warta->kategori) == $item->id_kategori ? 'selected' : ''}}>{{$loop->iteration." - ".$item->nama_kategori}}</option>
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
									<!-- /Kategori warta -->

                                    <!-- Image file uploader -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Gambar Warta <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input type="file" class="form-control @error('image') is-invalid @enderror" id="customFile" name="image">
											<div class="mt-3">
												<img src="{{asset('storage/romadan_gambar_web/'.$warta->image)}}" alt="" width="300px">
											</div>
											
                                            
											@error('image')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /image file uploader -->
                                    
									<!-- Isi warta Input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Isi Warta <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											{{-- <textarea rows="5" cols="5" name="isi" class="form-control @error('isi') is-invalid @enderror" required placeholder="Isi warta">{{old('isi')?? $warta->isi}}</textarea> --}}
											<textarea name="isi" class="form-control @error('isi') is-invalid @enderror" required placeholder="Isi warta" id="ckeditor_classic_empty">{{ old('isi') ?? $warta->isi }}</textarea>
										</div>
									</div>
									<!-- /Isi warta Input -->

									<!-- Status Warta -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Status Warta <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<select id="status" name="status" class="form-control form-control-select2 select" @error('status') is-invalid @enderror required>
												<option>--PILIH--</option>
												@foreach ($status as $item)
												<option value="{{ $item->id_status }}" {{ old('status',$warta->status) == $item->id_status ? 'selected' : ''}}>{{$loop->iteration." - ".$item->nama_status}}</option>
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

								</div>

						</div>

						<div class="card-footer d-flex justify-content-end">
							<a href="{{ route('warta.index') }}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
							<button type="reset" class="btn btn-dark ms-3">Reset</button>
							<button type="submit" class="btn btn-primary ms-3">Update <i class="ph-paper-plane-tilt ms-2"></i></button>
						</div>
							</form>
					</div>
<!-- /form validation -->
@endsection




