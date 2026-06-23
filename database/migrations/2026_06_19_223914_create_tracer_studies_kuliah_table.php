<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracer_studies_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_kuliah_id')->constrained('alumni_kuliah')->onDelete('cascade');
            $table->enum('status_kuliah', ['aktif', 'lulus', 'cuti', 'putus'])->default('aktif');
            $table->string('kampus_tujuan')->nullable();
            $table->string('program_studi')->nullable();
            $table->text('detail_status')->nullable();
            $table->text('testimoni')->nullable();
            $table->date('tanggal_update')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracer_studies_kuliah');
    }
};
