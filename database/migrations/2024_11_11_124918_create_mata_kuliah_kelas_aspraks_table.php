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
        Schema::create('mata_kuliah_kelas_aspraks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_kelas_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('asprak_id');
            $table->timestamps();

            $table->foreign('asprak_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['mata_kuliah_kelas_id', 'asprak_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah_kelas_aspraks');
    }
};
