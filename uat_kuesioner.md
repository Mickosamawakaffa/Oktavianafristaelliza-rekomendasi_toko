# 📝 Kuesioner User Acceptance Testing (UAT)
## Sistem Pendukung Keputusan Pemilihan Toko Fashion Secondhand Menggunakan Metode ROC + SMART + Haversine Distance

Kuesioner ini bertujuan untuk mengukur tingkat penerimaan pengguna (*User Acceptance Testing*) terhadap aplikasi rekomendasi toko fashion secondhand berbasis Sistem Pendukung Keputusan (SPK).

---

## 👤 I. Identitas Responden
*Silakan isi data diri Anda di bawah ini:*

- **Nama / Inisial** : ____________________________________
- **Usia** : ______ Tahun
- **Jenis Kelamin** : [  ] Laki-Laki  |  [  ] Perempuan
- **Status / Pekerjaan** : [  ] Mahasiswa  |  [  ] Umum  |  [  ] Pemilik Toko  |  [  ] Admin
- **Tanggal Pengisian** : ____________________________________

---

## 📌 II. Petunjuk Pengisian Kuesioner
Berikan tanda silang $(\times)$ atau centang $(\checkmark)$ pada salah satu angka skala $1 - 5$ yang paling sesuai dengan penilaian Anda untuk setiap pernyataan:

- **1 = Sangat Tidak Setuju (STS)**
- **2 = Tidak Setuju (TS)**
- **3 = Netral / Ragu-ragu (N)**
- **4 = Setuju (S)**
- **5 = Sangat Setuju (SS)**

---

## 📊 III. Tabel Pertanyaan UAT

| No | Pernyataan / Indikator Penilaian | 1 (STS) | 2 (TS) | 3 (N) | 4 (S) | 5 (SS) |
|:---:|---|:---:|:---:|:---:|:---:|:---:|
| **A** | **Kemudahan Penggunaan (Usability)** | | | | | |
| 1 | Aplikasi mudah dipahami dan dioperasikan tanpa memerlukan panduan yang rumit. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 2 | Alur dan navigasi penggunaan aplikasi terasa terstruktur dan logis. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 3 | Fitur registrasi, login, serta penginputan preferensi kriteria dapat dilakukan dengan mudah. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 4 | Pengguna tidak mengalami kebingungan saat berpindah antar halaman dalam aplikasi. | [   ] | [   ] | [   ] | [   ] | [   ] |
| **B** | **Kejelasan Tampilan & Antarmuka (UI/UX Clarity)** | | | | | |
| 5 | Desain antarmuka (*interface*) aplikasi menarik, rapi, dan nyaman dilihat. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 6 | Informasi yang ditampilkan (teks, tombol, ikon, tabel) mudah dibaca dan jelas fungsinya. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 7 | Tampilan peta lokasi (Google Maps/Marker) jelas dan memudahkan posisi toko ditemukan. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 8 | Tata letak elemen (*layout*) pada halaman preferensi dan hasil rekomendasi tersusun rapi. | [   ] | [   ] | [   ] | [   ] | [   ] |
| **C** | **Kesesuaian Hasil Rekomendasi (Recommendation Accuracy & Relevance)** | | | | | |
| 9 | Urutan prioritas kriteria yang saya atur menghasilkan ranking toko yang sesuai harapan. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 10 | Perhitungan jarak fisik GPS (Haversine) membantu menyajikan toko yang dekat dari lokasi saya. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 11 | Rincian bobot ROC dan nilai utility SMART memudahkan pengguna memahami alasan pemeringkatan toko. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 12 | Informasi detail toko (alamat, jam operasional, media sosial, peta) bermanfaat dalam menentukan pilihan. | [   ] | [   ] | [   ] | [   ] | [   ] |
| **D** | **Kecepatan & Kinerja Sistem (System Speed & Performance)** | | | | | |
| 13 | Proses kalkulasi dan pemrosesan rekomendasi toko berjalan dengan cepat tanpa *lagging*. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 14 | Pemuatan halaman (*page loading*) saat berpindah menu berjalan dengan responsif. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 15 | Sistem berjalan dengan stabil tanpa mengalami error atau kendala teknis saat digunakan. | [   ] | [   ] | [   ] | [   ] | [   ] |
| **E** | **Kepuasan Keseluruhan (Overall Satisfaction)** | | | | | |
| 16 | Aplikasi ini secara objektif membantu dalam memilih toko fashion secondhand terbaik. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 17 | Saya merasa puas menggunakan aplikasi Sistem Pendukung Keputusan (SPK) ini secara keseluruhan. | [   ] | [   ] | [   ] | [   ] | [   ] |
| 18 | Saya bersedia merekomendasikan aplikasi ini kepada orang lain yang mencari toko secondhand. | [   ] | [   ] | [   ] | [   ] | [   ] |

---

## 💬 IV. Catatan / Masukan / Saran Tambahan
*Tuliskan masukan atau saran Anda untuk pengembangan aplikasi ini ke depannya:*

____________________________________________________________________________________________________

____________________________________________________________________________________________________

____________________________________________________________________________________________________

---

## 📐 V. Panduan Perhitungan Persentase Kelayakan UAT (Untuk Pengolah Data/Peneliti)

Untuk menghitung nilai persentase kelayakan aplikasi berdasarkan jawaban seluruh responden, gunakan rumus persentase Likert:

$$\text{Persentase Kelayakan (\%)} = \frac{\text{Total Skor Pengumpulan}}{\text{Jumlah Responden} \times \text{Jumlah Pertanyaan (18)} \times 5} \times 100\%$$

### 📈 Kriteria Interpretasi Skor UAT:
- **81% - 100%** : **Sangat Layak / Sangat Baik**
- **61% - 80%**  : **Layak / Baik**
- **41% - 60%**  : **Cukup Layak / Cukup**
- **21% - 40%**  : **Kurang Layak / Kurang**
- **0% - 20%**   : **Sangat Kurang Layak**
