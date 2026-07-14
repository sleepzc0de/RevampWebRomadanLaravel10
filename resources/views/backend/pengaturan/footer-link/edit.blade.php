@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Tautan Footer</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('footer-link.update', encrypt($footerLink->id)) }}" method="post" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="label" label="Label" :value="$footerLink->label" required maxlength="100" />

            <x-form.field name="url" label="URL" :value="$footerLink->url" required maxlength="500" help="Bisa berupa path relatif (mis. /layanan) atau URL lengkap (mis. https://contoh.com)." />

            <x-form.field type="number" name="sort_order" label="Urutan" :value="old('sort_order', $footerLink->sort_order)" min="0" />

            <div class="mb-4">
                <label class="form-label mb-1.5 block">Status</label>
                <div class="form-check form-switch">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" class="form-check-input" name="is_active" value="1" id="is_active" {{ old('is_active', $footerLink->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif (tampil di footer)</label>
                </div>
            </div>
        </div>

        <x-form.actions :back-route="route('footer-link.index')" submit-label="Update" :show-reset="false" />
    </form>
</div>
@endsection
