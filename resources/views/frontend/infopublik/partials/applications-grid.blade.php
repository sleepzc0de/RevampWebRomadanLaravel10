{{-- resources/views/frontend/infopublik/partials/applications-grid.blade.php --}}
@forelse ($data as $item)
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="app-card">
            <a href="{{ $item->link_aplikasi }}" class="app-link" target="_blank" rel="noopener noreferrer">
                <div class="app-content">
                    <img
                        src="{{ asset('storage/romadan_gambar_web/' . $item->image) }}"
                        class="app-icon"
                        alt="{{ $item->judul_aplikasi }}"
                        loading="lazy"
                    >
                    <div class="app-info">
                        <h3 class="app-title">{{ $item->judul_aplikasi }}</h3>
                        <p class="app-subtitle">{{ $item->sub_judul_aplikasi }}</p>
                    </div>
                    <i class="fa-solid fa-arrow-right app-arrow"></i>
                </div>
            </a>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="empty-state">
            <i class="fas fa-search"></i>
            <p>Mohon maaf, aplikasi yang Anda cari tidak ditemukan!</p>
        </div>
    </div>
@endforelse
