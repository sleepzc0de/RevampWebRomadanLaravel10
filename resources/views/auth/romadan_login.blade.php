

@extends('layouts.webromadan_frontend.master_layout')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/css/romadan.css')}}">
@endsection


@section('script_atas')
@endsection

@section('script_bawah')
@endsection


@section('content')

<div class="page-content" style="background-color: white">
<div class="col-lg-6" style="background-image: url({{asset('storage/romadan_gambar_web/'.$gambar->image)}});background-size: cover;background-position: center;
">

</div>
<div class="col-lg-6 mt-4">
<!-- Content area -->
				<div class="content d-flex justify-content-center align-items-center">

					<!-- Login card -->
					<form method="POST" action="{{ route('login') }}" autocomplete="off">
                        @csrf
						<div class="p-3">
							<div class="text-center mb-3">
								<div class="d-inline-flex align-items-center justify-content-center mb-2 mt-2">
									<img src="{{asset('webromadan/fe/images/romadan/logo_3.png')}}" class="h-48px" alt="">
								</div>
								
							</div>
                            <div class="txt-login-romadan mb-3">LOGIN</div>

							<div class="mb-3">
								<label class="form-label txt-login-romadan-user-pass">Email</label>
								<div class="form-control-feedback form-control-feedback-start">
									<input name="email" id="email" type="email" class="form-control" placeholder="Masukkan Email" required>
									<div class="form-control-feedback-icon">
										<i class="ph-user-circle text-muted"></i>
									</div>
								</div>
                                  <x-input-error :messages="$errors->get('email')" class="mt-2" />
							</div>

							<div class="mb-3">
								<label class="form-label txt-login-romadan-user-pass">Password</label>
								<div class="form-control-feedback form-control-feedback-start">
									<input name="password" type="password" class="form-control" placeholder="Masukkan Password">
									<div class="form-control-feedback-icon">
										<i class="ph-lock text-muted"></i>
									</div>
								</div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2"/>
							</div>

							<div class="">
								<button type="submit" class="btn btn-primary w-100 txt-login-romadan-submit">Let's Go !<i class="ph-arrow-right ms-2"></i></button>
							</div>
						</div>
					</form>
					<!-- /login card -->

	</div>
	<!-- /page content -->
    <!-- Footer -->
<div class="navbar navbar-sm navbar-footer">
    <div class="container-fluid">
        <span></span>
        
        <span>&copy; {{date("Y",strtotime(now()))}} <a style="color: #0F5FAE;" href="http://www.romadan.kemenkeu.go.id/">Biro Manajemen BMN dan Pengadaan.</a> Powered by MTDI</span>
        <span></span>
    </div>
</div>
<!-- /footer -->
</div>
</div>

				



@endsection
