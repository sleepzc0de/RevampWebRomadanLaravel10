@php
    $children = $byParent->get($node->id, collect());
@endphp
<div class="w-full rounded-2xl bg-white px-4 py-3 text-left shadow-[var(--shadow-soft)] ring-1 ring-slate-900/5 dark:bg-navy-900 dark:ring-white/10">
    <p class="text-sm font-bold text-navy-800 dark:text-white">{{ $node->nama_jabatan }}</p>
    @if ($node->pejabat->isNotEmpty())
        <div class="mt-2 flex flex-col gap-1.5">
            @foreach ($node->pejabat as $p)
                <div class="flex items-center gap-2">
                    @if ($p->foto)
                        <img
                            src="{{ asset('storage/romadan_gambar_web/' . $p->foto) }}"
                            alt="{{ $p->nama }}"
                            width="24"
                            height="24"
                            loading="lazy"
                            class="h-6 w-6 rounded-full object-cover"
                        >
                    @else
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-brand-100 text-[10px] font-bold text-brand-700 dark:bg-brand-900 dark:text-brand-300">
                            {{ strtoupper(Str::substr($p->nama, 0, 1)) }}
                        </span>
                    @endif
                    <span class="text-xs text-slate-600 dark:text-slate-300">{{ $p->nama }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>
@if ($children->isNotEmpty())
    <div class="mt-3 mb-1 ml-3 space-y-3 border-l-2 border-slate-200 pl-4 dark:border-white/10">
        @foreach ($children as $child)
            @include('frontend.profile._struktur-jabatan-node-mobile', ['node' => $child, 'byParent' => $byParent])
        @endforeach
    </div>
@endif
