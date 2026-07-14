@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Status Peraturan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('status-peraturan.update', encrypt($peraturan->id_ref_peraturan_status)) }}" method="post" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field
                name="nama_peraturan_status"
                label="Nama Status Peraturan"
                :value="$peraturan->nama_peraturan_status"
                required
                maxlength="255"
                placeholder="Masukkan Nama Status Peraturan"
            />
        </div>

        <x-form.actions :back-route="route('status-peraturan.index')" back-label="Batal" submit-label="Update" />
    </form>
</div>
@endsection
