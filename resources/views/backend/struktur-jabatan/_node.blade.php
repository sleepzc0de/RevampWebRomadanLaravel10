@php
    $children = $byParent->get($node->id, collect());
@endphp
<div class="struktur-node mb-3">
    <div class="card struktur-card">
        <div class="card-body d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div class="flex-grow-1">
                <h6 class="mb-2">{{ $node->nama_jabatan }}</h6>
                @if ($node->pejabat->isEmpty())
                    <span class="text-muted small">Belum ada pejabat</span>
                @else
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($node->pejabat as $p)
                            <div class="pejabat-chip d-flex align-items-center gap-2 border rounded-pill ps-1 pe-2 py-1">
                                @if ($p->foto)
                                    <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $p->foto]) }}" class="rounded-circle" width="28" height="28" style="object-fit:cover;background:#eef1f4;">
                                @else
                                    <span class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center text-white" style="width:28px;height:28px;font-size:.7rem;">{{ strtoupper(substr($p->nama, 0, 1)) }}</span>
                                @endif
                                <span class="small">{{ $p->nama }}</span>
                                <button type="button" class="btn btn-icon btn-sm p-0 ms-1 edit-pejabat-btn" data-id="{{ encrypt($p->id) }}" data-nama="{{ e($p->nama) }}" title="Edit pejabat">
                                    <i class="ph-pencil-simple"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-sm p-0 delete-btn" data-action="{{ route('struktur-jabatan.pejabat.destroy', encrypt($p->id)) }}" data-label="pejabat &quot;{{ e($p->nama) }}&quot;" title="Hapus pejabat">
                                    <i class="ph-x text-danger"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="d-flex flex-wrap gap-1">
                <button type="button" class="btn btn-sm btn-outline-primary add-pejabat-btn" data-jabatan-id="{{ encrypt($node->id) }}" data-jabatan-nama="{{ e($node->nama_jabatan) }}">
                    <i class="ph-user-plus"></i> Pejabat
                </button>
                <button type="button" class="btn btn-sm btn-outline-success add-jabatan-btn" data-parent-id="{{ encrypt($node->id) }}" data-parent-nama="{{ e($node->nama_jabatan) }}">
                    <i class="ph-plus"></i> Sub-Jabatan
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary edit-jabatan-btn" data-id="{{ encrypt($node->id) }}" data-nama="{{ e($node->nama_jabatan) }}" data-urutan="{{ $node->urutan }}" title="Edit jabatan">
                    <i class="ph-pencil-simple"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-action="{{ route('struktur-jabatan.jabatan.destroy', encrypt($node->id)) }}" data-label="jabatan &quot;{{ e($node->nama_jabatan) }}&quot;" title="Hapus jabatan">
                    <i class="ph-trash"></i>
                </button>
            </div>
        </div>
    </div>
    @if ($children->isNotEmpty())
        <div class="struktur-children ms-4 ps-3 border-start mt-2">
            @foreach ($children as $child)
                @include('backend.struktur-jabatan._node', ['node' => $child, 'byParent' => $byParent])
            @endforeach
        </div>
    @endif
</div>
