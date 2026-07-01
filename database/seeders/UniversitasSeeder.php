<?php

namespace Database\Seeders;

use App\Models\Universitas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniversitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $universitas = [
            // ╔═══ NEGERI DALAM NEGERI ═══╗
            [
                'nama_universitas' => 'Universitas Indonesia',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'website' => 'https://www.ui.ac.id',
                'nomor_telepon' => '(021) 7270000',
                'jenis' => 'negeri',
                'akreditasi' => 'A',
                'lokasi_kuliah' => 'dalam_negeri',

                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Institut Teknologi Bandung',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'website' => 'https://www.itb.ac.id',
                'nomor_telepon' => '(022) 2502100',
                'jenis' => 'negeri',
                'akreditasi' => 'A',
                'lokasi_kuliah' => 'dalam_negeri',

                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Universitas Gadjah Mada',
                'kota' => 'Yogyakarta',
                'provinsi' => 'Daerah Istimewa Yogyakarta',
                'website' => 'https://www.ugm.ac.id',
                'nomor_telepon' => '(0274) 564000',
                'jenis' => 'negeri',
                'akreditasi' => 'A',
                'lokasi_kuliah' => 'dalam_negeri',

                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Universitas Diponegoro',
                'kota' => 'Semarang',
                'provinsi' => 'Jawa Tengah',
                'website' => 'https://www.undip.ac.id',
                'nomor_telepon' => '(024) 3515985',
                'jenis' => 'negeri',
                'akreditasi' => 'A',
                'lokasi_kuliah' => 'dalam_negeri',

                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Universitas Airlangga',
                'kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'website' => 'https://www.unair.ac.id',
                'nomor_telepon' => '(031) 5034465',
                'jenis' => 'negeri',
                'akreditasi' => 'A',
                'lokasi_kuliah' => 'dalam_negeri',

                'status' => 'aktif',
            ],

            // ╔═══ SWASTA DALAM NEGERI ═══╗
            [
                'nama_universitas' => 'Universitas Bina Nusantara',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'website' => 'https://www.binus.ac.id',
                'nomor_telepon' => '(021) 5345830',
                'jenis' => 'swasta',
                'akreditasi' => 'A',
                'lokasi_kuliah' => 'dalam_negeri',

                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Universitas Pelita Harapan',
                'kota' => 'Tangerang',
                'provinsi' => 'Banten',
                'website' => 'https://www.uph.edu',
                'nomor_telepon' => '(021) 5460901',
                'jenis' => 'swasta',
                'akreditasi' => 'A',
                'lokasi_kuliah' => 'dalam_negeri',

                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Universitas Padjajaran',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'website' => 'https://www.unpad.ac.id',
                'nomor_telepon' => '(022) 7796363',
                'jenis' => 'swasta',
                'akreditasi' => 'A',
                'lokasi_kuliah' => 'dalam_negeri',

                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Universitas Tarumanagara',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'website' => 'https://www.untar.ac.id',
                'nomor_telepon' => '(021) 5696969',
                'jenis' => 'swasta',
                'akreditasi' => 'B',
                'lokasi_kuliah' => 'dalam_negeri',

                'status' => 'aktif',
            ],

            // ╔═══ LUAR NEGERI ═══╗
        [
                'nama_universitas' => 'National University of Singapore',
                'kota' => 'Singapore',
                'provinsi' => 'Singapore',
                'website' => 'https://www.nus.edu.sg',
                'nomor_telepon' => '+65 6516 1111',
                'jenis' => 'negeri',
                'akreditasi' => 'A',
                'lokasi_kuliah' => 'luar_negeri',

                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'University of Melbourne',
                'kota' => 'Melbourne',
                'provinsi' => 'Victoria',
                'website' => 'https://www.unimelb.edu.au',
                'nomor_telepon' => '+61 3 9035 5511',
                'jenis' => 'negeri',
                'akreditasi' => 'A',
                'lokasi_kuliah' => 'luar_negeri',

                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'University of Tokyo',
                'kota' => 'Tokyo',
                'provinsi' => 'Tokyo',
                'website' => 'https://www.u-tokyo.ac.jp',
                'nomor_telepon' => '+81 3 3812 2111',
                'jenis' => 'negeri',
                'akreditasi' => 'A',
                'lokasi_kuliah' => 'luar_negeri',
                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Chulalongkorn University',
                'kota' => 'Bangkok',
                'provinsi' => 'Bangkok',
                'website' => 'https://www.chula.ac.th',
                'nomor_telepon' => '+66 2 218 0000',
                'jenis' => 'negeri',
                'akreditasi' => 'A',
                'lokasi_kuliah' => 'luar_negeri',
                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Universitas Malaya',
                'kota' => 'Kuala Lumpur',
                'provinsi' => 'Kuala Lumpur',
                'website' => 'https://www.um.edu.my',
                'nomor_telepon' => '+60 3 7967 7022',
                'jenis' => 'negeri',
                'akreditasi' => 'A',
                'lokasi_kuliah' => 'luar_negeri',

                'status' => 'aktif',
            ],
        ];

        foreach ($universitas as $item) {
            Universitas::create($item);
        }
    }
}
