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
        Schema::create('alumni_wirausahas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alumni');
            $table->string('nama_usaha');
            $table->string('bidang_usaha');
            $table->string('lama_usaha'); // e.g. "1 Tahun", "6 Bulan"
            $table->integer('tahun_lulus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni_wirausahas');
    }
};
