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
        // 1. Tabel Perusahaan Mitra
        Schema::create('perusahaan_mitras', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->string('bidang_industri');
            $table->text('alamat');
            $table->string('email');
            $table->string('nomor_telepon');
            $table->string('pic');
            $table->string('website')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status_aktif', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->timestamps();
        });

        // 2. Tabel Alumni Bekerja
        Schema::create('alumni_bekerjas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alumni');
            $table->foreignId('perusahaan_mitra_id')->nullable()->constrained('perusahaan_mitras')->nullOnDelete();
            $table->string('perusahaan_nama')->nullable(); // Jika manual/non-mitra
            $table->string('jabatan');
            $table->date('tanggal_masuk');
            $table->integer('tahun_lulus');
            $table->string('bidang_industri'); // disimpan langsung untuk mempermudah statistik
            $table->decimal('gaji', 15, 2)->nullable();
            $table->string('status_pekerjaan')->default('Tetap'); // Tetap, Kontrak, Magang
            $table->timestamps();
        });

        // 3. Tabel Lowongan Kerja
        Schema::create('lowongan_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->foreignId('perusahaan_mitra_id')->nullable()->constrained('perusahaan_mitras')->nullOnDelete();
            $table->string('perusahaan_nama')->nullable(); // Jika manual/non-mitra
            $table->string('posisi');
            $table->text('persyaratan');
            $table->text('deskripsi')->nullable();
            $table->string('gaji')->nullable(); // e.g. "Rp 4.000.000 - Rp 6.000.000"
            $table->date('tanggal_tutup')->nullable();
            $table->enum('status', ['Aktif', 'Tutup'])->default('Aktif');
            $table->timestamps();
        });

        // 4. Tabel Tracer Study
        Schema::create('tracer_studies', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alumni');
            $table->integer('tahun_lulus');
            $table->enum('status_alumni', ['Bekerja', 'Kuliah', 'Wirausaha', 'Mencari Kerja']);
            $table->string('detail_status')->nullable(); // Nama perusahaan / kampus / nama usaha
            $table->text('testimoni')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracer_studies');
        Schema::dropIfExists('lowongan_kerjas');
        Schema::dropIfExists('alumni_bekerjas');
        Schema::dropIfExists('perusahaan_mitras');
    }
};
