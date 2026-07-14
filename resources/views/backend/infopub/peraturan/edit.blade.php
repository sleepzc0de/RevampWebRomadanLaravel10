@extends('layouts.webromadan_backend.master_layout')

@php
    $kategoriOptions = collect($kategori)->mapWithKeys(fn ($item, $i) => [$item->id_kategori => ($i + 1).' - '.$item->nama_kategori])->all();
    $jenisOptions = collect($jenis_peraturan)->mapWithKeys(fn ($item, $i) => [$item->id_jenis_peraturan => ($i + 1).' - '.$item->nama_jenis_peraturan])->all();
    $statusOptions = collect($status_peraturan)->mapWithKeys(fn ($item, $i) => [$item->id_ref_peraturan_status => ($i + 1).' - '.$item->nama_peraturan_status])->all();
@endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Peraturan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('peraturan.update', encrypt($peraturan->id)) }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="nomor_peraturan" label="Nomor Peraturan" :value="$peraturan->nomor_peraturan" required maxlength="100" />

            <x-form.field name="judul_peraturan" label="Judul Peraturan" :value="$peraturan->judul_peraturan" required maxlength="255" />

            <x-form.field type="file" name="file" label="File Peraturan" help="Kosongkan jika tidak ingin mengganti file. File saat ini: {{ $peraturan->file }}" />

            <x-form.select name="kategori" label="Kategori" :options="$kategoriOptions" :value="$peraturan->kategori" placeholder="-- Pilih Kategori --" required />

            <x-form.select name="jenis_peraturan" label="Jenis Peraturan" :options="$jenisOptions" :value="$peraturan->jenis_peraturan" placeholder="-- Pilih Jenis Peraturan --" required />

            <div x-data="{ tanggalPenetapan: @js(old('tanggal_penetapan', $peraturan->tanggal_penetapan)) }">
                <x-form.field type="date" name="tanggal_penetapan" label="Tanggal Penetapan" :value="old('tanggal_penetapan', $peraturan->tanggal_penetapan)" required x-on:change="tanggalPenetapan = $event.target.value" />
                <x-form.field type="date" name="tanggal_berlaku" label="Tanggal Berlaku" :value="old('tanggal_berlaku', $peraturan->tanggal_berlaku)" required x-bind:min="tanggalPenetapan" />
            </div>

            @if(count($status_peraturan) > 0)
                <x-form.select name="status_peraturan" label="Status Peraturan" :options="$statusOptions" :value="$peraturan->status_peraturan" placeholder="-- Pilih Status Peraturan --" />
            @else
                <div class="mb-4">
                    <label class="form-label mb-1.5 block">Status Peraturan</label>
                    <input type="text" class="form-control" value="DATA KOSONG, HARAP HUBUNGI ADMINISTRATOR" disabled>
                </div>
            @endif
        </div>

        <x-form.actions :back-route="route('peraturan.index')" submit-label="Update" />
    </form>
</div>
@endsection
