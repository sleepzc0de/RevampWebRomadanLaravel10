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
@endsection

@section('content')

<!-- Form validation -->
					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">Edit User</h5>
                             @if($message = Session::get('success'))
                            <div class="alert alert-success alert-icon-start alert-dismissible fade show">
											<span class="alert-icon bg-success text-white">
												<i class="ph-gear"></i>
											</span>
											<span class="fw-semibold">{{$message}}</span> 
											<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
									    </div>
                            @endif

                             @if($message = Session::get('failed'))
                            <div class="alert alert-danger alert-icon-start alert-dismissible fade show">
											<span class="alert-icon bg-danger text-white">
												<i class="ph-gear"></i>
											</span>
											<span class="fw-semibold">{{$message}}</span> 
											<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
									    </div>
                            @endif
						</div>

						<form class="form-validate-jquery" action="{{route('users.update', $userdata->id)}}" autocomplete="off" method="POST">
                            @method('PUT')
                            @csrf
							<div class="card-body">

                                <div class="mb-4">

									<!-- Username Input -->
									{{-- <div class="row mb-3">
										<label class="col-form-label col-lg-3">Username <span class="text-danger">*</span></label>
										<div class="col-lg-9">
											<input type="text" name="username" class="form-control" required placeholder="Username" value="{{old('username') ?? $userdata->username}}">
										</div>
									</div> --}}
									<!-- /Username Input -->

                                    <!-- NIP INPUT -->
									{{-- <div class="row mb-3">
										<label class="col-form-label col-lg-3">NIP <span class="text-danger">*</span></label>
										<div class="col-lg-9">
											<input type="text" name="nip" class="form-control" required placeholder="NIP" value="{{old('nip') ?? $userdata->nip}}">
										</div>
									</div> --}}
									<!-- /NIP INPUT -->

                                     <!-- NAMA INPUT -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-3">Nama <span class="text-danger">*</span></label>
										<div class="col-lg-9">
											<input type="text" name="name" class="form-control" required placeholder="Nama" value="{{old('name') ?? $userdata->name}}">
										</div>
									</div>
									<!-- /NAMA INPUT -->

                                    <!-- EMAIL INPUT -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-3">Email <span class="text-danger">*</span></label>
										<div class="col-lg-9">
											<input type="email" name="email" class="form-control" required placeholder="Email" value="{{old('email') ?? $userdata->email}}">
										</div>
									</div>
									<!-- /EMAIL INPUT -->

                                    <!-- PASSWORD INPUT -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-3">Password <span class="text-danger">*</span></label>
										<div class="col-lg-9">
											<input type="password" name="password" class="form-control" placeholder="Password">
                                            <span><i>Biarkan kosong jika tidak ingin mengganti password</i></span>
										</div>
									</div>
									<!-- /PASSWORD INPUT -->
								</div>
						</div>

						<div class="card-footer d-flex justify-content-end">
							<a href="{{route('users.index')}}" class="btn btn-warning ms-2">Kembali</a>
                            
							<button type="submit" class="btn btn-primary ms-2">Update</button>
						</div>
							</form>
					</div>
					<!-- /form validation -->

@endsection
