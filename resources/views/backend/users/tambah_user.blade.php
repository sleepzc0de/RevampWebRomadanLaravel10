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
<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('.select').select2();

        // Reset form dan Select2
        $('#reset').on('click', function() {
            $('.select').val('').trigger('change');
        });

        // Validasi form
        $('.form-validate-jquery').validate({
            ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
            errorClass: 'validation-invalid-label',
            successClass: 'validation-valid-label',
            validClass: 'validation-valid-label',
            highlight: function(element, errorClass) {
                $(element).removeClass(errorClass);
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass) {
                $(element).removeClass('is-invalid');
                $(element).removeClass(errorClass);
            },
            errorPlacement: function(error, element) {
                // Input dengan class .select2
                if (element.hasClass('select2-hidden-accessible')) {
                    error.appendTo(element.parent());
                }
                // Input biasa
                else {
                    error.insertAfter(element);
                }
            }
        });
    });
</script>
@endsection

@section('content')

<!-- Form validation -->
					<div class="card">

                        <div class="card-header">
                            <h5 class="mb-0">Tambah User WEBSITE ROMADAN</h5>
                            @if(session('failed'))
                                <div class="alert alert-danger alert-dismissible fade show mt-2">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    <i class="ph-x-circle me-2"></i>
                                    {{ session('failed') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show mt-2">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    <i class="ph-x-circle me-2"></i>
                                    <strong>Error!</strong> Silakan periksa kembali form anda:
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

						<form class="form-validate-jquery" action="{{route('users.store')}}" autocomplete="off" method="POST">
                            @csrf
							<div class="card-body">

                                <div class="mb-4">

									<!-- Username Input -->
									{{-- <div class="row mb-3">
										<label class="col-form-label col-lg-3">Username <span class="text-danger">*</span></label>
										<div class="col-lg-9">
											<input type="text" name="username" class="form-control" required placeholder="Username">
										</div>
									</div> --}}
									<!-- /Username Input -->

                                    <!-- NIP INPUT -->
									{{-- <div class="row mb-3">
										<label class="col-form-label col-lg-3">NIP <span class="text-danger">*</span></label>
										<div class="col-lg-9">
											<input type="text" name="nip" class="form-control" required placeholder="NIP">
										</div>
									</div> --}}
									<!-- /NIP INPUT -->

                                   <!-- NAMA INPUT -->
<div class="row mb-3">
    <label class="col-form-label col-lg-3">Nama <span class="text-danger">*</span></label>
    <div class="col-lg-9">
        <input maxlength="100"
               type="text"
               name="name"
               class="form-control @error('name') is-invalid @enderror"
               required
               placeholder="Nama"
               value="{{ old('name') }}">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- EMAIL INPUT -->
<div class="row mb-3">
    <label class="col-form-label col-lg-3">Email <span class="text-danger">*</span></label>
    <div class="col-lg-9">
        <input type="email"
               name="email"
               class="form-control @error('email') is-invalid @enderror"
               required
               placeholder="Email"
               value="{{ old('email') }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- ROLE SELECT -->
<div class="row mb-3">
    <label class="col-form-label col-lg-3">Role <span class="text-danger">*</span></label>
    <div class="col-lg-9">
        <select id="role"
                name="role"
                class="form-control form-control-select2 select @error('role') is-invalid @enderror"
                required>
            <option value="">--Pilih Role--</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                    {{$loop->iteration}} - {{$role->name}}
                </option>
            @endforeach
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- PASSWORD INPUT -->
<div class="row mb-3">
    <label class="col-form-label col-lg-3">Password <span class="text-danger">*</span></label>
    <div class="col-lg-9">
        <input type="password"
               name="password"
               class="form-control @error('password') is-invalid @enderror"
               required
               placeholder="Password">
        <small class="form-text text-muted">
            Password harus memiliki minimal 8 karakter dan mengandung kombinasi huruf besar kecil, angka, dan simbol.
        </small>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- PASSWORD CONFIRMATION -->
<div class="row mb-3">
    <label class="col-form-label col-lg-3">Konfirmasi Password <span class="text-danger">*</span></label>
    <div class="col-lg-9">
        <input type="password"
               name="password_confirmation"
               class="form-control @error('password_confirmation') is-invalid @enderror"
               required
               placeholder="Konfirmasi Password">
        @error('password_confirmation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

                                    {{-- @error('name')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    @error('email')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    @error('password')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror --}}

								</div>
						</div>

						<div class="card-footer d-flex justify-content-end">
							<a href="{{route('users.index') }}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
							<button type="reset" class="btn btn-light ms-3" id="reset">Reset</button>
							<button type="submit" class="btn btn-primary ms-3">Submit <i class="ph-paper-plane-tilt ms-2"></i></button>
						</div>
							</form>
					</div>
					<!-- /form validation -->

@endsection
