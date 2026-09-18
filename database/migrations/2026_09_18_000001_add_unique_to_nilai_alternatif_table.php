<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan UNIQUE constraint pada kombinasi (toko_id, kriteria_id)
     * di tabel nilai_alternatif untuk mencegah duplikat baris yang sama.
     * Tanpa constraint ini, scopeLengkap() bisa salah menghitung jumlah kriteria.
     */
    public function up(): void
    {
        Schema::table('nilai_alternatif', function (Blueprint $table) {
            $table->unique(['toko_id', 'kriteria_id'], 'nilai_alternatif_toko_kriteria_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_alternatif', function (Blueprint $table) {
            $table->dropUnique('nilai_alternatif_toko_kriteria_unique');
        });
    }
};
