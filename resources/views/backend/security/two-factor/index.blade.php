@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="max-w-3xl">

    @include('layouts.webromadan_backend.session_notif')

    @if (session('new_recovery_codes'))
        <div class="card mb-4 border-amber-300 dark:border-amber-700">
            <div class="card-header bg-amber-50 dark:bg-amber-950/40">
                <h5 class="mb-0"><i class="ph-warning"></i> Simpan Kode Pemulihan Ini Sekarang</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Kode ini hanya ditampilkan satu kali. Simpan di tempat aman — setiap kode hanya bisa dipakai sekali untuk masuk jika Anda kehilangan akses ke aplikasi authenticator.</p>
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                    @foreach (session('new_recovery_codes') as $code)
                        <code class="block rounded-md border border-slate-200 bg-slate-50 p-2 text-center dark:border-white/10 dark:bg-white/5">{{ $code }}</code>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h5 class="mb-0">Autentikasi Dua Faktor (2FA)</h5>
            @if ($user->hasTwoFactorEnabled())
                <span class="badge bg-success">Aktif</span>
            @else
                <span class="badge bg-secondary">Nonaktif</span>
            @endif
        </div>
        <div class="card-body">
            <p class="text-muted">Tambahkan lapisan keamanan ekstra pada akun Anda. Setelah aktif, Anda perlu memasukkan kode 6 digit dari aplikasi authenticator (Google Authenticator, Authy, dll.) setiap kali login, selain email &amp; password.</p>

            @if ($user->hasTwoFactorEnabled())
                {{-- ===== Status: aktif ===== --}}
                <hr class="my-4 border-slate-200 dark:border-white/10">
                <h6 class="mb-1 font-bold text-slate-800 dark:text-slate-100">Buat Ulang Kode Pemulihan</h6>
                <p class="mb-3 text-sm text-slate-500 dark:text-slate-400">Kode pemulihan lama akan tidak berlaku lagi.</p>
                <form method="POST" action="{{ route('two-factor.regenerate-codes') }}" class="mb-6 flex flex-wrap items-center gap-2" onsubmit="return confirm('Kode pemulihan lama akan digantikan yang baru. Lanjutkan?');">
                    @csrf
                    <input type="password" name="password" class="form-control w-auto" placeholder="Password Anda" required>
                    <button type="submit" class="btn btn-outline-primary">Buat Ulang Kode</button>
                </form>

                <hr class="my-4 border-slate-200 dark:border-white/10">
                <h6 class="mb-1 font-bold text-red-600 dark:text-red-400">Nonaktifkan 2FA</h6>
                <form method="POST" action="{{ route('two-factor.disable') }}" class="flex flex-wrap items-center gap-2" onsubmit="return confirm('Yakin ingin menonaktifkan 2FA? Akun akan lebih rentan tanpa lapisan keamanan ini.');">
                    @csrf
                    @method('DELETE')
                    <input type="password" name="password" class="form-control w-auto" placeholder="Password Anda" required>
                    <button type="submit" class="btn btn-outline-danger">Nonaktifkan 2FA</button>
                </form>

            @elseif ($pendingSetup)
                {{-- ===== Status: setup sedang berjalan ===== --}}
                <hr class="my-4 border-slate-200 dark:border-white/10">
                <div class="flex flex-col gap-6 md:flex-row">
                    <div class="text-center md:w-5/12">
                        <div class="inline-block rounded-md border border-slate-200 bg-white p-2 dark:border-white/10">{!! $qrCodeSvg !!}</div>
                        <p class="mb-0 mt-2 text-sm text-slate-500 dark:text-slate-400">Scan dengan aplikasi authenticator</p>
                    </div>
                    <div class="md:w-7/12">
                        <p>1. Scan QR di samping dengan Google Authenticator / Authy / aplikasi sejenis.</p>
                        <p>Atau masukkan kunci ini secara manual:</p>
                        <code class="mb-3 block rounded-md border border-slate-200 bg-slate-50 p-2 dark:border-white/10 dark:bg-white/5">{{ $manualKey }}</code>
                        <p>2. Masukkan kode 6 digit yang muncul di aplikasi untuk konfirmasi:</p>
                        <form method="POST" action="{{ route('two-factor.confirm') }}" class="flex gap-2">
                            @csrf
                            <input type="text" name="code" class="form-control max-w-40" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" placeholder="123456" required autofocus>
                            <button type="submit" class="btn btn-primary">Konfirmasi &amp; Aktifkan</button>
                        </form>
                        <form method="POST" action="{{ route('two-factor.setup.cancel') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 text-slate-500">Batalkan</button>
                        </form>
                    </div>
                </div>

            @else
                {{-- ===== Status: nonaktif ===== --}}
                <form method="POST" action="{{ route('two-factor.enable') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary"><i class="ph-shield-check"></i> Aktifkan 2FA</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
