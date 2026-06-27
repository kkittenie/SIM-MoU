<?php

namespace Database\Seeders;

use App\Models\TracerKuliah;
use App\Models\AlumniKuliah;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TracerKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $alumni = AlumniKuliah::all();

        $testimoniList = [
            'Sangat bersyukur bisa melanjutkan pendidikan di universitas ternama. Terima kasih kepada sekolah.',
            'Pengalaman di sekolah sangat berharga untuk kesuksesan kuliah saya sekarang.',
            'Sekolah telah mempersiapkan saya dengan baik untuk menghadapi perkuliahan.',
            'Banyak pembelajaran berharga yang saya dapatkan dari sekolah.',
            'Rekan-rekan sekolah sangat membantu dalam perjalanan akademik saya.',
            'Guru-guru di sekolah telah memberikan motivasi dan bimbingan terbaik.',
            'Saya merekomendasikan sekolah ini untuk calon siswa yang bersemangat.',
            'Masa sekolah adalah waktu terbaik dalam hidup saya.',
            'Fasilitas dan kurikulum sekolah sangat mendukung persiapan kuliah.',
            'Terima kasih telah menjadi bagian dari perjalanan pendidikan saya.',
        ];

        foreach ($alumni->take(20) as $alumnus) {
            TracerKuliah::firstOrCreate(
                ['alumni_kuliah_id' => $alumnus->id],
                [
                    'status_kuliah' => ['aktif', 'lulus', 'cuti', 'putus'][array_rand(['aktif', 'lulus', 'cuti', 'putus'])],
                    'kampus_tujuan' => $alumnus->universitas?->nama_universitas ?? 'Universitas XYZ',
                    'program_studi' => $alumnus->program_studi,
                    'detail_status' => 'Sedang menjalani perkuliahan semester ' . rand(1, 8),
                    'testimoni' => $testimoniList[array_rand($testimoniList)],
                    'tanggal_update' => Carbon::now()->subDays(rand(0, 60)),
                ]
            );
        }
    }
}
