<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kriteria = [
            [
                'nama_kriteria' => 'Harga',
                'jenis' => 'cost',
            ],
            [
                'nama_kriteria' => 'Kualitas Produk',
                'jenis' => 'benefit',
            ],
            [
                'nama_kriteria' => 'Kelengkapan Produk',
                'jenis' => 'benefit',
            ],
            [
                'nama_kriteria' => 'Variasi Barang',
                'jenis' => 'benefit',
            ],
            [
                'nama_kriteria' => 'Lokasi',
                'jenis' => 'benefit',
            ],
            [
                'nama_kriteria' => 'Ulasan',
                'jenis' => 'benefit',
            ],
        ];

        foreach ($kriteria as $item) {
            Kriteria::updateOrCreate(
                ['nama_kriteria' => $item['nama_kriteria']],
                ['jenis' => $item['jenis']]
            );
        }
    }
}
