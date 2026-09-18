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
            $table->decimal('rating_ulasan', 3, 2)->nullable()->after('jam_operasional');
            $table->unsignedInteger('jumlah_ulasan')->nullable()->after('rating_ulasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('toko', function (Blueprint $table) {
            $table->dropColumn(['rating_ulasan', 'jumlah_ulasan']);
        });
    }
};
