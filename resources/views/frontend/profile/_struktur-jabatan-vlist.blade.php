{{-- Daftar posisi bertumpuk vertikal dengan garis siku (elbow connector),
     dipakai untuk seluruh level di bawah cabang pertama supaya bagan tetap
     compact/vertikal, tidak melebar ke samping tiap turun satu level.
     Menerima: $items (koleksi StrukturJabatanModel sejawat), $byParent. --}}
<div class="vtree">
    @foreach ($items as $item)
        @php $grandchildren = $byParent->get($item->id, collect()); @endphp
        <div class="vtree-item">
            @include('frontend.profile._struktur-jabatan-card', ['node' => $item])
            @if ($grandchildren->isNotEmpty())
                <div class="mt-2 ml-4">
                    @include('frontend.profile._struktur-jabatan-vlist', ['items' => $grandchildren, 'byParent' => $byParent])
                </div>
            @endif
        </div>
    @endforeach
</div>
