# Aset lokal (tanpa CDN)

Aplikasi memakai Tailwind v3, Alpine, Leaflet, SortableJS, dan font Plus Jakarta Sans yang
dilayani dari folder `public/` (`public/css/app.css`, `public/vendor/*`, `public/fonts/*`),
sehingga tampilan tetap utuh walau internet mati. Satu-satunya yang tetap butuh internet
adalah **tile peta OpenStreetMap** (gambar petanya).

File hasil build sudah ikut di `public/`, jadi **tidak perlu menjalankan apa pun** untuk
sekadar memakai aplikasi.

## Build ulang (hanya jika menambah/mengubah class Tailwind di view)

```bash
cd tools/tailwind
npm install      # sekali saja
npm run build    # membuat public/css/app.css dan menyalin pustaka ke public/vendor
```

Butuh Node.js 18 atau lebih baru. Konfigurasi warna/tema ada di `tailwind.config.js`
(sama dengan konfigurasi CDN sebelumnya).
