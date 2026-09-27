# Jurnalku

Aplikasi jurnal sederhana berbasis Laravel 11 dengan CRUD satu tabel (`posts`).

## Identitas

- **Nama:** Dzakiya Hakima Adila
- **NIM:** 24/542103/SV/25003
- **Mata kuliah:** Evolusi Perangkat Lunak

## Menjalankan aplikasi dan test secara lokal

Persyaratan: PHP 8.2+, Composer, dan ekstensi PHP SQLite.

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan test
php artisan serve
```

Test menggunakan SQLite `:memory:` sesuai `phpunit.xml`; file SQLite lokal dipakai untuk menjalankan aplikasi.

## Fitur dan test

- Membuat, membaca, memperbarui, dan menghapus catatan jurnal.
- Mengisi mood pada catatan dan melihat daftar catatan terbaru.
- `tests/Feature/PostTest.php` menguji daftar, penambahan, validasi, pembaruan, dan penghapusan catatan.

## CI/CD empat tahap

Workflow `.github/workflows/ci.yml` berjalan saat push ke `main`, `dev`, dan `feature/**`, serta saat pull request ke `main` atau `dev`.

| Job | Kegiatan |
| --- | --- |
| `build` | `composer install` dan pemeriksaan gaya kode dengan Pint. |
| `test` | Memasang dependensi di runner tersendiri, lalu `php artisan test`. |
| `staging` | Mencetak simulasi deployment staging. |
| `production` | Mencetak tujuh tahap dari `deploy.sh`, tanpa akses server. |

Keempat job berurutan dengan `needs:`. `production` hanya memenuhi syarat pada push ke `main`, lalu menunggu persetujuan reviewer pada environment GitHub bernama `production`. Required reviewer harus dikonfigurasi di **Settings → Environments → production**; deklarasi `environment:` di YAML saja belum mengaktifkan persetujuan.

`deploy.sh` adalah contoh urutan perintah **untuk server di masa depan** sesuai materi kuliah. Workflow tidak menjalankan skrip tersebut. Jalur `/var/www/aplikasi` perlu disesuaikan apabila kelak ada server.

## Alur kontribusi

1. Buat branch `feature/*` dari `main` atau branch integrasi yang disepakati.
2. Commit perubahan per tujuan, misalnya `ci: add four-stage Laravel pipeline`.
3. Jalankan `php artisan test` secara lokal lalu push branch fitur.
4. Periksa run Actions dan ajukan pull request untuk peninjauan.
5. Setelah perubahan masuk `main`, persetujuan environment diperlukan sebelum job production dimulai.
