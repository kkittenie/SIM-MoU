<?php

namespace Database\Seeders;

use App\Models\KerjaSama;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KerjaSamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::today();

        // 1. Aktif (MOU berakhir jauh di masa depan)
        KerjaSama::create([
            'nama_mitra' => 'PT. Toyota Motor Manufacturing Indonesia',
            'jenis_mitra' => 'Perusahaan',
            'alamat' => 'Kawasan Industri KIIC, Karawang, Jawa Barat',
            'email' => 'hrd@toyota.co.id',
            'nomor_telepon' => '021-8901234',
            'pic' => 'Budi Santoso',
            'nomor_mou' => '001/MOU-TMMIN/I/2026',
            'tanggal_mulai' => $today->copy()->subYears(1),
            'tanggal_berakhir' => $today->copy()->addYears(2),
            'deskripsi' => 'Kerja sama magang industri, kunjungan industri, dan perekrutan lulusan SMK.',
            'dokumen_pdf' => null
        ]);

        KerjaSama::create([
            'nama_mitra' => 'Universitas Indonesia',
            'jenis_mitra' => 'Perguruan Tinggi',
            'alamat' => 'Kampus UI Depok, Jawa Barat',
            'email' => 'kerjasama@ui.ac.id',
            'nomor_telepon' => '021-7867222',
            'pic' => 'Prof. Dr. Ir. Heri Hermansyah',
            'nomor_mou' => 'UI/KERSAM/04/2026',
            'tanggal_mulai' => $today->copy()->subMonths(6),
            'tanggal_berakhir' => $today->copy()->addMonths(18),
            'deskripsi' => 'Program pengabdian masyarakat bersama, workshop teknologi, dan jalur prestasi lulusan.',
            'dokumen_pdf' => null
        ]);

        KerjaSama::create([
            'nama_mitra' => 'Dinas Pendidikan Provinsi DKI Jakarta',
            'jenis_mitra' => 'Instansi Pemerintah',
            'alamat' => 'Jl. Gatot Subroto No. 40-41, Jakarta Selatan',
            'email' => 'disdik@jakarta.go.id',
            'nomor_telepon' => '021-5255385',
            'pic' => 'Drs. Purwosusilo, M.Pd.',
            'nomor_mou' => 'DISDIK/MOU/IX/2025',
            'tanggal_mulai' => $today->copy()->subMonths(9),
            'tanggal_berakhir' => $today->copy()->addMonths(15),
            'deskripsi' => 'Pengembangan kurikulum SMK vokasi berbasis standar industri nasional.',
            'dokumen_pdf' => null
        ]);

        KerjaSama::create([
            'nama_mitra' => 'PT. GoTo Gojek Tokopedia Tbk',
            'jenis_mitra' => 'Perusahaan',
            'alamat' => 'Pasaraya Blok M Gedung B, Jakarta Selatan',
            'email' => 'vocation@goto.com',
            'nomor_telepon' => '021-2918293',
            'pic' => 'Nadiem Makarim Jr.',
            'nomor_mou' => 'GOTO/VOC/SMK/2026',
            'tanggal_mulai' => $today->copy()->subMonths(3),
            'tanggal_berakhir' => $today->copy()->addYears(1),
            'deskripsi' => 'Pelatihan coding, magang siswa jurusan RPL, dan rekrutmen alumni berprestasi.',
            'dokumen_pdf' => null
        ]);

        // 2. Akan Berakhir (Berakhir dalam waktu <= 30 hari dari sekarang)
        KerjaSama::create([
            'nama_mitra' => 'PT. Astra Honda Motor',
            'jenis_mitra' => 'Perusahaan',
            'alamat' => 'Jl. Laksda Yos Sudarso, Sunter, Jakarta Utara',
            'email' => 'recruitment@ahm.co.id',
            'nomor_telepon' => '021-6518080',
            'pic' => 'Rian Hidayat',
            'nomor_mou' => '015/AHM-BKK/VI/2023',
            'tanggal_mulai' => $today->copy()->subYears(3),
            'tanggal_berakhir' => $today->copy()->addDays(10), // Berakhir 10 hari lagi
            'deskripsi' => 'Bantuan alat praktik bengkel sepeda motor Astra Honda dan uji sertifikasi mekanik.',
            'dokumen_pdf' => null
        ]);

        KerjaSama::create([
            'nama_mitra' => 'Politeknik Negeri Jakarta',
            'jenis_mitra' => 'Perguruan Tinggi',
            'alamat' => 'Kampus UI Depok, Jawa Barat',
            'email' => 'info@pnj.ac.id',
            'nomor_telepon' => '021-7270036',
            'pic' => 'Ir. Syamsurizal',
            'nomor_mou' => 'PNJ/SMK/MOU/VII/2024',
            'tanggal_mulai' => $today->copy()->subYears(2),
            'tanggal_berakhir' => $today->copy()->addDays(25), // Berakhir 25 hari lagi
            'deskripsi' => 'Program Diploma 1 (D1) jalur cepat untuk lulusan SMK kelistrikan dan mesin.',
            'dokumen_pdf' => null
        ]);

        KerjaSama::create([
            'nama_mitra' => 'Balai Latihan Kerja (BLK) Depok',
            'jenis_mitra' => 'Instansi Pemerintah',
            'alamat' => 'Jl. Raya Bogor KM 38, Depok',
            'email' => 'blk.depok@kemnaker.go.id',
            'nomor_telepon' => '021-8741515',
            'pic' => 'Subhan, S.Sos.',
            'nomor_mou' => 'BLK/MOU-PELAT/VIII/2024',
            'tanggal_mulai' => $today->copy()->subYears(2),
            'tanggal_berakhir' => $today->copy()->addDays(5), // Berakhir 5 hari lagi
            'deskripsi' => 'Pelatihan sertifikasi kompetensi mesin CNC dan otomotif gratis untuk siswa tingkat akhir.',
            'dokumen_pdf' => null
        ]);

        // 3. Expired / Kedaluwarsa (Berakhir sebelum hari ini)
        KerjaSama::create([
            'nama_mitra' => 'PT. Telkom Indonesia Tbk',
            'jenis_mitra' => 'Perusahaan',
            'alamat' => 'Jl. Japati No. 1, Bandung, Jawa Barat',
            'email' => 'csr@telkom.co.id',
            'nomor_telepon' => '022-4527110',
            'pic' => 'Ahmad Fauzi',
            'nomor_mou' => 'TELKOM/CSR-DIGITAL/XII/2024',
            'tanggal_mulai' => $today->copy()->subYears(2),
            'tanggal_berakhir' => $today->copy()->subMonths(3), // Sudah kedaluwarsa 3 bulan lalu
            'deskripsi' => 'Penyediaan bandwidth internet sekolah gratis dan pelatihan teknisi jaringan fiber optik.',
            'dokumen_pdf' => null
        ]);

        KerjaSama::create([
            'nama_mitra' => 'PT. Kimia Farma Tbk',
            'jenis_mitra' => 'Perusahaan',
            'alamat' => 'Jl. Veteran No. 9, Jakarta Pusat',
            'email' => 'relations@kimiafarma.co.id',
            'nomor_telepon' => '021-3847777',
            'pic' => 'Dewi Sartika',
            'nomor_mou' => 'KF/KERSAM-APOTEK/X/2023',
            'tanggal_mulai' => $today->copy()->subYears(2),
            'tanggal_berakhir' => $today->copy()->subMonths(6), // Sudah kedaluwarsa 6 bulan lalu
            'deskripsi' => 'Tempat magang/PKL siswa farmasi klinis dan suplai bahan praktik laboratorium.',
            'dokumen_pdf' => null
        ]);

        KerjaSama::create([
            'nama_mitra' => 'Yayasan Dompet Dhuafa',
            'jenis_mitra' => 'Lainnya',
            'alamat' => 'Philanthropy Building, Jl. Warung Jati Barat, Jakarta',
            'email' => 'kemitraan@dompetdhuafa.org',
            'nomor_telepon' => '021-7821234',
            'pic' => 'Iwan Ridwan',
            'nomor_mou' => 'DD/BEASISWA/IV/2025',
            'tanggal_mulai' => $today->copy()->subYears(1),
            'tanggal_berakhir' => $today->copy()->subDays(12), // Sudah kedaluwarsa 12 hari lalu
            'deskripsi' => 'Pemberian beasiswa pendidikan vokasi untuk siswa kurang mampu.',
            'dokumen_pdf' => null
        ]);
    }
}
