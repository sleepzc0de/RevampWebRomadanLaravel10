@forelse ($data as $item)
    <div class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[var(--shadow-soft)] transition duration-300 hover:-translate-y-1 hover:shadow-[var(--shadow-lift)] dark:border-white/10 dark:bg-navy-900">
        <a href="{{ $item->link_aplikasi }}" target="_blank" rel="noopener noreferrer" class="flex flex-1 items-center gap-4 p-5">
            <img src="{{ asset('storage/romadan_gambar_web/' . $item->image) }}" alt="{{ $item->judul_aplikasi }}"
                class="h-14 w-14 flex-none rounded-xl object-contain" loading="lazy">
            <div class="min-w-0 flex-1">
                <h3 class="line-clamp-2 text-sm font-bold text-navy-800 dark:text-white" title="{{ $item->judul_aplikasi }}">
                    {{ Str::limit($item->judul_aplikasi, 40) }}
                </h3>
                <p class="mt-1 line-clamp-2 text-xs text-slate-500 dark:text-slate-400" title="{{ $item->sub_judul_aplikasi }}">
                    {{ Str::limit($item->sub_judul_aplikasi, 60) }}
                </p>
            </div>
            <i class="fa-solid fa-arrow-right flex-none text-brand-700 transition group-hover:translate-x-1 dark:text-brand-400"></i>
        </a>
    </div>
@empty
    <div class="col-span-full">
        <x-fe.empty-state icon="fa-magnifying-glass" title="Tidak ditemukan" text="Mohon maaf, aplikasi yang Anda cari tidak ditemukan." />
    </div>
@endforelse
