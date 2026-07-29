<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Login - Kementerian Keuangan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white font-sans antialiased">
    <div class="flex min-h-screen">
        {{-- Panel kiri: branding, hanya tampil di layar lebar --}}
        <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-navy-950 p-12 lg:flex">
            @if ($gambar)
                <img src="{{ asset('storage/romadan_gambar_web/' . $gambar->image) }}" alt=""
                    class="absolute inset-0 h-full w-full object-cover opacity-40">
            @else
                {{-- Pola dekoratif ketika belum ada gambar login yang diunggah admin --}}
                <div class="absolute inset-0 opacity-[0.07]"
                     style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 28px 28px;"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-navy-950/85 to-navy-950/60"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-navy-950/40 via-transparent to-transparent"></div>

            <div class="relative z-10 flex items-center gap-3 animate-fade-in">
                <img src="{{ asset('frontend_romadan_web/images/icons/romadanlogo.png') }}" alt="Logo"
                    class="h-12 w-12 flex-none object-contain">
                <span class="leading-tight">
                    <span class="block text-sm font-bold text-white">Biro Manajemen BMN &amp; Pengadaan</span>
                    <span class="block text-xs font-medium text-slate-300">Kementerian Keuangan Republik Indonesia</span>
                </span>
            </div>

            <div class="relative z-10 max-w-md animate-fade-up">
                <span class="fe-eyebrow text-gold-400">CMS Romadan Versi 2</span>
                <h1 class="mt-4 text-3xl font-extrabold leading-tight text-white">
                    Kelola informasi publik dengan transparan &amp; akuntabel.
                </h1>
                <p class="mt-4 text-sm leading-relaxed text-slate-300">
                    Portal pengelolaan konten resmi untuk mendukung tata kelola Barang Milik Negara dan proses pengadaan yang lebih baik.
                </p>

                <ul class="mt-8 space-y-3 text-sm text-slate-200">
                    <li class="flex items-center gap-3">
                        <span class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-white/10"><i class="fa-solid fa-shield-halved text-gold-400"></i></span>
                        Autentikasi aman dengan verifikasi CAPTCHA
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-white/10"><i class="fa-solid fa-clock-rotate-left text-gold-400"></i></span>
                        Log aktivitas tercatat untuk setiap perubahan
                    </li>
                </ul>
            </div>

            <p class="relative z-10 text-xs text-slate-400">&copy; {{ date('Y') }} Biro Manajemen BMN dan Pengadaan.</p>
        </div>

        {{-- Panel kanan: form login --}}
        <div class="flex w-full flex-col items-center justify-center bg-white px-6 py-12 sm:px-10 lg:w-1/2">
            <div class="w-full max-w-sm animate-fade-up">
                {{-- Logo untuk mobile (panel kiri disembunyikan) --}}
                <div class="mb-8 flex items-center gap-3 lg:hidden">
                    <img src="{{ asset('webromadan/fe/images/romadan/logo_3.png') }}" alt="Logo" class="h-11 w-11 object-contain">
                    <span class="leading-tight">
                        <span class="block text-sm font-bold text-navy-800">Biro Manajemen BMN &amp; Pengadaan</span>
                        <span class="block text-xs font-medium text-slate-500">Kementerian Keuangan</span>
                    </span>
                </div>

                <span class="fe-eyebrow">CMS Romadan V2</span>
                <h2 class="mt-3 text-2xl font-extrabold text-navy-800 sm:text-3xl">Selamat Datang Kembali</h2>
                {{-- <p class="mt-2 text-sm text-slate-500">Masuk untuk mengelola konten website.</p> --}}

                <form method="POST" action="{{ route('login') }}" class="mt-3 space-y-5 {{ $errors->any() ? 'animate-shake' : '' }}"
                    x-data="{ showPassword: false, submitting: false }"
                    @submit="submitting = true">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-semibold text-navy-800">Email</label>
                        <div class="relative">
                            <i class="fa-regular fa-envelope pointer-events-none absolute top-1/2 left-4 -translate-y-1/2 text-slate-400"></i>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                placeholder="nama@kemenkeu.go.id"
                                class="w-full rounded-xl border py-3 pr-4 pl-11 text-sm text-navy-800 transition placeholder:text-slate-400 focus:outline-none focus:ring-4 {{ $errors->has('email') ? 'border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-100' : 'border-slate-200 bg-slate-50 focus:border-brand-500 focus:bg-white focus:ring-brand-100' }}">
                        </div>
                        @error('email')
                            <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-red-600">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-semibold text-navy-800">Password</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock pointer-events-none absolute top-1/2 left-4 -translate-y-1/2 text-slate-400"></i>
                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                                placeholder="Masukkan password"
                                class="w-full rounded-xl border py-3 pr-11 pl-11 text-sm text-navy-800 transition placeholder:text-slate-400 focus:outline-none focus:ring-4 {{ $errors->has('password') ? 'border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-100' : 'border-slate-200 bg-slate-50 focus:border-brand-500 focus:bg-white focus:ring-brand-100' }}">
                            <button type="button" @click="showPassword = !showPassword" tabindex="-1"
                                class="absolute top-1/2 right-4 -translate-y-1/2 text-slate-400 transition hover:text-brand-700"
                                :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                                <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-red-600">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- CAPTCHA --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-navy-800">Verifikasi</label>
                        <div class="flex items-center justify-between gap-3 rounded-xl border border-brand-100 bg-brand-50/60 px-4 py-3">
                            <div>
                                <span class="text-base font-bold text-navy-800">{{ $captcha['question'] }}</span>
                                <p class="mt-0.5 text-xs text-slate-500">Selesaikan perhitungan di atas</p>
                            </div>
                            <button type="button" onclick="window.location.reload()" title="Muat ulang pertanyaan"
                                class="flex h-9 w-9 flex-none items-center justify-center rounded-full text-brand-700 transition hover:bg-brand-100">
                                <i class="fa-solid fa-rotate-right text-sm"></i>
                            </button>
                        </div>
                        <input type="text" name="captcha" required placeholder="Masukkan jawaban Anda" autocomplete="off"
                            class="mt-2.5 w-full rounded-xl border py-3 px-4 text-sm text-navy-800 transition placeholder:text-slate-400 focus:outline-none focus:ring-4 {{ $errors->has('captcha') ? 'border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-100' : 'border-slate-200 bg-slate-50 focus:border-brand-500 focus:bg-white focus:ring-brand-100' }}">
                        <input type="hidden" name="captcha_token" value="{{ $captcha['token'] }}">
                        @error('captcha')
                            <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-red-600">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit" :disabled="submitting"
                        class="fe-btn fe-btn-primary mt-2 w-full py-3 disabled:cursor-not-allowed disabled:opacity-70">
                        <i class="fa-solid fa-spinner animate-spin" x-show="submitting" x-cloak></i>
                        <span x-text="submitting ? 'Memproses...' : 'Masuk'"></span>
                        <i class="fa-solid fa-arrow-right text-xs" x-show="!submitting"></i>
                    </button>
                </form>

                <div class="mt-8 text-center text-xs text-slate-400">
                    <p>&copy; {{ date('Y') }} <a href="http://www.romadan.kemenkeu.go.id/" class="font-medium text-brand-700 hover:text-brand-800">Biro Manajemen BMN dan Pengadaan</a></p>
                    <p class="mt-1">Powered by Romadan {{ App\Helpers\VersionHelper::getShortVersion() }}</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
