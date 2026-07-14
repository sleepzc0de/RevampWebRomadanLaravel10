@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Informasi Kontak (Footer)</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('contact-info.update', encrypt($contact->id)) }}" method="post" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <p class="text-muted mb-4">Informasi ini akan tampil pada bagian "Hubungi Kami" di footer seluruh halaman frontend.</p>

            <x-form.field type="email" name="email" label="Email" :value="$contact->email" maxlength="255" placeholder="contoh@kemenkeu.go.id" />

            <x-form.field name="whatsapp" label="Nomor WhatsApp" :value="$contact->whatsapp" maxlength="50" placeholder="0813-1000-4134" />

            <x-form.textarea name="address" label="Alamat" :value="$contact->address" maxlength="500" placeholder="Gedung Djuanda 2 Lt. 16-17, Jl. Dr. Wahidin Raya No. 1" />
        </div>

        <x-form.actions submit-label="Simpan" :show-reset="false" />
    </form>
</div>
@endsection
