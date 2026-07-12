<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Verifikasi 2FA - Kementerian Keuangan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white font-sans antialiased dark:bg-navy-950">
    <div class="flex min-h-screen flex-col items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm animate-fade-up {{ $errors->any() ? 'animate-shake' : '' }}">
            <div class="mb-8 flex flex-col items-center text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400">
                    <i class="fa-solid fa-shield-halved text-xl"></i>
                </div>
                <h2 class="mt-4 text-2xl font-extrabold text-navy-800 dark:text-white">Verifikasi Dua Faktor</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Masukkan kode 6 digit dari aplikasi authenticator Anda, atau gunakan salah satu kode pemulihan.</p>
            </div>

            <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-5"
                x-data="{ submitting: false }" @submit="submitting = true">
                @csrf

                <div>
                    <input id="code" type="text" name="code" required autofocus autocomplete="one-time-code"
                        inputmode="numeric" maxlength="20" placeholder="123456"
                        class="w-full rounded-xl border py-3 px-4 text-center text-lg font-semibold tracking-[0.3em] text-navy-800 transition placeholder:tracking-normal placeholder:text-slate-400 focus:outline-none focus:ring-4 dark:bg-navy-900 dark:text-white {{ $errors->has('code') ? 'border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-100' : 'border-slate-200 bg-slate-50 focus:border-brand-500 focus:bg-white focus:ring-brand-100' }}">
                    @error('code')
                        <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-red-600">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit" :disabled="submitting"
                    class="fe-btn fe-btn-primary w-full py-3 disabled:cursor-not-allowed disabled:opacity-70">
                    <i class="fa-solid fa-spinner animate-spin" x-show="submitting" x-cloak></i>
                    <span x-text="submitting ? 'Memproses...' : 'Verifikasi'"></span>
                </button>
            </form>

            <form method="POST" action="{{ route('two-factor.challenge.cancel') }}" class="mt-4 text-center">
                @csrf
                <button type="submit" class="text-sm text-slate-400 hover:text-brand-700 dark:hover:text-brand-400">
                    Batal, kembali ke halaman login
                </button>
            </form>
        </div>
    </div>
</body>

</html>
