<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom 'gambar' untuk menyimpan path file foto toko.
     */
    public function up(): void
    {
        Schema::table('toko', function (Blueprint $table) {
            $table->string('gambar')->nullable()->after('jam_operasional');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('toko', function (Blueprint $table) {
            $table->dropColumn('gambar');
        });
    }
};
