@extends('layouts.webromadan_frontend.fe_master')

@section('content')

<section class="border-b border-slate-100 bg-slate-50/60 py-10 dark:border-white/10 dark:bg-navy-900/40 sm:py-14">
    <div class="fe-container">
        <span class="fe-eyebrow">Pencarian</span>
        <h1 class="mt-3 text-2xl font-extrabold text-navy-800 sm:text-3xl dark:text-white">Cari Informasi</h1>

        <form action="{{ route('search-fe') }}" method="GET" class="mt-6 max-w-xl">
            <div class="relative">
                <input type="text" name="q" value="{{ $query }}" autofocus placeholder="Cari berita, peraturan, kegiatan, FAQ, dan lainnya..."
                    class="w-full rounded-full border border-slate-200 bg-white py-3.5 pr-14 pl-5 text-sm text-navy-800 shadow-[var(--shadow-soft)] transition placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-4 focus:ring-brand-100 dark:border-white/10 dark:bg-navy-900 dark:text-white dark:placeholder:text-slate-500">
                <button type="submit" aria-label="Cari" class="absolute top-1/2 right-2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-brand-700 text-white transition hover:bg-brand-800">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </button>
            </div>
        </form>
    </div>
</section>

<section class="py-10 sm:py-14">
    <div class="fe-container">
        @if (mb_strlen($query) < 2)
            <p class="text-slate-500 dark:text-slate-400">Masukkan minimal 2 karakter untuk mulai mencari.</p>
        @elseif ($results->total() === 0)
            <div class="rounded-2xl border border-dashed border-slate-200 py-16 text-center dark:border-white/10">
                <i class="fa-solid fa-magnifying-glass mb-3 text-3xl text-slate-300 dark:text-slate-600"></i>
                <p class="text-slate-500 dark:text-slate-400">Tidak ada hasil untuk "<span class="font-semibold text-navy-800 dark:text-white">{{ $query }}</span>".</p>
            </div>
        @else
            <p class="mb-6 text-sm text-slate-500 dark:text-slate-400">
                Menampilkan {{ $results->total() }} hasil untuk "<span class="font-semibold text-navy-800 dark:text-white">{{ $query }}</span>"
            </p>

            <div class="space-y-4">
                @foreach ($results as $item)
                    <a href="{{ $item['url'] }}" class="fe-card block p-5 sm:p-6">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="fe-pill !py-1 !px-3 text-xs">{{ $item['type'] }}</span>
                            @if ($item['date'])
                                <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($item['date'])->translatedFormat('d F Y') }}</span>
                            @endif
                        </div>
                        <h2 class="mt-2.5 text-lg font-bold text-navy-800 dark:text-white">{{ $item['title'] }}</h2>
                        @if ($item['excerpt'])
                            <p class="mt-1.5 line-clamp-2 text-sm text-slate-500 dark:text-slate-400">{{ $item['excerpt'] }}</p>
                        @endif
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {!! $results->appends(['q' => $query])->links() !!}
            </div>
        @endif
    </div>
</section>
@endsection
