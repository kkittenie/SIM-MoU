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
        Schema::create('kerja_sama', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mitra');
            $table->enum('jenis_mitra', ['Perusahaan', 'Perguruan Tinggi', 'Instansi Pemerintah', 'Lainnya']);
            $table->text('alamat');
            $table->string('email');
            $table->string('nomor_telepon');
            $table->string('pic');
            $table->string('nomor_mou');
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->text('deskripsi')->nullable();
            $table->string('dokumen_pdf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kerja_sama');
    }
};
