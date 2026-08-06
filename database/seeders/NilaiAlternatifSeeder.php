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

        // Matriks nilai realistis (skala 1-100) untuk tiap toko dan kriteria
        $nilaiMatrix = [
            'Thrift Vintage Jember' => [
                'Harga' => 80.0, // Agak mahal (vintage branded)
                'Kualitas Produk' => 90.0,
                'Kelengkapan Produk' => 85.0,
                'Variasi Barang' => 88.0,
                'Lokasi' => 90.0,
                'Ulasan' => 92.0,
            ],
            'Secondhand Store Kampus' => [
                'Harga' => 60.0, // Sangat murah (harga mahasiswa)
                'Kualitas Produk' => 78.0,
                'Kelengkapan Produk' => 80.0,
                'Variasi Barang' => 82.0,
                'Lokasi' => 95.0,
                'Ulasan' => 85.0,
            ],
            'Jember Thrift Central' => [
                'Harga' => 85.0,
                'Kualitas Produk' => 92.0,
                'Kelengkapan Produk' => 90.0,
                'Variasi Barang' => 95.0,
                'Lokasi' => 88.0,
                'Ulasan' => 90.0,
            ],
            'Oldschool Fashion Thrift' => [
                'Harga' => 75.0,
                'Kualitas Produk' => 88.0,
                'Kelengkapan Produk' => 75.0,
                'Variasi Barang' => 80.0,
                'Lokasi' => 82.0,
                'Ulasan' => 87.0,
            ],
            'Retroline Thrift Spot' => [
                'Harga' => 70.0,
                'Kualitas Produk' => 82.0,
                'Kelengkapan Produk' => 85.0,
                'Variasi Barang' => 85.0,
                'Lokasi' => 92.0,
                'Ulasan' => 88.0,
            ],
            'Garasi Thrift Jember' => [
                'Harga' => 55.0, // Paling murah
                'Kualitas Produk' => 75.0,
                'Kelengkapan Produk' => 70.0,
                'Variasi Barang' => 78.0,
                'Lokasi' => 75.0,
                'Ulasan' => 80.0,
            ],
            'Urban Thrift Outlet' => [
                'Harga' => 78.0,
                'Kualitas Produk' => 85.0,
                'Kelengkapan Produk' => 88.0,
                'Variasi Barang' => 90.0,
                'Lokasi' => 85.0,
                'Ulasan' => 86.0,
            ],
            'Preloved Fashion Corner' => [
                'Harga' => 65.0,
                'Kualitas Produk' => 84.0,
                'Kelengkapan Produk' => 82.0,
                'Variasi Barang' => 84.0,
                'Lokasi' => 90.0,
                'Ulasan' => 89.0,
            ],
        ];

        foreach ($nilaiMatrix as $tokoNama => $kriteriaValues) {
            if (isset($tokos[$tokoNama])) {
                $toko = $tokos[$tokoNama];
                foreach ($kriteriaValues as $kriteriaNama => $val) {
                    if (isset($kriterias[$kriteriaNama])) {
                        $kriteria = $kriterias[$kriteriaNama];
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
