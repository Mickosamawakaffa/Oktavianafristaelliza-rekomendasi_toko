# Documentasi Blackbox Testing - Aplikasi Rekomendasi Toko Fashion Secondhand (ROC-SMART)

Dokumen ini berisi daftar skenario pengujian fungsionalitas (*Blackbox Testing*) untuk sistem pendukung keputusan (SPK) rekomendasi toko secondhand menggunakan metode **ROC + SMART + Haversine Distance (GPS)**.

---

## 📋 Petunjuk Pengisian Manual
1. Kolom **Hasil Pengujian**: Isi sesuai dengan apa yang terjadi saat Anda mencoba fitur di browser (misal: *"Form login berhasil mengarahkan ke dashboard admin"*, *"Peta Google Maps berhasil menampilkan marker toko"*).
2. Kolom **Kesimpulan**: Isi dengan **Valid** (jika fungsi sesuai ekspektasi) atau **Tidak Valid** (jika terjadi error/kelainan).

---

## 🧪 Tabel Skenario Pengujian Blackbox

| No | Skenario Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Kesimpulan |
|---|---|---|---|---|
| 1 | Registrasi Akun Baru sebagai User | User memasukkan Nama, Email, Password, Konfirmasi Password, dan memilih Role "Pencari Toko (User)". Sistem menyimpan akun ke database dan mengarahkan ke halaman pengaturan preferensi. | | |
| 2 | Registrasi Akun Baru sebagai Pemilik Toko | Pemilik toko memasukkan data registrasi dan memilih Role "Pemilik Toko". Sistem menyimpan akun dan mengarahkan langsung ke Dashboard Pemilik Toko. | | |
| 3 | Login Akun Role User (`user@gmail.com`) | User memasukkan kredensial yang benar. Sistem memvalidasi login, menyimpan session, dan mengarahkan user ke halaman input preferensi (`/user/preferensi`). | | |
| 4 | Login Akun Role Admin (`admin@gmail.com`) | Admin memasukkan kredensial yang benar. Sistem memvalidasi login dan mengarahkan ke Dashboard Admin (`/admin/dashboard`). | | |
| 5 | Login Akun Role Pemilik Toko (`pemilik@toko.com`) | Pemilik toko memasukkan kredensial yang benar. Sistem memvalidasi login dan mengarahkan ke Dashboard Pemilik (`/pemilik/dashboard`). | | |
| 6 | Login dengan Kredensial Salah | User/Admin/Pemilik memasukkan email atau password yang salah. Sistem menolak autentikasi dan menampilkan pesan error peringatan. | | |
| 7 | Logout Pengguna | Pengguna menekan tombol Logout. Session dihapus dan sistem mengarahkan pengguna kembali ke halaman utama (`/`). | | |
| 8 | Pembatasan Akses Guest ke Halaman Protected | Pengguna yang belum login mencoba mengakses halaman `/user/preferensi`, `/admin/dashboard`, atau `/pemilik/dashboard`. Sistem menolak akses dan mengarahkan ke halaman `/login`. | | |
| 9 | Pembatasan Akses Role User ke Halaman Admin & Pemilik | User yang login sebagai `user` mencoba membuka URL `/admin/dashboard` atau `/pemilik/dashboard`. Sistem mengembalikan response `403 Forbidden` (Akses Ditolak). | | |
| 10 | Pembatasan Akses Role Pemilik ke Halaman Admin & User | Pemilik toko yang login mencoba membuka URL `/admin/dashboard` atau `/user/preferensi`. Sistem mengembalikan response `403 Forbidden`. | | |
| 11 | Pembatasan Akses Role Admin ke Halaman User & Pemilik | Admin yang login mencoba membuka URL `/user/preferensi` atau `/pemilik/dashboard`. Sistem mengembalikan response `403 Forbidden`. | | |
| 12 | Tambah Toko Baru oleh Admin | Admin mengisi form Tambah Toko di `/admin/toko/create` (nama, alamat, deskripsi, medos, jam operasional, lat, lng, dan memilih pemilik toko). Data tersimpan di DB. | | |
| 13 | Edit & Update Data Toko oleh Admin | Admin mengubah informasi toko di `/admin/toko/{id}/edit`. Data toko diperbarui di database dan menampilkan notifikasi sukses. | | |
| 14 | Hapus Data Toko oleh Admin | Admin menekan tombol Hapus pada salah satu toko di tabel admin. Toko beserta nilai alternatif terkait terhapus dari database. | | |
| 15 | Tambah Kriteria Baru oleh Admin | Admin membuat kriteria baru (misal: "Diskon", jenis "benefit") di `/admin/kriteria/create`. Kriteria tersimpan di DB dan muncul di daftar. | | |
| 16 | Edit & Update Kriteria oleh Admin | Admin mengubah nama atau jenis kriteria (benefit/cost) di `/admin/kriteria/{id}/edit`. Data kriteria ter-update di DB. | | |
| 17 | Hapus Kriteria oleh Admin | Admin menghapus kriteria. Kriteria terhapus dan nilai alternatif kriteria tersebut disesuaikan di database. | | |
| 18 | Update Matriks Nilai Alternatif Toko oleh Admin | Admin menginput/mengubah nilai skala 0-100 untuk setiap kriteria pada toko tertentu di `/admin/nilai-alternatif/{tokoId}/edit`. Nilai tersimpan di DB. | | |
| 19 | Validasi Input Form Admin (Nilai Out of Bounds / Kosong) | Admin mengosongkan nama toko atau menginput nilai kriteria > 100. Sistem menolak dan menampilkan pesan error validasi input. | | |
| 20 | Tambah Toko Milik Sendiri oleh Pemilik Toko | Pemilik toko menambah toko baru di `/pemilik/toko/create`. Toko otomatis terikat ke ID pemilik toko yang sedang login (`pemilik_id`). | | |
| 21 | Edit & Update Toko Milik Sendiri oleh Pemilik Toko | Pemilik toko mengedit informasi tokonya sendiri di `/pemilik/toko/{id}/edit`. Data ter-update dengan sukses. | | |
| 22 | Proteksi Akses Edit Toko Pemilik Lain | Pemilik Toko B mencoba mengedit atau menghapus toko milik Pemilik Toko A secara manual via URL. Sistem menolak dengan response `403 Forbidden`. | | |
| 23 | Hapus Toko Milik Sendiri oleh Pemilik Toko | Pemilik toko menghapus toko miliknya di dashboard pemilik. Toko terhapus dari database. | | |
| 24 | Input Urutan Prioritas Kriteria (Drag & Drop / Select) | User mengatur urutan prioritas kriteria dari yang paling penting ke kurang penting di `/user/preferensi`. Sistem menerima urutan kriteria. | | |
| 25 | Perhitungan Bobot ROC (Rank Order Centroid) | Sistem menghitung bobot tiap kriteria berdasarkan rumus ROC ($W_k = \frac{1}{m} \sum_{i=k}^m \frac{1}{i}$). Total akumulasi seluruh bobot sama dengan $1.0$ ($100\%$). | | |
| 26 | Perhitungan Jarak GPS (Haversine Formula) | Sistem menghitung jarak fisik kelengkungan bumi (KM) antara koordinat GPS User (`user_lat`, `user_lng`) dan koordinat toko menggunakan Haversine Formula. | | |
| 27 | Perhitungan Nilai Utility SMART & Ranking Toko | Sistem mengalihkan nilai kriteria ke rentang 0–1 menggunakan rumus utility SMART (Benefit & Cost), mengalikan dengan bobot ROC, dan meranking toko dari skor terbesar ke terkecil. | | |
| 28 | Tampilan Halaman Hasil Rekomendasi (`/user/hasil`) | Halaman menampilkan ringkasan bobot ROC user, kartu toko terurut sesuai ranking (skor SMART, jarak KM), serta rincian nilai utility per kriteria. | | |
| 29 | Integrasi Google Maps API & Marker Lokasi Toko | Halaman rekomendasi (`/user/hasil`) dan detail toko (`/toko/{id}`) memuat peta interaktif. Marker biru menandai posisi user dan marker bernomor menandai posisi toko rekomendasi. | | |

---

## 📌 Ringkasan Hasil Pengecekan Sistem (Automated Pre-Verification)
- **Total Skenario Diuji**: 29 Skenario
- **Hasil Pengecekan Otomatis (PHPUnit/Pest)**: **29 PASSED (104 assertions)**
- **Status Database & Migrasi**: Migration & Seeder Ran 100% Valid
- **Hasil Pengecekan Integrasi Maps**: Script Google Maps / Leaflet fallback terintegrasi dengan valid di view `user.hasil` dan `toko.show`.

Dokumen ini siap digunakan untuk pengisian pengujian manual (*Blackbox Testing*).
