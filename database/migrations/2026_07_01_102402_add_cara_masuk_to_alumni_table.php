<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni_kuliah', function (Blueprint $table) {
            $table->enum('cara_masuk', ['snbp', 'utbk', 'ujian_masuk', 'beasiswa', 'transfer', 'lainnya'])
                ->nullable()
                ->after('program_studi');
        });
    }

    public function down(): void
    {
        Schema::table('alumni_kuliah', function (Blueprint $table) {
            $table->dropColumn('cara_masuk');
        });
    }
};
