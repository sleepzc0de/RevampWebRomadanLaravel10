@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Nama Tipe</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('tipe.update', encrypt($tipe->id_tipe)) }}" method="post" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field
                name="nama_tipe"
                label="Nama Tipe"
                :value="$tipe->nama_tipe"
                required
                maxlength="255"
                placeholder="Masukkan Nama Tipe"
            />
        </div>

        <x-form.actions :back-route="url()->previous()" back-label="Batal" submit-label="Update" />
    </form>
</div>
@endsection
