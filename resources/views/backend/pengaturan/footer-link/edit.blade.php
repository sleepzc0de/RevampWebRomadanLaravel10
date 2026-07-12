@extends('layouts.webromadan_backend.master_layout')

@section('css')
@endsection

@section('script_atas')
<script src="{{asset('webromadan/be/js/vendor/forms/validation/validate.min.js')}}"></script>
@endsection

@section('script_bawah')
<script src="{{asset('webromadan/be/demo/pages/form_validation_library.js')}}"></script>
@endsection

@section('content')
<!-- Form validation -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Tautan Footer</h5>
    </div>

    <form class="form-validate-jquery" action="{{route('footer-link.update', encrypt($footerLink->id))}}" method="post" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="mb-4">
                <!-- Label -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Label <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <input maxlength="100" value="{{ old('label') ?? $footerLink->label }}" type="text" name="label" class="form-control @error('label') is-invalid @enderror" required>
                        @error('label')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- /Label -->

                <!-- URL -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">URL <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <input maxlength="500" value="{{ old('url') ?? $footerLink->url }}" type="text" name="url" class="form-control @error('url') is-invalid @enderror" required>
                        <small class="text-muted">Bisa berupa path relatif (mis. /layanan) atau URL lengkap (mis. https://contoh.com).</small>
                        @error('url')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- /URL -->

                <!-- Urutan -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Urutan</label>
                    <div class="col-lg-10">
                        <input value="{{ old('sort_order') ?? $footerLink->sort_order }}" type="number" min="0" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror">
                        @error('sort_order')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- /Urutan -->

                <!-- Status -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Status</label>
                    <div class="col-lg-10">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="form-check-input" name="is_active" value="1" id="is_active" {{ old('is_active', $footerLink->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif (tampil di footer)</label>
                        </div>
                    </div>
                </div>
                <!-- /Status -->
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
            <a href="{{route('footer-link.index')}}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
            <button type="submit" class="btn btn-primary ms-3">Update <i class="ph-paper-plane-tilt ms-2"></i></button>
        </div>
    </form>
</div>
<!-- /form validation -->
@endsection
