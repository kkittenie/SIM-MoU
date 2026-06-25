<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_kuliah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alumni');
            $table->string('nis')->unique();
            $table->integer('tahun_lulus');
            $table->foreignId('universitas_id')->nullable()->constrained('universitas')->onDelete('set null');
            $table->string('program_studi');
            $table->string('email_alumni')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->enum('status_alumni', ['aktif', 'lulus', 'cuti', 'belum_terdata'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_kuliah');
    }
};
