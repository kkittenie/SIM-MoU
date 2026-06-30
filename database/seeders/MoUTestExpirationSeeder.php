<?php

namespace Database\Seeders;

use App\Models\KerjaSama;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MoUTestExpirationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::today();

        // 1. Mitra yang berakhir 6 bulan lagi
        KerjaSama::create([
            'nama_mitra' => 'Mitra Uji 6 Bulan (PT. Hexagon Digital)',
            'jenis_mitra' => 'Perusahaan',
            'alamat' => 'Sudirman Central Business District, Jakarta',
            'email' => 'contact@hexagon.test',
            'nomor_telepon' => '081234567890',
            'pic' => 'Ahmad Hexa',
            'nomor_mou' => 'MOU-TEST/6-MONTH/2026',
            'tanggal_mulai' => $today->copy()->subMonths(6),
            'tanggal_berakhir' => $today->copy()->addMonths(6),
            'deskripsi' => 'Pengujian pengingat MoU berakhir dalam 6 bulan.',
            'dokumen_pdf' => null
        ]);

        // 2. Mitra yang berakhir 5 bulan lagi
        KerjaSama::create([
            'nama_mitra' => 'Mitra Uji 5 Bulan (PT. Penta Global)',
            'jenis_mitra' => 'Perusahaan',
            'alamat' => 'Kuningan, Jakarta Selatan',
            'email' => 'hrd@penta.test',
            'nomor_telepon' => '081234567891',
            'pic' => 'Dewi Penta',
            'nomor_mou' => 'MOU-TEST/5-MONTH/2026',
            'tanggal_mulai' => $today->copy()->subMonths(7),
            'tanggal_berakhir' => $today->copy()->addMonths(5),
            'deskripsi' => 'Pengujian pengingat MoU berakhir dalam 5 bulan.',
            'dokumen_pdf' => null
        ]);

        // 3. Mitra yang berakhir 4 bulan lagi
        KerjaSama::create([
            'nama_mitra' => 'Mitra Uji 4 Bulan (PT. Quadra Solusindo)',
            'jenis_mitra' => 'Perusahaan',
            'alamat' => 'Slipi, Jakarta Barat',
            'email' => 'support@quadra.test',
            'nomor_telepon' => '081234567892',
            'pic' => 'Budi Quadra',
            'nomor_mou' => 'MOU-TEST/4-MONTH/2026',
            'tanggal_mulai' => $today->copy()->subMonths(8),
            'tanggal_berakhir' => $today->copy()->addMonths(4),
            'deskripsi' => 'Pengujian pengingat MoU berakhir dalam 4 bulan.',
            'dokumen_pdf' => null
        ]);

        // 4. Mitra yang berakhir 3 bulan lagi
        KerjaSama::create([
            'nama_mitra' => 'Mitra Uji 3 Bulan (PT. Tridaya Utama)',
            'jenis_mitra' => 'Perusahaan',
            'alamat' => 'Cawang, Jakarta Timur',
            'email' => 'admin@tridaya.test',
            'nomor_telepon' => '081234567893',
            'pic' => 'Chandra Tri',
            'nomor_mou' => 'MOU-TEST/3-MONTH/2026',
            'tanggal_mulai' => $today->copy()->subMonths(9),
            'tanggal_berakhir' => $today->copy()->addMonths(3),
            'deskripsi' => 'Pengujian pengingat MoU berakhir dalam 3 bulan.',
            'dokumen_pdf' => null
        ]);

        // 5. Mitra yang berakhir 2 bulan lagi
        KerjaSama::create([
            'nama_mitra' => 'Mitra Uji 2 Bulan (PT. Dwi Mitra Perkasa)',
            'jenis_mitra' => 'Perusahaan',
            'alamat' => 'Kelapa Gading, Jakarta Utara',
            'email' => 'info@dwimitra.test',
            'nomor_telepon' => '081234567894',
            'pic' => 'Eko Dwi',
            'nomor_mou' => 'MOU-TEST/2-MONTH/2026',
            'tanggal_mulai' => $today->copy()->subMonths(10),
            'tanggal_berakhir' => $today->copy()->addMonths(2),
            'deskripsi' => 'Pengujian pengingat MoU berakhir dalam 2 bulan.',
            'dokumen_pdf' => null
        ]);

        // 6. Mitra yang berakhir 1 bulan lagi
        KerjaSama::create([
            'nama_mitra' => 'Mitra Uji 1 Bulan (PT. Eka Solusi Inovasi)',
            'jenis_mitra' => 'Perusahaan',
            'alamat' => 'Tangerang, Banten',
            'email' => 'sales@eka.test',
            'nomor_telepon' => '081234567895',
            'pic' => 'Fahmi Eka',
            'nomor_mou' => 'MOU-TEST/1-MONTH/2026',
            'tanggal_mulai' => $today->copy()->subMonths(11),
            'tanggal_berakhir' => $today->copy()->addMonths(1),
            'deskripsi' => 'Pengujian pengingat MoU berakhir dalam 1 bulan.',
            'dokumen_pdf' => null
        ]);
    }
}
