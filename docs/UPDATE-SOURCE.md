# Update Source Aplikasi Web Romadan dengan `deploy.sh`

Runbook update rutin untuk server **production** (`romadan.kemenkeu.go.id`,
path `/var/www/webromadanv1`, branch `UPGRADE_PROD`). Untuk server development
pakai `deploy-dev.sh`; untuk instalasi VPS baru lihat
[INSTALASI-VPS-PERTAMA.md](INSTALASI-VPS-PERTAMA.md).

## Prasyarat (cek sekali, bukan tiap update)

- **PHP 8.3 atau lebih baru:** `php -v`.
- **Composer 2.x terbaru.** Composer 2.2.6 bawaan apt Ubuntu 22.04 bermasalah di
  PHP 8.4 ke atas; pasang yang resmi dari getcomposer.org.
- **`.env` benar:** `APP_ENV=production`, `APP_DEBUG=false`, serta `APP_KEY` dan
  `PASSWORD_PEPPER` terisi dan **tidak pernah diganti**.
- **`setup-server.sh` pernah dijalankan** (memasang Node 22, pengaturan log, dan
  cron scheduler). Cek cron:

```bash
sudo crontab -u www-data -l
```

- **Backup native SQL Server** diatur DBA. Backup bawaan aplikasi hanya cadangan
  sekunder (lihat catatan di bawah).

## Prosedur rutin (setiap rilis)

**STEP 0: Masuk dan catat commit saat ini.** Catat hash-nya, ini titik rollback.

```bash
cd /var/www/webromadanv1 && git log --oneline -1
```

**STEP 1: Cek ruang disk dan perubahan lokal.** `deploy.sh` membuang perubahan
lokal yang belum di-commit.

```bash
df -h /var/www | tail -n 1
```

```bash
git status --short
```

**STEP 2: Ketahui apakah rilis membawa migrasi database.**

```bash
git fetch origin UPGRADE_PROD && git diff --name-only HEAD..origin/UPGRADE_PROD -- database/migrations
```

- **Kosong:** tidak ada perubahan skema; rollback cukup dari sisi kode.
- **Ada nama file:** minta DBA membuat backup native SQL Server **sebelum** lanjut.

**STEP 3: Tarik skrip terbaru.**

```bash
cd /var/www/webromadanv1 && git pull origin UPGRADE_PROD
```

Pull ini perlu karena `deploy.sh` yang sedang berjalan adalah salinan yang sudah
dimuat di memori; tanpa pull dulu, rilis yang mengubah `deploy.sh` sendiri akan
dijalankan dengan versi lamanya. Jika muncul "Your local changes would be
overwritten", jalankan `sudo git stash` lalu ulangi pull.

`setup-server.sh` **tidak perlu tiap update**; cukup saat pertama kali, setelah
server di-rebuild, atau bila rilis mengubah file itu. Menjalankannya tiap kali
tidak berbahaya:

```bash
sudo bash setup-server.sh
```

**STEP 4: Jalankan deploy.** Situs masuk mode pemeliharaan selama proses
(beberapa menit).

```bash
sudo bash deploy.sh
```

Berhasil bila berakhir dengan banner **DEPLOY BERHASIL**. Situs otomatis online
lagi, **hanya jika** pemeriksaan kesiapan lolos.

**STEP 5: Verifikasi.**

```bash
sudo -u www-data php artisan deploy:check
```

```bash
sudo tail -n 40 storage/logs/laravel-$(date +%F).log
```

Cek di browser: halaman depan tampil dengan gaya, login admin berhasil, dan menu
Monitor Sistem menunjukkan Config/Route Cache "Aktif".

## Apa yang dikerjakan `deploy.sh` (pemetaan dari langkah manual lama)

| Langkah manual lama | Di `deploy.sh` |
|---|---|
| 1 `apt install nodejs` | Tidak perlu tiap update. Node 22 dipasang `setup-server.sh`; `deploy.sh` hanya memeriksa versinya (>= 20) |
| 2 `artisan down` | Langkah 2 |
| 3-5 `stash` → `pull` → `stash drop` | Langkah 3 (`stash -u`, `fetch`, `checkout`, `pull`, lalu drop) |
| 6 `composer install --no-dev ...` | Langkah 4 (ditambah `--no-progress`) |
| 7-8 `npm ci` dan `npm run build` | Langkah 5-6 (juga memastikan `manifest.json` terbentuk) |
| 9 `migrate --force` | Langkah 7, **didahului snapshot database** |
| *(tidak ada)* | Langkah 8: pastikan symlink `public/storage` |
| 10 `rm -f storage/logs/*.log` | Langkah 9: **hanya log lebih dari 7 hari** |
| 11 `optimize:clear` | Langkah 9 |
| 12-14 `config`, `route`, `view:cache` | Langkah 10 |
| 15 `chown ...` | Langkah 11 (ditambah `chmod 775`) |
| *(tidak ada)* | Langkah 12: **`deploy:check` sebagai gerbang**, merender halaman login dan depan |
| 16 `artisan up` | Otomatis di akhir, **hanya bila langkah 12 lolos**, lalu `php-fpm` di-reload |

## Beda perilaku dibanding cara manual lama

- **Situs tidak online jika ada yang rusak.** Dulu `artisan up` selalu dijalankan;
  sekarang bila ada langkah gagal, pengunjung melihat halaman pemeliharaan, bukan
  error 500.
- **Log tidak lagi dihapus semua**, hanya yang lebih dari 7 hari.
- **Snapshot database sebelum migrasi** (`backup:run --db-only`). Untuk SQL Server
  di Linux ini adalah *dump data lewat PHP* yang **belum diuji terhadap SQL Server
  sungguhan**. Pada deploy pertama dengan skrip baru, pantau langkah 7; bila
  database besar, langkah ini bisa lama. Bila gagal, situs aman tertahan di
  halaman pemeliharaan.

Bila langkah 7 gagal dan Anda sudah punya backup native dari DBA, lanjutkan tanpa
snapshot:

```bash
sudo SKIP_PRE_MIGRATE_BACKUP=1 bash deploy.sh
```

## Jika gagal

Pesan di layar menyebut langkah yang gagal. Perbaiki penyebabnya lalu jalankan
`sudo bash deploy.sh` lagi; aman diulang. **Jangan** memaksa online dengan
`php artisan up` sebelum penyebabnya jelas.

| Gagal di langkah | Yang biasanya terjadi |
|---|---|
| 1 | PHP < 8.3 atau Node tidak ada. Jalankan `sudo bash setup-server.sh` |
| 4 | Composer lama, atau `.env`/izin bermasalah. Perbarui Composer |
| 5-6 | Build aset gagal. Cek Node dan koneksi npm; bila `Killed`, RAM habis (tambah swap/RAM) |
| 7 | Database tidak tersambung, snapshot gagal, atau migrasi error. Baca pesan; lihat opsi `SKIP` di atas |
| 12 | `deploy:check` menandai masalah (APP_DEBUG aktif, DB, atau izin). Perintah perbaikannya tercetak di layar |

## Rollback

Ganti `HASH_LAMA` dengan commit dari STEP 0. **Hanya aman bila rilis tidak membawa
migrasi** (hasil STEP 2 kosong). Bila ada migrasi, pulihkan juga database dari
backup.

```bash
cd /var/www/webromadanv1 && sudo php artisan down && sudo git reset --hard HASH_LAMA && sudo composer install --no-dev --optimize-autoloader --no-interaction --no-progress && sudo npm ci --include=dev && sudo npm run build && sudo php artisan optimize:clear && sudo php artisan config:cache && sudo php artisan route:cache && sudo php artisan view:cache && sudo chown -R www-data:www-data storage bootstrap/cache public/build && sudo php artisan up
```

## Ringkasan satu baris

```bash
cd /var/www/webromadanv1 && git pull origin UPGRADE_PROD && sudo bash deploy.sh
```
