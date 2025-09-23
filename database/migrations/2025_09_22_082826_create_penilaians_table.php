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
            $table->string('nim_penilai', 20);
            $table->string('nim_dinilai', 20);
            $table->unsignedBigInteger('id_kriteria');
            $table->integer('skor');
            $table->date('tanggal_penilaian');
            $table->foreign('nim_penilai')->references('nim')->on('users')->cascadeOnDelete();
            $table->foreign('nim_dinilai')->references('nim')->on('users')->cascadeOnDelete();
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
