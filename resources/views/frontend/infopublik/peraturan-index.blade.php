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
					<h5 class="txt-judul-infopublik m-t-2">
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
							
							<hr>
							<h5>Kategori</h5>
							@forelse ($kategori as $item)
							<div class="form-check mt-3">
								<input name="kategori[]" class="form-check-input" type="checkbox" value="{{$item->nama_kategori}}" id="{{$item->nama_kategori}}" {{ is_array($selectedKategori) && in_array($item->nama_kategori, $selectedKategori) ? 'checked' : '' }}>
								<label class="form-check-label" for="{{$item->nama_kategori}}" onChange="submitForm()" >
									{{$item->nama_kategori}}
								</label>
							</div>
							@empty
								<label class="form-check-label" for="DataKosongKategori">
									Tidak Ada Data
								</label>
							@endforelse
							
							
							
							{{-- <input type="submit" value="Submit"> --}}
							
							<h5 class="mt-4">Jenis Peraturan</h5>
							@forelse ($jenis_peraturan as $item)
								<div class="form-check mt-3">
								<input name="jenis_peraturan[]" class="form-check-input" type="checkbox" value="{{$item->nama_jenis_peraturan}}" id="{{$item->nama_jenis_peraturan}}" {{ is_array($selectedJenisPeraturan) && in_array($item->nama_jenis_peraturan, $selectedJenisPeraturan) ? 'checked' : '' }}>

								<label class="form-check-label" for="{{$item->nama_jenis_peraturan}}">
									{{$item->nama_jenis_peraturan}}
								</label>
							</div>
							@empty
								<label class="form-check-label" for="DataKosongJenisPeraturan">
									Tidak Ada Data
								</label>
							@endforelse

							<div class="wrap-btn-booking flex-c-m m-t-13">
								<button type="submit" class="btn3-kegiatan flex-c-m size36 txt11 trans-0-4">
									Cari
								</button>
								<a class="btn3-kegiatan-refresh flex-c-m size36 txt11 trans-0-4 ml-2" href="{{route('informasi-publik-peraturan-index-fe')}}">Refresh
								</a>
							</div>	
							</form>
						</div>
					</div>
					<div class="col-lg-8">
						<h4 class="romadan-peraturan-utama">Daftar Peraturan Tentang Barang Milik Negara & Pengadaan</h4>
						<div class="row p-t-30">
							@forelse ($peraturan as $item)
								 {{-- <div class="col-md-6">
									<div class="card w-100" style="height: 170px; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);">
										<div class="card-body">
											<h5 class="card-title romadan-peraturan-judul">{{$item->nomor_peraturan}}</h5>
											<p class="card-text romadan-peraturan-subjudul">{{$item->judul_peraturan}}</p>
											<a href="{{route('informasi-publik-peraturan-detail-fe', $item->slug)}}" class="btn romadan-peraturan-link mt-2">Lihat Semuanya<i class="fa-solid fa-arrow-right ml-3"></i></a>
										</div>
									</div>
					   		     </div> --}}
								 <div class="col-md-6">
									<a class="card3" style="height: 100%;" href="{{route('informasi-publik-peraturan-detail-fe', $item->slug)}}">
										<h5 class="card-title romadan-peraturan-judul">{{$item->nomor_peraturan}}</h5>
										<p class="small">{{$item->judul_peraturan}}</p>
										<h6 class="mt-2">Lihat Detail<i class="fa-solid fa-arrow-right ml-3"></i></h6>
										<div class="dimmer"></div>
										<div class="go-corner-card3" href="{{route('informasi-publik-peraturan-detail-fe', $item->slug)}}">
										<div class="go-arrow-card3">
											→
										</div>
										</div>
									</a>
								 </div>
							@empty
							
							<section class="section-welcome p-t-50 p-b-105" style="background-color: white;">

                                <div class="container">
									
                                    <div class="title-section-ourmenu m-b-22">
											{{-- <h3 class="m-b-2"> Anda sedang mencari : "{{$searchValue}}"</h3> --}}
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
{{-- <script>
    function submitForm() {
        document.getElementById('filterForm').submit();
    }
</script> --}}


@endsection

@endsection