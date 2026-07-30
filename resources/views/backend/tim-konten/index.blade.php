@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="mb-5 flex flex-wrap items-end justify-between gap-3">
    <div>
        <h1 class="mb-1 text-xl font-bold">Tim Konten Publikasi</h1>
        <p class="m-0 text-sm text-slate-500 dark:text-slate-400">
            Daftar pengelola konten beserta jabatan dan kontribusinya.
        </p>
    </div>
    @role('ADMINISTRATOR')
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="ph-user-gear"></i> Kelola Anggota &amp; Jabatan
        </a>
    @endrole
</div>

{{-- ===== Ringkasan ===== --}}
<div class="row g-3 mb-1">
    <div class="col-xl-3 col-sm-6">
        <div class="cms-stat h-full" style="border-inline-start-color: var(--color-brand-500);">
            <i class="ph-users-four cms-stat-icon"></i>
            <span class="cms-stat-label">Total Anggota Tim</span>
            <span class="cms-stat-value">{{ number_format($totalAnggota, 0, ',', '.') }}</span>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="cms-stat h-full" style="border-inline-start-color: #059669;">
            <i class="ph-newspaper cms-stat-icon"></i>
            <span class="cms-stat-label">Publikasi Dibuat Tim</span>
            <span class="cms-stat-value">{{ number_format($totalPublikasiTim, 0, ',', '.') }}</span>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="cms-stat h-full" style="border-inline-start-color: #d69e00;">
            <i class="ph-crown-simple cms-stat-icon"></i>
            <span class="cms-stat-label">Kontributor Teraktif</span>
            {{-- truncate + pr-8: nama panjang tidak boleh meluber melewati kartu
                 maupun menabrak ikon di sudut kanan atas --}}
            <span class="cms-stat-value block truncate pr-8 text-base!"
                  title="{{ $terproduktif['nama'] ?? '' }}">
                {{ $terproduktif && $terproduktif['ditulis'] > 0 ? $terproduktif['nama'] : '—' }}
            </span>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="cms-stat h-full" style="border-inline-start-color: {{ $tanpaDuaFaktor > 0 ? '#dc2626' : '#64748b' }};">
            <i class="ph-lock-key-open cms-stat-icon"></i>
            <span class="cms-stat-label">Belum Mengaktifkan 2FA</span>
            <span class="cms-stat-value" @if($tanpaDuaFaktor > 0) style="color:#dc2626;" @endif>
                {{ number_format($tanpaDuaFaktor, 0, ',', '.') }}
            </span>
        </div>
    </div>
</div>

{{-- ===== Anggota per jabatan ===== --}}
@foreach ($perJabatan as $role => $grup)
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-lg text-white"
                      style="background-color: {{ $grup['meta']['warna'] }};">
                    <i class="{{ $grup['meta']['ikon'] }}"></i>
                </span>
                <div>
                    <h5 class="mb-0">{{ $grup['meta']['label'] }}</h5>
                    <p class="m-0 mt-0.5 text-xs font-normal text-slate-500 dark:text-slate-400">
                        {{ $grup['meta']['tugas'] }}
                    </p>
                </div>
            </div>
            <span class="badge bg-primary bg-opacity-10 text-primary">
                {{ $grup['anggota']->count() }} orang
            </span>
        </div>

        <div class="card-body">
            @forelse ($grup['anggota'] as $orang)
                @if ($loop->first)
                    <div class="row g-3">
                @endif

                <div class="col-xl-4 col-md-6">
                    <div class="h-full rounded-lg border border-slate-200 bg-white p-4 transition hover:border-brand-400 dark:border-white/10 dark:bg-navy-900 dark:hover:border-brand-500">
                        {{-- Identitas --}}
                        <div class="d-flex align-items-start gap-3">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-sm font-bold text-white"
                                  style="background-color: {{ $grup['meta']['warna'] }};">
                                {{ $orang['inisial'] }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="m-0 truncate text-sm font-bold text-slate-800 dark:text-white" title="{{ $orang['nama'] }}">
                                    {{ $orang['nama'] }}
                                </p>
                                <p class="m-0 mt-0.5 d-flex align-items-center gap-1 truncate text-xs text-slate-500 dark:text-slate-400"
                                   title="{{ $orang['email'] }}">
                                    <i class="ph-envelope-simple"></i>{{ $orang['email'] }}
                                </p>
                            </div>
                        </div>

                        {{-- Jabatan tambahan bila punya lebih dari satu role --}}
                        @if (count($orang['semua_role']) > 1)
                            <div class="mt-2.5 d-flex flex-wrap gap-1">
                                @foreach ($orang['semua_role'] as $r)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $r }}</span>
                                @endforeach
                            </div>
                        @endif

                        {{-- Kontribusi --}}
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <div class="rounded-md bg-slate-50 px-3 py-2 dark:bg-white/5">
                                <span class="block font-mono text-lg font-bold text-slate-800 dark:text-white">
                                    {{ number_format($orang['ditulis'], 0, ',', '.') }}
                                </span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Publikasi dibuat</span>
                            </div>
                            <div class="rounded-md bg-slate-50 px-3 py-2 dark:bg-white/5">
                                <span class="block font-mono text-lg font-bold text-slate-800 dark:text-white">
                                    {{ number_format($orang['disunting'], 0, ',', '.') }}
                                </span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Kali menyunting</span>
                            </div>
                        </div>

                        {{-- Jejak waktu & keamanan --}}
                        <div class="mt-3 border-t border-slate-100 pt-2.5 dark:border-white/5">
                            <p class="m-0 d-flex align-items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                                <i class="ph-trend-up"></i>
                                Terakhir menulis:
                                <span class="font-medium text-slate-700 dark:text-slate-200">
                                    {{ $orang['terakhir_menulis']
                                        ? \Carbon\Carbon::parse($orang['terakhir_menulis'])->locale('id')->isoFormat('D MMM Y')
                                        : 'belum ada' }}
                                </span>
                            </p>
                            <p class="m-0 mt-1 d-flex align-items-center justify-content-between gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                                <span class="d-flex align-items-center gap-1.5">
                                    <i class="ph-calendar-blank"></i>
                                    Bergabung
                                    <span class="font-medium text-slate-700 dark:text-slate-200">
                                        {{ $orang['bergabung']
                                            ? \Carbon\Carbon::parse($orang['bergabung'])->locale('id')->isoFormat('MMM Y')
                                            : '—' }}
                                    </span>
                                </span>
                                @if ($orang['dua_faktor'])
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        <i class="ph-check-circle"></i> 2FA
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning" title="Belum mengaktifkan autentikasi dua faktor">
                                        <i class="ph-lock-key-open"></i> Tanpa 2FA
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                @if ($loop->last)
                    </div>
                @endif
            @empty
                <div class="py-4 text-center">
                    <i class="{{ $grup['meta']['ikon'] }} mb-2 block text-3xl text-slate-300 dark:text-slate-600"></i>
                    <p class="mb-0 text-sm font-semibold text-slate-500 dark:text-slate-400">
                        Belum ada anggota dengan jabatan {{ $grup['meta']['label'] }}
                    </p>
                    @role('ADMINISTRATOR')
                        <p class="mb-0 mt-1 text-xs text-slate-400">
                            Tetapkan jabatan ini lewat
                            <a href="{{ route('users.index') }}" class="text-brand-600 dark:text-brand-400">User Manajemen</a>
                        </p>
                    @endrole
                </div>
            @endforelse
        </div>
    </div>
@endforeach

<p class="mt-4 d-flex align-items-start gap-2 text-xs text-slate-400 dark:text-slate-500">
    <i class="ph-info mt-0.5"></i>
    <span>
        Keanggotaan tim diturunkan otomatis dari jabatan (role) pengguna, sehingga daftar ini
        selalu mencerminkan hak akses yang berlaku. Untuk menambah, mengubah, atau mencabut
        jabatan seseorang, gunakan modul <strong>User Manajemen</strong>.
    </span>
</p>
@endsection
