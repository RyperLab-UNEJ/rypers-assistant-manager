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
        Schema::create('mata_kuliah_kelas_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_kelas_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['mata_kuliah_kelas_id', 'mahasiswa_id'], 'mata_kuliah_kelas_mahasiswa_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah_kelas_mahasiswas');
    }
};
