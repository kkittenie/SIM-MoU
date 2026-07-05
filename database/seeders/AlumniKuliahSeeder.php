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

            // ══ Tahun 2024 ══
            ['nama_alumni' => 'Umar Faruq', 'nis' => 'NIS000021', 'tahun_lulus' => 2024, 'universitas_id' => 1, 'program_studi' => 'Teknik Informatika', 'cara_masuk' => 'snbp', 'email_alumni' => 'umar.faruq@email.com', 'nomor_telepon' => '+6281234567810', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Vina Marlina', 'nis' => 'NIS000022', 'tahun_lulus' => 2024, 'universitas_id' => 10, 'program_studi' => 'Computer Science', 'cara_masuk' => 'beasiswa', 'email_alumni' => 'vina.marlina@email.com', 'nomor_telepon' => '+6281234567811', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Wahyu Pratama', 'nis' => 'NIS000023', 'tahun_lulus' => 2024, 'universitas_id' => 3, 'program_studi' => 'Teknik Mesin', 'cara_masuk' => 'utbk', 'email_alumni' => 'wahyu.pratama@email.com', 'nomor_telepon' => '+6281234567812', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Xena Amalia', 'nis' => 'NIS000024', 'tahun_lulus' => 2024, 'universitas_id' => 11, 'program_studi' => 'Business Administration', 'cara_masuk' => 'beasiswa', 'email_alumni' => 'xena.amalia@email.com', 'nomor_telepon' => '+6281234567813', 'status_alumni' => 'aktif'],

            // ══ Tahun 2025 ══
            ['nama_alumni' => 'Yusuf Habibi', 'nis' => 'NIS000025', 'tahun_lulus' => 2025, 'universitas_id' => 5, 'program_studi' => 'Kedokteran', 'cara_masuk' => 'utbk', 'email_alumni' => 'yusuf.habibi@email.com', 'nomor_telepon' => '+6281234567814', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Zahra Putri', 'nis' => 'NIS000026', 'tahun_lulus' => 2025, 'universitas_id' => 12, 'program_studi' => 'Mechanical Engineering', 'cara_masuk' => 'beasiswa', 'email_alumni' => 'zahra.putri@email.com', 'nomor_telepon' => '+6281234567815', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Andika Putra', 'nis' => 'NIS000027', 'tahun_lulus' => 2025, 'universitas_id' => 7, 'program_studi' => 'Teknik Informatika', 'cara_masuk' => 'snbp', 'email_alumni' => 'andika.putra@email.com', 'nomor_telepon' => '+6281234567816', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Bella Octavia', 'nis' => 'NIS000028', 'tahun_lulus' => 2025, 'universitas_id' => 13, 'program_studi' => 'International Relations', 'cara_masuk' => 'beasiswa', 'email_alumni' => 'bella.octavia@email.com', 'nomor_telepon' => '+6281234567817', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Dimas Ardianto', 'nis' => 'NIS000029', 'tahun_lulus' => 2025, 'universitas_id' => 2, 'program_studi' => 'Teknik Elektro', 'cara_masuk' => 'utbk', 'email_alumni' => 'dimas.ardianto@email.com', 'nomor_telepon' => '+6281234567818', 'status_alumni' => 'aktif'],

            // ══ Tahun 2026 (Tahun Ajaran Aktif) ══
            ['nama_alumni' => 'Elsa Maharani', 'nis' => 'NIS000030', 'tahun_lulus' => 2026, 'universitas_id' => 1, 'program_studi' => 'Teknik Informatika', 'cara_masuk' => 'snbp', 'email_alumni' => 'elsa.maharani@email.com', 'nomor_telepon' => '+6281234567819', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Faisal Akbar', 'nis' => 'NIS000031', 'tahun_lulus' => 2026, 'universitas_id' => 2, 'program_studi' => 'Sistem Informasi', 'cara_masuk' => 'utbk', 'email_alumni' => 'faisal.akbar@email.com', 'nomor_telepon' => '+6281234567820', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Galuh Pramesti', 'nis' => 'NIS000032', 'tahun_lulus' => 2026, 'universitas_id' => 3, 'program_studi' => 'Teknik Sipil', 'cara_masuk' => 'utbk', 'email_alumni' => 'galuh.pramesti@email.com', 'nomor_telepon' => '+6281234567821', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Hafiz Ramadhan', 'nis' => 'NIS000033', 'tahun_lulus' => 2026, 'universitas_id' => 4, 'program_studi' => 'Ekonomi', 'cara_masuk' => 'ujian_masuk', 'email_alumni' => 'hafiz.ramadhan@email.com', 'nomor_telepon' => '+6281234567822', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Intan Permata', 'nis' => 'NIS000034', 'tahun_lulus' => 2026, 'universitas_id' => 5, 'program_studi' => 'Farmasi', 'cara_masuk' => 'snbp', 'email_alumni' => 'intan.permata@email.com', 'nomor_telepon' => '+6281234567823', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Jihan Safitri', 'nis' => 'NIS000035', 'tahun_lulus' => 2026, 'universitas_id' => 6, 'program_studi' => 'Desain Komunikasi Visual', 'cara_masuk' => 'ujian_masuk', 'email_alumni' => 'jihan.safitri@email.com', 'nomor_telepon' => '+6281234567824', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Kevin Anggara', 'nis' => 'NIS000036', 'tahun_lulus' => 2026, 'universitas_id' => 7, 'program_studi' => 'Teknik Informatika', 'cara_masuk' => 'utbk', 'email_alumni' => 'kevin.anggara@email.com', 'nomor_telepon' => '+6281234567825', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Laras Wulandari', 'nis' => 'NIS000037', 'tahun_lulus' => 2026, 'universitas_id' => 8, 'program_studi' => 'Hukum', 'cara_masuk' => 'snbp', 'email_alumni' => 'laras.wulandari@email.com', 'nomor_telepon' => '+6281234567826', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Maulana Rizki', 'nis' => 'NIS000038', 'tahun_lulus' => 2026, 'universitas_id' => 9, 'program_studi' => 'Psikologi', 'cara_masuk' => 'utbk', 'email_alumni' => 'maulana.rizki@email.com', 'nomor_telepon' => '+6281234567827', 'status_alumni' => 'cuti'],
            ['nama_alumni' => 'Nabila Azzahra', 'nis' => 'NIS000039', 'tahun_lulus' => 2026, 'universitas_id' => 1, 'program_studi' => 'Manajemen', 'cara_masuk' => 'transfer', 'email_alumni' => 'nabila.azzahra@email.com', 'nomor_telepon' => '+6281234567828', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Oscar Wijaya', 'nis' => 'NIS000040', 'tahun_lulus' => 2026, 'universitas_id' => 3, 'program_studi' => 'Teknik Mesin', 'cara_masuk' => 'utbk', 'email_alumni' => 'oscar.wijaya@email.com', 'nomor_telepon' => '+6281234567829', 'status_alumni' => 'aktif'],

            // ══ Tahun 2026 — Luar Negeri ══
            ['nama_alumni' => 'Putri Handayani', 'nis' => 'NIS000041', 'tahun_lulus' => 2026, 'universitas_id' => 10, 'program_studi' => 'Computer Science', 'cara_masuk' => 'beasiswa', 'email_alumni' => 'putri.handayani@email.com', 'nomor_telepon' => '+6281234567830', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Rizky Maulana', 'nis' => 'NIS000042', 'tahun_lulus' => 2026, 'universitas_id' => 11, 'program_studi' => 'Data Science', 'cara_masuk' => 'beasiswa', 'email_alumni' => 'rizky.maulana@email.com', 'nomor_telepon' => '+6281234567831', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Salsabila Nurul', 'nis' => 'NIS000043', 'tahun_lulus' => 2026, 'universitas_id' => 12, 'program_studi' => 'Mechanical Engineering', 'cara_masuk' => 'beasiswa', 'email_alumni' => 'salsabila.nurul@email.com', 'nomor_telepon' => '+6281234567832', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Taufik Hidayat', 'nis' => 'NIS000044', 'tahun_lulus' => 2026, 'universitas_id' => 13, 'program_studi' => 'International Business', 'cara_masuk' => 'beasiswa', 'email_alumni' => 'taufik.hidayat@email.com', 'nomor_telepon' => '+6281234567833', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Ulfa Rahmawati', 'nis' => 'NIS000045', 'tahun_lulus' => 2026, 'universitas_id' => 14, 'program_studi' => 'Biotechnology', 'cara_masuk' => 'beasiswa', 'email_alumni' => 'ulfa.rahmawati@email.com', 'nomor_telepon' => '+6281234567834', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Viktor Tanaka', 'nis' => 'NIS000046', 'tahun_lulus' => 2026, 'universitas_id' => 10, 'program_studi' => 'Electrical Engineering', 'cara_masuk' => 'beasiswa', 'email_alumni' => 'viktor.tanaka@email.com', 'nomor_telepon' => '+6281234567835', 'status_alumni' => 'aktif'],
            ['nama_alumni' => 'Winda Lestari', 'nis' => 'NIS000047', 'tahun_lulus' => 2026, 'universitas_id' => 12, 'program_studi' => 'Architecture', 'cara_masuk' => 'beasiswa', 'email_alumni' => 'winda.lestari@email.com', 'nomor_telepon' => '+6281234567836', 'status_alumni' => 'aktif'],
        ];

        foreach ($data as $item) {
            AlumniKuliah::firstOrCreate(
                ['nis' => $item['nis']],
                $item
            );
        }
    }
}
