<?php

namespace Database\Seeders;

use App\Models\AlumniWirausaha;
use Illuminate\Database\Seeder;

class AlumniWirausahaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_alumni' => 'Ginanjar 4 Sekawan',
                'nama_usaha' => 'Ginanjar Digital Printing',
                'bidang_usaha' => 'Percetakan & Desain Grafis',
                'lama_usaha' => '2 Tahun',
                'tahun_lulus' => 2022,
            ],
            [
                'nama_alumni' => 'Helmy Yahya',
                'nama_usaha' => 'HY Creative Studio',
                'bidang_usaha' => 'Multimedia & Agensi',
                'lama_usaha' => '1 Tahun 6 Bulan',
                'tahun_lulus' => 2023,
            ],
            [
                'nama_alumni' => 'Irfan Hakim',
                'nama_usaha' => 'Toko Komputer Rizqi Tech',
                'bidang_usaha' => 'Teknologi & Servis Komputer',
                'lama_usaha' => '1 Tahun',
                'tahun_lulus' => 2024,
            ],
            [
                'nama_alumni' => 'Julia Perez',
                'nama_usaha' => 'JuPe Bakery & Cafe',
                'bidang_usaha' => 'Kuliner (Roti & Kopi)',
                'lama_usaha' => '2 Tahun',
                'tahun_lulus' => 2023,
            ],
            [
                'nama_alumni' => 'Kristina Dangdut',
                'nama_usaha' => 'Kristina Butik Online',
                'bidang_usaha' => 'Fashion & E-Commerce',
                'lama_usaha' => '3 Tahun',
                'tahun_lulus' => 2022,
            ],
            [
                'nama_alumni' => 'Lucky Alamsyah',
                'nama_usaha' => 'Bengkel Motor Lucky Jaya',
                'bidang_usaha' => 'Otomotif & Bengkel',
                'lama_usaha' => '1 Tahun 8 Bulan',
                'tahun_lulus' => 2024,
            ],
            [
                'nama_alumni' => 'Rahmat Hidayat',
                'nama_usaha' => 'Dayat Ternak Mandiri',
                'bidang_usaha' => 'Peternakan & Pertanian',
                'lama_usaha' => '6 Bulan',
                'tahun_lulus' => 2025,
            ],
            [
                'nama_alumni' => 'Siti Maulida',
                'nama_usaha' => 'Lida Hijab & Fashion',
                'bidang_usaha' => 'Fashion & Konveksi',
                'lama_usaha' => '1 Tahun',
                'tahun_lulus' => 2024,
            ]
        ];

        foreach ($data as $item) {
            AlumniWirausaha::create($item);
        }
    }
}
