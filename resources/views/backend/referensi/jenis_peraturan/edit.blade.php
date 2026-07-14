@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Jenis Peraturan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('jenis-peraturan.update', encrypt($peraturan->id_jenis_peraturan)) }}" method="post" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field
                name="nama_jenis_peraturan"
                label="Nama Jenis Peraturan"
                :value="$peraturan->nama_jenis_peraturan"
                required
                maxlength="255"
                placeholder="Masukkan Nama Jenis Peraturan"
            />
        </div>

        <x-form.actions :back-route="route('jenis-peraturan.index')" back-label="Batal" submit-label="Update" />
    </form>
</div>
@endsection
