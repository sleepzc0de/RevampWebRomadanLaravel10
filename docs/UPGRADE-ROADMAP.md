# Roadmap Perbaikan Lanjutan (Item Di-defer)

Dokumen ini merinci **rencana bertahap** untuk tiga item audit yang sengaja
**tidak dieksekusi** pada rangkaian perbaikan #1–#32 karena berisiko memecah
aplikasi bila dilakukan tanpa pengujian terdedikasi. Semua item di sini adalah
**keputusan tim** (menyangkut trade-off effort vs risiko vs timing), bukan bug
yang harus segera ditambal.

Ringkasan status: item keamanan & bug (#1–#24) dan refactor aman (#25, #28, #29,
#30, #32) sudah selesai. Yang tersisa:

| Item | Judul | Risiko | Estimasi | Status |
|------|-------|--------|----------|--------|
| #31 | Upgrade Laravel 10 → 12 | Tinggi (breaking changes) | — | ✅ **SELESAI** (12.63.0) |
| #27 | Rename model ke konvensi StudlyCase | Sedang (churn lintas file) | — | ✅ **SELESAI** |
| #26 | Migrasi skema password ke standar Laravel | Tinggi (bisa mengunci login) | 2–4 hari | ⏸️ **Direkomendasikan TIDAK dieksekusi** (lihat di bawah) |

> **Aturan umum untuk ketiganya:** kerjakan di branch terpisah, satu item per PR,
> jalankan `php artisan test` + `vendor/bin/phpstan analyse` di setiap fase, dan
> siapkan jalur rollback (`git revert`/restore lock) sebelum mulai.

---

## #26 — Migrasi Skema Password ke Standar Laravel

> ### ⏸️ Rekomendasi: JANGAN dieksekusi sekarang
> Setelah perbaikan #1 (pepper ke `.env` + `PasswordService` terpusat + test),
> skema password saat ini **sudah aman dan bersih**. Migrasi ke `Auth::attempt`
> standar **tidak menyelesaikan masalah keamanan apa pun** — ia hanya "kerapian
> arsitektur". Padahal:
> - Aplikasi **tidak memakai** `Auth::attempt`, password-reset broker, maupun
>   `password.confirm` di mana pun — jadi tidak ada fitur yang sedang terhambat.
> - Risikonya **tinggi** (salah langkah = seluruh user tidak bisa login) dan
>   Fase 3-nya butuh **jendela migrasi 60–90 hari** yang tak bisa dituntaskan
>   dalam satu sesi.
> - Kolom `salt` yang redundan bersifat **tidak berbahaya**.
>
> **Kesimpulan:** biaya & risiko > manfaat. Kerjakan HANYA bila muncul pemicu
> konkret (mis. butuh fitur "lupa password" via email). Rencana di bawah tetap
> disimpan sebagai panduan bila saat itu tiba.

### Konteks
Saat ini password disimpan sebagai `bcrypt(hmac_sha256(password + salt, pepper))`
lewat `App\Services\PasswordService`. Skema ini fungsional dan sudah terpusat,
tapi mengorbankan seluruh ekosistem auth Laravel (`Auth::attempt`, password
reset broker, `password.confirm`, dsb.) dan menambah kolom `salt` yang sebenarnya
tidak menaikkan keamanan (bcrypt sudah ber-salt internal).

**Target akhir:** login memakai `Auth::attempt` standar dengan `Hash::make`
(bcrypt), pepper opsional lewat konfigurasi, dan **migrasi transparan** —
user lama tidak perlu reset password.

### Prasyarat (WAJIB sebelum mulai)
- [ ] Test feature login berhasil & gagal (kredensial benar/salah, CAPTCHA, rate limit).
- [ ] Backup tabel `users` (skema saat ini tidak bisa di-downgrade tanpa data lama).

### Fase 1 — Rehash-on-login (backward compatible, TANPA breaking)
Tujuan: mulai menulis hash skema-baru setiap kali user login sukses, sambil tetap
menerima hash skema-lama. Tidak ada user yang terkunci.

1. Tambah method `PasswordService::isLegacy(User $user): bool` — `true` bila kolom
   `salt` terisi (penanda skema lama).
2. Tambah method `PasswordService::rehashToStandard(User $user, string $password)`:
   set `password = Hash::make($this->pepperRaw($password))`, kosongkan `salt`.
   (`pepperRaw` = HMAC pepper tanpa salt, atau langsung raw bila pepper ikut dibuang.)
3. Di `AuthController::login`, setelah `verify()` sukses:
   ```php
   if ($this->passwordService->isLegacy($user)) {
       $this->passwordService->rehashToStandard($user, $request->password);
   }
   ```
4. **Verifikasi:** login user lama → cek DB, `salt` jadi kosong & `password`
   berformat baru; login kedua tetap sukses lewat jalur standar.

### Fase 2 — Alihkan verifikasi ke jalur standar
1. `PasswordService::verify` mendukung DUA jalur: bila `salt` kosong pakai
   `Hash::check($this->pepperRaw($password), $hash)`, bila terisi pakai jalur lama.
2. Tulis test unit untuk kedua jalur (sudah ada `PasswordServiceTest` sebagai basis).

### Fase 3 — Sunset skema lama (setelah jendela migrasi)
Tunggu sampai mayoritas user sudah login minimal sekali (mis. 60–90 hari), pantau
lewat query `SELECT COUNT(*) FROM users WHERE salt IS NOT NULL AND salt <> ''`.

1. Paksa reset password untuk sisa user legacy (email broker), atau reset manual.
2. Migrasi: drop kolom `salt`.
3. Hapus jalur legacy di `PasswordService`.
4. (Opsional) pindah sepenuhnya ke `Auth::attempt` + hapus CAPTCHA-in-controller manual.

### Rollback
Selama Fase 1–2, `git revert` PR mengembalikan verifikasi lama; hash yang sudah
ter-rehash tetap valid karena `verify` masih mengenali keduanya. **Jangan**
jalankan Fase 3 (drop `salt`) sebelum yakin 100%.

---

## #27 — Rename Model ke Konvensi StudlyCase

### Konteks
Class model saat ini lowercase (`berita`, `medsos`, `ref_kategori`) di namespace
`App\Models\backend` (huruf kecil). Di server Linux (case-sensitive) ketidak-
konsistenan kapitalisasi namespace vs folder rawan gagal autoload.

### Temuan penting — model mati (SUDAH DIHAPUS ✅)
6 model berikut yatim (0 referensi, controllernya dihapus pada pembersihan kode
mati) dan **sudah dihapus** bersama dokumen ini — tidak perlu di-rename:
- ~~`app/Models/backend/berita.php`~~
- ~~`app/Models/backend/file.php`~~
- ~~`app/Models/backend/menu.php`~~
- ~~`app/Models/backend/publikasi/artikel.php`~~
- ~~`app/Models/backend/publikasi/berita.php`~~
- ~~`app/Models/backend/publikasi/warta.php`~~

Yang benar-benar perlu di-rename (masih dipakai): `medsos`, `ref_kategori`,
`ref_status`, `ref_tipe`, `ref_jenis_peraturan`, `ref_peraturan_status`, plus
model ber-sufiks tak konsisten (`PublikasiModel`, `TentangImage`, dst.).

### Catatan Windows (filesystem case-insensitive)
`git mv berita.php Berita.php` bisa tidak terdeteksi. Gunakan dua langkah:
```bash
git mv berita.php berita.php.tmp && git mv berita.php.tmp Berita.php
```

### Fase 1 — Pilot satu model end-to-end
Ambil `medsos` sebagai pilot (paling sederhana, sudah punya cache event).
1. Rename class `medsos` → `Medsos`, file → `Medsos.php`, namespace →
   `App\Models\Medsos` (atau target final Anda).
2. Update SEMUA referensi: `use`, type-hint, relasi (`belongsTo(...::class)`),
   `View::composer`, dsb. Gunakan `grep -rn "medsos"` untuk audit.
3. (Opsional, jaring pengaman transisi) tambahkan di `bootstrap/app.php` atau
   provider: `class_alias(App\Models\Medsos::class, 'App\Models\medsos\medsos');`
   supaya referensi lama tak langsung fatal — hapus setelah semua diupdate.
4. **Verifikasi:** `composer dump-autoload`, `php artisan test`, buka halaman
   yang memakai model itu, `vendor/bin/phpstan analyse`.

### Fase 2 — Batch sisanya, satu grup per PR
Kerjakan per-folder agar PR kecil & mudah di-review:
1. Grup `Referensi` (`ref_*`) — 5 model + relasinya.
2. Grup `MenuProfile` image models.
3. Standarkan sufiks (`PublikasiModel` → `Publikasi`), dst.

Setiap PR: rename → update referensi → `dump-autoload` → test → phpstan.

### Rollback
Per-PR granular; `git revert` satu grup aman karena tiap grup independen.

---

## #31 — Upgrade Laravel 10 → 12 ✅ SELESAI

> **Status:** Selesai pada revamp ini. Aplikasi kini di **Laravel 12.63.0**,
> **PHP 8.5**, dan `composer audit` melaporkan **0 advisory** (sebelumnya 3).
>
> **Catatan penting:** ternyata Laravel 11.x (s/d 11.54.0) MASIH terkena advisory
> yang sama, sehingga upgrade langsung ke **Laravel 12** (bukan berhenti di 11).
> Paket yang ikut naik: `spatie/laravel-permission` v5→v6.25, `yajra/laravel-
> datatables` v10→v12, `laravel/sanctum` v3→v4, `nesbot/carbon` v2→v3,
> `phpunit` 10→11, `nunomaduro/collision` 7→8, `larastan` 2→3. `laravel/breeze`
> dihapus (scaffolding-nya sudah tidak dipakai).
>
> **Terverifikasi:** 15/15 test hijau (PHPUnit 11), PHPStan hijau (Larastan 3),
> semua Blade terkompilasi, boot penuh (`route:list`/`about`) OK, Carbon locale
> Indonesia benar, spatie v6 & yajra v12 API kompatibel. Struktur Kernel lama
> (`Http/Kernel.php`, `bootstrap/app.php`) dipertahankan — L12 masih mendukungnya.
>
> **Belum dilakukan (opsional, follow-up):** migrasi ke slim skeleton Laravel 12
> (`bootstrap/app.php` sentral) — tidak wajib, aplikasi berjalan tanpa itu.

### Konteks (historis)
Laravel 10 sudah berhenti menerima patch keamanan (EOL Feb 2025). Tersisa **3
advisory** di `laravel/framework` yang hanya teratasi lewat upgrade major. Patch
transitif (guzzle/psr7/symfony) sudah ditarik pada #31 subset aman.

### Prasyarat (sudah terpenuhi ✅)
- [x] PHP `^8.2` (server dev PHP 8.5).
- [x] Test suite hijau (15 test).
- [x] Larastan/PHPStan terpasang + baseline.
- [ ] Naikkan cakupan test dulu bila memungkinkan (feature test alur utama:
      publikasi CRUD, login, role) — makin banyak test, makin aman upgrade.

### Strategi: naik BERTAHAP, jangan lompat langsung ke 12
Upgrade 10→11 dulu, stabilkan, baru 11→12. Jangan gabung.

### Fase 1 — Persiapan
1. Branch `upgrade/laravel-11`.
2. Inventaris paket pihak-ketiga & kcompatibilitasnya dengan L11:
   - `spatie/laravel-permission` → v6 (ada breaking di config & migration).
   - `yajra/laravel-datatables` → v11.
   - `laravel/sanctum` → v4.
   - `mews/purifier`, `cohensive/oembed` → cek rilis L11-compatible.
3. Pertimbangkan **Laravel Shift** (berbayar) atau **Rector set Laravel** untuk
   otomasi sebagian breaking changes.

### Fase 2 — Bump composer & resolusi
1. `composer.json`: `laravel/framework:^11.0`, `php:^8.2`, plus versi paket di atas.
2. `composer update` — selesaikan konflik constraint satu per satu.
3. Laravel 11 memakai skeleton ramping (`bootstrap/app.php` sentral, tanpa
   `Http/Kernel.php` & `Console/Kernel.php`). **Tidak wajib** pindah skeleton —
   aplikasi lama tetap jalan. Bila ingin pindah, lakukan sebagai langkah terpisah.

### Fase 3 — Perbaiki breaking changes
Rujuk [Upgrade Guide 11.x](https://laravel.com/docs/11.x/upgrade). Titik rawan
untuk proyek ini:
1. Middleware alias & grup (`app/Http/Kernel.php`) — tetap didukung via
   kompat, tapi verifikasi `role`, `auth`, `CheckIdleTimeout`, `SecurityHeaders`.
2. `spatie/laravel-permission` v6 — jalankan migration/perubahan config-nya.
3. Perubahan default `password` rule, casting, dan `Model::casts()` method.
4. Sanctum v4 — cek `personal_access_tokens`.

### Fase 4 — Verifikasi menyeluruh
1. `php artisan test` + `vendor/bin/phpstan analyse` (regenerasi baseline bila perlu).
2. `composer audit` — pastikan 3 advisory framework hilang.
3. Uji manual jalur kritis: login, dashboard per-role, publikasi CRUD + upload,
   backup, halaman frontend + embed media.

### Fase 5 — Lanjut 11 → 12
Ulangi Fase 1–4 dengan target `laravel/framework:^12.0` setelah L11 stabil di
produksi minimal 1–2 minggu.

### Rollback
`git revert` merge commit + `composer install` dari `composer.lock` lama
mengembalikan seluruh dependensi. Simpan `composer.lock` pra-upgrade sebagai tag.

---

## Status akhir
1. ~~**#31 (upgrade Laravel)**~~ — ✅ **SELESAI** (langsung ke Laravel 12, advisory keamanan tertutup).
2. ~~**#27 (rename model)**~~ — ✅ **SELESAI** (6 model mati dihapus, 6 model dipakai di-rename ke StudlyCase).
3. **#26 (skema password)** — ⏸️ **direkomendasikan tidak dieksekusi** (manfaat rendah, risiko tinggi; lihat bagian #26).

Dengan ini seluruh audit #1–#32 tuntas: dikerjakan, atau (untuk #26) sengaja
tidak dikerjakan dengan alasan yang terdokumentasi.
