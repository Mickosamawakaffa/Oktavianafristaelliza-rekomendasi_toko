<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('toko', function (Blueprint $table) {
            // Status verifikasi: 'pending' (menunggu verifikasi), 'approved' (disetujui), 'rejected' (ditolak)
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('approved')
                  ->after('pemilik_id');

            // Catatan atau alasan dari admin jika ditolak / revisi
            $table->text('catatan_verifikasi')
                  ->nullable()
                  ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('toko', function (Blueprint $table) {
            $table->dropColumn(['status', 'catatan_verifikasi']);
        });
    }
};
