@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Nama Kategori</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('kategori.update', encrypt($kategori->id_kategori)) }}" method="post" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field
                name="nama_kategori"
                label="Nama Kategori"
                :value="$kategori->nama_kategori"
                required
                maxlength="255"
                placeholder="Masukkan Nama Kategori"
            />
        </div>

        <x-form.actions :back-route="url()->previous()" back-label="Batal" submit-label="Update" />
    </form>
</div>
@endsection
