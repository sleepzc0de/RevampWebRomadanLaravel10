{{$data}}
<!-- Images Gallery -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Galeri Gambar</h5>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($data->images as $image)
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <img src="{{ asset('storage/romadan_gambar_web/'.$image->image_path) }}"
                             class="card-img-top" alt="Image" style="height: 200px; object-fit: cover;">
                        <div class="card-body p-2 text-center">
                            @if($image->is_primary)
                                <span class="badge bg-primary">Gambar Utama</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            @if($data->images->count() < 1)
                <div class="col-12">
                    <div class="alert alert-warning">
                        Tidak ada gambar tersedia.
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- /Images Gallery -->
