@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Referensi Tipe</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('tipe.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field
                name="nama_tipe"
                label="Nama Tipe"
                required
                maxlength="255"
                placeholder="Masukkan Nama Tipe"
            />
        </div>

        <x-form.actions :back-route="route('tipe.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
