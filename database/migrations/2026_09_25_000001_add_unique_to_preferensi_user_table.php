<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan UNIQUE constraint pada kombinasi (user_id, kriteria_id)
     * di tabel preferensi_user untuk mencegah duplikat preferensi yang sama
     * oleh user yang sama, sebagai defense tambahan di level database.
     */
    public function up(): void
    {
        Schema::table('preferensi_user', function (Blueprint $table) {
            $table->unique(['user_id', 'kriteria_id'], 'preferensi_user_user_kriteria_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preferensi_user', function (Blueprint $table) {
            $table->dropUnique('preferensi_user_user_kriteria_unique');
        });
    }
};
