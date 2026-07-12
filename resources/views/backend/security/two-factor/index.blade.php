@extends('layouts.webromadan_backend.master_layout')

@section('css')
@endsection

@section('script_bawah')
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">

        @include('layouts.webromadan_backend.session_notif')

        @if (session('new_recovery_codes'))
            <div class="card border-warning mb-3">
                <div class="card-header bg-warning bg-opacity-10">
                    <h5 class="mb-0"><i class="ph-warning"></i> Simpan Kode Pemulihan Ini Sekarang</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Kode ini hanya ditampilkan satu kali. Simpan di tempat aman — setiap kode hanya bisa dipakai sekali untuk masuk jika Anda kehilangan akses ke aplikasi authenticator.</p>
                    <div class="row g-2">
                        @foreach (session('new_recovery_codes') as $code)
                            <div class="col-6 col-md-3">
                                <code class="d-block bg-light border rounded p-2 text-center">{{ $code }}</code>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
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
                    <hr>
                    <h6>Buat Ulang Kode Pemulihan</h6>
                    <p class="text-muted small">Kode pemulihan lama akan tidak berlaku lagi.</p>
                    <form method="POST" action="{{ route('two-factor.regenerate-codes') }}" class="row g-2 align-items-center mb-4" onsubmit="return confirm('Kode pemulihan lama akan digantikan yang baru. Lanjutkan?');">
                        @csrf
                        <div class="col-auto">
                            <input type="password" name="password" class="form-control" placeholder="Password Anda" required>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-outline-primary">Buat Ulang Kode</button>
                        </div>
                    </form>

                    <hr>
                    <h6 class="text-danger">Nonaktifkan 2FA</h6>
                    <form method="POST" action="{{ route('two-factor.disable') }}" class="row g-2 align-items-center" onsubmit="return confirm('Yakin ingin menonaktifkan 2FA? Akun akan lebih rentan tanpa lapisan keamanan ini.');">
                        @csrf
                        @method('DELETE')
                        <div class="col-auto">
                            <input type="password" name="password" class="form-control" placeholder="Password Anda" required>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-outline-danger">Nonaktifkan 2FA</button>
                        </div>
                    </form>

                @elseif ($pendingSetup)
                    {{-- ===== Status: setup sedang berjalan ===== --}}
                    <hr>
                    <div class="row g-4">
                        <div class="col-md-5 text-center">
                            <div class="d-inline-block p-2 bg-white border rounded">{!! $qrCodeSvg !!}</div>
                            <p class="small text-muted mt-2 mb-0">Scan dengan aplikasi authenticator</p>
                        </div>
                        <div class="col-md-7">
                            <p>1. Scan QR di samping dengan Google Authenticator / Authy / aplikasi sejenis.</p>
                            <p>Atau masukkan kunci ini secara manual:</p>
                            <code class="d-block bg-light border rounded p-2 mb-3">{{ $manualKey }}</code>
                            <p>2. Masukkan kode 6 digit yang muncul di aplikasi untuk konfirmasi:</p>
                            <form method="POST" action="{{ route('two-factor.confirm') }}" class="d-flex gap-2">
                                @csrf
                                <input type="text" name="code" class="form-control" style="max-width:160px;" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" placeholder="123456" required autofocus>
                                <button type="submit" class="btn btn-primary">Konfirmasi &amp; Aktifkan</button>
                            </form>
                            <form method="POST" action="{{ route('two-factor.setup.cancel') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-link text-muted p-0">Batalkan</button>
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
</div>
@endsection
