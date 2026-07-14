@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Medsos Romadan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('medsos.update', encrypt($medsos2->id)) }}" method="post" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="nama_medsos" label="Nama Medsos" :value="$medsos2->nama_medsos" required maxlength="255" placeholder="Masukkan Nama Medsos" />

            <x-form.field name="link_medsos" label="Link Medsos" :value="$medsos2->link_medsos" required maxlength="1000" placeholder="Masukkan Link Medsos" />

            <x-form.field name="logo_medsos" label="Logo Medsos" :value="$medsos2->logo_medsos" required maxlength="255" placeholder="Masukkan Logo Medsos" />
        </div>

        <x-form.actions :back-route="route('medsos.index')" back-label="Batal" submit-label="Update" />
    </form>
</div>
@endsection
