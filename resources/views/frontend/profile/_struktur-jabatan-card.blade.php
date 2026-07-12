{{-- Kartu satu posisi jabatan — dipakai di level fork (org-tree) maupun
     level daftar vertikal (vtree). Menerima: $node (StrukturJabatanModel). --}}
<div class="org-card">
    <p class="text-xs leading-tight font-bold text-navy-800 dark:text-white">{{ $node->nama_jabatan }}</p>
    @if ($node->pejabat->isNotEmpty())
        <div class="mt-1.5 flex flex-col items-center gap-1">
            @foreach ($node->pejabat as $p)
                <div class="flex items-center gap-1.5">
                    @if ($p->foto)
                        <img
                            src="{{ asset('storage/romadan_gambar_web/' . $p->foto) }}"
                            alt="{{ $p->nama }}"
                            width="18"
                            height="18"
                            loading="lazy"
                            class="h-[18px] w-[18px] rounded-full object-cover"
                        >
                    @else
                        <span class="flex h-[18px] w-[18px] items-center justify-center rounded-full bg-brand-100 text-[8px] font-bold text-brand-700 dark:bg-brand-900 dark:text-brand-300">
                            {{ strtoupper(Str::substr($p->nama, 0, 1)) }}
                        </span>
                    @endif
                    <span class="text-[10px] leading-tight text-slate-600 dark:text-slate-300">{{ $p->nama }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>
