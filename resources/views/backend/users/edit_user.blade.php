@extends('layouts.webromadan_backend.master_layout')

@section('css')
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
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit User</h5>
        @if(session('success'))
            <div class="alert alert-success alert-icon-start alert-dismissible fade show">
                <span class="alert-icon bg-success text-white">
                    <i class="ph-gear"></i>
                </span>
                <span class="fw-semibold">{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('failed'))
            <div class="alert alert-danger alert-icon-start alert-dismissible fade show">
                <span class="alert-icon bg-danger text-white">
                    <i class="ph-gear"></i>
                </span>
                <span class="fw-semibold">{{ session('failed') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <form class="form-validate-jquery" action="{{ route('users.update', $data['encrypted_id']) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="mb-4">
                <div class="row mb-3">
                    <label class="col-form-label col-lg-3">Nama <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <input maxlength="100" type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               required placeholder="Nama" value="{{ old('name', $data['user']->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-form-label col-lg-3">Email <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               required placeholder="Email" value="{{ old('email', $data['user']->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-form-label col-lg-3">Role <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <select name="role" class="form-control form-control-select2 @error('role') is-invalid @enderror" required>
                            <option value="">--Pilih Role--</option>
                            @foreach ($data['role'] as $role)
                                <option value="{{ $role->id }}"
                                    {{ (old('role', optional($data['olduser'])->id) == $role->id) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-form-label col-lg-3">Password</label>
                    <div class="col-lg-9">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Password">
                        <span class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti password</span>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-form-label col-lg-3">Konfirmasi Password</label>
                    <div class="col-lg-9">
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="Konfirmasi Password">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
            <a href="{{ route('users.index') }}" class="btn btn-warning me-2">Kembali</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection
