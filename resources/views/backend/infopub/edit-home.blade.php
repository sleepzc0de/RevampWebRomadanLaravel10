@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Home Informasi Publik</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('informasi-publik.update-home', encrypt($data->id)) }}" method="post" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="judul" label="Judul Informasi Publik" :value="$data->judul" required maxlength="255" placeholder="Masukkan Judul kegiatan" />

            <x-form.textarea name="isi" label="Isi Informasi Publik" :value="$data->isi" required maxlength="3000" placeholder="Isikan List Informasi" rich />
        </div>

        <x-form.actions :back-route="route('informasi-publik.index')" back-label="Batal" submit-label="Update" />
    </form>
</div>
@endsection
