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
        // 1. Add program_keahlian_id to users for Admin Jurusan department assignment
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'program_keahlian_id')) {
                $table->foreignId('program_keahlian_id')
                    ->nullable()
                    ->after('role')
                    ->constrained('program_keahlians')
                    ->nullOnDelete();
            }
        });

        // 2. Create student_nis table for registered student NIS records
        if (!Schema::hasTable('student_nis')) {
            Schema::create('student_nis', function (Blueprint $table) {
                $table->id();
                $table->string('nis')->unique();
                $table->string('nama_siswa');
                $table->foreignId('program_keahlian_id')
                    ->nullable()
                    ->constrained('program_keahlians')
                    ->nullOnDelete();
                $table->integer('tahun_lulus')->nullable();
                $table->string('kelas')->nullable();
                $table->foreignId('created_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
                $table->timestamps();
            });
        }

        // 3. Ensure default ProgramKeahlian exists and assign existing admin_jurusan users
        $firstProgram = DB::table('program_keahlians')->orderBy('id')->first();
        if ($firstProgram) {
            DB::table('users')
                ->where('role', 'admin_jurusan')
                ->whereNull('program_keahlian_id')
                ->update(['program_keahlian_id' => $firstProgram->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_nis');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'program_keahlian_id')) {
                $table->dropConstrainedForeignId('program_keahlian_id');
            }
        });
    }
};
