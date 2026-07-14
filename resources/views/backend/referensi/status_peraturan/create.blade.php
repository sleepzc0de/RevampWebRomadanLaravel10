@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Referensi Status Peraturan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('status-peraturan.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field
                name="nama_peraturan_status"
                label="Nama Status Peraturan"
                required
                maxlength="255"
                placeholder="Masukkan Nama Status Peraturan"
            />
        </div>

        <x-form.actions :back-route="route('status-peraturan.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
