# Instalasi Pertama Kali di VPS Baru (Ubuntu + Nginx + PHP + SQL Server)

Runbook untuk memasang Web Romadan di VPS yang masih kosong. Untuk update
sesudahnya lihat [UPDATE-SOURCE.md](UPDATE-SOURCE.md).

Path memakai `/var/www/webromadanv1`; ganti bila berbeda. Domain ditulis
`DOMAIN-ANDA`. Perintah instalasi driver dan SQL Server mengikuti dokumentasi
Microsoft Learn (diperiksa Oktober 2026); belum dijalankan di VPS sungguhan oleh
penulis dokumen ini, jadi perhatikan tiap hasilnya.

## Asumsi

- Ubuntu 22.04 atau 24.04, akses `sudo`, dan domain yang sudah mengarah ke IP VPS
  (**HTTPS wajib** di production).
- PHP 8.3 (PHP 8.5 juga jalan; ganti semua `8.3` menjadi `8.5`).
- SQL Server berada di **server lain** (Bagian 3A) atau **di VPS ini** (Bagian 3B,
  minimal 2 GB RAM). Database MySQL/PostgreSQL tidak dicakup dokumen ini.

## Hal yang sering salah

1. **Jangan `apt install composer`.** Versi 2.2.6 bermasalah di PHP 8.4 ke atas.
   Pasang Composer resmi (STEP 4) plus `php8.3-intl`.
2. **Atur `APP_ENV=production` dan `APP_DEBUG=false` sebelum deploy.** Nilai bawaan
   `.env.example` adalah `local` dan `true`.
3. **SQL Server harus sudah hidup dan dites sebelum deploy** (Bagian 3C).
4. **Di server production hanya pakai `deploy.sh`**; `deploy-dev.sh` untuk development.

---

## Bagian 1: Server dasar

**STEP 1: Update sistem**

```bash
sudo apt update && sudo apt upgrade -y
```

**STEP 2: Tambah repo PHP.** Khusus Ubuntu 22.04 (24.04 tidak perlu):

```bash
sudo apt install -y software-properties-common && sudo add-apt-repository -y ppa:ondrej/php && sudo apt update
```

**STEP 3: Paket utama**

```bash
sudo apt install -y nginx git unzip curl cron build-essential php-pear php8.3-fpm php8.3-cli php8.3-dev php8.3-mbstring php8.3-xml php8.3-zip php8.3-gd php8.3-curl php8.3-intl
```

```bash
php -v
```

**STEP 4: Composer resmi**

```bash
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

```bash
hash -r && composer --version
```

**STEP 5: Firewall.** Jangan buka port 1433 ke internet.

```bash
sudo ufw allow OpenSSH && sudo ufw allow 'Nginx Full' && sudo ufw --force enable
```

**STEP 6: Batas upload** (menu Peraturan menerima file sampai 100 MB)

```bash
printf "upload_max_filesize=100M\npost_max_size=110M\nmemory_limit=256M\n" | sudo tee /etc/php/8.3/fpm/conf.d/99-romadan.ini && sudo systemctl restart php8.3-fpm
```

## Bagian 2: Driver SQL Server untuk PHP

**STEP 7: ODBC Driver 18, sqlcmd, dan header unixODBC**

```bash
curl -sSL -O https://packages.microsoft.com/config/ubuntu/$(grep VERSION_ID /etc/os-release | cut -d '"' -f 2)/packages-microsoft-prod.deb && sudo dpkg -i packages-microsoft-prod.deb && rm packages-microsoft-prod.deb
```

```bash
sudo apt-get update && sudo ACCEPT_EULA=Y apt-get install -y msodbcsql18 mssql-tools18 unixodbc-dev
```

```bash
echo 'export PATH="$PATH:/opt/mssql-tools18/bin"' >> ~/.bashrc && source ~/.bashrc
```

**STEP 8: Ekstensi PHP `sqlsrv` dan `pdo_sqlsrv`**

```bash
sudo pecl config-set php_ini /etc/php/8.3/fpm/php.ini
```

```bash
sudo pecl install sqlsrv
```

```bash
sudo pecl install pdo_sqlsrv
```

```bash
printf "; priority=20\nextension=sqlsrv.so\n" | sudo tee /etc/php/8.3/mods-available/sqlsrv.ini
```

```bash
printf "; priority=30\nextension=pdo_sqlsrv.so\n" | sudo tee /etc/php/8.3/mods-available/pdo_sqlsrv.ini
```

```bash
sudo phpenmod -v 8.3 sqlsrv pdo_sqlsrv && sudo systemctl restart php8.3-fpm
```

```bash
php -m | grep -i sqlsrv
```

Harus tampil `pdo_sqlsrv` dan `sqlsrv`. Pesan "already installed ... install
failed" dari `pecl` bila dijalankan ulang tidak masalah.

## Bagian 3: SQL Server

### 3A. SQL Server di server lain

Minta DBA menyiapkan:

- database `webromadan` dengan collation `SQL_Latin1_General_CP1_CI_AS`
  (**harus CI**: aplikasi menjodohkan `Published` dengan `published`);
- login `romadan_app` dengan `db_owner` di database itu;
- firewall 1433 terbuka untuk IP VPS.

Uji dari VPS dengan alamat asli:

```bash
timeout 5 bash -c '</dev/tcp/ALAMAT_SQLSERVER/1433' && echo "port 1433 TERBUKA" || echo "port 1433 TERTUTUP"
```

Alamat internal (`10.x.x.x`) hanya terjangkau bila VPS berada di jaringan yang
sama atau memakai VPN.

### 3B. SQL Server di VPS ini

Ubuntu 22.04 memakai SQL Server 2022:

```bash
curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | sudo gpg --dearmor -o /usr/share/keyrings/microsoft-prod.gpg
```

```bash
curl -fsSL https://packages.microsoft.com/config/ubuntu/22.04/mssql-server-2022.list | sudo tee /etc/apt/sources.list.d/mssql-server-2022.list
```

Ubuntu 24.04: ganti URL menjadi `.../ubuntu/24.04/mssql-server-2025.list`.

```bash
sudo apt-get update && sudo apt-get install -y mssql-server
```

```bash
sudo /opt/mssql/bin/mssql-conf setup
```

Pilih edisi **Express** (gratis untuk produksi, batas 10 GB per database) atau
edisi berlisensi; Developer hanya untuk non-produksi. Batasi memori agar PHP dan
Nginx kebagian:

```bash
sudo /opt/mssql/bin/mssql-conf set memory.memorylimitmb 2048 && sudo systemctl restart mssql-server
```

Pastikan benar-benar hidup:

```bash
systemctl status mssql-server --no-pager
```

```bash
sudo ss -ltnp | grep 1433
```

Buat database dan login aplikasi (password `sa` dibaca tanpa tampil; hindari
tanda kutip tunggal di password baru):

```bash
read -rs SQLCMDPASSWORD && export SQLCMDPASSWORD
```

```bash
sqlcmd -S localhost -U sa -C -Q "CREATE DATABASE webromadan COLLATE SQL_Latin1_General_CP1_CI_AS; CREATE LOGIN romadan_app WITH PASSWORD = 'GANTI_PASSWORD_KUAT_1', CHECK_POLICY = ON;"
```

```bash
sqlcmd -S localhost -U sa -C -d webromadan -Q "CREATE USER romadan_app FOR LOGIN romadan_app; ALTER ROLE db_owner ADD MEMBER romadan_app;"
```

```bash
unset SQLCMDPASSWORD
```

### 3C. Tes koneksi sebagai login aplikasi (wajib, 3A maupun 3B)

Untuk 3A, ganti `127.0.0.1` dengan alamat server.

```bash
read -rs DBP && export DBP
```

```bash
php -r '$c = new PDO("sqlsrv:Server=127.0.0.1,1433;Database=webromadan;Encrypt=yes;TrustServerCertificate=yes", "romadan_app", getenv("DBP")); echo $c->query("SELECT @@VERSION")->fetchColumn(), PHP_EOL;'
```

```bash
unset DBP
```

**Jangan lanjut sebelum ini mencetak versi SQL Server.**

## Bagian 4: Aplikasi

**STEP 9: Ambil kode.** Bila repo private, siapkan deploy key atau token GitHub dulu.

```bash
sudo git clone -b UPGRADE_PROD https://github.com/sleepzc0de/RevampWebRomadanLaravel10.git /var/www/webromadanv1
```

**STEP 10: Buat `.env`**

```bash
cd /var/www/webromadanv1 && sudo cp .env.example .env
```

```bash
sudo sed -i "s|^APP_KEY=.*|APP_KEY=base64:$(openssl rand -base64 32)|" .env
```

```bash
sudo sed -i "s|^PASSWORD_PEPPER=.*|PASSWORD_PEPPER=$(php -r 'echo base64_encode(bin2hex(random_bytes(48)));')|" .env
```

> **Simpan `APP_KEY` dan `PASSWORD_PEPPER` ke password manager sekarang.**
> Kehilangan pepper membuat semua password tidak bisa dipakai; kehilangan
> `APP_KEY` merusak semua rahasia 2FA.

```bash
sudo nano .env
```

```ini
APP_NAME="CMS Romadan"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://DOMAIN-ANDA
DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=webromadan
DB_USERNAME=romadan_app
DB_PASSWORD='PASSWORD_ROMADAN_APP'
DB_ENCRYPT=yes
DB_TRUST_SERVER_CERTIFICATE=true
```

Untuk 3A, isi `DB_HOST` dengan alamat server SQL. Password dibungkus kutip tunggal
karena `#`, `$`, dan spasi bisa terbaca salah. Cek hasilnya:

```bash
sudo grep -E '^(APP_ENV|APP_DEBUG|APP_URL|DB_CONNECTION|DB_HOST|DB_DATABASE|DB_USERNAME)=' .env
```

Izinkan php-fpm membaca `.env`, tetapi bukan semua user:

```bash
sudo chown root:www-data .env && sudo chmod 640 .env
```

## Bagian 5: Nginx dan HTTPS

**STEP 11: Konfigurasi Nginx**

```bash
sudo nano /etc/nginx/sites-available/romadan
```

```nginx
server {
    listen 80;
    server_name DOMAIN-ANDA;
    root /var/www/webromadanv1/public;
    index index.php;

    client_max_body_size 100M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Folder unggahan tidak boleh mengeksekusi PHP (harus SEBELUM blok .php)
    location ~* ^/storage/.*\.php$ { deny all; }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }

    location ~ /\.(?!well-known) { deny all; }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/romadan /etc/nginx/sites-enabled/romadan && sudo rm -f /etc/nginx/sites-enabled/default
```

```bash
sudo nginx -t && sudo systemctl reload nginx
```

**STEP 12: HTTPS**

```bash
sudo apt install -y certbot python3-certbot-nginx
```

```bash
sudo certbot --nginx -d DOMAIN-ANDA
```

## Bagian 6: Deploy pertama

**STEP 13: Persiapan server** (Node 22, pengaturan log, cron scheduler, izin berkas)

```bash
cd /var/www/webromadanv1 && sudo bash setup-server.sh
```

**STEP 14: Deploy.** Database masih kosong, jadi snapshot sebelum migrasi dilewati:

```bash
sudo SKIP_PRE_MIGRATE_BACKUP=1 bash deploy.sh
```

Berhasil bila berakhir dengan banner **DEPLOY BERHASIL**.

**STEP 15: Data awal. Cukup SEKALI.** Menjalankannya dua kali menggandakan data
referensi. Password admin tercetak sekali, jadi catat.

```bash
sudo -u www-data php artisan db:seed --force
```

Opsional, kontak dan link footer bawaan (aman diulang):

```bash
sudo -u www-data php artisan db:seed --class=FooterSettingsSeeder --force
```

**STEP 16: Ganti password admin.** Segera; password seeder hanya sementara.

```bash
sudo -u www-data php artisan user:set-password admin@romadan.kemenkeu.go.id
```

Buka `https://DOMAIN-ANDA/bDBnMW5fY201X2IxcjBtNGQ0bl9rM21lbmszdQ==`, masuk, lalu
**aktifkan 2FA** di menu Keamanan.

## Bagian 7: Backup (SQL Server)

Backup bawaan aplikasi di Linux berupa dump data lewat PHP dan **belum diuji
terhadap SQL Server sungguhan**. Jadikan **backup native** sebagai yang utama.
Untuk 3A ini tugas DBA. Untuk 3B:

```bash
sudo bash -c 'read -rs -p "Password romadan_app: " P && printf "%s" "$P" > /root/.romadan-sql-pass && chmod 600 /root/.romadan-sql-pass'
```

```bash
sudo mkdir -p /var/opt/mssql/backup && sudo chown mssql:mssql /var/opt/mssql/backup
```

```bash
sudo tee /usr/local/sbin/romadan-sql-backup.sh >/dev/null <<'EOF'
#!/usr/bin/env bash
set -euo pipefail
export SQLCMDPASSWORD="$(cat /root/.romadan-sql-pass)"
FILE="/var/opt/mssql/backup/romadan-$(date +%F).bak"
/opt/mssql-tools18/bin/sqlcmd -S localhost -U romadan_app -C -b -Q "BACKUP DATABASE [webromadan] TO DISK = N'${FILE}' WITH CHECKSUM, INIT"
find /var/opt/mssql/backup -name 'romadan-*.bak' -mtime +7 -delete
EOF
```

```bash
sudo chmod 700 /usr/local/sbin/romadan-sql-backup.sh && sudo /usr/local/sbin/romadan-sql-backup.sh && ls -lh /var/opt/mssql/backup
```

```bash
echo "30 0 * * * root /usr/local/sbin/romadan-sql-backup.sh" | sudo tee /etc/cron.d/romadan-sql-backup
```

Berkas `.bak` berada di disk VPS yang sama: salin juga ke luar server secara
berkala dan **uji restore** ke database lain sebelum bergantung padanya.

## Bagian 8: Verifikasi akhir

```bash
sudo -u www-data php artisan deploy:check
```

```bash
sudo crontab -u www-data -l
```

Harus ada baris `schedule:run`. Cek di browser: halaman depan tampil dengan gaya
dan HTTPS valid, login admin berhasil, Monitor Sistem menunjukkan Config/Route
Cache "Aktif", dan menyimpan form kosong di CMS menampilkan alasan error yang
jelas.

## Jika bermasalah

| Gejala | Cek |
|---|---|
| `Login timeout expired` saat deploy | SQL Server belum hidup atau salah alamat: `sudo ss -ltnp \| grep 1433`, ulangi 3C |
| `could not find driver` | `ls /etc/php/8.3/fpm/conf.d/*sqlsrv.ini`, lalu `sudo systemctl restart php8.3-fpm` |
| Composer fatal `Normalizer` | Gunakan Composer resmi (STEP 4) dan pasang `php8.3-intl` |
| Tampilan tanpa gaya / login berputar | HTTPS belum aktif atau `APP_URL` bukan `https://domain` |
| 502 Bad Gateway | Nama socket di Nginx harus cocok dengan `ls /run/php/` |
| `vite build` mati dengan `Killed` | RAM habis; tambahkan swap atau tambah RAM |
| Error umum | `sudo tail -n 50 /var/www/webromadanv1/storage/logs/laravel-$(date +%F).log` |

## Sumber

- [PHP drivers di Linux](https://learn.microsoft.com/en-us/sql/connect/php/installation-tutorial-linux-mac)
- [ODBC Driver di Linux](https://learn.microsoft.com/en-us/sql/connect/odbc/linux-mac/installing-the-microsoft-odbc-driver-for-sql-server)
- [Install SQL Server di Ubuntu](https://learn.microsoft.com/en-us/sql/linux/quickstart-install-connect-ubuntu)
