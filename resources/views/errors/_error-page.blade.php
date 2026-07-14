{{-- Kerangka bersama halaman error HTTP. Sepenuhnya mandiri (CSS inline,
     tanpa Vite/DB/Auth) supaya tetap tampil walau kondisi server sedang rusak.
     Menerima: $code, $title, $message, $showHomeLink (opsional, default true),
               $showReload (opsional, default false) --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $code }} — {{ $title }}</title>
    <style>
        *{ margin:0; padding:0; box-sizing:border-box; }
        body{
            font-family:'Segoe UI',system-ui,-apple-system,sans-serif;
            background:#f1f5f9; color:#1e293b;
            min-height:100vh; display:flex; align-items:center; justify-content:center;
            padding:1.5rem;
        }
        .wrap{ text-align:center; max-width:28rem; }
        .code{
            font-size:6rem; font-weight:800; line-height:1;
            color:#0f5fae; letter-spacing:-.03em;
        }
        .bar{ width:3rem; height:.25rem; background:#f5b800; border-radius:9999px; margin:1.25rem auto; }
        h1{ font-size:1.375rem; font-weight:700; color:#0f172a; }
        p{ margin-top:.625rem; font-size:.9375rem; line-height:1.6; color:#64748b; }
        .actions{ margin-top:1.75rem; display:flex; gap:.75rem; justify-content:center; flex-wrap:wrap; }
        a.btn, button.btn{
            display:inline-block; padding:.625rem 1.5rem; border-radius:.5rem; border:0;
            font-size:.875rem; font-weight:600; text-decoration:none; cursor:pointer;
            background:#0f5fae; color:#fff; transition:background .15s;
        }
        a.btn:hover, button.btn:hover{ background:#0c4d8f; }
        a.btn-ghost{ background:transparent; color:#0f5fae; border:1px solid #cbd5e1; }
        a.btn-ghost:hover{ background:#e2e8f0; }
        .brand{ margin-top:2.5rem; font-size:.75rem; color:#94a3b8; }
        @media (prefers-color-scheme: dark){
            body{ background:#0b1220; color:#e2e8f0; }
            h1{ color:#f1f5f9; }
            p{ color:#94a3b8; }
            .code{ color:#4d9fe8; }
            a.btn-ghost{ color:#4d9fe8; border-color:#334155; }
            a.btn-ghost:hover{ background:#1e293b; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="code">{{ $code }}</div>
        <div class="bar"></div>
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
        <div class="actions">
            @if($showReload ?? false)
                <button type="button" class="btn" onclick="window.location.reload()">Muat Ulang Halaman</button>
            @endif
            @if($showHomeLink ?? true)
                <a href="{{ url('/') }}" class="btn {{ ($showReload ?? false) ? 'btn-ghost' : '' }}">Kembali ke Beranda</a>
            @endif
        </div>
        <div class="brand">Biro Manajemen BMN dan Pengadaan</div>
    </div>
</body>
</html>
