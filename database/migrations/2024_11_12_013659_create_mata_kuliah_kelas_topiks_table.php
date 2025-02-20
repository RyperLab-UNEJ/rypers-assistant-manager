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
        Schema::create('mata_kuliah_kelas_topiks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_kelas_id')->constrained()->cascadeOnDelete();
            $table->string('judul_topik');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah_kelas_topiks');
    }
};
