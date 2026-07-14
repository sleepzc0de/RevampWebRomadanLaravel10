@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div x-data="backupPage()" class="mx-auto max-w-6xl">

    {{-- Overlay proses backup --}}
    <div x-show="processing" x-cloak x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-white/95 dark:bg-navy-950/95">
        <div class="mx-4 w-full max-w-md rounded-lg border border-slate-200 bg-white p-8 shadow-xl dark:border-white/10 dark:bg-navy-900">
            <div class="flex flex-col items-center">
                <div class="mb-4 h-12 w-12 animate-spin rounded-full border-b-2 border-brand-600"></div>
                <h3 class="mb-2 text-lg font-bold">Membuat Backup…</h3>
                <p x-text="currentStep" class="mb-4 text-center text-sm text-slate-500 dark:text-slate-400"></p>
                <div class="h-2.5 w-full rounded-full bg-slate-200 dark:bg-white/10">
                    <div class="h-2.5 rounded-full bg-brand-600 transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="mb-0">Backup Database</h5>
                <p class="m-0 mt-1 text-xs font-normal text-slate-500 dark:text-slate-400">Kelola cadangan data sistem — buat, unduh, atau hapus.</p>
            </div>
            <form action="{{ route('backups.create') }}" method="POST" @submit="handleBackup">
                @csrf
                <button type="submit" class="btn btn-primary" :disabled="processing">
                    <i class="ph-plus-circle"></i>
                    <span x-text="processing ? 'Memproses…' : 'Buat Backup Baru'"></span>
                </button>
            </form>
        </div>

        @include('layouts.webromadan_backend.session_notif')

        {{-- Banner hasil proses AJAX --}}
        <div x-show="notice.message" x-cloak class="alert" :class="notice.ok ? 'alert-success' : 'alert-danger'">
            <p class="mb-0" x-text="notice.message"></p>
        </div>

        <div class="datatable-scroll">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama File</th>
                        <th class="text-center">Ukuran (KB)</th>
                        <th class="text-center">Terakhir Diubah</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($backups as $backup)
                        <tr>
                            <td>
                                <span class="d-inline-flex align-items-center gap-2 font-medium">
                                    <i class="ph-archive text-lg text-slate-400"></i>
                                    {{ $backup['name'] }}
                                </span>
                            </td>
                            <td class="text-center">{{ number_format($backup['size'] / 1024, 2) }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::createFromTimestamp($backup['date'])->format('Y-m-d H:i:s') }}</td>
                            <td class="text-center">
                                <div class="d-inline-flex align-items-center gap-2">
                                    <a href="{{ route('backups.download', $backup['name']) }}" class="btn btn-sm btn-light">
                                        <i class="ph-download-simple"></i> Unduh
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        @click="confirmDelete('{{ $backup['name'] }}')">
                                        <i class="ph-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center">
                                <i class="ph-archive-tray mb-2 block text-4xl text-slate-300 dark:text-slate-600"></i>
                                <p class="mb-0 font-semibold text-slate-500 dark:text-slate-400">Belum ada backup</p>
                                <p class="mb-0 text-xs text-slate-400">Buat backup pertama Anda untuk memulai</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal konfirmasi hapus (Alpine) --}}
    <div x-show="showConfirmDelete" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="modal-backdrop" @click="showConfirmDelete = false"></div>
        <div class="relative mx-auto my-24 w-[calc(100%-2rem)] max-w-md rounded-lg border border-slate-200 bg-white p-6 shadow-xl dark:border-white/10 dark:bg-navy-900"
             x-transition.scale.origin.center>
            <div class="d-flex align-items-start gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 text-lg text-red-600 dark:bg-red-950/50 dark:text-red-400">
                    <i class="ph-warning"></i>
                </span>
                <div>
                    <h3 class="mb-1 text-base font-bold">Hapus Backup</h3>
                    <p class="mb-0 text-sm text-slate-500 dark:text-slate-400">
                        Yakin ingin menghapus <strong x-text="selectedBackup" class="text-slate-800 dark:text-white"></strong>?
                        Tindakan ini tidak bisa dibatalkan.
                    </p>
                </div>
            </div>
            <div class="mt-5 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light" @click="showConfirmDelete = false">Batal</button>
                <form :action="'{{ route('backups.destroy', '__FILENAME__') }}'.replace('__FILENAME__', encodeURIComponent(selectedBackup))" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function backupPage() {
        return {
            showConfirmDelete: false,
            selectedBackup: '',
            processing: false,
            currentStep: '',
            progress: 0,
            notice: { ok: true, message: '' },

            confirmDelete(name) {
                this.selectedBackup = name;
                this.showConfirmDelete = true;
            },

            async handleBackup(event) {
                event.preventDefault();
                const form = event.target;

                this.processing = true;
                this.progress = 10;
                this.currentStep = 'Menyiapkan proses backup…';

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin',
                    });
                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Gagal membuat backup.');
                    }

                    this.currentStep = 'Menyimpan file backup…';
                    this.progress = 100;
                    await new Promise((r) => setTimeout(r, 600));

                    this.processing = false;
                    this.notice = { ok: true, message: data.message || 'Backup berhasil dibuat.' };
                    setTimeout(() => window.location.reload(), 1200);
                } catch (error) {
                    this.processing = false;
                    this.notice = { ok: false, message: error.message || 'Gagal membuat backup, coba lagi.' };
                }
            },
        };
    }
</script>
@endpush
