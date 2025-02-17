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
        Schema::create('mata_kuliah_kelas_topik_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_kelas_topik_id')->constrained('mata_kuliah_kelas_topiks', 'id', 'mata_kuliah_topik_constrained')->onDelete('cascade');
            $table->enum('jenis', ['forum', 'file', 'url', 'assignment', 'quiz']);
            $table->string('judul_topik_detail');
            $table->longText('deskripsi')->nullable();
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah_kelas_topik_details');
    }
};
