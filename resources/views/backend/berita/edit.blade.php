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
							<h5 class="mb-0">Edit Berita</h5>
						</div>

						<form class="form-validate-jquery" action="{{route('berita.update', encrypt($berita->id))}}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
                            @method('PUT')
							<div class="card-body">
							
								<div class="mb-4">

									<!-- Judul Berita input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Judul Berita <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('judul') ?? $berita->judul }}" type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" required placeholder="Masukkan Judul Berita">
											<!-- error message untuk judul -->
											@error('judul')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Judul Berita input -->

                                    <!-- Sub Judul Berita input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Sub Judul Berita <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('sub_judul') ?? $berita->sub_judul }}" type="text" name="sub_judul" class="form-control @error('sub_judul') is-invalid @enderror" required placeholder="Masukkan Sub Judul Berita">
											<!-- error message untuk judul -->
												@error('sub_judul')
												<div class="alert alert-danger mt-2">
													{{ $message }}
												</div>
												@enderror
										</div>
									</div>
									<!-- /Sub Judul Berita input -->

                                    <!-- Kategori Berita -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Kategori Berita <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<select id="kategori" name="kategori" class="form-control form-control-select2 select" @error('kategori') is-invalid @enderror required>
												<option>--PILIH--</option>
												@foreach ($kategori as $item)
												<option value="{{ $item->id_kategori }}" {{ old('kategori',$berita->kategori) == $item->id_kategori ? 'selected' : ''}}>{{$loop->iteration." - ".$item->nama_kategori}}</option>
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
									<!-- /Kategori Berita -->

                                    <!-- Image file uploader -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Gambar Berita <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input type="file" class="form-control @error('image') is-invalid @enderror" id="customFile" name="image">
											<div class="mt-3">
												<img src="{{asset('storage/romadan_gambar_web/'.$berita->image)}}" alt="" width="300px">
											</div>
											
                                            
											@error('image')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /image file uploader -->
                                    
									<!-- Isi Berita Input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Isi Berita <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											{{-- <textarea rows="5" cols="5" name="isi" class="form-control @error('isi') is-invalid @enderror" required placeholder="Isi Berita">{{old('isi')?? $berita->isi}}</textarea> --}}
											<textarea name="isi" class="form-control @error('isi') is-invalid @enderror" required placeholder="Isi Berita" id="ckeditor_classic_empty">{{ old('isi') ?? $berita->isi }}</textarea>
										</div>
									</div>
									<!-- /Isi Berita Input -->

									<!-- Status Warta -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Status Berita <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<select id="status" name="status" class="form-control form-control-select2 select" @error('status') is-invalid @enderror required>
												<option>--PILIH--</option>
												@foreach ($status as $item)
												<option value="{{ $item->id_status }}" {{ old('status',$berita->status) == $item->id_status ? 'selected' : ''}}>{{$loop->iteration." - ".$item->nama_status}}</option>
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
							<a href="{{ URL::previous() }}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
							<a href="{{route('berita.index')}}"><button class="btn btn-light ms-3">Batal</button></a>
							<button type="submit" class="btn btn-primary ms-3">Update <i class="ph-paper-plane-tilt ms-2"></i></button>
						</div>
							</form>
					</div>
<!-- /form validation -->
@endsection




