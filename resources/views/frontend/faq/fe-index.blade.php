@extends('layouts.webromadan_frontend.fe_master')

@section('title', 'FAQ — Biro Manajemen BMN dan Pengadaan')

@section('content')
<section class="bg-white py-16 sm:py-20 dark:bg-navy-950">
    <div class="fe-container">
        <div class="text-center">
            <span class="fe-eyebrow justify-center">Bantuan</span>
            <h1 class="mt-3 text-3xl font-extrabold text-navy-800 sm:text-4xl dark:text-white">Pertanyaan yang Sering Diajukan</h1>
            <p class="mt-3 text-slate-500 dark:text-slate-400">Temukan jawaban atas pertanyaan umum seputar layanan kami.</p>
        </div>

        @if ($faq->count() > 0)
            <div x-data="faqAccordion" class="mt-12 space-y-3">
                @foreach ($faq as $item)
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[var(--shadow-soft)] dark:border-white/10 dark:bg-navy-900">
                        <button type="button" @click="toggle({{ $loop->index }})"
                            :aria-expanded="openIndex === {{ $loop->index }}"
                            class="flex w-full items-center gap-4 px-5 py-4 text-left transition"
                            :class="openIndex === {{ $loop->index }} ? 'bg-brand-700 text-white' : 'hover:bg-slate-50 dark:hover:bg-white/5'">
                            <span class="flex h-8 w-8 flex-none items-center justify-center rounded-full text-sm font-bold transition"
                                :class="openIndex === {{ $loop->index }} ? 'bg-white text-brand-700' : 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400'">
                                {{ $loop->iteration }}
                            </span>
                            <span class="flex-1 text-sm font-semibold sm:text-base" :class="openIndex === {{ $loop->index }} ? '' : 'dark:text-white'">{{ $item->faq_judul }}</span>
                            <i class="fa-solid fa-chevron-down flex-none text-xs transition-transform duration-300"
                               :class="openIndex === {{ $loop->index }} ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="openIndex === {{ $loop->index }}" x-collapse>
                            <div class="prose-fe border-t border-slate-100 px-5 py-5 dark:border-white/10">
                                {!! clean($item->faq_isi) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-fe.empty-state class="mt-12" icon="fa-circle-question" title="Belum ada FAQ" text="Pertanyaan yang sering diajukan belum tersedia, silakan hubungi administrator." />
        @endif
    </div>
</section>
@endsection
