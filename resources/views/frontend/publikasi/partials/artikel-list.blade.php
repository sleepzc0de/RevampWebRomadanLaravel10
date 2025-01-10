@if ($isSearch)
    <div class="search-result-message">
        Anda sedang mencari: "{{ e($searchValue) }}"
    </div>
@endif

@if ($artikel->count() > 0)
    <div class="row">
        @foreach ($artikel as $item)
            <div class="col-md-4 p-b-30">
                <div class="blo4">
                    <div class="pic-blo4 hov-img-zoom bo-rad-10 pos-relative">
                        <a href="{{ route('artikel-fe', $item->slug) }}">
                            <img loading="lazy"
                                 src="{{ asset('storage/romadan_gambar_web/' . e($item->image)) }}"
                                 alt="{{ e($item->judul) }}"
                                 onerror="this.src='{{ asset('images/placeholder-image.jpg') }}'">
                        </a>
                    </div>
                    <div class="text-blo4">
                        <div class="txt32 flex-w p-b-24">
                            <span>
                                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('j F Y') }}
                                <span class="m-r-6 m-l-4">|</span>
                            </span>
                            <span>
                                {{ strlen($item->nama_kategori) <= 3 ? strtoupper(e($item->nama_kategori)) : ucfirst(strtolower(e($item->nama_kategori))) }}
                            </span>
                            <span class="m-l-4">
                                <i class="fa-regular fa-eye"></i> {{ number_format($item->views) }} Views
                            </span>
                        </div>
                        <a href="{{ route('artikel-fe', $item->slug) }}"
                            class="artikel-terkini-judul-romadan">{{ e($item->judul) }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($artikel->hasPages())
        <div class="pagination-container">
            {{ $artikel->links() }}
        </div>
    @endif
@else
    <div class="search-result-message-not-found">
        Mohon maaf, data yang Bapak/Ibu cari belum tersedia :(
    </div>
@endif
