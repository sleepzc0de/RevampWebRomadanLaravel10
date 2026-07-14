@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Informasi Publik Romadan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('informasi-publik.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field name="judul_list_informasi" label="Judul List Informasi" required maxlength="255" placeholder="Masukkan Judul List Informasi Publik" />

            <x-form.textarea name="isi_list_informasi" label="Isi Infopub" required maxlength="1000" placeholder="Isi list informasi" rich />

            <x-form.select
                name="link_list_informasi"
                label="Link List Informasi"
                :options="\App\Http\Controllers\MenuInformasiPublik\InformasiPublikController::LINK_OPTIONS"
                placeholder="-- Pilih Halaman Tujuan --"
                required
            />
        </div>

        <x-form.actions :back-route="route('informasi-publik.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
