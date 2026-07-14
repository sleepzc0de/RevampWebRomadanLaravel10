@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Tautan Footer</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('footer-link.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field name="label" label="Label" required maxlength="100" placeholder="Misal: Layanan" />

            <x-form.field name="url" label="URL" required maxlength="500" placeholder="/layanan atau https://contoh.com" help="Bisa berupa path relatif (mis. /layanan) atau URL lengkap (mis. https://contoh.com)." />

            <x-form.field type="number" name="sort_order" label="Urutan" :value="old('sort_order', 0)" min="0" help="Semakin kecil angkanya, semakin awal tautan ditampilkan." />

            <div class="mb-4">
                <label class="form-label mb-1.5 block">Status</label>
                <div class="form-check form-switch">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" class="form-check-input" name="is_active" value="1" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif (tampil di footer)</label>
                </div>
            </div>
        </div>

        <x-form.actions :back-route="route('footer-link.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
