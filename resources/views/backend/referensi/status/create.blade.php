@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Referensi Status Berita</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('status.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field
                name="nama_status"
                label="Nama Status"
                required
                maxlength="255"
                placeholder="Masukkan Nama Status"
            />
        </div>

        <x-form.actions :back-route="route('status.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
