#!/usr/bin/env bash
set -e

# Rujukan urutan deployment dari slide 6. Workflow CI hanya meng-echo perintah ini.
# Ganti direktori aplikasi ketika deployment ke server benar-benar disiapkan.
cd /var/www/aplikasi

# 1. Kunci pintu - tampilkan halaman pemeliharaan.
php artisan down --retry=60

# 2. Ambil kode terbaru.
git pull origin main

# 3. Pasang dependensi produksi.
composer install --no-dev --optimize-autoloader

# 4. Ubah skema basis data.
php artisan migrate --force

# 5. Bangun ulang cache dengan kode dan konfigurasi baru.
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Muat ulang pekerja antrean.
php artisan queue:restart

# 7. Buka pintu kembali.
php artisan up
