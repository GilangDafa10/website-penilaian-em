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
        Schema::table('users', function (Blueprint $table) {
            // Pastikan tipe kolom sesuai (unsignedBigInteger)
            // Jika belum, Anda mungkin perlu mengubah tipe kolom dulu
            $table->foreign('id_jabatan')
                ->references('id_jabatan')
                ->on('jabatans')
                ->onDelete('cascade') // atau 'set null', 'restrict', dll
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['id_jabatan']);
        });
    }
};
