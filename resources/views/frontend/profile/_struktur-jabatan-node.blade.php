@php
    $children = $byParent->get($node->id, collect());
    $depth = $depth ?? 0;
@endphp
<li>
    @include('frontend.profile._struktur-jabatan-card', ['node' => $node])

    @if ($children->isNotEmpty())
        @if ($depth === 0)
            {{-- Hanya satu level cabang horizontal (root -> anak langsung),
                 persis seperti "Board -> President -> 3 Director" pada
                 bagan referensi. Level di bawahnya memakai daftar vertikal
                 (vtree) supaya bagan tidak melebar terus ke samping. --}}
            <ul>
                @foreach ($children as $child)
                    @include('frontend.profile._struktur-jabatan-node', ['node' => $child, 'byParent' => $byParent, 'depth' => $depth + 1])
                @endforeach
            </ul>
        @else
            <div class="mt-2 flex justify-center">
                @include('frontend.profile._struktur-jabatan-vlist', ['items' => $children, 'byParent' => $byParent])
            </div>
        @endif
    @endif
</li>
