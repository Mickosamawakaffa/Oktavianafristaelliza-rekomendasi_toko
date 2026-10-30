# ThriftJember — Sistem Pendukung Keputusan Rekomendasi Toko Thrift

Aplikasi web Laravel untuk merekomendasikan toko thrift terbaik di Jember menggunakan metode **ROC-SMART** (Rank Order Centroid — Simple Multi-Attribute Rating Technique).

## Instalasi

```bash
# 1. Clone & install dependencies
composer install
npm install

# 2. Copy dan konfigurasi environment
cp .env.example .env
php artisan key:generate

# 3. Migrasi database
php artisan migrate

# 4. (Opsional) Seed data demo
php artisan db:seed

# 5. Jalankan development server
composer dev
# atau
php artisan serve
```

## Konfigurasi Email (untuk Reset Password)

Agar fitur "Lupa Password" berfungsi, atur variabel berikut di `.env`:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com       # Sesuaikan provider
MAIL_PORT=587
MAIL_USERNAME=email@kamu.com
MAIL_PASSWORD=app-password-kamu
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@thriftjember.com
MAIL_FROM_NAME="ThriftJember"
```

Untuk development lokal, gunakan `MAIL_MAILER=log` — email akan tercatat di `storage/logs/laravel.log`.

## Akun Demo (Development)

Setelah `php artisan db:seed`, tersedia akun berikut:

| Role     | Email              | Password |
|----------|--------------------|----------|
| Admin    | admin@gmail.com    | password |
| User     | user@gmail.com     | password |
| Pemilik  | pemilik@toko.com   | password |

> **Catatan:** Akun demo hanya ditampilkan di halaman login pada environment `local`.

## Roles & Redirect

| Role          | Dashboard setelah login       |
|---------------|-------------------------------|
| Admin         | `/admin/dashboard`            |
| Pemilik Toko  | `/pemilik/dashboard`          |
| User          | `/` (daftar toko/rekomendasi) |

## Lisensi

[MIT License](https://opensource.org/licenses/MIT)
