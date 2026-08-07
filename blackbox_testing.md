# 📋 Dokumen Blackbox Testing — Sistem Rekomendasi Toko Fashion Secondhand (SPK ROC-SMART)

Dokumen ini berisi tabel skenario pengujian fungsionalitas (*Blackbox Testing*) untuk sistem pendukung keputusan (SPK) rekomendasi toko fashion secondhand berbasis metode **ROC (Rank Order Centroid) + SMART (Simple Multi-Attribute Rating Technique) + Haversine Formula (GPS)**.

---

## 📌 Petunjuk Pengisian Manual
1. **Hasil Pengujian**: Isi secara manual sesuai hasil percobaan Anda di browser (misal: *"Berhasil mengarahkan ke dashboard admin"*, *"Form ditolak dan menampilkan 403 Forbidden"*).
2. **Kesimpulan**: Isi secara manual dengan **Valid** (jika sesuai ekspektasi) atau **Tidak Valid** (jika terjadi error/kelainan).

---

## 🧪 Tabel Skenario Pengujian Blackbox

| No | Skenario Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Kesimpulan |
|---|---|---|---|---|
| **A** | **AUTENTIKASI & REGISTRASI** | | | |
| 1 | Registrasi Publik Akun User | Pengguna mendaftar di `/register` dengan memilih role "Pengguna". Akun tersimpan dan pengguna langsung diarahkan ke halaman pengaturan preferensi (`/user/preferensi`). | | |
| 2 | Registrasi Publik Akun Pemilik Toko | Pemilik toko mendaftar di `/register` dengan memilih role "Pemilik Toko". Akun tersimpan dan pengguna langsung diarahkan ke Dashboard Pemilik Toko (`/pemilik/dashboard`). | | |
| 3 | Verifikasi Tidak Ada Option Admin di Registrasi Publik | Pada form registrasi publik `/register`, **TIDAK ADA** pilihan radio button role "Admin". Jika dikirim paksa via form manipulation/API, backend menolak registrasi. | | |
| 4 | Login Role Admin (`admin@thriftjember.com` / `Adm!n@Th3Jmb#2026`) | Admin memasukkan email & password yang benar. Sistem mengautentikasi dan mengarahkan langsung ke Dashboard Admin (`/admin/dashboard`). | | |
| 5 | Login Role User (`user@gmail.com` / `password`) | User memasukkan email & password yang benar. Sistem mengautentikasi dan mengarahkan ke halaman pengaturan preferensi (`/user/preferensi`). | | |
| 6 | Login Role Pemilik Toko (`pemilik@toko.com` / `password`) | Pemilik toko memasukkan email & password yang benar. Sistem mengautentikasi dan mengarahkan ke Dashboard Pemilik Toko (`/pemilik/dashboard`). | | |
| 7 | Login dengan Password / Email Salah | Pengguna memasukkan kredensial salah. Sistem menolak login dan menampilkan pesan peringatan error. | | |
| 8 | Logout Pengguna | Pengguna menekan tombol Logout. Session dihapus dan sistem mengembalikan ke halaman utama (`/`). | | |
| **B** | **MIDDLEWARE & PROTEKSI HAK AKSES ROLE** | | | |
| 9 | Pembatasan Akses Guest (Belum Login) | Guest mencoba buka `/admin/dashboard`, `/pemilik/dashboard`, atau `/user/preferensi`. Sistem menolak dan mengarahkan ke `/login`. | | |
| 10 | Pembatasan Akses User ke Dashboard Admin & Pemilik | User biasa buka URL `/admin/dashboard` atau `/pemilik/dashboard`. Sistem menolak dengan response `403 Forbidden`. | | |
| 11 | Pembatasan Akses Pemilik Toko ke Dashboard Admin | Pemilik toko buka URL `/admin/dashboard` atau `/admin/nilai-alternatif`. Sistem menolak dengan response `403 Forbidden`. | | |
| 12 | Pembatasan Akses Admin ke Dashboard Pemilik & User | Admin buka URL `/pemilik/dashboard` atau `/user/preferensi`. Sistem menolak dengan response `403 Forbidden`. | | |
| **C** | **KELOLA DATA TOKO (ADMIN)** | | | |
| 13 | Tambah Toko Baru oleh Admin | Admin mengisi form tambah toko di `/admin/toko/create` (termasuk memilih pemilik terdaftar). Toko baru berhasil tersimpan ke database. | | |
| 14 | Edit & Update Data Toko oleh Admin | Admin mengubah informasi toko di `/admin/toko/{id}/edit`. Data toko di database berhasil diperbarui. | | |
| 15 | Hapus Data Toko oleh Admin | Admin menekan tombol Hapus toko. Toko beserta nilai kriteria terkait terhapus dari database. | | |
| **D** | **KELOLA KRITERIA & NILAI ALTERNATIF (ADMIN)** | | | |
| 16 | Tambah Kriteria Baru oleh Admin | Admin membuat kriteria baru (nama & jenis benefit/cost) di `/admin/kriteria/create`. Kriteria tersimpan di DB. | | |
| 17 | Edit & Update Kriteria oleh Admin | Admin mengubah nama atau jenis kriteria (benefit/cost) di `/admin/kriteria/{id}/edit`. Kriteria berhasil diperbarui. | | |
| 18 | Hapus Kriteria oleh Admin | Admin menghapus kriteria di `/admin/kriteria`. Kriteria terhapus dari database. | | |
| 19 | Input/Edit Nilai Alternatif Matriks (Admin) | Admin menginput nilai skala 1–100 untuk tiap kriteria toko di `/admin/nilai-alternatif/{tokoId}/edit`. Data tersimpan di DB. | | |
| 20 | Panel "Toko Belum Dinilai" di Dashboard Admin | Dashboard admin menampilkan daftar toko baru yang belum memiliki nilai kriteria, lengkap dengan tombol cepat "Nilai Sekarang". | | |
| 21 | Form Inline "Isi Nilai Kriteria" di Detail Toko (Admin) | Di halaman detail toko `/toko/{id}`, tombol modal **"Isi Nilai Kriteria"** HANYA muncul untuk admin. Admin dapat mengisi/mengedit nilai 1–100 langsung dari halaman detail. | | |
| 22 | Proteksi Backend Form Nilai Alternatif dari Pemilik Toko | Pemilik toko mencoba mengirim POST/PUT ke URL nilai alternatif secara langsung. Backend menolak dengan response `403 Forbidden`. | | |
| **E** | **KELOLA TOKO SENDIRI (PEMILIK TOKO)** | | | |
| 23 | Pendaftaran Toko Baru oleh Pemilik Toko | Pemilik toko menambah toko di `/pemilik/toko/create`. Toko otomatis terikat ke akun pemilik yang sedang login (`pemilik_id`). | | |
| 24 | Edit Toko Sendiri oleh Pemilik Toko | Pemilik toko mengedit profil tokonya sendiri di `/pemilik/toko/{id}/edit`. Data berhasil diperbarui. | | |
| 25 | Proteksi Toko Pemilik Lain | Pemilik Toko A mencoba membuka URL edit/hapus toko milik Pemilik Toko B (`/pemilik/toko/{id_toko_B}/edit`). Sistem menolak dengan response `403 Forbidden`. | | |
| 26 | Card Status Penilaian Admin di Dashboard Pemilik | Dashboard pemilik toko menampilkan status badge ("Menunggu Penilaian", "Penilaian Sebagian", "Sudah Dinilai Admin") **tanpa** menampilkan angka skor detail kriteria. | | |
| **F** | **FITUR GPS GEOLOCATION & MAP PREVIEW** | | | |
| 27 | Deteksi Lokasi GPS di Form Toko (`pemilik_toko` & `admin`) | Menekan tombol **"Deteksi Lokasi Saya"** pada form toko mengaktifkan GPS browser, memperbarui input latitude/longitude, serta menampilkan status sukses (hijau) atau error (merah). | | |
| 28 | Preview Peta Leaflet Interaktif di Form Toko | Mengubah input lat/lng secara manual atau mengklik/menggeser marker di preview peta Leaflet memperbarui koordinat secara 2-arah. | | |
| 29 | Deteksi Lokasi GPS di Halaman Preferensi User | User menekan tombol **"Deteksi GPS Otomatis"** di `/user/preferensi`. Koordinat GPS user berhasil dideteksi untuk perhitungan jarak Haversine. | | |
| **G** | **PREFERENSI USER & PERHITUNGAN ROC-SMART** | | | |
| 30 | Drag-and-Drop Prioritas Kriteria (User) | User menggeser urutan kriteria di `/user/preferensi`. Urutan nomor di kiri menyesuaikan dan tombol panah naik/turun berfungsi. | | |
| 31 | Real-time Preview Bobot ROC | Saat urutan kriteria digeser, baris Preview Bobot ROC di sebelah kanan memperbarui persentase bobot ROC secara *real-time*. | | |
| 32 | Perhitungan Nilai Akhir SMART & Ranking Toko | Sistem mengalihkan nilai toko ke utilitas SMART (0–1), mengalikan dengan bobot ROC & faktor jarak Haversine, lalu mengurutkan toko dari skor tertinggi ke terendah. | | |
| 33 | Personalisasi Hasil Rekomendasi per User | Dua user yang memasukkan urutan preferensi kriteria berbeda menerima daftar rekomendasi toko dengan urutan ranking yang berbeda sesuai preferensinya. | | |
| **H** | **PETA LEAFLET / OPENSTREETMAP** | | | |
| 34 | Peta Leaflet di Detail Toko (`/toko/{id}`) | Halaman detail toko menampilkan peta Leaflet + tile OpenStreetMap dengan marker lokasi toko dan popup peta yang valid (tanpa dependency Google Maps API). | | |
| 35 | Peta Leaflet di Hasil Rekomendasi (`/user/hasil`) | Halaman hasil rekomendasi menampilkan peta Leaflet yang menandai posisi user (marker biru) dan titik lokasi seluruh toko hasil rekomendasi. | | |

---

## 📊 Ringkasan Pengujian Otomatis Internal (Automated Test Suite)
- **Total Automated Tests**: 30 Passed (112 Assertions)
- **Status Database & Migrasi**: Seed & Migration 100% Passed
- **Engine SPK**: ROC Weight Calculation + SMART Utility + Haversine Formula 100% Passed

Dokumen ini siap dicetak / diisi manual untuk keperluan pengujian Blackbox Sistem.
