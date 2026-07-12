@extends('layouts.webromadan_backend.master_layout')

@section('css')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/datatables.min.js')}}"></script>
@endsection

@section('script_atas')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/forms/validation/validate.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/forms/selects/select2.min.js')}}"></script>
@endsection

@section('script_bawah')
<script src="{{asset('webromadan/be/demo/pages/form_validation_library.js')}}"></script>
<script src="{{asset('webromadan/be/demo/pages/form_select2.js')}}"></script>
@endsection

@section('content')
<!-- Form validation -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Pedoman</h5>
        @include('layouts.webromadan_backend.session_notif')
    </div>

    <form class="form-validate-jquery" action="{{route('pedoman.store')}}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="card-body">
            <div class="mb-4">
                <!-- Judul Pedoman -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Judul Pedoman <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <input maxlength="255" value="{{ old('judul_pedoman') }}" type="text" name="judul_pedoman" class="form-control @error('judul_pedoman') is-invalid @enderror" required placeholder="Masukkan Judul Pedoman">
                        @error('judul_pedoman')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- /Judul Pedoman -->

                <!-- Deskripsi -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Deskripsi</label>
                    <div class="col-lg-10">
                        <textarea maxlength="1000" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Deskripsi singkat pedoman (opsional)">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- /Deskripsi -->

                <!-- Kategori -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Kategori</label>
                    <div class="col-lg-10">
                        <select name="kategori" class="form-control form-control-select2 select @error('kategori') is-invalid @enderror">
                            <option value="">-- Pilih Kategori (opsional) --</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->id_kategori }}" {{ old('kategori') == $item->id_kategori ? 'selected' : '' }}>{{ $item->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- /Kategori -->

                <!-- Tanggal Terbit -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Tanggal Terbit</label>
                    <div class="col-lg-10">
                        <input value="{{ old('tanggal_terbit') }}" type="date" name="tanggal_terbit" class="form-control @error('tanggal_terbit') is-invalid @enderror">
                        @error('tanggal_terbit')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- /Tanggal Terbit -->

                <!-- File uploader -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">File Pedoman <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" required>
                        <small class="text-muted">Format: doc, docx, ppt, pptx, csv, xls, xlsx, pdf, zip, rar. Maks 20MB.</small>
                        @error('file')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- /File uploader -->
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
            <a href="{{route('pedoman.index')}}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
            <button type="reset" class="btn btn-light ms-3" id="reset">Reset</button>
            <button type="submit" class="btn btn-primary ms-3">Submit <i class="ph-paper-plane-tilt ms-2"></i></button>
        </div>
    </form>
</div>
<!-- /form validation -->
@endsection
