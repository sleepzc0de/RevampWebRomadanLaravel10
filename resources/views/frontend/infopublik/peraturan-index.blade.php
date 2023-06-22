@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
<style>
  .input-with-logo {
	background-image: url('{{ asset('frontend_romadan_web/images/search_romadan_peraturan.svg') }}');
	background-size:20px;
	background-position: 10px center;
    background-repeat: no-repeat;
    padding-left: 40px; /* Sesuaikan jarak logo dengan input */
  }
</style>
@endsection


@section('content')


	<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
		<div class="container">
            <div class="title-section-ourmenu m-b-22">
					<h5 class="romadan-berita m-t-2">
						Informasi Publik
					</h5>
                    <br>
			</div>
                <div class="row">
					<div class="col-lg-4">
						<div class="alert alert-secondary" role="alert">
							<h5 class="alert-heading">REFINE YOUR SEARCH</h5>
							<form class="form-outline" action="{{route('informasi-publik-peraturan-index-fe')}}" method="POST" autocomplete="off">
								@csrf
								<div class="form-group">
									<input name="cari_peraturan" type="text" class="form-control mt-3 input-with-logo" id="cari_peraturan" aria-describedby="cari_peraturan" placeholder="Cari peraturan disini">
								</div>
							</form>
							<hr>
							<h5>Kategori</h5>
							@forelse ($kategori as $item)
							<div class="form-check mt-3">
								<input name="[]" class="form-check-input" type="checkbox" value="" id="">
								<label class="form-check-label" for="{{$item->nama_kategori}}">
									{{$item->nama_kategori}}
								</label>
							</div>
							@empty
								<label class="form-check-label" for="DataKosongKategori">
									Tidak Ada Data
								</label>
							@endforelse
							
							<h5 class="mt-4">Jenis Peraturan</h5>
							@forelse ($jenis_peraturan as $item)
								<div class="form-check mt-3">
								<input class="form-check-input" type="checkbox" value="" id="{{$item->nama_jenis_peraturan.$item->id_jenis_peraturan}}">
								<label class="form-check-label" for="{{$item->nama_jenis_peraturan.$item->id_jenis_peraturan}}">
									{{$item->nama_jenis_peraturan}}
								</label>
							</div>
							@empty
								<label class="form-check-label" for="DataKosongJenisPeraturan">
									Tidak Ada Data
								</label>
							@endforelse
							
						</div>
					</div>
					<div class="col-lg-8">
						<h4 class="romadan-peraturan-utama">Daftar Peraturan Tentang Barang Milik Negara & Pengadaan</h4>
						<div class="row">
							@forelse ($peraturan as $item)
								 <div class="col-md-6 p-t-30">
									<div class="card w-100">
										<div class="card-body">
											<h5 class="card-title romadan-peraturan-judul">{{$item->nomor_peraturan}}</h5>
											<p class="card-text romadan-peraturan-subjudul">{{$item->judul_peraturan}}</p>
											<a href="{{route('informasi-publik-peraturan-detail-fe', $item->slug)}}" class="btn romadan-peraturan-link mt-1">Lihat Semuanya<i class="fa-solid fa-arrow-right ml-3"></i></a>
										</div>
									</div>
					   		 </div>
							@empty
							
							<section class="section-welcome p-t-50 p-b-105" style="background-color: white;">

                                <div class="container">
									
                                    <div class="title-section-ourmenu m-b-22">
											<h3 class="m-b-2"> Anda sedang mencari : "{{$searchValue}}"</h3>
                                            <h5 class="romadan-faq m-t-5">
                                                Mohon maaf, data yang anda cari tidak ada :(
                                            </h5>
                                    </div>
                                    
                                </div>
                            
                            </section>
								
							@endforelse
							
							 {{-- <div class="col-md-6 p-t-30">
									<div class="card w-100" style="background-color:#0F5FAE;">
										<div class="card-body infopublik-card-home">
											<h5 class="card-title">B</h5>
											<p class="card-text">Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.</p>
											<a href="#" class="btn btn-link mt-1">Lihat Semuanya<i class="fa-solid fa-arrow-right ml-3"></i></a>
										</div>
									</div>
					   		 </div> --}}

							 
						</div>
						<div class="d-flex justify-content-center mt-5">
             					 {!! $peraturan->appends(request()->input())->links() !!}
       						 </div>
					</div>
                </div>
            </div>
	</section>

@section('script_fe')
@endsection

@endsection