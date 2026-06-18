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
        // 1. Create kategori_mitra table
        Schema::create('kategori_mitra', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });

        // Seed default categories
        $defaults = ['Perusahaan', 'Perguruan Tinggi', 'Instansi Pemerintah', 'Lainnya'];
        foreach ($defaults as $name) {
            \Illuminate\Support\Facades\DB::table('kategori_mitra')->insert([
                'nama' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Modify kerja_sama table to add kategori_mitra_id and make jenis_mitra a regular string
        Schema::table('kerja_sama', function (Blueprint $table) {
            $table->unsignedBigInteger('kategori_mitra_id')->nullable()->after('id');
            // We use string instead of enum to allow any arbitrary category string
            $table->string('jenis_mitra')->nullable()->change();
        });

        // 3. Migrate existing data
        $categories = \Illuminate\Support\Facades\DB::table('kategori_mitra')->get();
        foreach ($categories as $cat) {
            \Illuminate\Support\Facades\DB::table('kerja_sama')
                ->where('jenis_mitra', $cat->nama)
                ->update(['kategori_mitra_id' => $cat->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kerja_sama', function (Blueprint $table) {
            $table->dropColumn('kategori_mitra_id');
        });

        Schema::dropIfExists('kategori_mitra');
    }
};
