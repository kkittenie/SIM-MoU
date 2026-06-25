<?php

namespace Database\Seeders;

use App\Models\Universitas;
use Illuminate\Database\Seeder;

class UniversitasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_universitas' => 'Institut Teknologi Bandung (ITB)',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'website' => 'https://www.itb.ac.id',
                'nomor_telepon' => '(022) 2534810',
                'jenis' => 'negeri',
                'akreditasi' => 'A',
                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Universitas Indonesia (UI)',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'website' => 'https://www.ui.ac.id',
                'nomor_telepon' => '(021) 7270020',
                'jenis' => 'negeri',
                'akreditasi' => 'A',
                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Universitas Padjadjaran (Unpad)',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'website' => 'https://www.unpad.ac.id',
                'nomor_telepon' => '(022) 7799696',
                'jenis' => 'negeri',
                'akreditasi' => 'A',
                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Telkom University',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'website' => 'https://telkomuniversity.ac.id',
                'nomor_telepon' => '(022) 5220040',
                'jenis' => 'swasta',
                'akreditasi' => 'A',
                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Universitas Pendidikan Indonesia (UPI)',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'website' => 'https://www.upi.edu',
                'nomor_telepon' => '(022) 2013163',
                'jenis' => 'negeri',
                'akreditasi' => 'A',
                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Universitas Bina Nusantara',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'website' => 'https://www.binus.ac.id',
                'nomor_telepon' => '(021) 5350660',
                'jenis' => 'swasta',
                'akreditasi' => 'A',
                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Universitas Gunadarma',
                'kota' => 'Depok',
                'provinsi' => 'Jawa Barat',
                'website' => 'https://www.gunadarma.ac.id',
                'nomor_telepon' => '(021) 78881112',
                'jenis' => 'swasta',
                'akreditasi' => 'B',
                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Universitas Pasundan',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'website' => 'https://www.unpas.ac.id',
                'nomor_telepon' => '(022) 2019474',
                'jenis' => 'swasta',
                'akreditasi' => 'B',
                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'Universitas Negeri Bandung (UNB)',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'website' => 'https://www.unb.ac.id',
                'nomor_telepon' => '(022) 2006200',
                'jenis' => 'negeri',
                'akreditasi' => 'B',
                'status' => 'aktif',
            ],
            [
                'nama_universitas' => 'STMIK Bandung',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'website' => 'https://www.stmik-bandung.ac.id',
                'nomor_telepon' => '(022) 2045040',
                'jenis' => 'swasta',
                'akreditasi' => 'B',
                'status' => 'aktif',
            ],
        ];

        foreach ($data as $item) {
            Universitas::create($item);
        }
    }
}
