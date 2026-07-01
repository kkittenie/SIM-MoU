<?php

namespace Database\Seeders;

use App\Models\AlumniKuliah;
use Illuminate\Database\Seeder;

class AlumniKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_alumni' => 'Adi Wijaya', 'nis' => 'NIS000001', 'tahun_lulus' => 2022, 'universitas_id' => 1, 'program_studi' => 'Teknik Informatika', 'cara_masuk' => 'snbp', 'email_alumni' => 'adi.wijaya@email.com', 'nomor_telepon' => '+6281234567890', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Budi Santoso', 'nis' => 'NIS000002', 'tahun_lulus' => 2022, 'universitas_id' => 2, 'program_studi' => 'Sistem Informasi', 'cara_masuk' => 'utbk', 'email_alumni' => 'budi.santoso@email.com', 'nomor_telepon' => '+6281234567891', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Citra Dewi', 'nis' => 'NIS000003', 'tahun_lulus' => 2023, 'universitas_id' => 3, 'program_studi' => 'Teknik Elektro', 'cara_masuk' => 'utbk', 'email_alumni' => 'citra.dewi@email.com', 'nomor_telepon' => '+6281234567892', 'status_alumni' => 'lulus'],
            ['nama_alumni' => 'Doni Hermawan', 'nis' => 'NIS000004', 'tahun_lulus' => 2023, 'universitas_id' => 4, 'program_studi' => 'Bisnis Digital', 'cara_masuk' => 'ujian_masuk', 'email_alumni' => 'doni.hermawan@email.com', 'nomor_telepon' => '+6281234567893', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Eka Putri', 'nis' => 'NIS000005', 'tahun_lulus' => 2021, 'universitas_id' => 5, 'program_studi' => 'Akuntansi', 'cara_masuk' => 'snbp', 'email_alumni' => 'eka.putri@email.com', 'nomor_telepon' => '+6281234567894', 'status_alumni' => 'lulus'],
            ['nama_alumni' => 'Fajar Rahman', 'nis' => 'NIS000006', 'tahun_lulus' => 2022, 'universitas_id' => 6, 'program_studi' => 'Manajemen', 'cara_masuk' => 'beasiswa', 'email_alumni' => 'fajar.rahman@email.com', 'nomor_telepon' => '+6281234567895', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Gina Susanti', 'nis' => 'NIS000007', 'tahun_lulus' => 2023, 'universitas_id' => 1, 'program_studi' => 'Teknik Informatika', 'cara_masuk' => 'utbk', 'email_alumni' => 'gina.susanti@email.com', 'nomor_telepon' => '+6281234567896', 'status_alumni' => 'cuti'],
            ['nama_alumni' => 'Hendra Wijaksono', 'nis' => 'NIS000008', 'tahun_lulus' => 2022, 'universitas_id' => 2, 'program_studi' => 'Psikologi', 'cara_masuk' => 'transfer', 'email_alumni' => 'hendra.wijaksono@email.com', 'nomor_telepon' => '+6281234567897', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Indah Kusuma', 'nis' => 'NIS000009', 'tahun_lulus' => 2021, 'universitas_id' => 3, 'program_studi' => 'Hukum', 'cara_masuk' => 'ujian_masuk', 'email_alumni' => 'indah.kusuma@email.com', 'nomor_telepon' => '+6281234567898', 'status_alumni' => 'lulus'],
            ['nama_alumni' => 'Joko Sudargo', 'nis' => 'NIS000010', 'tahun_lulus' => 2023, 'universitas_id' => 4, 'program_studi' => 'Ekonomi', 'cara_masuk' => 'snbp', 'email_alumni' => 'joko.sudargo@email.com', 'nomor_telepon' => '+6281234567899', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Karina Sasha', 'nis' => 'NIS000011', 'tahun_lulus' => 2022, 'universitas_id' => 5, 'program_studi' => 'Teknik Sipil', 'cara_masuk' => 'utbk', 'email_alumni' => 'karina.sasha@email.com', 'nomor_telepon' => '+6281234567800', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Luthfi Rahmani', 'nis' => 'NIS000012', 'tahun_lulus' => 2023, 'universitas_id' => 6, 'program_studi' => 'Teknik Mesin', 'cara_masuk' => 'beasiswa', 'email_alumni' => 'luthfi.rahmani@email.com', 'nomor_telepon' => '+6281234567801', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Maya Sari', 'nis' => 'NIS000013', 'tahun_lulus' => 2021, 'universitas_id' => 1, 'program_studi' => 'Farmasi', 'cara_masuk' => 'utbk', 'email_alumni' => 'maya.sari@email.com', 'nomor_telepon' => '+6281234567802', 'status_alumni' => 'lulus'],
            ['nama_alumni' => 'Nanda Pratama', 'nis' => 'NIS000014', 'tahun_lulus' => 2022, 'universitas_id' => 2, 'program_studi' => 'Pendidikan Matematika', 'cara_masuk' => 'ujian_masuk', 'email_alumni' => 'nanda.pratama@email.com', 'nomor_telepon' => '+6281234567803', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Olivia Chen', 'nis' => 'NIS000015', 'tahun_lulus' => 2023, 'universitas_id' => 3, 'program_studi' => 'Pendidikan Bahasa Inggris', 'cara_masuk' => 'transfer', 'email_alumni' => 'olivia.chen@email.com', 'nomor_telepon' => '+6281234567804', 'status_alumni' => 'belum_terdata'],
            ['nama_alumni' => 'Panji Kusuma', 'nis' => 'NIS000016', 'tahun_lulus' => 2022, 'universitas_id' => 4, 'program_studi' => 'Teknik Informatika', 'cara_masuk' => 'snbp', 'email_alumni' => 'panji.kusuma@email.com', 'nomor_telepon' => '+6281234567805', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Qitra Amalina', 'nis' => 'NIS000017', 'tahun_lulus' => 2021, 'universitas_id' => 5, 'program_studi' => 'Sistem Informasi', 'cara_masuk' => 'lainnya', 'email_alumni' => 'qitra.amalina@email.com', 'nomor_telepon' => '+6281234567806', 'status_alumni' => 'lulus'],
            ['nama_alumni' => 'Ridho Aribowo', 'nis' => 'NIS000018', 'tahun_lulus' => 2023, 'universitas_id' => 6, 'program_studi' => 'Bisnis Digital', 'cara_masuk' => 'beasiswa', 'email_alumni' => 'ridho.aribowo@email.com', 'nomor_telepon' => '+6281234567807', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Siti Rahma', 'nis' => 'NIS000019', 'tahun_lulus' => 2022, 'universitas_id' => 1, 'program_studi' => 'Akuntansi', 'cara_masuk' => 'utbk', 'email_alumni' => 'siti.rahma@email.com', 'nomor_telepon' => '+6281234567808', 'status_alumni' => 'cuti'],
            ['nama_alumni' => 'Teguh Suryanto', 'nis' => 'NIS000020', 'tahun_lulus' => 2023, 'universitas_id' => 2, 'program_studi' => 'Manajemen', 'cara_masuk' => 'ujian_masuk', 'email_alumni' => 'teguh.suryanto@email.com', 'nomor_telepon' => '+6281234567809', 'status_alumni' => 'aktif'],
        ];

        foreach ($data as $item) {
            AlumniKuliah::firstOrCreate(
                ['nis' => $item['nis']],
                $item
            );
        }
    }
}
