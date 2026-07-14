@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit List Informasi Publik</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('informasi-publik.update', encrypt($infopub->id)) }}" method="post" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="judul_list_informasi" label="Judul List Informasi" :value="$infopub->judul_list_informasi" required maxlength="255" placeholder="Masukkan Judul kegiatan" />

            <x-form.textarea name="isi_list_informasi" label="Isi Informasi Publik" :value="$infopub->isi_list_informasi" required maxlength="1000" placeholder="Isikan List Informasi" rich />

            <x-form.select
                name="link_list_informasi"
                label="Link List Informasi"
                :options="\App\Http\Controllers\MenuInformasiPublik\InformasiPublikController::LINK_OPTIONS"
                :value="$infopub->link_list_informasi"
                placeholder="-- Pilih Halaman Tujuan --"
                required
            />
        </div>

        <x-form.actions :back-route="route('informasi-publik.index')" back-label="Batal" submit-label="Update" />
    </form>
</div>
@endsection
