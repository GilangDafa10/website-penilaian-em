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
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id('id_penilaian');
            $table->unsignedBigInteger('id_penilai');
            $table->unsignedBigInteger('id_dinilai');
            $table->unsignedBigInteger('id_kriteria');
            $table->integer('skor');
            $table->date('tanggal_penilaian');
            $table->foreign('id_penilai')->references('id_user')->on('users')->cascadeOnDelete();
            $table->foreign('id_dinilai')->references('id_user')->on('users')->cascadeOnDelete();
            $table->foreign('id_kriteria')->references('id_kriteria')->on('kriterias')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
