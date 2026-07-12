@extends('layouts.webromadan_frontend.fe_master')

@section('title', $data->nomor_peraturan . ' — Biro Manajemen BMN dan Pengadaan')

@section('content')
@php
    $rows = [
        ['label' => 'Nomor', 'value' => $data->nomor_peraturan],
        ['label' => 'Judul', 'value' => $data->judul_peraturan],
        ['label' => 'Bentuk', 'value' => $data->data_jenis_peraturan->nama_jenis_peraturan ?? '-'],
        ['label' => 'Tanggal Penetapan', 'value' => $data->tanggal_penetapan],
        ['label' => 'Tanggal Berlaku Efektif', 'value' => $data->tanggal_berlaku],
        ['label' => 'Status', 'value' => $data->data_status_peraturan->nama_peraturan_status ?? '-'],
    ];
@endphp

<section class="bg-white py-16 sm:py-20 dark:bg-navy-950">
    <div class="fe-container max-w-3xl">
        <a href="{{ route('informasi-publik-peraturan-index-fe') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-700 hover:text-brand-800 dark:text-brand-400 dark:hover:text-brand-300">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Daftar Peraturan
        </a>

        <div class="mt-6">
            <span class="fe-eyebrow">Detail Peraturan</span>
            <h1 class="mt-3 text-2xl font-extrabold text-navy-800 sm:text-3xl dark:text-white">{{ $data->judul_peraturan }}</h1>
        </div>

        <div class="mt-8 overflow-hidden rounded-2xl border border-slate-200 shadow-[var(--shadow-soft)] dark:border-white/10">
            @foreach ($rows as $row)
                <div class="grid grid-cols-1 gap-1 border-b border-slate-100 px-6 py-4 last:border-b-0 sm:grid-cols-3 sm:gap-4 dark:border-white/10">
                    <dt class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $row['label'] }}</dt>
                    <dd class="text-sm text-navy-800 sm:col-span-2 dark:text-white">{{ $row['value'] }}</dd>
                </div>
            @endforeach
        </div>

        @if ($data->file)
            <a href="{{ asset('storage/romadan_file_web/' . $data->file) }}" target="_blank" rel="noopener noreferrer"
               class="fe-btn fe-btn-primary mt-8">
                <i class="fa-solid fa-download text-xs"></i> Download Peraturan
            </a>
        @endif
    </div>
</section>
@endsection
