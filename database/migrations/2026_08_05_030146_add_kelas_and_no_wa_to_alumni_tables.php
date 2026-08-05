<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah ke tabel alumni_bekerjas
        Schema::table('alumni_bekerjas', function (Blueprint $table) {
            $table->string('kelas')->nullable()->after('nama_alumni');
            $table->string('no_wa', 20)->nullable()->after('kelas');
        });

        // Tambah ke tabel alumni_kuliah
        Schema::table('alumni_kuliah', function (Blueprint $table) {
            $table->string('kelas')->nullable()->after('nama_alumni');
            $table->string('no_wa', 20)->nullable()->after('kelas');
        });

        // Tambah ke tabel alumni_wirausahas
        Schema::table('alumni_wirausahas', function (Blueprint $table) {
            $table->string('kelas')->nullable()->after('nama_alumni');
            $table->string('no_wa', 20)->nullable()->after('kelas');
        });
    }

    public function down(): void
    {
        Schema::table('alumni_bekerjas', function (Blueprint $table) {
            $table->dropColumn(['kelas', 'no_wa']);
        });

        Schema::table('alumni_kuliah', function (Blueprint $table) {
            $table->dropColumn(['kelas', 'no_wa']);
        });

        Schema::table('alumni_wirausahas', function (Blueprint $table) {
            $table->dropColumn(['kelas', 'no_wa']);
        });
    }
};
