@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div x-data="{ open: false, subject: '', json: '' }"
     @click="const btn = $event.target.closest('.activity-detail-btn'); if (btn) { subject = btn.dataset.subject || '-'; try { json = JSON.stringify(JSON.parse(btn.dataset.properties || '{}'), null, 2); } catch (e) { json = btn.dataset.properties || ''; } open = true; }"
     @keydown.window.escape="open = false">
    <x-data-table
        title="Log Aktivitas"
        :ajax="route('activity-log.index')"
        :order="[1, 'desc']"
        search-placeholder="Cari log..."
        :columns="[
            ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
            ['label' => 'Waktu', 'data' => 'waktu', 'name' => 'created_at'],
            ['label' => 'Modul', 'data' => 'modul', 'name' => 'log_name'],
            ['label' => 'Event', 'data' => 'event', 'raw' => true],
            ['label' => 'Deskripsi', 'data' => 'deskripsi', 'name' => 'description'],
            ['label' => 'Pengguna', 'data' => 'pengguna', 'name' => 'causer.name'],
            ['label' => 'Detail', 'data' => 'detail', 'orderable' => false, 'searchable' => false, 'raw' => true],
        ]"
    >
        <x-slot:notice>
            @include('layouts.webromadan_backend.session_notif')
        </x-slot:notice>

        <x-slot:headerActions>
            <select class="form-select form-select-sm" style="width:auto;" x-model="extra.log_name" @change="page = 0; load()">
                <option value="">Semua Modul</option>
                @foreach($logNames as $logName)
                    <option value="{{ $logName }}">{{ ucwords(str_replace('_', ' ', $logName)) }}</option>
                @endforeach
            </select>
            <a :href="extra.log_name ? @js(route('activity-log.export')) + '?log_name=' + encodeURIComponent(extra.log_name) : @js(route('activity-log.export'))" class="btn btn-sm btn-outline-success">
                <i class="ph-file-xls"></i> Ekspor Excel
            </a>
            <form action="{{ route('activity-log.clean') }}" method="post" onsubmit="return confirm('Hapus semua log lebih dari 365 hari?');">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="ph-broom"></i> Bersihkan Log Lama
                </button>
            </form>
        </x-slot:headerActions>
    </x-data-table>

    {{-- Modal Detail Aktivitas --}}
    <div x-show="open" x-cloak x-transition.opacity class="modal-backdrop" @click="open = false"></div>
    <div x-show="open" x-cloak class="modal" style="display: block;">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content" @click.outside="open = false">
                <div class="modal-header">
                    <h6 class="modal-title">Detail Perubahan</h6>
                    <button type="button" class="btn-close" @click="open = false"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-2">Subjek: <span x-text="subject"></span></p>
                    <pre class="rounded-md bg-slate-50 p-3 dark:bg-white/5" style="white-space: pre-wrap; word-break: break-word;" x-text="json"></pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
