@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Nama Status</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('status.update', encrypt($status->id_status)) }}" method="post" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field
                name="nama_status"
                label="Nama Status"
                :value="$status->nama_status"
                required
                maxlength="255"
                placeholder="Masukkan Nama Status"
            />
        </div>

        <x-form.actions :back-route="url()->previous()" back-label="Batal" submit-label="Update" />
    </form>
</div>
@endsection
