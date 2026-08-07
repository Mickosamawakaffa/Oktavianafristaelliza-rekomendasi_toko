# 📝 Kuesioner User Acceptance Testing (UAT)
## Sistem Pendukung Keputusan Pemilihan Toko Fashion Secondhand Jember (ROC + SMART + GPS Haversine)

Kuesioner ini bertujuan untuk mengukur tingkat penerimaan pengguna (*User Acceptance Testing*) terhadap aplikasi Sistem Pendukung Keputusan (SPK) rekomendasi toko fashion secondhand di Jember.

---

## 👤 I. Identitas Responden
*Silakan isi data diri Anda di bawah ini:*

- **Nama / Inisial** : ____________________________________
- **Usia** : ______ Tahun
- **Pengalaman Belanja Thrift Secara Online** :
  - [  ] **Pernah** (pernah membeli produk thrift/fashion secondhand secara online)
  - [  ] **Belum Pernah**
- **Peran / Status** :
  - [  ] **Pengguna / Pembeli** (Pencari Toko)
  - [  ] **Pemilik Toko** (Mitra Toko)
  - [  ] **Lainnya**: ____________________________________
- **Tanggal Pengisian** : ____________________________________

---

## 📌 II. Petunjuk Pengisian Kuesioner
Berikan tanda centang $(\checkmark)$ atau silang $(\times)$ pada kolom skala **1 sampai 5** yang paling sesuai dengan pengalaman Anda saat mencoba aplikasi:

- **1 = Sangat Tidak Setuju (STS)**
- **2 = Tidak Setuju (TS)**
- **3 = Netral / Ragu-ragu (N)**
- **4 = Setuju (S)**
- **5 = Sangat Setuju (SS)**

---

## 📊 III. Tabel Pertanyaan Kuesioner UAT

| No | Pernyataan / Indikator Penilaian | 1 (STS) | 2 (TS) | 3 (N) | 4 (S) | 5 (SS) |
|:---:|---|:---:|:---:|:---:|:---:|:---:|
| **A** | **Kemudahan Penggunaan (Usability)** | | | | | |
| 1 | Fitur **drag-and-drop urutan prioritas kriteria** mudah digunakan untuk mengatur kriteria pilihan saya. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 2 | Fitur **Deteksi Lokasi GPS ("Deteksi Lokasi Saya")** mempermudah pengisian koordinat posisi saya/toko secara otomatis. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 3 | Aplikasi mudah dipahami dan dioperasikan tanpa memerlukan panduan manual yang rumit. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 4 | Alur navigasi antar halaman (Daftar Toko, Preferensi, Hasil, Detail Toko) terasa terstruktur dan lancar. | [   ] | [   ] | [   ] | [   ] | [   ] |
| **B** | **Kejelasan Tampilan / Antarmuka (UI Clarity)** | | | | | |
| 5 | Desain antarmuka aplikasi menarik, rapi, dan nyaman dipandang. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 6 | Informasi teks, tombol, badge status, dan ikon mudah dibaca serta jelas fungsinya. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 7 | Tampilan **Peta Leaflet / OpenStreetMap** dan marker lokasi toko/user terlihat jelas dan membantu menentukan posisi. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 8 | Tampilan rincian bobot ROC dan persentase skor disajikan dengan rapi dan mudah dipahami. | [   ] | [   ] | [   ] | [   ] | [   ] |
| **C** | **Kesesuaian Hasil Rekomendasi (Accuracy & Relevance)** | | | | | |
| 9 | Hasil urutan ranking toko yang ditampilkan sesuai dengan preferensi kriteria yang saya tetapkan. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 10 | Perhitungan jarak lokasi GPS (Haversine) membantu memberikan rekomendasi toko terdekat dari posisi saya. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 11 | Informasi detail toko (alamat, jam operasional, media sosial, koordinat) lengkap dan berguna. | [   ] | [   ] | [   ] | [   ] | [   ] |
| **D** | **Kecepatan & Performa Sistem (Speed & Performance)** | | | | | |
| 12 | Sistem menghitung dan menampilkan hasil rekomendasi toko dengan sangat cepat tanpa rasa lambat (*lag*). | [   ] | [   ] | [   ] | [   ] | [   ] |
| 13 | Pemuatan halaman (*page loading*) dan respon tombol berjalan sangat cepat dan responsif. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 14 | Aplikasi berjalan stabil tanpa mengalami error atau kendala teknis saat digunakan. | [   ] | [   ] | [   ] | [   ] | [   ] |
| **E** | **Kepuasan Keseluruhan (Overall Satisfaction)** | | | | | |
| 15 | Aplikasi ini secara objektif membantu saya menemukan toko fashion secondhand terbaik di Jember. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 16 | Saya merasa puas menggunakan aplikasi Sistem Pendukung Keputusan (SPK) ini secara keseluruhan. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 17 | Saya bersedia merekomendasikan aplikasi ini kepada teman atau orang lain yang ingin mencari toko thrift. | [   ] | [   ] | [   ] | [   ] | [   ] |

---

## 💬 IV. Masukan / Saran Tambahan
*Silakan tuliskan masukan atau saran Anda untuk perbaikan aplikasi ini ke depannya:*

____________________________________________________________________________________________________

____________________________________________________________________________________________________

---

## 📐 V. Rumus Perhitungan & Panduan Analisis UAT (Untuk Peneliti)

### 1. Perhitungan Skor Rata-Rata Per Pertanyaan
Hitung rata-rata jawaban seluruh responden untuk setiap nomor pertanyaan:

$$\bar{X}_i = \frac{\sum_{j=1}^{N} X_{ij}}{N}$$

*Keterangan:*
- $\bar{X}_i$ = Nilai rata-rata skor indikator/pertanyaan ke-$i$
- $X_{ij}$ = Skor jawaban dari responden ke-$j$ pada pertanyaan ke-$i$ (nilai 1–5)
- $N$ = Total jumlah responden yang mengisi kuesioner

### 2. Perhitungan Persentase Kelayakan Sistem (Persentase Likert)
Hitung persentase total kelayakan sistem dari seluruh variabel pertanyaan:

$$\text{Persentase Kelayakan (\%)} = \left( \frac{\text{Total Skor Jawaban Actual}}{\text{Total Skor Maksimum Ideal}} \right) \times 100\%$$

$$\text{Persentase Kelayakan (\%)} = \left( \frac{\sum \text{Seluruh Skor Jawaban Responden}}{N \times K \times 5} \right) \times 100\%$$

*Keterangan:*
- $N$ = Jumlah responden yang mengisi kuesioner
- $K$ = Jumlah butir pertanyaan ($17$ butir pertanyaan)
- $5$ = Skor maksimum tertinggi untuk setiap pertanyaan (Sangat Setuju)

---

### 📈 3. Kriteria Interpretasi Skor Kelayakan (Skala Likert)

| Rentang Persentase Skor (%) | Kategori Kelayakan System | Interpretasi Kelayakan UAT |
|:---:|:---:|---|
| **81% — 100%** | **Sangat Layak / Sangat Baik** | Aplikasi **sangat diterima** oleh pengguna, seluruh fitur berfungsi optimal dan sangat memuaskan. |
| **61% — 80%** | **Layak / Baik** | Aplikasi **diterima** oleh pengguna dan berfungsi dengan baik, hanya perlu penyempurnaan kecil. |
| **41% — 60%** | **Cukup Layak** | Aplikasi **cukup diterima**, tetapi masih memerlukan beberapa perbaikan pada fitur tertentu. |
| **21% — 40%** | **Kurang Layak** | Aplikasi **kurang diterima**, memerlukan perbaikan besar pada alur dan fungsi. |
| **0% — 20%** | **Sangat Kurang Layak** | Aplikasi **ditolak** oleh pengguna, tidak layak digunakan. |
