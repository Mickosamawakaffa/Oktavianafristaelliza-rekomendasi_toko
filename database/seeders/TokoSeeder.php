<?php

namespace Database\Seeders;

use App\Models\Toko;
use App\Models\User;
use Illuminate\Database\Seeder;

class TokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 1 sampel user pemilik toko jika belum ada
        $pemilik = User::firstOrCreate(
            ['email' => 'pemilik@toko.com'],
            [
                'name' => 'Pemilik Toko Dummy',
                'password' => bcrypt('password'),
                'role' => 'pemilik_toko',
            ]
        );

        $tokoData = [
            [
                'nama' => 'Thrift Vintage Jember',
                'alamat' => 'Jl. Kalimantan No. 12, Sumbersari, Jember',
                'deskripsi' => 'Toko thrift pakaian impor vintage branded pilihan (crewneck, hoodie, jacket).',
                'media_sosial' => '@thriftvintage_jember',
                'jam_operasional' => '10:00 - 21:00',
                'latitude' => -8.16950000,
                'longitude' => 113.71520000,
                'pemilik_id' => $pemilik->id,
            ],
            [
                'nama' => 'Secondhand Store Kampus',
                'alamat' => 'Jl. Jawa No. 45, Sumbersari, Jember',
                'deskripsi' => 'Pusat kaos dan kemeja thrift berkualitas harga mahasiswa.',
                'media_sosial' => '@secondhand.kampus',
                'jam_operasional' => '09:00 - 22:00',
                'latitude' => -8.16480000,
                'longitude' => 113.71250000,
                'pemilik_id' => null,
            ],
            [
                'nama' => 'Jember Thrift Central',
                'alamat' => 'Jl. Mastrip No. 88, Sumbersari, Jember',
                'deskripsi' => 'Menyediakan berbagai brand fashion secondhand ternama dari Jepang dan Korea.',
                'media_sosial' => '@jemberthrift_central',
                'jam_operasional' => '10:00 - 21:00',
                'latitude' => -8.16120000,
                'longitude' => 113.72010000,
                'pemilik_id' => null,
            ],
            [
                'nama' => 'Oldschool Fashion Thrift',
                'alamat' => 'Jl. Gajah Mada No. 104, Kaliwates, Jember',
                'deskripsi' => 'Spesialis jaket kulit, denim, dan celana jeans vintage terawat.',
                'media_sosial' => '@oldschool_jember',
                'jam_operasional' => '11:00 - 21:30',
                'latitude' => -8.17650000,
                'longitude' => 113.69340000,
                'pemilik_id' => null,
            ],
            [
                'nama' => 'Retroline Thrift Spot',
                'alamat' => 'Jl. Riau No. 18, Sumbersari, Jember',
                'deskripsi' => 'Toko thrift kekinian dengan tempat nyaman dan koleksi terbatas.',
                'media_sosial' => '@retroline_thrift',
                'jam_operasional' => '10:00 - 22:00',
                'latitude' => -8.16700000,
                'longitude' => 113.71000000,
                'pemilik_id' => null,
            ],
            [
                'nama' => 'Garasi Thrift Jember',
                'alamat' => 'Jl. Hayam Wuruk No. 56, Sempusari, Jember',
                'deskripsi' => 'Konsep garasi thrift murah meriah dengan sistem serbu bulanan.',
                'media_sosial' => '@garasithrift_jbr',
                'jam_operasional' => '13:00 - 21:00',
                'latitude' => -8.18210000,
                'longitude' => 113.67890000,
                'pemilik_id' => null,
            ],
            [
                'nama' => 'Urban Thrift Outlet',
                'alamat' => 'Jl. Trunojoyo No. 34, Kepatihan, Jember',
                'deskripsi' => 'Outlet pakaian secondhand outfit street style dan sportswear.',
                'media_sosial' => '@urbanthrift_outlet',
                'jam_operasional' => '10:00 - 20:30',
                'latitude' => -8.17380000,
                'longitude' => 113.70120000,
                'pemilik_id' => null,
            ],
            [
                'nama' => 'Preloved Fashion Corner',
                'alamat' => 'Jl. Karimata No. 77, Sumbersari, Jember',
                'deskripsi' => 'Tempat berburu pakaian preloved wanita dan pria higienis dan sudah dicuci.',
                'media_sosial' => '@prelovedfashion_corner',
                'jam_operasional' => '09:30 - 21:00',
                'latitude' => -8.16330000,
                'longitude' => 113.71880000,
                'pemilik_id' => null,
            ],
        ];

        foreach ($tokoData as $data) {
            Toko::updateOrCreate(
                ['nama' => $data['nama']],
                $data
            );
        }
    }
}
