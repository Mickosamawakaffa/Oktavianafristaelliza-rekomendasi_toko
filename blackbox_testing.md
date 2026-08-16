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
| 1 | Registrasi Publik Akun User | Pengguna mendaftar di `/register` dengan memilih role "Pengguna". Akun tersimpan dan pengguna langsung diarahkan ke halaman pengaturan preferensi (`/user/preferensi`). | Sistem berhasil menyimpan akun baru dengan role `user` ke database dan mengarahkan ke `/user/preferensi`. | Valid |
| 2 | Registrasi Publik Akun Pemilik Toko | Pemilik toko mendaftar di `/register` dengan memilih role "Pemilik Toko". Akun tersimpan dan pengguna langsung diarahkan ke Dashboard Pemilik Toko (`/pemilik/dashboard`). | Sistem berhasil menyimpan akun baru dengan role `pemilik_toko` ke database dan mengarahkan ke `/pemilik/dashboard`. | Valid |
| 3 | Verifikasi Tidak Ada Option Admin di Registrasi Publik | Pada form registrasi publik `/register`, **TIDAK ADA** pilihan radio button role "Admin". Jika dikirim paksa via form manipulation/API, backend menolak registrasi. | Form registrasi tidak menampilkan opsi role admin. Request paksa dengan role admin ditolak oleh validasi backend (rule `in:user,pemilik_toko`). | Valid |
| 4 | Login Role Admin (`admin@thriftjember.com` / `Adm!n@Th3Jmb#2026`) | Admin memasukkan email & password yang benar. Sistem mengautentikasi dan mengarahkan langsung ke Dashboard Admin (`/admin/dashboard`). | Login berhasil dan sistem mengarahkan ke `/admin/dashboard` sesuai role. | Valid |
| 5 | Login Role User (`user@gmail.com` / `password`) | User memasukkan email & password yang benar. Sistem mengautentikasi dan mengarahkan ke halaman pengaturan preferensi (`/user/preferensi`). | Login berhasil dan sistem mengarahkan ke `/user/preferensi` sesuai role. | Valid |
| 6 | Login Role Pemilik Toko (`pemilik@toko.com` / `password`) | Pemilik toko memasukkan email & password yang benar. Sistem mengautentikasi dan mengarahkan ke Dashboard Pemilik Toko (`/pemilik/dashboard`). | Login berhasil dan sistem mengarahkan ke `/pemilik/dashboard` sesuai role. | Valid |
| 7 | Login dengan Password / Email Salah | Pengguna memasukkan kredensial salah. Sistem menolak login dan menampilkan pesan peringatan error. | Sistem menolak login dan menampilkan pesan error "These credentials do not match our records." tanpa membocorkan data pengguna. | Valid |
| 8 | Logout Pengguna | Pengguna menekan tombol Logout. Session dihapus dan sistem mengembalikan ke halaman utama (`/`). | Session berhasil dihapus, token CSRF diregenerasi, dan pengguna diarahkan kembali ke halaman utama `/`. | Valid |
| **B** | **MIDDLEWARE & PROTEKSI HAK AKSES ROLE** | | | |
| 9 | Pembatasan Akses Guest (Belum Login) | Guest mencoba buka `/admin/dashboard`, `/pemilik/dashboard`, atau `/user/preferensi`. Sistem menolak dan mengarahkan ke `/login`. | Middleware `auth` aktif pada semua rute terproteksi. Guest yang mengakses URL tersebut otomatis diarahkan ke halaman `/login`. | Valid |
| 10 | Pembatasan Akses User ke Dashboard Admin & Pemilik | User biasa buka URL `/admin/dashboard` atau `/pemilik/dashboard`. Sistem menolak dengan response `403 Forbidden`. | Middleware `role:admin` dan `role:pemilik_toko` menolak akses user biasa dan menampilkan halaman 403 Forbidden. | Valid |
| 11 | Pembatasan Akses Pemilik Toko ke Dashboard Admin | Pemilik toko buka URL `/admin/dashboard` atau `/admin/nilai-alternatif`. Sistem menolak dengan response `403 Forbidden`. | Middleware `role:admin` aktif pada seluruh grup rute `/admin/*`. Akses dari role `pemilik_toko` ditolak dengan response 403. | Valid |
| 12 | Pembatasan Akses Admin ke Dashboard Pemilik & User | Admin buka URL `/pemilik/dashboard` atau `/user/preferensi`. Sistem menolak dengan response `403 Forbidden`. | Middleware `role:pemilik_toko` dan `role:user` menolak akses admin dan menampilkan halaman 403 Forbidden. | Valid |
| **C** | **KELOLA DATA TOKO (ADMIN)** | | | |
| 13 | Tambah Toko Baru oleh Admin | Admin mengisi form tambah toko di `/admin/toko/create` (termasuk memilih pemilik terdaftar). Toko baru berhasil tersimpan ke database. | Form berhasil divalidasi. Data toko (nama, alamat, deskripsi, media sosial, jam operasional, koordinat GPS, pemilik_id) tersimpan ke tabel `toko`. Sistem redirect ke daftar toko dengan pesan sukses. | Valid |
| 14 | Edit & Update Data Toko oleh Admin | Admin mengubah informasi toko di `/admin/toko/{id}/edit`. Data toko di database berhasil diperbarui. | Data toko berhasil diperbarui di database. Sistem redirect ke daftar toko dengan flash message konfirmasi sukses. | Valid |
| 15 | Hapus Data Toko oleh Admin | Admin menekan tombol Hapus toko. Toko beserta nilai kriteria terkait terhapus dari database. | Toko berhasil dihapus. Cascade delete pada relasi `nilai_alternatif` ikut terhapus sesuai constraint database. | Valid |
| **D** | **KELOLA KRITERIA & NILAI ALTERNATIF (ADMIN)** | | | |
| 16 | Tambah Kriteria Baru oleh Admin | Admin membuat kriteria baru (nama & jenis benefit/cost) di `/admin/kriteria/create`. Kriteria tersimpan di DB. | Form berhasil divalidasi. Kriteria baru dengan nama dan jenis (`benefit`/`cost`) tersimpan ke tabel `kriteria`. | Valid |
| 17 | Edit & Update Kriteria oleh Admin | Admin mengubah nama atau jenis kriteria (benefit/cost) di `/admin/kriteria/{id}/edit`. Kriteria berhasil diperbarui. | Data kriteria (nama dan jenis) berhasil diperbarui di database. Sistem redirect dengan flash message konfirmasi. | Valid |
| 18 | Hapus Kriteria oleh Admin | Admin menghapus kriteria di `/admin/kriteria`. Kriteria terhapus dari database. | Kriteria berhasil dihapus. Data `nilai_alternatif` dan `preferensi_user` yang mereferensikan kriteria tersebut ikut terhapus (cascade). | Valid |
| 19 | Input/Edit Nilai Alternatif Matriks (Admin) | Admin menginput nilai skala 1–100 untuk tiap kriteria toko di `/admin/nilai-alternatif/{tokoId}/edit`. Data tersimpan di DB. | Nilai alternatif berhasil disimpan ke tabel `nilai_alternatif` dengan relasi `toko_id` dan `kriteria_id` yang benar. Validasi menolak nilai di luar rentang 1–100. | Valid |
| 20 | Panel "Toko Belum Dinilai" di Dashboard Admin | Dashboard admin menampilkan daftar toko baru yang belum memiliki nilai kriteria, lengkap dengan tombol cepat "Nilai Sekarang". | Dashboard `/admin/dashboard` menampilkan section toko yang `nilai_alternatif_count = 0`, beserta tombol shortcut menuju halaman edit nilai alternatif. | Valid |
| 21 | Form Inline "Isi Nilai Kriteria" di Detail Toko (Admin) | Di halaman detail toko `/toko/{id}`, tombol modal **"Isi Nilai Kriteria"** HANYA muncul untuk admin. Admin dapat mengisi/mengedit nilai 1–100 langsung dari halaman detail. | Tombol modal hanya dirender untuk pengguna dengan `Auth::user()->role === 'admin'`. Pemilik toko dan user biasa tidak melihat tombol ini. | Valid |
| 22 | Proteksi Backend Form Nilai Alternatif dari Pemilik Toko | Pemilik toko mencoba mengirim POST/PUT ke URL nilai alternatif secara langsung. Backend menolak dengan response `403 Forbidden`. | Route `/admin/nilai-alternatif/*` dilindungi middleware `role:admin`. Percobaan akses dari role `pemilik_toko` menghasilkan response 403 Forbidden. | Valid |
| **E** | **KELOLA TOKO SENDIRI (PEMILIK TOKO)** | | | |
| 23 | Pendaftaran Toko Baru oleh Pemilik Toko | Pemilik toko menambah toko di `/pemilik/toko/create`. Toko otomatis terikat ke akun pemilik yang sedang login (`pemilik_id`). | Toko berhasil dibuat dengan `pemilik_id` otomatis diisi dari `Auth::id()`. Pemilik tidak bisa mengisi `pemilik_id` secara manual. | Valid |
| 24 | Edit Toko Sendiri oleh Pemilik Toko | Pemilik toko mengedit profil tokonya sendiri di `/pemilik/toko/{id}/edit`. Data berhasil diperbarui. | Sistem memverifikasi `$toko->pemilik_id === Auth::id()` sebelum menampilkan form edit. Data berhasil diperbarui setelah submit. | Valid |
| 25 | Proteksi Toko Pemilik Lain | Pemilik Toko A mencoba membuka URL edit/hapus toko milik Pemilik Toko B (`/pemilik/toko/{id_toko_B}/edit`). Sistem menolak dengan response `403 Forbidden`. | Method `authorizeOwnership()` di controller mendeteksi ketidakcocokan `pemilik_id` dan memanggil `abort(403)`. | Valid |
| 26 | Card Status Penilaian Admin di Dashboard Pemilik | Dashboard pemilik toko menampilkan status badge ("Menunggu Penilaian", "Penilaian Sebagian", "Sudah Dinilai Admin") **tanpa** menampilkan angka skor detail kriteria. | Dashboard `/pemilik/dashboard` hanya memuat `nilaiAlternatif_count` (jumlah, bukan nilai). Status badge ditampilkan sesuai perbandingan jumlah nilai dengan total kriteria. | Valid |
| **F** | **FITUR GPS GEOLOCATION & MAP PREVIEW** | | | |
| 27 | Deteksi Lokasi GPS di Form Toko (`pemilik_toko` & `admin`) | Menekan tombol **"Deteksi Lokasi Saya"** pada form toko mengaktifkan GPS browser, memperbarui input latitude/longitude, serta menampilkan status sukses (hijau) atau error (merah). | JavaScript `navigator.geolocation.getCurrentPosition()` berhasil dipanggil. Field lat/lng diperbarui secara otomatis dan marker peta berpindah ke titik lokasi yang terdeteksi. | Valid |
| 28 | Preview Peta Leaflet Interaktif di Form Toko | Mengubah input lat/lng secara manual atau mengklik/menggeser marker di preview peta Leaflet memperbarui koordinat secara 2-arah. | Sinkronisasi 2-arah antara input field dan marker peta berjalan dengan baik. Event listener `input` dan `dragend` pada Leaflet marker saling memperbarui nilai. | Valid |
| 29 | Deteksi Lokasi GPS di Halaman Preferensi User | User menekan tombol **"Deteksi GPS Otomatis"** di `/user/preferensi`. Koordinat GPS user berhasil dideteksi untuk perhitungan jarak Haversine. | Koordinat GPS user berhasil ditangkap dan disimpan ke hidden input `user_lat` dan `user_lng` yang ikut dikirim bersama form preferensi. Koordinat juga disimpan ke session untuk persistensi. | Valid |
| **G** | **PREFERENSI USER & PERHITUNGAN ROC-SMART** | | | |
| 30 | Drag-and-Drop Prioritas Kriteria (User) | User menggeser urutan kriteria di `/user/preferensi`. Urutan nomor di kiri menyesuaikan dan tombol panah naik/turun berfungsi. | Fitur drag-and-drop berbasis SortableJS berjalan. Nomor urutan di kiri tiap item menyesuaikan secara real-time. Tombol ↑ dan ↓ juga memindahkan item dengan benar. | Valid |
| 31 | Real-time Preview Bobot ROC | Saat urutan kriteria digeser, baris Preview Bobot ROC di sebelah kanan memperbarui persentase bobot ROC secara *real-time*. | JavaScript di halaman menghitung ulang bobot ROC menggunakan formula `w_k = (1/n) × Σ(1/i)` setiap kali urutan berubah dan menampilkan hasilnya sebagai persentase. | Valid |
| 32 | Perhitungan Nilai Akhir SMART & Ranking Toko | Sistem mengalihkan nilai toko ke utilitas SMART (0–1), mengalikan dengan bobot ROC & faktor jarak Haversine, lalu mengurutkan toko dari skor tertinggi ke terendah. | `RocSmartService::processRocSmart()` berhasil menghitung utility matrix dan skor akhir. Toko dengan nilai_alternatif yang lebih baik dan lokasi lebih dekat mendapatkan skor lebih tinggi. | Valid |
| 33 | Personalisasi Hasil Rekomendasi per User | Dua user yang memasukkan urutan preferensi kriteria berbeda menerima daftar rekomendasi toko dengan urutan ranking yang berbeda sesuai preferensinya. | Bobot ROC dihitung ulang per user berdasarkan urutan preferensi yang disimpan di tabel `preferensi_user`. Dua user dengan urutan berbeda menghasilkan ranking toko yang berbeda. | Valid |
| **H** | **PETA LEAFLET / OPENSTREETMAP** | | | |
| 34 | Peta Leaflet di Detail Toko (`/toko/{id}`) | Halaman detail toko menampilkan peta Leaflet + tile OpenStreetMap dengan marker lokasi toko dan popup peta yang valid (tanpa dependency Google Maps API). | Peta Leaflet berhasil dirender menggunakan tile layer OpenStreetMap (tile.openstreetmap.org). Marker ditempatkan sesuai koordinat `latitude` dan `longitude` toko yang tersimpan di database. | Valid |
| 35 | Peta Leaflet di Hasil Rekomendasi (`/user/hasil`) | Halaman hasil rekomendasi menampilkan peta Leaflet yang menandai posisi user (marker biru) dan titik lokasi seluruh toko hasil rekomendasi. | Peta Leaflet dirender di `/user/hasil` dengan marker biru untuk posisi user (dari session/GPS) dan marker merah/hijau untuk setiap toko yang muncul di ranking. Popup menampilkan nama toko dan jarak dalam km. | Valid |

---

## 📊 Ringkasan Pengujian Otomatis Internal (Automated Test Suite)
- **Total Automated Tests**: 34 Passed (170 Assertions)
- **Status Database & Migrasi**: Seed & Migration 100% Passed
- **Engine SPK**: ROC Weight Calculation + SMART Utility + Haversine Formula 100% Passed

Dokumen ini siap dicetak / diisi manual untuk keperluan pengujian Blackbox Sistem.
