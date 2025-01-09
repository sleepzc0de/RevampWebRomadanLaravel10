@if ($isSearch)
    <div class="search-result-message">
        <h3 class="m-0">Anda sedang mencari: "{{ $searchValue }}"</h3>
    </div>
@endif

<div class="row">
    @forelse ($artikel as $item)
        <div class="col-md-4 p-t-30">
            <div class="blo4">
                <div class="pic-blo4 hov-img-zoom bo-rad-10 pos-relative">
                    <a href="{{ route('artikel-fe', $item->slug) }}">
                        <img src="{{ asset('storage/romadan_gambar_web/'.$item->image) }}" alt="IMG-BLOG">
                    </a>
                </div>
                <div class="text-blo4">
                    <div class="txt32 flex-w p-b-24">
                        <span>
                            {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('j F Y') }}
                            <span class="m-r-6 m-l-4">|</span>
                        </span>
                        <span>
                            {{ strlen($item->nama_kategori) <= 3 ? strtoupper($item->nama_kategori) : ucfirst(strtolower($item->nama_kategori)) }}
                        </span>
                        <span class="m-l-4">
                            <i class="fa-regular fa-eye"></i> {{ $item->views }} Views
                        </span>
                    </div>
                    <a href="{{ route('artikel-fe', $item->slug) }}" class="berita-terkini-judul-romadan">{{ $item->judul }}</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center">
            <div class="title-section-ourmenu m-b-22">
                <h5 class="romadan-faq m-t-5">
                    Mohon maaf, data yang Bapak/Ibu cari belum tersedia :(
                </h5>
            </div>
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-5">
    {!! $artikel->appends(request()->input())->links() !!}
</div>


