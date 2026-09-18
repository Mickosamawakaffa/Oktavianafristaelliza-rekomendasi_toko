<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\NilaiAlternatif;
use App\Models\Toko;
use Illuminate\Database\Seeder;

class NilaiAlternatifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tokos = Toko::all()->keyBy('nama');
        $kriterias = Kriteria::all()->keyBy('nama_kriteria');

        if ($tokos->isEmpty() || $kriterias->isEmpty()) {
            return;
        }

        // Data rating ulasan (skala 1-5) dan jumlah ulasan per toko
        // Skor Ulasan di nilai_alternatif dihitung otomatis: (rating / 5) * 100
        $ratingUlasanData = [
            'Thrift Vintage Jember'    => ['rating' => 4.60, 'jumlah' => 120],
            'Secondhand Store Kampus'  => ['rating' => 4.25, 'jumlah' => 45],
            'Jember Thrift Central'    => ['rating' => 4.50, 'jumlah' => 200],
            'Oldschool Fashion Thrift' => ['rating' => 4.35, 'jumlah' => 78],
            'Retroline Thrift Spot'    => ['rating' => 4.40, 'jumlah' => 95],
            'Garasi Thrift Jember'     => ['rating' => 4.00, 'jumlah' => 30],
            'Urban Thrift Outlet'      => ['rating' => 4.30, 'jumlah' => 65],
            'Preloved Fashion Corner'  => ['rating' => 4.45, 'jumlah' => 112],
        ];

        // Update rating_ulasan & jumlah_ulasan di tabel toko
        foreach ($ratingUlasanData as $tokoNama => $data) {
            if (isset($tokos[$tokoNama])) {
                $tokos[$tokoNama]->update([
                    'rating_ulasan'  => $data['rating'],
                    'jumlah_ulasan'  => $data['jumlah'],
                ]);
            }
        }

        // Matriks nilai realistis (skala 1-100) untuk tiap toko dan kriteria
        // Untuk kriteria Ulasan, skor dihitung otomatis dari rating_ulasan
        $nilaiMatrix = [
            'Thrift Vintage Jember' => [
                'Harga' => 80.0, // Agak mahal (vintage branded)
                'Kualitas Produk' => 90.0,
                'Kelengkapan Produk' => 85.0,
                'Variasi Barang' => 88.0,
                'Lokasi' => 90.0,
                'Ulasan' => null, // Akan dihitung dari rating_ulasan
            ],
            'Secondhand Store Kampus' => [
                'Harga' => 60.0, // Sangat murah (harga mahasiswa)
                'Kualitas Produk' => 78.0,
                'Kelengkapan Produk' => 80.0,
                'Variasi Barang' => 82.0,
                'Lokasi' => 95.0,
                'Ulasan' => null,
            ],
            'Jember Thrift Central' => [
                'Harga' => 85.0,
                'Kualitas Produk' => 92.0,
                'Kelengkapan Produk' => 90.0,
                'Variasi Barang' => 95.0,
                'Lokasi' => 88.0,
                'Ulasan' => null,
            ],
            'Oldschool Fashion Thrift' => [
                'Harga' => 75.0,
                'Kualitas Produk' => 88.0,
                'Kelengkapan Produk' => 75.0,
                'Variasi Barang' => 80.0,
                'Lokasi' => 82.0,
                'Ulasan' => null,
            ],
            'Retroline Thrift Spot' => [
                'Harga' => 70.0,
                'Kualitas Produk' => 82.0,
                'Kelengkapan Produk' => 85.0,
                'Variasi Barang' => 85.0,
                'Lokasi' => 92.0,
                'Ulasan' => null,
            ],
            'Garasi Thrift Jember' => [
                'Harga' => 55.0, // Paling murah
                'Kualitas Produk' => 75.0,
                'Kelengkapan Produk' => 70.0,
                'Variasi Barang' => 78.0,
                'Lokasi' => 75.0,
                'Ulasan' => null,
            ],
            'Urban Thrift Outlet' => [
                'Harga' => 78.0,
                'Kualitas Produk' => 85.0,
                'Kelengkapan Produk' => 88.0,
                'Variasi Barang' => 90.0,
                'Lokasi' => 85.0,
                'Ulasan' => null,
            ],
            'Preloved Fashion Corner' => [
                'Harga' => 65.0,
                'Kualitas Produk' => 84.0,
                'Kelengkapan Produk' => 82.0,
                'Variasi Barang' => 84.0,
                'Lokasi' => 90.0,
                'Ulasan' => null,
            ],
        ];

        foreach ($nilaiMatrix as $tokoNama => $kriteriaValues) {
            if (isset($tokos[$tokoNama])) {
                $toko = $tokos[$tokoNama];
                foreach ($kriteriaValues as $kriteriaNama => $val) {
                    if (isset($kriterias[$kriteriaNama])) {
                        $kriteria = $kriterias[$kriteriaNama];

                        // Untuk kriteria Ulasan, hitung skor dari rating_ulasan
                        if ($kriteriaNama === 'Ulasan' && isset($ratingUlasanData[$tokoNama])) {
                            $val = round(($ratingUlasanData[$tokoNama]['rating'] / 5) * 100, 1);
                        }

                        // Skip jika val masih null
                        if ($val === null) {
                            continue;
                        }

                        NilaiAlternatif::updateOrCreate(
                            [
                                'toko_id' => $toko->id,
                                'kriteria_id' => $kriteria->id,
                            ],
                            [
                                'nilai' => $val,
                            ]
                        );
                    }
                }
            }
        }
    }
}
