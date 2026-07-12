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
        <h5 class="mb-0">Informasi Kontak (Footer)</h5>
        @include('layouts.webromadan_backend.session_notif')
    </div>

    <form class="form-validate-jquery" action="{{route('contact-info.update', encrypt($contact->id))}}" method="post" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <p class="text-muted">Informasi ini akan tampil pada bagian "Hubungi Kami" di footer seluruh halaman frontend.</p>

            <div class="mb-4">
                <!-- Email -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Email</label>
                    <div class="col-lg-10">
                        <input maxlength="255" value="{{ old('email') ?? $contact->email }}" type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="contoh@kemenkeu.go.id">
                        @error('email')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- /Email -->

                <!-- WhatsApp -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Nomor WhatsApp</label>
                    <div class="col-lg-10">
                        <input maxlength="50" value="{{ old('whatsapp') ?? $contact->whatsapp }}" type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" placeholder="0813-1000-4134">
                        @error('whatsapp')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- /WhatsApp -->

                <!-- Alamat -->
                <div class="row mb-3">
                    <label class="col-form-label col-lg-2">Alamat</label>
                    <div class="col-lg-10">
                        <textarea maxlength="500" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Gedung Djuanda 2 Lt. 16-17, Jl. Dr. Wahidin Raya No. 1">{{ old('address') ?? $contact->address }}</textarea>
                        @error('address')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- /Alamat -->
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
            <button type="submit" class="btn btn-primary ms-3">Simpan <i class="ph-paper-plane-tilt ms-2"></i></button>
        </div>
    </form>
</div>
<!-- /form validation -->
@endsection
