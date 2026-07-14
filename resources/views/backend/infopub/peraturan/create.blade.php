@extends('layouts.webromadan_backend.master_layout')

@php
    $kategoriOptions = collect($kategori)->mapWithKeys(fn ($item, $i) => [$item->id_kategori => ($i + 1).' - '.$item->nama_kategori])->all();
    $jenisOptions = collect($data_jenis_peraturan)->mapWithKeys(fn ($item, $i) => [$item->id_jenis_peraturan => ($i + 1).' - '.$item->nama_jenis_peraturan])->all();
    $statusOptions = collect($data_status_peraturan)->mapWithKeys(fn ($item, $i) => [$item->id_ref_peraturan_status => ($i + 1).' - '.$item->nama_peraturan_status])->all();
@endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Peraturan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('peraturan.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field name="nomor_peraturan" label="Nomor Peraturan" required maxlength="100" />

            <x-form.field name="judul_peraturan" label="Judul Peraturan" required maxlength="255" />

            <x-form.field type="file" name="file" label="File Peraturan" required accept=".doc,.docx,.ppt,.pptx,.csv,.xls,.xlsx,.pdf,.zip,.rar" help="Format: doc, docx, ppt, pptx, csv, xls, xlsx, pdf, zip, rar. Maks 100MB." />

            <x-form.select name="kategori" label="Kategori" :options="$kategoriOptions" placeholder="-- Pilih Kategori --" required />

            <x-form.select name="jenis_peraturan" label="Jenis Peraturan" :options="$jenisOptions" placeholder="-- Pilih Jenis Peraturan --" required />

            <div x-data="{ tanggalPenetapan: '' }">
                <x-form.field type="date" name="tanggal_penetapan" label="Tanggal Penetapan" required x-on:change="tanggalPenetapan = $event.target.value" />
                <x-form.field type="date" name="tanggal_berlaku" label="Tanggal Berlaku" required x-bind:min="tanggalPenetapan" />
            </div>

            @if(count($data_status_peraturan) > 0)
                <x-form.select name="status_peraturan" label="Status Peraturan" :options="$statusOptions" placeholder="-- Pilih Status Peraturan --" />
            @else
                <div class="mb-4">
                    <label class="form-label mb-1.5 block">Status Peraturan</label>
                    <input type="text" class="form-control" value="DATA KOSONG, HARAP HUBUNGI ADMINISTRATOR" disabled>
                </div>
            @endif
        </div>

        <x-form.actions :back-route="route('peraturan.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
