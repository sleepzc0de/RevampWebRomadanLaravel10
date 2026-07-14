@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div x-data="systemMonitor(@js($app), @js($database))">
    {{-- ===== Toolbar ===== --}}
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="mb-1 text-xl font-bold">Monitor Sistem</h1>
            <p class="m-0 text-sm text-slate-500 dark:text-slate-400">Pantau resource aplikasi dan database secara langsung.</p>
        </div>
        <div class="flex items-center gap-3 rounded-md border border-slate-200 bg-white px-3.5 py-2 dark:border-white/10 dark:bg-navy-900">
            <label class="flex items-center gap-1.5 m-0 cursor-pointer select-none">
                <input type="checkbox" class="form-check-input m-0" x-model="autoRefresh">
                <span class="text-xs text-slate-500 dark:text-slate-400">Auto-refresh</span>
            </label>
            <span class="h-4 w-px bg-slate-200 dark:bg-white/10"></span>
            <button type="button" class="d-inline-flex align-items-center gap-1.5 border-0 bg-transparent p-0 text-xs font-semibold text-brand-600 dark:text-brand-400" @click="refresh()" :disabled="loading">
                <i class="ph-arrows-clockwise" :class="{ 'animate-spin': loading }"></i> Refresh
            </button>
            <span class="h-4 w-px bg-slate-200 dark:bg-white/10"></span>
            <span class="font-mono text-[11px] text-slate-400 dark:text-slate-500">Diperbarui <span x-text="lastChecked"></span></span>
        </div>
    </div>

    {{-- ===== KPI utama (warna dinamis mengikuti ambang batas) ===== --}}
    <div class="row g-3 mb-1">
        <div class="col-xl-3 col-sm-6">
            <div class="cms-stat h-full" style="border-inline-start-color: var(--color-brand-500);">
                <i class="ph-cpu cms-stat-icon"></i>
                <span class="cms-stat-label">Memori PHP (request ini)</span>
                <span class="cms-stat-value"><span x-text="app.memory_used_mb"></span> MB</span>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="cms-stat h-full" :style="'border-inline-start-color: ' + gaugeColor(app.disk_used_percent) + ';'">
                <i class="ph-hard-drive cms-stat-icon"></i>
                <span class="cms-stat-label">Disk Terpakai</span>
                <span class="cms-stat-value" :style="'color: ' + gaugeColor(app.disk_used_percent) + ';'"><span x-text="app.disk_used_percent ?? '-'"></span>%</span>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="cms-stat h-full" style="border-inline-start-color: #059669;">
                <i class="ph-database cms-stat-icon"></i>
                <span class="cms-stat-label">Ukuran Database</span>
                <span class="cms-stat-value"><span x-text="database.size_mb ?? '-'"></span> MB</span>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="cms-stat h-full" :style="'border-inline-start-color: ' + (database.connected ? '#059669' : '#dc2626') + ';'">
                <i class="ph-plugs-connected cms-stat-icon"></i>
                <span class="cms-stat-label">Koneksi Aktif DB</span>
                <span class="cms-stat-value"><span x-text="database.active_connections ?? 'N/A'"></span></span>
            </div>
        </div>
    </div>

    {{-- ===== Gauge disk + cek izin tulis storage ===== --}}
    <div class="row g-3 mb-1">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">Penggunaan Disk</h5></div>
                <div class="card-body">
                    <div class="h-2.5 w-full rounded-full bg-slate-200 dark:bg-white/10 mb-2 overflow-hidden">
                        <div class="h-2.5 rounded-full transition-all duration-500"
                             :style="'width: ' + (app.disk_used_percent ?? 0) + '%; background-color: ' + gaugeColor(app.disk_used_percent) + ';'"></div>
                    </div>
                    <p class="mb-0 text-sm text-slate-500 dark:text-slate-400">
                        <span x-text="app.disk_free_gb ?? '-'"></span> GB bebas dari <span x-text="app.disk_total_gb ?? '-'"></span> GB total
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">Izin Tulis Storage</h5></div>
                <div class="card-body d-flex flex-column gap-2">
                    @foreach($storage as $check)
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-sm text-slate-600 dark:text-slate-300">{{ $check['label'] }}</span>
                            @if($check['writable'])
                                <span class="badge bg-success bg-opacity-10 text-success"><i class="ph-check-circle"></i> OK</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger"><i class="ph-x-circle"></i> Bermasalah</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Info aplikasi & database ===== --}}
    <div class="row g-3 mb-1">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">Informasi Aplikasi</h5></div>
                <div class="card-body p-0">
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-code-simple"></i> PHP</span>
                        <span class="cms-kv-value">{{ $app['php_version'] }}</span>
                    </div>
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-cube"></i> Laravel</span>
                        <span class="cms-kv-value">{{ $app['laravel_version'] }}</span>
                    </div>
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-flask"></i> Environment</span>
                        <span class="cms-kv-value d-flex align-items-center gap-2">
                            {{ $app['environment'] }}
                            @if($app['debug_mode'])
                                <span class="badge bg-warning bg-opacity-10 text-warning">DEBUG ON</span>
                            @endif
                        </span>
                    </div>
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-desktop-tower"></i> Sistem Operasi</span>
                        <span class="cms-kv-value">{{ $app['os_family'] }}</span>
                    </div>
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-hard-drives"></i> Server</span>
                        <span class="cms-kv-value">{{ $app['server_software'] }}</span>
                    </div>
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-gauge"></i> Batas Memori PHP</span>
                        <span class="cms-kv-value">{{ $app['memory_limit'] }}</span>
                    </div>
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-lightning"></i> Config Cache</span>
                        <span class="cms-kv-value">
                            @if($app['config_cached'])
                                <span class="text-success">Aktif</span>
                            @else
                                <span class="text-muted">Tidak aktif</span>
                            @endif
                        </span>
                    </div>
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-signpost"></i> Route Cache</span>
                        <span class="cms-kv-value">
                            @if($app['routes_cached'])
                                <span class="text-success">Aktif</span>
                            @else
                                <span class="text-muted">Tidak aktif</span>
                            @endif
                        </span>
                    </div>
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-file-text"></i> Ukuran Log Laravel</span>
                        <span class="cms-kv-value"><span x-text="app.log_size_mb"></span> MB</span>
                    </div>
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-list-checks"></i> Antrian (Queue)</span>
                        <span class="cms-kv-value">
                            {{ $app['queue_connection'] }}
                            @if($app['queue_pending'] !== null)
                                <span class="text-muted font-normal">&middot; {{ $app['queue_pending'] }} pending</span>
                            @endif
                        </span>
                    </div>
                    @if($app['load_average'])
                        <div class="cms-kv-row">
                            <span class="cms-kv-label"><i class="ph-chart-line"></i> Load Average</span>
                            <span class="cms-kv-value">{{ implode(' / ', array_map(fn($v) => round($v, 2), $app['load_average'])) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Informasi Database</h5>
                    <span class="badge" :class="database.connected ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger'">
                        <span x-text="database.connected ? 'Terhubung' : 'Terputus'"></span>
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-database"></i> Driver</span>
                        <span class="cms-kv-value">{{ $database['driver'] }}</span>
                    </div>
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-identification-badge"></i> Nama Database</span>
                        <span class="cms-kv-value">{{ $database['database'] }}</span>
                    </div>
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-info"></i> Versi Server</span>
                        <span class="cms-kv-value">{{ $database['version'] ?? '-' }}</span>
                    </div>
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-hard-drives"></i> Ukuran Total</span>
                        <span class="cms-kv-value"><span x-text="database.size_mb ?? '-'"></span> MB</span>
                    </div>
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-plugs-connected"></i> Koneksi Aktif</span>
                        <span class="cms-kv-value"><span x-text="database.active_connections ?? 'N/A'"></span></span>
                    </div>
                    <div class="cms-kv-row">
                        <span class="cms-kv-label"><i class="ph-arrows-clockwise"></i> Migrasi Pending</span>
                        <span class="cms-kv-value">
                            <template x-if="database.pending_migrations !== null">
                                <span :class="database.pending_migrations > 0 ? 'text-warning' : 'text-success'" x-text="database.pending_migrations"></span>
                            </template>
                            <template x-if="database.pending_migrations === null">
                                <span class="text-muted font-normal">Tidak dapat diperiksa</span>
                            </template>
                        </span>
                    </div>
                    @if($database['error'])
                        <div class="cms-kv-row">
                            <span class="cms-kv-label"><i class="ph-warning text-danger"></i> Catatan</span>
                            <span class="cms-kv-value text-danger">{{ $database['error'] }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Tabel database terbesar ===== --}}
    <div class="row g-3 mb-1">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">10 Tabel Terbesar</h5></div>
                <div class="card-body p-0">
                    @if(count($database['tables']) > 0)
                        @php $maxTableSize = collect($database['tables'])->max('size_mb') ?: 1; @endphp
                        <div class="datatable-scroll">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Nama Tabel</th>
                                        <th class="text-end">Jumlah Baris</th>
                                        <th style="width: 220px;">Ukuran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($database['tables'] as $table)
                                        <tr>
                                            <td class="font-mono text-sm">{{ $table['name'] }}</td>
                                            <td class="text-end">{{ number_format($table['rows'], 0, ',', '.') }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="h-1.5 flex-fill rounded-full bg-slate-100 dark:bg-white/10 overflow-hidden">
                                                        <div class="h-1.5 rounded-full bg-brand-500" style="width: {{ round(($table['size_mb'] / $maxTableSize) * 100, 1) }}%;"></div>
                                                    </div>
                                                    <span class="font-mono text-xs text-slate-500 dark:text-slate-400" style="min-width: 70px; text-align: right;">{{ number_format($table['size_mb'], 2, ',', '.') }} MB</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0 p-3">Statistik tabel tidak tersedia (izin database terbatas).</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function systemMonitor(initialApp, initialDatabase) {
        return {
            app: initialApp,
            database: initialDatabase,
            lastChecked: new Date().toLocaleTimeString('id-ID'),
            loading: false,
            autoRefresh: true,
            timer: null,

            init() {
                this.$watch('autoRefresh', (value) => value ? this.startTimer() : this.stopTimer());
                this.startTimer();
            },

            startTimer() {
                this.stopTimer();
                this.timer = setInterval(() => this.refresh(), 10000);
            },

            stopTimer() {
                if (this.timer) clearInterval(this.timer);
                this.timer = null;
            },

            gaugeColor(percent) {
                if (percent === null || percent === undefined) return '#64748b';
                if (percent >= 90) return '#dc2626';
                if (percent >= 75) return '#d69e00';
                return '#059669';
            },

            async refresh() {
                this.loading = true;
                try {
                    const response = await fetch('{{ route('system-monitor.data') }}', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin',
                    });
                    const data = await response.json();
                    this.app = { ...this.app, ...data.app };
                    this.database = { ...this.database, ...data.database };
                    this.lastChecked = new Date().toLocaleTimeString('id-ID');
                } catch (error) {
                    console.error('Gagal memuat data monitor sistem', error);
                } finally {
                    this.loading = false;
                }
            },
        };
    }
</script>
@endpush
