#!/usr/bin/env bash
#
# Persiapan server production CMS Romadan — CUKUP DIJALANKAN SEKALI.
# Semua otomatis, tidak ada yang perlu diedit manual.
#
# Pemakaian:  sudo bash setup-server.sh
#
# Yang dikerjakan:
#   1. Memasang Node.js 22 (dari NodeSource) bila belum ada / terlalu lama
#   2. Menyetel logging hemat resource (hanya error, rotasi 7 hari)
#   3. Membuat symlink public/storage
#   4. Memasang cron scheduler Laravel (publikasi terjadwal, backup, media sync)
#   5. Merapikan kepemilikan berkas

set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
WEB_USER="www-data"
NODE_MAJOR_MIN=20

C_RESET=$'\033[0m'; C_BOLD=$'\033[1m'; C_GREEN=$'\033[32m'; C_RED=$'\033[31m'
C_CYAN=$'\033[36m'; C_GRAY=$'\033[90m'

step() { printf '\n%s==>%s %s%s%s\n' "$C_CYAN" "$C_RESET" "$C_BOLD" "$1" "$C_RESET"; }
info() { printf '    %s%s%s\n' "$C_GRAY" "$1" "$C_RESET"; }
ok()   { printf '    %s%s%s\n' "$C_GREEN" "$1" "$C_RESET"; }
die()  { printf '\n%s%sGAGAL:%s %s\n\n' "$C_RED" "$C_BOLD" "$C_RESET" "$1"; exit 1; }

cd "$APP_DIR"
[ -f artisan ] || die "direktori $APP_DIR bukan root aplikasi Laravel."
[ "$(id -u)" -eq 0 ] || die "jalankan dengan sudo: sudo bash setup-server.sh"

printf '\n%s%sPersiapan Server CMS Romadan%s %s(%s)%s\n' "$C_BOLD" "$C_CYAN" "$C_RESET" "$C_GRAY" "$APP_DIR" "$C_RESET"

# --------------------------------------------------------------------------
step "1/5  Node.js (wajib untuk membangun tampilan)"
NEED_NODE=1
if command -v node >/dev/null 2>&1 && command -v npm >/dev/null 2>&1; then
  CUR_MAJOR="$(node -p 'process.versions.node.split(".")[0]')"
  if [ "$CUR_MAJOR" -ge "$NODE_MAJOR_MIN" ]; then
    NEED_NODE=0
    ok "sudah terpasang: node $(node -v), npm $(npm -v)"
  else
    info "node $(node -v) terlalu lama (butuh v${NODE_MAJOR_MIN}+), akan diperbarui"
  fi
else
  info "node/npm belum terpasang"
fi

if [ "$NEED_NODE" -eq 1 ]; then
  info "memasang Node.js 22 dari NodeSource…"
  curl -fsSL https://deb.nodesource.com/setup_22.x | bash -
  apt-get install -y nodejs
  command -v npm >/dev/null 2>&1 || die "npm tetap tidak tersedia setelah pemasangan."
  NEW_MAJOR="$(node -p 'process.versions.node.split(".")[0]')"
  [ "$NEW_MAJOR" -ge "$NODE_MAJOR_MIN" ] || die "versi node masih $(node -v) setelah pemasangan."
  ok "terpasang: node $(node -v), npm $(npm -v)"
fi

# --------------------------------------------------------------------------
step "2/5  Logging hemat resource"
set_env() {
  local key="$1" val="$2"
  if grep -q "^${key}=" .env; then
    sed -i "s|^${key}=.*|${key}=${val}|" .env
  else
    printf '%s=%s\n' "$key" "$val" >> .env
  fi
}

[ -f .env ] || die "berkas .env tidak ditemukan."

# Catatan penting (terukur): LOG_CHANNEL=null JUSTRU memboroskan resource.
# Laravel membaca 'null' sebagai nilai kosong sehingga channel jadi tidak
# valid; setiap penulisan log memicu exception, lalu ditulis lewat emergency
# logger LENGKAP DENGAN stack trace (~1.4 KB per baris) — bukan dibuang.
# Setelan di bawah hanya mencatat level error, dirotasi harian, simpan 7 hari:
# saat situs sehat nyaris tidak ada penulisan sama sekali.
set_env LOG_CHANNEL daily
set_env LOG_LEVEL error
set_env LOG_DAILY_DAYS 7
ok "LOG_CHANNEL=daily, LOG_LEVEL=error, LOG_DAILY_DAYS=7"

# --------------------------------------------------------------------------
step "3/5  Symlink storage"
if [ -e public/storage ]; then
  ok "public/storage sudah ada"
else
  php artisan storage:link
  ok "public/storage dibuat"
fi

# --------------------------------------------------------------------------
step "4/5  Cron scheduler Laravel"
CRON_LINE="* * * * * cd ${APP_DIR} && php artisan schedule:run >> /dev/null 2>&1"
if crontab -u "$WEB_USER" -l 2>/dev/null | grep -Fq "artisan schedule:run"; then
  info "entri lama ditemukan, akan diganti agar path selalu benar"
fi
{ crontab -u "$WEB_USER" -l 2>/dev/null | grep -Fv "artisan schedule:run" || true; echo "$CRON_LINE"; } \
  | crontab -u "$WEB_USER" -
ok "terpasang: publikasi terjadwal, backup harian, sinkronisasi media"

# --------------------------------------------------------------------------
step "5/5  Kepemilikan berkas"
chown -R "$WEB_USER:$WEB_USER" storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
[ -d public/build ] && chown -R "$WEB_USER:$WEB_USER" public/build
ok "storage & bootstrap/cache milik $WEB_USER"

printf '\n%s%s======================================================%s\n' "$C_GREEN" "$C_BOLD" "$C_RESET"
printf '%s%s PERSIAPAN SELESAI%s\n' "$C_GREEN" "$C_BOLD" "$C_RESET"
printf '%s======================================================%s\n\n' "$C_GREEN" "$C_RESET"
printf ' Lanjutkan dengan deploy:\n'
printf '   %ssudo bash deploy.sh%s\n\n' "$C_BOLD" "$C_RESET"
