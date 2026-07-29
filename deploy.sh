#!/usr/bin/env bash
#
# Deploy CMS Romadan ke server production.
#
# Pemakaian:  sudo bash deploy.sh [nama-branch]     (default: UPGRADE_PROD)
#
# Prinsip keamanan skrip ini:
#   1. `set -e` — SATU langkah gagal langsung menghentikan seluruh proses.
#   2. Situs dimasukkan ke mode pemeliharaan di awal dan HANYA dikeluarkan
#      setelah `php artisan deploy:check` lolos sepenuhnya.
#   Artinya: bila ada langkah yang gagal (mis. `npm run build`), pengunjung
#   melihat halaman "Sedang Dalam Pemeliharaan", BUKAN halaman 500.

set -euo pipefail

BRANCH="${1:-UPGRADE_PROD}"
APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
WEB_USER="www-data"
STASHED=0
STEP=""

C_RESET=$'\033[0m'; C_BOLD=$'\033[1m'; C_GREEN=$'\033[32m'; C_RED=$'\033[31m'
C_YELLOW=$'\033[33m'; C_CYAN=$'\033[36m'; C_GRAY=$'\033[90m'

step()  { STEP="$1"; printf '\n%s==>%s %s%s%s\n' "$C_CYAN" "$C_RESET" "$C_BOLD" "$1" "$C_RESET"; }
info()  { printf '    %s%s%s\n' "$C_GRAY" "$1" "$C_RESET"; }
ok()    { printf '    %s%s%s\n' "$C_GREEN" "$1" "$C_RESET"; }
die()   { printf '\n%s%sGAGAL:%s %s\n' "$C_RED" "$C_BOLD" "$C_RESET" "$1"; exit 1; }

on_error() {
  printf '\n%s%s======================================================%s\n' "$C_RED" "$C_BOLD" "$C_RESET"
  printf '%s%s DEPLOY DIHENTIKAN pada langkah: %s%s\n' "$C_RED" "$C_BOLD" "$STEP" "$C_RESET"
  printf '%s======================================================%s\n\n' "$C_RED" "$C_RESET"
  printf ' Situs SENGAJA dibiarkan dalam mode pemeliharaan agar\n'
  printf ' pengunjung tidak melihat halaman error.\n\n'
  printf ' Setelah masalah di atas diperbaiki, jalankan ulang:\n'
  printf '   %ssudo bash deploy.sh %s%s\n\n' "$C_BOLD" "$BRANCH" "$C_RESET"
  printf ' Untuk memaksa situs online tanpa perbaikan (TIDAK disarankan):\n'
  printf '   %scd %s && php artisan up%s\n\n' "$C_BOLD" "$APP_DIR" "$C_RESET"
}
trap on_error ERR

cd "$APP_DIR"
[ -f artisan ] || die "direktori $APP_DIR bukan root aplikasi Laravel (file 'artisan' tidak ada)."

printf '\n%s%sDeploy CMS Romadan%s  %sbranch: %s | direktori: %s%s\n' \
  "$C_BOLD" "$C_CYAN" "$C_RESET" "$C_GRAY" "$BRANCH" "$APP_DIR" "$C_RESET"

# --------------------------------------------------------------------------
step "1/12  Memeriksa prasyarat"
for cmd in php composer git; do
  command -v "$cmd" >/dev/null 2>&1 || die "'$cmd' tidak ditemukan di PATH."
done

PHP_VER="$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')"
php -r 'exit(version_compare(PHP_VERSION, "8.3.0", ">=") ? 0 : 1);' \
  || die "PHP $PHP_VER terlalu lama — aplikasi membutuhkan PHP 8.3 atau lebih baru."
info "PHP $PHP_VER"

command -v node >/dev/null 2>&1 || die \
"Node.js belum terpasang — WAJIB untuk membangun aset tampilan (Tailwind/Alpine).
       Pasang dulu dengan:
         curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
         sudo apt install -y nodejs"

NODE_MAJOR="$(node -p 'process.versions.node.split(".")[0]')"
[ "$NODE_MAJOR" -ge 20 ] || die \
"Node.js v$(node -v) terlalu lama (butuh v20 ke atas).
       Perbarui dengan:
         curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
         sudo apt install -y nodejs"
info "Node.js $(node -v), npm $(npm -v)"

# --------------------------------------------------------------------------
step "2/12  Mengaktifkan mode pemeliharaan"
php artisan down --retry=60 >/dev/null 2>&1 || true
ok "situs menampilkan halaman pemeliharaan"

# --------------------------------------------------------------------------
step "3/12  Mengambil source terbaru ($BRANCH)"
if ! git diff --quiet || ! git diff --cached --quiet; then
  git stash push -u -m "deploy-$(date +%Y%m%d-%H%M%S)" >/dev/null
  STASHED=1
  info "perubahan lokal disimpan sementara (git stash)"
fi

git fetch origin "$BRANCH"
git checkout "$BRANCH" >/dev/null 2>&1 || true
git pull origin "$BRANCH"

if [ "$STASHED" -eq 1 ]; then
  git stash drop >/dev/null
  info "stash dibuang (perubahan lokal tidak dipakai di production)"
fi
ok "commit sekarang: $(git rev-parse --short HEAD) — $(git log -1 --pretty=%s | cut -c1-60)"

# --------------------------------------------------------------------------
step "4/12  Memasang dependensi PHP (composer)"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
ok "vendor/ sinkron dengan composer.lock"

# --------------------------------------------------------------------------
step "5/12  Memasang dependensi build (npm)"
npm ci --include=dev --no-audit --no-fund
ok "node_modules/ siap"

# --------------------------------------------------------------------------
step "6/12  Membangun aset tampilan (Vite)"
npm run build
[ -f public/build/manifest.json ] || die "build selesai tapi public/build/manifest.json tidak terbentuk."
ok "public/build/manifest.json terbentuk"

# --------------------------------------------------------------------------
step "7/12  Menjalankan migrasi database"
php artisan migrate --force
ok "skema database terbaru"

# --------------------------------------------------------------------------
step "8/12  Memastikan symlink storage"
[ -e public/storage ] || php artisan storage:link
ok "public/storage tersedia"

# --------------------------------------------------------------------------
step "9/12  Membersihkan log lama & cache"
rm -f storage/logs/*.log
php artisan optimize:clear
ok "cache lama dibersihkan"

# --------------------------------------------------------------------------
step "10/12  Membangun cache production"
php artisan config:cache
php artisan route:cache
php artisan view:cache
ok "config, route, dan view ter-cache"

# --------------------------------------------------------------------------
step "11/12  Menyetel kepemilikan berkas"
if id -u "$WEB_USER" >/dev/null 2>&1; then
  chown -R "$WEB_USER:$WEB_USER" storage bootstrap/cache public/build
  chmod -R 775 storage bootstrap/cache
  ok "storage, bootstrap/cache, public/build milik $WEB_USER"
else
  info "pengguna $WEB_USER tidak ada — langkah kepemilikan dilewati"
fi

# --------------------------------------------------------------------------
step "12/12  Verifikasi kesiapan (gerbang sebelum online)"
# Dijalankan sebagai pengguna web supaya pemeriksaan izin tulis akurat
# (root selalu lolos is_writable sehingga hasilnya menyesatkan).
if id -u "$WEB_USER" >/dev/null 2>&1 && command -v sudo >/dev/null 2>&1; then
  sudo -u "$WEB_USER" php artisan deploy:check
else
  php artisan deploy:check
fi

# --------------------------------------------------------------------------
trap - ERR
php artisan up >/dev/null
command -v systemctl >/dev/null 2>&1 && systemctl reload "php${PHP_VER}-fpm" 2>/dev/null || true

printf '\n%s%s======================================================%s\n' "$C_GREEN" "$C_BOLD" "$C_RESET"
printf '%s%s DEPLOY BERHASIL — situs kembali online%s\n' "$C_GREEN" "$C_BOLD" "$C_RESET"
printf '%s======================================================%s\n\n' "$C_GREEN" "$C_RESET"
printf ' Verifikasi cepat di browser:\n'
printf '   1. Buka halaman depan — tampilan bergaya (bukan teks polos)\n'
printf '   2. Login admin — form tampil normal\n'
printf '   3. Menu Extras > Monitor Sistem — Config/Route Cache "Aktif"\n\n'
