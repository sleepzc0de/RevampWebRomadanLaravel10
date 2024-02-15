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
{{-- <script src="{{asset('webromadan/be/demo/pages/editor_ckeditor_classic.js')}}"></script> --}}
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
        ClassicEditor.create(document.querySelector('#ckeditor_classic_empty'), {
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
});
</script>
<script>
	function handleTanggalMulaiChange() {
  var tanggalMulai = document.getElementById('tanggal_penetapan').value;
  var tanggalSelesai = document.getElementById('tanggal_berlaku');
  
  if (tanggalMulai) {
    tanggalSelesai.disabled = false;
    tanggalSelesai.min = tanggalMulai;
  } else {
    tanggalSelesai.disabled = true;
    tanggalSelesai.value = '';
  }
}

</script>
@endsection

@section('content')
<!-- Form validation -->
					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">Tambah Peraturan</h5>
                            @include('layouts.webromadan_backend.session_notif')
						</div>

						<form class="form-validate-jquery" action="{{route('peraturan.store')}}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
							<div class="card-body">
							
								<div class="mb-4">

									<!-- Nomor Peraturan input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Nomor Peraturan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('nomor_peraturan') }}" type="text" name="nomor_peraturan" class="form-control @error('nomor_peraturan') is-invalid @enderror" required placeholder="Masukkan Nomor Peraturan">
											<!-- error message untuk nomor_peraturan -->
											@error('nomor_peraturan')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Nomor Peraturan input -->

                                    <!-- Judul Peraturan -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Judul Peraturan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('judul_peraturan') }}" type="text" name="judul_peraturan" class="form-control @error('judul_peraturan') is-invalid @enderror" required placeholder="Masukkan Judul Peraturan">
											<!-- error message untuk judul -->
												@error('judul_peraturan')
												<div class="alert alert-danger mt-2">
													{{ $message }}
												</div>
												@enderror
										</div>
									</div>
									<!-- /Judul Peraturan -->

                                    <!-- File Kegiatan -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">File Peraturan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input type="file" class="form-control @error('file') is-invalid @enderror required" id="file" name="file">
											@error('file')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /File Kegiatan -->


                                    <!-- Kategori Peraturan-->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Kategori Peraturan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<select value="{{ old('kategori') }}" name="kategori" class="form-control form-control-select2 select" @error('kategori') is-invalid @enderror required>
												<option>--PILIH--</option>
												@foreach ($kategori as $item)
												<option value="{{ $item->id_kategori }}" {{ old('kategori') == $item->id_kategori ? 'selected' : null}}>{{$loop->iteration." - ".$item->nama_kategori}}</option>
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
									<!-- /Kategori Peraturan-->

                                    <!-- Jenis Peraturan-->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Kategori Peraturan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<select value="{{ old('jenis_peraturan') }}" name="jenis_peraturan" class="form-control form-control-select2 select" @error('jenis_peraturan') is-invalid @enderror required>
												<option>--PILIH--</option>
												@foreach ($data_jenis_peraturan as $item)
												<option value="{{ $item->id_jenis_peraturan }}" {{ old('jenis_peraturan') == $item->id_jenis_peraturan ? 'selected' : null}}>{{$loop->iteration." - ".$item->nama_jenis_peraturan}}</option>
												@endforeach
													
												
											</select>

											<!-- error message untuk judul -->
											@error('jenis_peraturan')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Jenis Peraturan-->

                                    <!-- Tanggal Penetapan-->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Tanggal Penetapan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input class="form-control @error('tanggal_penetapan') is-invalid @enderror required" id="tanggal_penetapan" name="tanggal_penetapan" type="date" onchange="handleTanggalMulaiChange()" required>
											@error('tanggal_penetapan')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Tanggal Penetapan-->

                                    <!-- Tanggal Berlaku Efektif-->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Tanggal Berlaku <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input class="form-control @error('tanggal_berlaku') is-invalid @enderror required" id="tanggal_berlaku" name="tanggal_berlaku" type="date" required>
											@error('tanggal_berlaku')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Tanggal Berlaku Efektif-->

                                    <!-- Status Peraturan-->
								<div class="row mb-3">
										<label class="col-form-label col-lg-2">Status Peraturan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											@if (count($data_status_peraturan) <= 0)
													<input value="{{ old('status_peraturan') }}" type="text" name="status_peraturan" class="form-control @error('status_peraturan') is-invalid @enderror" required placeholder="DATA KOSONG, HARAP HUBUNGI ADMINISTRATOR" @disabled(true)>
											@else 
											<select value="{{ old('status_peraturan') }}" name="status_peraturan" class="form-control form-control-select2 select" @error('status_peraturan') is-invalid @enderror required>
												<option>--PILIH--</option>
												@foreach ($data_status_peraturan as $item)
												<option value="{{ $item->id_ref_peraturan_status }}" {{ old('status_peraturan') == $item->id_ref_peraturan_status ? 'selected' : null}}>{{$loop->iteration." - ".$item->nama_peraturan_status}}</option>
												@endforeach
													
												
											</select>

											<!-- error message untuk judul -->
											@error('status_peraturan')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
											@endif
											
										</div>
									</div>
									<!-- /Jenis Peraturan-->

								</div>

						</div>

						<div class="card-footer d-flex justify-content-end">
							<a href="{{route('peraturan.index') }}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
							<button type="reset" class="btn btn-light ms-3" id="reset">Reset</button>
							<button type="submit" class="btn btn-primary ms-3">Submit <i class="ph-paper-plane-tilt ms-2"></i></button>
						</div>
							</form>
					</div>
<!-- /form validation -->
@endsection




