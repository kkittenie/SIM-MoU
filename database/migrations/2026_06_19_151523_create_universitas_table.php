<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('universitas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_universitas');
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('website')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->enum('jenis', ['negeri', 'swasta'])->default('swasta');
            $table->string('akreditasi')->nullable();
            $table->enum('lokasi_kuliah', ['dalam_negeri', 'luar_negeri'])->default('dalam_negeri');
            // ❌ cara_masuk dihapus - dipindahkan ke alumni_kuliah
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('universitas');
    }
};
