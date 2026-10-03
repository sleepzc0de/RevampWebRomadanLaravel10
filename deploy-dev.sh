#!/usr/bin/env bash
#
# Deploy CMS Romadan ke server DEVELOPMENT / STAGING (Ubuntu).
# Untuk PRODUCTION pakai deploy.sh — jangan tertukar.
#
# Pemakaian:
#   bash deploy-dev.sh [nama-branch] [opsi]
#   sudo bash deploy-dev.sh [nama-branch] [opsi]   (bila perlu mengubah kepemilikan berkas)
#
#   Tanpa nama-branch: memakai branch yang sedang aktif di server ini.
#
# Opsi:
#   --check          jalankan Pint + PHPStan + tes SEBELUM migrasi; gagal = berhenti
#   --seed           jalankan db:seed setelah migrasi (HANYA untuk database KOSONG;
#                    ditolak otomatis bila tabel users sudah berisi)
#   --skip-migrate   lewati migrasi database
#   --skip-assets    lewati npm ci + build Vite
#   --allow-production-env
#                    izinkan berjalan walau .env berisi APP_ENV=production (staging yang
#                    meniru production). Tanpa ini skrip MENOLAK — pengaman agar skrip dev
#                    tidak sengaja dijalankan di server production.
#   -h, --help       tampilkan bantuan ini
#
# Variabel lingkungan:  WEB_USER (default: www-data)
#
# Perbedaan dengan deploy.sh (production):
#   - TIDAK memakai mode pemeliharaan (pengguna dev boleh melihat error sesaat)
#   - composer install DENGAN paket dev (Pint, PHPStan, PHPUnit tetap tersedia)
#   - TIDAK membangun cache config/route/view: perubahan .env langsung berlaku
#   - TIDAK ada snapshot database sebelum migrasi
#   - perubahan lokal di git di-stash dan DISIMPAN (production membuangnya)
#   - git pull --ff-only: bila histori server menyimpang, berhenti — bukan membuat merge
#   - npm build otomatis dilewati bila tidak ada perubahan aset
#   - deploy:check hanya informasi (tidak menahan deploy)
#   - .env dibuat otomatis dari .env.example pada deploy pertama

set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
WEB_USER="${WEB_USER:-www-data}"
BRANCH=""
RUN_CHECKS=0
RUN_SEED=0
SKIP_MIGRATE=0
SKIP_ASSETS=0
ALLOW_PROD_ENV=0
STASHED=0
ENV_CREATED=0
STEP=""

C_RESET=$'\033[0m'; C_BOLD=$'\033[1m'; C_GREEN=$'\033[32m'; C_RED=$'\033[31m'
C_YELLOW=$'\033[33m'; C_CYAN=$'\033[36m'; C_GRAY=$'\033[90m'

step() { STEP="$1"; printf '\n%s==>%s %s%s%s\n' "$C_CYAN" "$C_RESET" "$C_BOLD" "$1" "$C_RESET"; }
info() { printf '    %s%s%s\n' "$C_GRAY" "$1" "$C_RESET"; }
ok()   { printf '    %s%s%s\n' "$C_GREEN" "$1" "$C_RESET"; }
warn() { printf '    %s%s%s\n' "$C_YELLOW" "$1" "$C_RESET"; }
die()  { printf '\n%s%sGAGAL:%s %s\n' "$C_RED" "$C_BOLD" "$C_RESET" "$1"; exit 1; }

usage() { awk 'NR>2 && /^#/ { sub(/^# ?/, ""); print; next } NR>2 { exit }' "${BASH_SOURCE[0]}"; }

for arg in "$@"; do
  case "$arg" in
    --check)        RUN_CHECKS=1 ;;
    --seed)         RUN_SEED=1 ;;
    --skip-migrate) SKIP_MIGRATE=1 ;;
    --skip-assets)  SKIP_ASSETS=1 ;;
    --allow-production-env) ALLOW_PROD_ENV=1 ;;
    -h|--help)      usage; exit 0 ;;
    -*)             die "opsi tidak dikenal: $arg (lihat: bash deploy-dev.sh --help)" ;;
    *)              [ -z "$BRANCH" ] || die "hanya boleh satu nama branch (dapat: '$BRANCH' dan '$arg')"
                    BRANCH="$arg" ;;
  esac
done

on_error() {
  printf '\n%s%s======================================================%s\n' "$C_RED" "$C_BOLD" "$C_RESET"
  printf '%s%s DEPLOY DEV DIHENTIKAN pada langkah: %s%s\n' "$C_RED" "$C_BOLD" "$STEP" "$C_RESET"
  printf '%s======================================================%s\n\n' "$C_RED" "$C_RESET"
  printf ' Tidak ada mode pemeliharaan di development — situs tetap melayani\n'
  printf ' kode/ dependensi yang sudah ter-update sampai langkah di atas.\n'
  printf ' Perbaiki penyebabnya, lalu jalankan ulang: %sbash deploy-dev.sh%s\n\n' "$C_BOLD" "$C_RESET"
  [ "$STASHED" -eq 1 ] && printf ' Perubahan lokal Anda aman di stash: %sgit stash list%s\n\n' "$C_BOLD" "$C_RESET"
  return 0
}
trap on_error ERR

cd "$APP_DIR"
[ -f artisan ] || die "direktori $APP_DIR bukan root aplikasi Laravel (file 'artisan' tidak ada)."

# Cegah dua deploy berjalan bersamaan di folder yang sama.
if command -v flock >/dev/null 2>&1; then
  LOCK_FILE="${TMPDIR:-/tmp}/romadan-deploy-dev-$(printf '%s' "$APP_DIR" | cksum | cut -d' ' -f1).lock"
  exec 9>"$LOCK_FILE"
  flock -n 9 || die "deploy lain sedang berjalan di $APP_DIR."
fi

IS_ROOT=0; [ "$(id -u)" -eq 0 ] && IS_ROOT=1

# Pengaman: dijalankan SEBELUM langkah apa pun yang mengubah server (git pull, composer,
# migrasi), supaya skrip dev yang salah dijalankan di PRODUCTION tidak sempat berbuat apa-apa.
if [ -f .env ] && grep -qE '^APP_ENV=production' .env && [ "$ALLOW_PROD_ENV" -ne 1 ]; then
  die "APP_ENV=production di .env — ini tampak seperti server PRODUCTION. Pakai deploy.sh. Bila ini memang staging yang meniru production, jalankan dengan --allow-production-env."
fi

# --------------------------------------------------------------------------
step "1/9  Memeriksa prasyarat"
REQUIRED_CMDS="php composer git"
[ "$SKIP_ASSETS" -eq 1 ] || REQUIRED_CMDS="$REQUIRED_CMDS node npm"
for cmd in $REQUIRED_CMDS; do
  command -v "$cmd" >/dev/null 2>&1 || die "'$cmd' tidak ditemukan di PATH."
done

PHP_VER="$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')"
php -r 'exit(version_compare(PHP_VERSION, "8.3.0", ">=") ? 0 : 1);' \
  || die "PHP $PHP_VER terlalu lama — aplikasi membutuhkan PHP 8.3 atau lebih baru."
info "PHP $PHP_VER, $(composer --version 2>/dev/null | head -n1 | cut -c1-40)"

if [ "$SKIP_ASSETS" -ne 1 ]; then
  NODE_MAJOR="$(node -p 'process.versions.node.split(".")[0]')"
  [ "$NODE_MAJOR" -ge 20 ] || die "Node.js v$(node -v) terlalu lama (butuh v20 ke atas)."
  info "Node.js $(node -v), npm $(npm -v)"
fi

# --------------------------------------------------------------------------
step "2/9  Mengambil source terbaru"
# Root menjalankan skrip pada repo milik user lain → git menolak "dubious ownership".
[ "$IS_ROOT" -eq 1 ] && { git config --global --add safe.directory "$APP_DIR" 2>/dev/null || true; }

if [ -z "$BRANCH" ]; then
  BRANCH="$(git rev-parse --abbrev-ref HEAD)"
  [ "$BRANCH" != "HEAD" ] || die "server sedang di detached HEAD — sebutkan branch: bash deploy-dev.sh nama-branch"
  info "branch tidak disebut → memakai branch aktif: $BRANCH"
fi

OLD_HEAD="$(git rev-parse HEAD)"

# Perubahan lokal yang BELUM di-commit disimpan di stash — TIDAK dibuang.
if ! git diff --quiet || ! git diff --cached --quiet; then
  git stash push -m "deploy-dev-$(date +%Y%m%d-%H%M%S)" >/dev/null
  STASHED=1
  warn "perubahan lokal disimpan di git stash (lihat: git stash list)"
fi

git fetch origin "$BRANCH"
git checkout "$BRANCH"
git pull --ff-only origin "$BRANCH" \
  || die "branch '$BRANCH' di server menyimpang dari origin (tidak bisa fast-forward). Periksa: git status / git log origin/$BRANCH..HEAD"

NEW_HEAD="$(git rev-parse HEAD)"
CHANGED_FILES=""
if [ "$OLD_HEAD" = "$NEW_HEAD" ]; then
  info "tidak ada commit baru (langkah berikutnya tetap dijalankan)"
else
  CHANGED_FILES="$(git diff --name-only "$OLD_HEAD" "$NEW_HEAD")"
  info "$(printf '%s\n' "$CHANGED_FILES" | wc -l | tr -d ' ') berkas berubah: ${OLD_HEAD:0:7} -> ${NEW_HEAD:0:7}"
fi
ok "commit sekarang: ${NEW_HEAD:0:7} — $(git log -1 --pretty=%s | cut -c1-60)"

# --------------------------------------------------------------------------
step "3/9  Menyiapkan .env"
if [ ! -f .env ]; then
  cp .env.example .env
  ENV_CREATED=1
  warn ".env belum ada → dibuat dari .env.example (APP_ENV=local, APP_DEBUG=true)"
else
  ok ".env sudah ada (tidak diubah)"
  if grep -qE '^APP_ENV=production' .env; then
    warn "APP_ENV=production di skrip DEVELOPMENT — dilanjutkan karena --allow-production-env"
  fi
fi

set_env() {
  local key="$1" val="$2"
  if grep -q "^${key}=" .env; then
    sed -i "s|^${key}=.*|${key}=${val}|" .env
  else
    printf '%s=%s\n' "$key" "$val" >> .env
  fi
}

# --------------------------------------------------------------------------
step "4/9  Memasang dependensi PHP (composer, termasuk paket dev)"
composer install --no-interaction --prefer-dist --no-progress
ok "vendor/ sinkron dengan composer.lock"

if ! grep -qE '^APP_KEY=.+' .env; then
  php artisan key:generate --force >/dev/null
  info "APP_KEY dibuat"
fi

if [ "$ENV_CREATED" -eq 1 ]; then
  # Pepper password: dibuat HANYA saat .env baru dibuat. Mengganti pepper pada DB
  # yang sudah berisi user membuat semua password tidak bisa dipakai.
  set_env PASSWORD_PEPPER "$(php -r 'echo base64_encode(bin2hex(random_bytes(48)));')"
  info "PASSWORD_PEPPER dibuat"
  printf '\n%s%s======================================================%s\n' "$C_YELLOW" "$C_BOLD" "$C_RESET"
  printf '%s%s .env BARU DIBUAT — deploy berhenti agar Anda mengisinya%s\n' "$C_YELLOW" "$C_BOLD" "$C_RESET"
  printf '%s======================================================%s\n\n' "$C_YELLOW" "$C_RESET"
  printf ' 1. Isi koneksi database di %s.env%s (DB_CONNECTION, DB_HOST, DB_PORT,\n' "$C_BOLD" "$C_RESET"
  printf '    DB_DATABASE, DB_USERNAME, DB_PASSWORD) dan APP_URL.\n'
  printf ' 2. Jalankan lagi: %sbash deploy-dev.sh %s --seed%s  (--seed hanya untuk DB kosong)\n\n' "$C_BOLD" "$BRANCH" "$C_RESET"
  trap - ERR
  exit 1
fi

# --------------------------------------------------------------------------
step "5/9  Aset tampilan (Vite)"
NEED_BUILD=1
if [ "$SKIP_ASSETS" -eq 1 ]; then
  NEED_BUILD=0
  info "dilewati (--skip-assets)"
elif [ -f public/build/manifest.json ] && [ "$OLD_HEAD" != "$NEW_HEAD" ] \
     && ! printf '%s\n' "$CHANGED_FILES" | grep -qE '^(resources/|package(-lock)?\.json$|vite\.config\.js$)'; then
  NEED_BUILD=0
  info "tidak ada perubahan aset sejak deploy terakhir — build dilewati"
elif [ -f public/build/manifest.json ] && [ "$OLD_HEAD" = "$NEW_HEAD" ]; then
  NEED_BUILD=0
  info "tidak ada commit baru dan build sudah ada — dilewati (hapus public/build untuk memaksa)"
fi

if [ "$NEED_BUILD" -eq 1 ]; then
  npm ci --no-audit --no-fund
  npm run build
  [ -f public/build/manifest.json ] || die "build selesai tapi public/build/manifest.json tidak terbentuk."
  ok "public/build/manifest.json terbentuk"
fi

# --------------------------------------------------------------------------
step "6/9  Pemeriksaan kualitas (opsional)"
if [ "$RUN_CHECKS" -eq 1 ]; then
  info "Pint…";    vendor/bin/pint --test
  info "PHPStan…"; php -d memory_limit=1G vendor/bin/phpstan analyse --no-progress
  info "Tes…";     php artisan test
  ok "Pint, PHPStan, dan tes lolos"
else
  info "dilewati (tambahkan --check untuk menjalankan Pint + PHPStan + tes)"
fi

# --------------------------------------------------------------------------
step "7/9  Database"
if [ "$SKIP_MIGRATE" -eq 1 ]; then
  info "migrasi dilewati (--skip-migrate)"
else
  php artisan migrate --force
  ok "skema database terbaru"
fi

if [ "$RUN_SEED" -eq 1 ]; then
  [ "$SKIP_MIGRATE" -eq 0 ] || die "--seed tidak bisa dipakai bersama --skip-migrate."
  USER_COUNT="$(php artisan tinker --execute='echo DB::table("users")->count();' 2>/dev/null | grep -E '^[0-9]+$' | tail -n1 || true)"
  [ -n "$USER_COUNT" ] || die "tidak bisa membaca tabel users — seed dibatalkan."
  [ "$USER_COUNT" -eq 0 ] || die "tabel users sudah berisi $USER_COUNT baris — --seed hanya untuk database KOSONG (seeder referensi akan menggandakan data)."
  php artisan db:seed --force
  warn "CATAT password admin yang tercetak di atas — hanya tampil sekali."
fi

# --------------------------------------------------------------------------
step "8/9  Symlink storage, cache, dan izin berkas"
[ -e public/storage ] || php artisan storage:link
# Sengaja TANPA config:cache/route:cache/view:cache: .env dan route berubah-ubah di dev.
php artisan optimize:clear >/dev/null
ok "cache dibersihkan (config/route/view tidak di-cache)"

if [ "$IS_ROOT" -eq 1 ]; then
  if id -u "$WEB_USER" >/dev/null 2>&1; then
    OWN_PATHS="storage bootstrap/cache"; [ -d public/build ] && OWN_PATHS="$OWN_PATHS public/build"
    # shellcheck disable=SC2086
    chown -R "$WEB_USER:$WEB_USER" $OWN_PATHS
    chmod -R 775 storage bootstrap/cache
    ok "storage, bootstrap/cache, public/build milik $WEB_USER"
  else
    warn "pengguna $WEB_USER tidak ada — kepemilikan berkas dilewati"
  fi
else
  chmod -R ug+rwX storage bootstrap/cache 2>/dev/null \
    || warn "tidak bisa mengubah izin storage/ (bukan root). Bila web server error 'permission denied', jalankan dengan sudo."
  info "bukan root — kepemilikan berkas tidak diubah"
fi

# --------------------------------------------------------------------------
step "9/9  Verifikasi (informasi saja)"
CHECK_OK=1
if php artisan deploy:check; then
  ok "deploy:check lolos"
else
  CHECK_OK=0
  warn "deploy:check melaporkan masalah di atas — di development tidak menahan deploy."
fi

if [ "$IS_ROOT" -eq 1 ] && command -v systemctl >/dev/null 2>&1; then
  systemctl reload "php${PHP_VER}-fpm" 2>/dev/null && info "php${PHP_VER}-fpm di-reload (opcache bersih)" || true
fi

trap - ERR
BANNER_COLOR="$C_GREEN"; BANNER_TEXT="DEPLOY DEVELOPMENT SELESAI"
if [ "$CHECK_OK" -eq 0 ]; then
  BANNER_COLOR="$C_YELLOW"; BANNER_TEXT="DEPLOY SELESAI, TAPI deploy:check MELAPORKAN MASALAH (lihat di atas)"
fi
printf '\n%s%s======================================================%s\n' "$BANNER_COLOR" "$C_BOLD" "$C_RESET"
printf '%s%s %s%s  %s(%s @ %s)%s\n' "$BANNER_COLOR" "$C_BOLD" "$BANNER_TEXT" "$C_RESET" "$C_GRAY" "$BRANCH" "${NEW_HEAD:0:7}" "$C_RESET"
printf '%s======================================================%s\n\n' "$BANNER_COLOR" "$C_RESET"
if [ "$STASHED" -eq 1 ]; then
  printf ' %sPerubahan lokal Anda ada di stash%s — kembalikan dengan: git stash pop\n\n' "$C_YELLOW" "$C_RESET"
fi
printf ' Verifikasi cepat: buka halaman depan, lalu login admin.\n\n'
