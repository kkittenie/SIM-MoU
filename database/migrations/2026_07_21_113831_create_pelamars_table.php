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
        Schema::create('pelamars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lowongan_kerja_id')->constrained('lowongan_kerjas')->cascadeOnDelete();
            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('nomor_telepon');
            $table->integer('tahun_lulus');
            $table->string('program_keahlian');
            $table->text('surat_lamaran');
            $table->string('file_cv')->nullable();
            $table->enum('status', ['Pending', 'Diterima', 'Ditolak'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelamars');
    }
};
