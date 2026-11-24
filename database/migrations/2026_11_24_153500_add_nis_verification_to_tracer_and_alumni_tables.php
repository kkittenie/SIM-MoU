<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add nis, program_keahlian, kelas, no_wa to tracer_studies
        Schema::table('tracer_studies', function (Blueprint $table) {
            if (!Schema::hasColumn('tracer_studies', 'nis')) {
                $table->string('nis')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('tracer_studies', 'program_keahlian')) {
                $table->string('program_keahlian')->nullable()->after('tahun_lulus');
            }
            if (!Schema::hasColumn('tracer_studies', 'kelas')) {
                $table->string('kelas')->nullable()->after('program_keahlian');
            }
            if (!Schema::hasColumn('tracer_studies', 'no_wa')) {
                $table->string('no_wa', 20)->nullable()->after('kelas');
            }
        });

        // 2. Add program_keahlian to alumni_kuliah
        Schema::table('alumni_kuliah', function (Blueprint $table) {
            if (!Schema::hasColumn('alumni_kuliah', 'program_keahlian')) {
                $table->string('program_keahlian')->nullable()->after('tahun_lulus');
            }
        });

        // 3. Add nis and program_keahlian to alumni_bekerjas
        Schema::table('alumni_bekerjas', function (Blueprint $table) {
            if (!Schema::hasColumn('alumni_bekerjas', 'nis')) {
                $table->string('nis')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('alumni_bekerjas', 'program_keahlian')) {
                $table->string('program_keahlian')->nullable()->after('tahun_lulus');
            }
        });

        // 4. Add nis and program_keahlian to alumni_wirausahas
        Schema::table('alumni_wirausahas', function (Blueprint $table) {
            if (!Schema::hasColumn('alumni_wirausahas', 'nis')) {
                $table->string('nis')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('alumni_wirausahas', 'program_keahlian')) {
                $table->string('program_keahlian')->nullable()->after('tahun_lulus');
            }
        });

        // 5. Populate default program_keahlian on existing alumni_kuliah rows if empty
        $defaultMajor = DB::table('program_keahlians')->orderBy('id')->value('nama') ?? 'Rekayasa Perangkat Lunak';
        DB::table('alumni_kuliah')
            ->whereNull('program_keahlian')
            ->update(['program_keahlian' => $defaultMajor]);

        // 6. Populate deterministic NIS & program_keahlian for existing alumni_bekerjas rows without NIS
        $bekerjaRows = DB::table('alumni_bekerjas')->whereNull('nis')->orderBy('id')->get();
        foreach ($bekerjaRows as $index => $row) {
            $generatedNis = sprintf('NIS10%04d', $row->id);
            DB::table('alumni_bekerjas')
                ->where('id', $row->id)
                ->update([
                    'nis' => $generatedNis,
                    'program_keahlian' => $row->program_keahlian ?? $defaultMajor,
                ]);
        }

        // 7. Populate deterministic NIS & program_keahlian for existing alumni_wirausahas rows without NIS
        $wirausahaRows = DB::table('alumni_wirausahas')->whereNull('nis')->orderBy('id')->get();
        foreach ($wirausahaRows as $index => $row) {
            $generatedNis = sprintf('NIS20%04d', $row->id);
            DB::table('alumni_wirausahas')
                ->where('id', $row->id)
                ->update([
                    'nis' => $generatedNis,
                    'program_keahlian' => $row->program_keahlian ?? $defaultMajor,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tracer_studies', function (Blueprint $table) {
            $table->dropUnique(['nis']);
            $table->dropColumn(['nis', 'program_keahlian', 'kelas', 'no_wa']);
        });

        Schema::table('alumni_kuliah', function (Blueprint $table) {
            $table->dropColumn(['program_keahlian']);
        });

        Schema::table('alumni_bekerjas', function (Blueprint $table) {
            $table->dropUnique(['nis']);
            $table->dropColumn(['nis', 'program_keahlian']);
        });

        Schema::table('alumni_wirausahas', function (Blueprint $table) {
            $table->dropUnique(['nis']);
            $table->dropColumn(['nis', 'program_keahlian']);
        });
    }
};
