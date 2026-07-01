<?php

namespace Database\Seeders;

use App\Models\PerusahaanMitra;
use App\Models\AlumniBekerja;
use App\Models\LowonganKerja;
use App\Models\TracerStudy;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BkkDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Perusahaan Mitra
        $mitras = [
            [
                'nama_perusahaan' => 'PT Teknologi Nusantara',
                'bidang_industri' => 'Teknologi',
                'alamat' => 'Jl. Sudirman No. 45, Jakarta Selatan',
                'email' => 'hrd@teknologinusantara.co.id',
                'nomor_telepon' => '021-5551234',
                'pic' => 'Budi Santoso',
                'website' => 'https://teknologinusantara.co.id',
                'deskripsi' => 'Perusahaan pengembangan perangkat lunak terkemuka di Indonesia.',
                'status_aktif' => 'Aktif',
            ],
            [
                'nama_perusahaan' => 'PT Astra Otoparts Tbk',
                'bidang_industri' => 'Manufaktur',
                'alamat' => 'Jl. Pegangsaan Dua No. 3, Jakarta Utara',
                'email' => 'recruitment@astra-otoparts.co.id',
                'nomor_telepon' => '021-4603550',
                'pic' => 'Hendra Wijaya',
                'website' => 'https://www.component.astra.co.id',
                'deskripsi' => 'Perusahaan manufaktur suku cadang otomotif terbesar di Indonesia.',
                'status_aktif' => 'Aktif',
            ],
            [
                'nama_perusahaan' => 'PT Bank Mandiri (Persero) Tbk',
                'bidang_industri' => 'Keuangan',
                'alamat' => 'Jl. Jenderal Gatot Subroto Kav. 36-38, Jakarta Selatan',
                'email' => 'career@bankmandiri.co.id',
                'nomor_telepon' => '021-5265000',
                'pic' => 'Siti Aminah',
                'website' => 'https://bankmandiri.co.id',
                'deskripsi' => 'Salah satu bank BUMN terbesar di Indonesia.',
                'status_aktif' => 'Aktif',
            ],
            [
                'nama_perusahaan' => 'PT Indofood Sukses Makmur Tbk',
                'bidang_industri' => 'Manufaktur',
                'alamat' => 'Jl. Sudirman Kav. 76-78, Jakarta Selatan',
                'email' => 'career@indofood.co.id',
                'nomor_telepon' => '021-57958800',
                'pic' => 'Rian Hidayat',
                'website' => 'https://indofood.com',
                'deskripsi' => 'Perusahaan produsen makanan olahan terkemuka di Indonesia.',
                'status_aktif' => 'Aktif',
            ],
            [
                'nama_perusahaan' => 'PT Kalbe Farma Tbk',
                'bidang_industri' => 'Kesehatan',
                'alamat' => 'Jl. Letjen Suprapto Kav. 4, Jakarta Pusat',
                'email' => 'hrd@kalbe.co.id',
                'nomor_telepon' => '021-42873888',
                'pic' => 'Dr. Lisa Anggraini',
                'website' => 'https://kalbe.co.id',
                'deskripsi' => 'Perusahaan farmasi dan produk kesehatan multinasional.',
                'status_aktif' => 'Aktif',
            ],
            [
                'nama_perusahaan' => 'PT Telekomunikasi Selular (Telkomsel)',
                'bidang_industri' => 'Telekomunikasi',
                'alamat' => 'Telkomsel Smart Office, Jl. Gatot Subroto, Jakarta Selatan',
                'email' => 'jobs@telkomsel.co.id',
                'nomor_telepon' => '021-5240811',
                'pic' => 'Agus Salim',
                'website' => 'https://telkomsel.com',
                'deskripsi' => 'Operator telekomunikasi seluler terbesar di Indonesia.',
                'status_aktif' => 'Aktif',
            ],
            [
                'nama_perusahaan' => 'CV Media Kreatif',
                'bidang_industri' => 'Jasa',
                'alamat' => 'Jl. Gejayan No. 12, Sleman, Yogyakarta',
                'email' => 'hello@mediakreatif.com',
                'nomor_telepon' => '0274-555987',
                'pic' => 'Dewi Lestari',
                'website' => 'https://mediakreatif.com',
                'deskripsi' => 'Agensi kreatif yang fokus pada branding, digital marketing, dan video production.',
                'status_aktif' => 'Aktif',
            ],
        ];

        $createdMitras = [];
        foreach ($mitras as $mitra) {
            $createdMitras[] = PerusahaanMitra::create($mitra);
        }

        // 2. Seed Alumni Bekerja
        $alumniData = [
            ['nama' => 'Aditya Pratama', 'tahun_lulus' => 2023, 'jabatan' => 'Junior Web Developer', 'mitra_index' => 0, 'gaji' => 5500000, 'status' => 'Freelance', 'tgl' => '2023-08-15'],
            ['nama' => 'Bambang Pamungkas', 'tahun_lulus' => 2022, 'jabatan' => 'Operator Produksi', 'mitra_index' => 1, 'gaji' => 4800000, 'status' => 'Tetap', 'tgl' => '2022-09-01'],
            ['nama' => 'Citra Kirana', 'tahun_lulus' => 2024, 'jabatan' => 'Customer Relations Officer', 'mitra_index' => 2, 'gaji' => 6000000, 'status' => 'Kontrak', 'tgl' => '2024-03-10', 'lokasi' => 'Luar Negeri'],
            ['nama' => 'Dian Sastrowardoyo', 'tahun_lulus' => 2023, 'jabatan' => 'Quality Control Staff', 'mitra_index' => 3, 'gaji' => 5000000, 'status' => 'Tetap', 'tgl' => '2023-11-20'],
            ['nama' => 'Eko Prasetyo', 'tahun_lulus' => 2023, 'jabatan' => 'Medical Representative', 'mitra_index' => 4, 'gaji' => 5800000, 'status' => 'Tetap', 'tgl' => '2024-01-05'],
            ['nama' => 'Fajar Alfian', 'tahun_lulus' => 2024, 'jabatan' => 'Network Engineer', 'mitra_index' => 5, 'gaji' => 6500000, 'status' => 'Kontrak', 'tgl' => '2024-07-01', 'lokasi' => 'Luar Negeri'],
            ['nama' => 'Gita Gutawa', 'tahun_lulus' => 2025, 'jabatan' => 'Graphic Designer', 'mitra_index' => 6, 'gaji' => 4500000, 'status' => 'Magang', 'tgl' => '2025-02-15'],
            
            // Manual entries (not in Mitra list)
            ['nama' => 'Hadi Wibowo', 'tahun_lulus' => 2022, 'jabatan' => 'Mobile Developer', 'perusahaan_manual' => 'PT Tokopedia', 'industri' => 'Teknologi', 'gaji' => 7500000, 'status' => 'Tetap', 'tgl' => '2022-10-10'],
            ['nama' => 'Indah Permatasari', 'tahun_lulus' => 2023, 'jabatan' => 'Admin Gudang', 'perusahaan_manual' => 'Shopee Indonesia', 'industri' => 'Teknologi', 'gaji' => 5200000, 'status' => 'Kontrak', 'tgl' => '2023-09-05'],
            ['nama' => 'Joko Widodo', 'tahun_lulus' => 2022, 'jabatan' => 'Teknisi Maintenance', 'perusahaan_manual' => 'PT Toyota Astra Motor', 'industri' => 'Otomotif', 'gaji' => 5100000, 'status' => 'Tetap', 'tgl' => '2023-02-28'],
            
            ['nama' => 'Kiki Amalia', 'tahun_lulus' => 2024, 'jabatan' => 'Software Engineer', 'mitra_index' => 0, 'gaji' => 7000000, 'status' => 'Tetap', 'tgl' => '2024-05-15'],
            ['nama' => 'Lutfi Hakim', 'tahun_lulus' => 2024, 'jabatan' => 'Operator Assembling', 'mitra_index' => 1, 'gaji' => 4850000, 'status' => 'Tetap', 'tgl' => '2024-04-10'],
            ['nama' => 'Mega Utami', 'tahun_lulus' => 2023, 'jabatan' => 'Telesales Representative', 'mitra_index' => 2, 'gaji' => 5500000, 'status' => 'Kontrak', 'tgl' => '2023-07-20'],
            ['nama' => 'Naufal Rizqi', 'tahun_lulus' => 2023, 'jabatan' => 'Staff Logistik', 'mitra_index' => 3, 'gaji' => 4900000, 'status' => 'Tetap', 'tgl' => '2023-12-01'],
            ['nama' => 'Oki Setiana', 'tahun_lulus' => 2025, 'jabatan' => 'Asisten Apoteker', 'mitra_index' => 4, 'gaji' => 4600000, 'status' => 'Magang', 'tgl' => '2025-03-01'],
            ['nama' => 'Putri Tanjung', 'tahun_lulus' => 2024, 'jabatan' => 'Social Media Specialist', 'mitra_index' => 6, 'gaji' => 4800000, 'status' => 'Tetap', 'tgl' => '2024-08-01'],
            
            ['nama' => 'Rian Ardianto', 'tahun_lulus' => 2023, 'jabatan' => 'System Administrator', 'mitra_index' => 0, 'gaji' => 6200000, 'status' => 'Tetap', 'tgl' => '2023-09-01'],
            ['nama' => 'Sinta Nuriyah', 'tahun_lulus' => 2022, 'jabatan' => 'Staff Admin HRD', 'mitra_index' => 1, 'gaji' => 5000000, 'status' => 'Tetap', 'tgl' => '2022-11-01'],
            ['nama' => 'Taufik Hidayat', 'tahun_lulus' => 2024, 'jabatan' => 'Teller', 'mitra_index' => 2, 'gaji' => 5200000, 'status' => 'Tetap', 'tgl' => '2024-06-15'],
            ['nama' => 'Utami Dewi', 'tahun_lulus' => 2025, 'jabatan' => 'UI/UX Designer Apprentice', 'mitra_index' => 0, 'gaji' => 3500000, 'status' => 'Magang', 'tgl' => '2025-04-01'],
            ['nama' => 'Vicky Prasetyo', 'tahun_lulus' => 2024, 'jabatan' => 'Production Planner', 'mitra_index' => 1, 'gaji' => 5200000, 'status' => 'Kontrak', 'tgl' => '2024-09-10'],
            ['nama' => 'Wulan Guritno', 'tahun_lulus' => 2023, 'jabatan' => 'Staff Laboratorium', 'mitra_index' => 4, 'gaji' => 5700000, 'status' => 'Tetap', 'tgl' => '2023-10-15'],
            ['nama' => 'Yuda Ramadhan', 'tahun_lulus' => 2024, 'jabatan' => 'Data Analyst', 'perusahaan_manual' => 'Gojek Indonesia', 'industri' => 'Teknologi', 'gaji' => 7800000, 'status' => 'Freelance', 'tgl' => '2024-10-01', 'lokasi' => 'Luar Negeri'],
            ['nama' => 'Zahra Nabila', 'tahun_lulus' => 2023, 'jabatan' => 'Staff Administrasi', 'perusahaan_manual' => 'PT Kereta Api Indonesia', 'industri' => 'Jasa', 'gaji' => 5300000, 'status' => 'Tetap', 'tgl' => '2023-08-01'],
        ];

        foreach ($alumniData as $alumni) {
            $data = [
                'nama_alumni' => $alumni['nama'],
                'tahun_lulus' => $alumni['tahun_lulus'],
                'jabatan' => $alumni['jabatan'],
                'tanggal_masuk' => $alumni['tgl'],
                'gaji' => $alumni['gaji'],
                'status_pekerjaan' => $alumni['status'],
                'lokasi_kerja' => $alumni['lokasi'] ?? 'Dalam Negeri',
            ];

            if (isset($alumni['mitra_index'])) {
                $mitra = $createdMitras[$alumni['mitra_index']];
                $data['perusahaan_mitra_id'] = $mitra->id;
                $data['perusahaan_nama'] = $mitra->nama_perusahaan;
                $data['bidang_industri'] = $mitra->bidang_industri;
            } else {
                $data['perusahaan_nama'] = $alumni['perusahaan_manual'];
                $data['bidang_industri'] = $alumni['industri'];
            }

            AlumniBekerja::create($data);
        }

        // 3. Seed Lowongan Kerja
        $jobs = [
            [
                'judul' => 'Frontend Web Developer (React)',
                'mitra_index' => 0,
                'posisi' => 'Junior Developer',
                'persyaratan' => "1. Lulusan SMK Jurusan RPL / TKJ\n2. Menguasai HTML, CSS, JS\n3. Memahami framework ReactJS adalah nilai tambah\n4. Mampu bekerja sama dalam tim",
                'deskripsi' => 'Membangun dan mengembangkan tampilan website interaktif untuk klien perusahaan.',
                'gaji' => 'Rp 5.000.000 - Rp 6.500.000',
                'tanggal_tutup' => Carbon::now()->addDays(20)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'judul' => 'Operator Mesin Bubut & CNC',
                'mitra_index' => 1,
                'posisi' => 'Operator Produksi',
                'persyaratan' => "1. Lulusan SMK Jurusan Teknik Mesin\n2. Memahami pembacaan gambar teknik\n3. Bersedia bekerja dengan sistem shift\n4. Memiliki disiplin tinggi",
                'deskripsi' => 'Mengoperasikan mesin CNC untuk memproduksi suku cadang otomotif sesuai standar presisi.',
                'gaji' => 'Ukr + Lembur',
                'tanggal_tutup' => Carbon::now()->addDays(10)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'judul' => 'Customer Service Officer',
                'mitra_index' => 2,
                'posisi' => 'Staff Frontliner',
                'persyaratan' => "1. Lulusan SMK/SMA semua jurusan\n2. Berpenampilan menarik dan komunikatif\n3. Ramah dan memiliki problem solving yang baik\n4. Tinggi badan minimal 160cm (Wanita) / 165cm (Pria)",
                'deskripsi' => 'Melayani kebutuhan nasabah bank, menangani keluhan, dan memberikan info produk keuangan.',
                'gaji' => 'Rp 4.700.000',
                'tanggal_tutup' => Carbon::now()->addDays(15)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'judul' => 'Staff Quality Control',
                'mitra_index' => 3,
                'posisi' => 'Staff QC',
                'persyaratan' => "1. Lulusan SMK Jurusan Kimia / Kimia Industri\n2. Teliti dan menyukai detail\n3. Memahami konsep GMP dan K3\n4. Sehat jasmani dan rohani",
                'deskripsi' => 'Melakukan pengujian kualitas berkala pada bahan baku dan produk makanan olahan.',
                'gaji' => 'Rp 4.900.000',
                'tanggal_tutup' => Carbon::now()->subDays(5)->toDateString(), // Closed job
                'status' => 'Tutup',
            ],
            [
                'judul' => 'Graphic Designer & Video Editor',
                'mitra_index' => 6,
                'posisi' => 'Kreatif Staff',
                'persyaratan' => "1. Lulusan SMK Jurusan Multimedia / DKV\n2. Menguasai Adobe Photoshop, Illustrator, Premiere Pro\n3. Melampirkan portofolio karya terbaru\n4. Mampu beradaptasi dengan tren desain terkini",
                'deskripsi' => 'Membuat konten desain grafis dan mengedit video promosi untuk kebutuhan digital marketing.',
                'gaji' => 'Rp 4.000.000 - Rp 5.000.000',
                'tanggal_tutup' => Carbon::now()->addDays(30)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'judul' => 'IT Support & Helpdesk',
                'perusahaan_manual' => 'PT Graha Computindo',
                'posisi' => 'Staff IT',
                'persyaratan' => "1. Lulusan SMK Jurusan TKJ\n2. Memahami instalasi OS, jaringan LAN/WLAN, troubleshooting hardware\n3. Cekatan dan ramah\n4. Memiliki kendaraan sendiri",
                'deskripsi' => 'Melakukan maintenance berkala pada hardware/software serta menangani kendala IT pengguna.',
                'gaji' => 'Rp 4.200.000',
                'tanggal_tutup' => Carbon::now()->addDays(5)->toDateString(),
                'status' => 'Aktif',
            ],
        ];

        foreach ($jobs as $job) {
            $data = [
                'judul' => $job['judul'],
                'posisi' => $job['posisi'],
                'persyaratan' => $job['persyaratan'],
                'deskripsi' => $job['deskripsi'],
                'gaji' => $job['gaji'],
                'tanggal_tutup' => $job['tanggal_tutup'],
                'status' => $job['status'],
            ];

            if (isset($job['mitra_index'])) {
                $mitra = $createdMitras[$job['mitra_index']];
                $data['perusahaan_mitra_id'] = $mitra->id;
                $data['perusahaan_nama'] = $mitra->nama_perusahaan;
            } else {
                $data['perusahaan_nama'] = $job['perusahaan_manual'];
            }

            LowonganKerja::create($data);
        }

        // 4. Seed Tracer Study (Total: 40 entries, with target distribution)
        // Bekerja: 24 (60%), Kuliah: 6 (15%), Wirausaha: 6 (15%), Mencari Kerja: 4 (10%)
        $tracers = [
            // Bekerja (24)
            ['nama' => 'Aditya Pratama', 'tahun' => 2023, 'status' => 'Bekerja', 'detail' => 'PT Teknologi Nusantara (Junior Web Developer)', 'testi' => 'Sekolah sangat membimbing saya melalui program penyaluran kerja BKK!'],
            ['nama' => 'Bambang Pamungkas', 'tahun' => 2022, 'status' => 'Bekerja', 'detail' => 'PT Astra Otoparts Tbk (Operator Produksi)', 'testi' => 'Pelajaran praktik kelistrikan mesin sangat terpakai di pekerjaan.'],
            ['nama' => 'Citra Kirana', 'tahun' => 2024, 'status' => 'Bekerja', 'detail' => 'PT Bank Mandiri (Customer Relations Officer)', 'testi' => 'Alhamdulillah langsung disalurkan kerja setelah lulus.'],
            ['nama' => 'Dian Sastrowardoyo', 'tahun' => 2023, 'status' => 'Bekerja', 'detail' => 'PT Indofood Sukses Makmur Tbk (Quality Control Staff)', 'testi' => 'Ilmu kimia terpakai di laboratorium QC Indofood.'],
            ['nama' => 'Eko Prasetyo', 'tahun' => 2023, 'status' => 'Bekerja', 'detail' => 'PT Kalbe Farma Tbk (Medical Representative)', 'testi' => 'Penyaluran BKK sangat efisien dan transparan.'],
            ['nama' => 'Fajar Alfian', 'tahun' => 2024, 'status' => 'Bekerja', 'detail' => 'PT Telkomsel (Network Engineer)', 'testi' => 'Praktik jaringan TKJ sangat relevan di lapangan.'],
            ['nama' => 'Gita Gutawa', 'tahun' => 2025, 'status' => 'Bekerja', 'detail' => 'CV Media Kreatif (Graphic Designer)', 'testi' => 'Terima kasih BKK atas lowongan magang yang keren.'],
            ['nama' => 'Hadi Wibowo', 'tahun' => 2022, 'status' => 'Bekerja', 'detail' => 'PT Tokopedia (Mobile Developer)', 'testi' => 'Materi pemrograman dari sekolah membantu karir saya.'],
            ['nama' => 'Indah Permatasari', 'tahun' => 2023, 'status' => 'Bekerja', 'detail' => 'Shopee Indonesia (Admin Gudang)', 'testi' => 'Sistem BKK memudahkan alumni mencari kerja.'],
            ['nama' => 'Joko Widodo', 'tahun' => 2022, 'status' => 'Bekerja', 'detail' => 'PT Toyota Astra Motor (Teknisi Maintenance)', 'testi' => 'Terima kasih program link-and-match SMK.'],
            ['nama' => 'Kiki Amalia', 'tahun' => 2024, 'status' => 'Bekerja', 'detail' => 'PT Teknologi Nusantara (Software Engineer)', 'testi' => 'Sukses selalu BKK sekolah kita!'],
            ['nama' => 'Lutfi Hakim', 'tahun' => 2024, 'status' => 'Bekerja', 'detail' => 'PT Astra Otoparts Tbk (Operator Assembling)', 'testi' => 'Disiplin semi-militer sekolah melatih mental saya di pabrik.'],
            ['nama' => 'Mega Utami', 'tahun' => 2023, 'status' => 'Bekerja', 'detail' => 'PT Bank Mandiri (Telesales Representative)', 'testi' => 'BKK sangat responsif menyalurkan info lowongan.'],
            ['nama' => 'Naufal Rizqi', 'tahun' => 2023, 'status' => 'Bekerja', 'detail' => 'PT Indofood Sukses Makmur Tbk (Staff Logistik)', 'testi' => 'Kerja sesuai passion logistik.'],
            ['nama' => 'Oki Setiana', 'tahun' => 2025, 'status' => 'Bekerja', 'detail' => 'PT Kalbe Farma Tbk (Asisten Apoteker)', 'testi' => 'Bimbingan karir sekolah sangat membantu persiapan wawancara.'],
            ['nama' => 'Putri Tanjung', 'tahun' => 2024, 'status' => 'Bekerja', 'detail' => 'CV Media Kreatif (Social Media Specialist)', 'testi' => 'Terima kasih, sangat bangga jadi alumni.'],
            ['nama' => 'Rian Ardianto', 'tahun' => 2023, 'status' => 'Bekerja', 'detail' => 'PT Teknologi Nusantara (System Administrator)', 'testi' => 'BKK membantu menjembatani industri dan alumni.'],
            ['nama' => 'Sinta Nuriyah', 'tahun' => 2022, 'status' => 'Bekerja', 'detail' => 'PT Astra Otoparts Tbk (Staff Admin HRD)', 'testi' => 'Sangat merekomendasikan BKK sekolah ini.'],
            ['nama' => 'Taufik Hidayat', 'tahun' => 2024, 'status' => 'Bekerja', 'detail' => 'PT Bank Mandiri (Teller)', 'testi' => 'Kerapian dan komunikasi yang diajarkan di sekolah sangat berguna.'],
            ['nama' => 'Utami Dewi', 'tahun' => 2025, 'status' => 'Bekerja', 'detail' => 'PT Teknologi Nusantara (UI/UX Designer Apprentice)', 'testi' => 'Senang bisa magang di tempat impian.'],
            ['nama' => 'Vicky Prasetyo', 'tahun' => 2024, 'status' => 'Bekerja', 'detail' => 'PT Astra Otoparts Tbk (Production Planner)', 'testi' => 'BKK sangat peduli dengan penyerapan kerja lulusan.'],
            ['nama' => 'Wulan Guritno', 'tahun' => 2023, 'status' => 'Bekerja', 'detail' => 'PT Kalbe Farma Tbk (Staff Laboratorium)', 'testi' => 'Lingkungan kerja nyaman, berkat info valid dari BKK.'],
            ['nama' => 'Yuda Ramadhan', 'tahun' => 2024, 'status' => 'Bekerja', 'detail' => 'Gojek Indonesia (Data Analyst)', 'testi' => 'Matematika terapan sangat berguna di dunia data analytics.'],
            ['nama' => 'Zahra Nabila', 'tahun' => 2023, 'status' => 'Bekerja', 'detail' => 'PT Kereta Api Indonesia (Staff Administrasi)', 'testi' => 'Kerja di BUMN impian berkat rekomendasi BKK.'],

            // Kuliah (6)
            ['nama' => 'Ahmad Dahlan', 'tahun' => 2024, 'status' => 'Kuliah', 'detail' => 'Universitas Indonesia (Teknik Informatika)', 'testi' => 'Memilih lanjut kuliah untuk memperdalam riset komputer.'],
            ['nama' => 'Bella Safira', 'tahun' => 2023, 'status' => 'Kuliah', 'detail' => 'Institut Teknologi Bandung (Teknik Elektro)', 'testi' => 'Ilmu dasar kelistrikan SMK menjadi bekal luar biasa di ITB.'],
            ['nama' => 'Candra Wijaya', 'tahun' => 2024, 'status' => 'Kuliah', 'detail' => 'Universitas Gadjah Mada (Teknik Mesin)', 'testi' => 'Ingin menjadi engineer profesional lewat jalur akademis.'],
            ['nama' => 'Dwi Andhika', 'tahun' => 2023, 'status' => 'Kuliah', 'detail' => 'Universitas Sebelas Maret (Pendidikan Teknik)', 'testi' => 'Cita-cita menjadi guru produktif di SMK.'],
            ['nama' => 'Elma Theana', 'tahun' => 2024, 'status' => 'Kuliah', 'detail' => 'Politeknik Negeri Jakarta (Akuntansi)', 'testi' => 'Melanjutkan ke politeknik untuk mempertahankan fokus praktik.'],
            ['nama' => 'Ferry Salim', 'tahun' => 2025, 'status' => 'Kuliah', 'detail' => 'Politeknik Negeri Bandung (Teknik Sipil)', 'testi' => 'Lanjut kuliah D4 untuk jenjang karir konstruksi.'],

            // Wirausaha (6)
            ['nama' => 'Ginanjar 4 Sekawan', 'tahun' => 2022, 'status' => 'Wirausaha', 'detail' => 'Ginanjar Digital Printing', 'testi' => 'Membuka usaha percetakan digital berbekal hobi desain di SMK.'],
            ['nama' => 'Helmy Yahya', 'tahun' => 2023, 'status' => 'Wirausaha', 'detail' => 'HY Creative Studio', 'testi' => 'Mendirikan agensi multimedia kecil-kecilan bersama teman sekelas.'],
            ['nama' => 'Irfan Hakim', 'tahun' => 2024, 'status' => 'Wirausaha', 'detail' => 'Toko Komputer Rizqi Tech', 'testi' => 'Usaha servis komputer dan perakitan PC rakitan.'],
            ['nama' => 'Julia Perez', 'tahun' => 2023, 'status' => 'Wirausaha', 'detail' => 'JuPe Bakery & Cafe', 'testi' => 'Usaha kuliner roti dan kopi, ilmu tata boga terpakai.'],
            ['nama' => 'Kristina Dangdut', 'tahun' => 2022, 'status' => 'Wirausaha', 'detail' => 'Kristina Butik Online', 'testi' => 'Mengembangkan usaha pakaian wanita berbasis marketplace.'],
            ['nama' => 'Lucky Alamsyah', 'tahun' => 2024, 'status' => 'Wirausaha', 'detail' => 'Bengkel Motor Lucky Jaya', 'testi' => 'Bengkel sepeda motor mandiri setelah lulus SMK teknik otomotif.'],

            // Mencari Kerja (4)
            ['nama' => 'Mona Ratuliu', 'tahun' => 2025, 'status' => 'Mencari Kerja', 'detail' => '-', 'testi' => 'Sedang mempersiapkan diri dan melamar lowongan lewat platform BKK.'],
            ['nama' => 'Nia Ramadhani', 'tahun' => 2025, 'status' => 'Mencari Kerja', 'detail' => '-', 'testi' => 'Baru lulus, sedang giat mengikuti pelatihan tambahan online.'],
            ['nama' => 'Olla Ramlan', 'tahun' => 2024, 'status' => 'Mencari Kerja', 'detail' => '-', 'testi' => 'Berharap segera mendapatkan panggilan kerja yang sesuai bidang.'],
            ['nama' => 'Primus Yustisio', 'tahun' => 2025, 'status' => 'Mencari Kerja', 'detail' => '-', 'testi' => 'Aktif berkonsultasi dengan BKK untuk bimbingan wawancara.'],
        ];

        foreach ($tracers as $tracer) {
            TracerStudy::create([
                'nama_alumni' => $tracer['nama'],
                'tahun_lulus' => $tracer['tahun'],
                'status_alumni' => $tracer['status'],
                'detail_status' => $tracer['detail'],
                'testimoni' => $tracer['testi'],
            ]);
        }
    }
}
