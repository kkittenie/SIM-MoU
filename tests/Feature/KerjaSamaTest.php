<?php

use App\Models\User;
use App\Models\KerjaSama;
use App\Models\KategoriMitra;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authorized users can filter MoU by category and year', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

    // Seed some categories
    $cat1 = KategoriMitra::create(['nama' => 'Kategori A']);
    $cat2 = KategoriMitra::create(['nama' => 'Kategori B']);

    // Create MoUs
    $mou1 = KerjaSama::create([
        'kategori_mitra_id' => $cat1->id,
        'nama_mitra' => 'Mitra A',
        'jenis_mitra' => 'Kategori A',
        'alamat' => 'Jl. A',
        'email' => 'a@mitra.com',
        'nomor_telepon' => '0811',
        'pic' => 'PIC A',
        'nomor_mou' => 'MOU-A',
        'tanggal_mulai' => '2024-01-01',
        'tanggal_berakhir' => '2025-01-01',
    ]);

    $mou2 = KerjaSama::create([
        'kategori_mitra_id' => $cat2->id,
        'nama_mitra' => 'Mitra B',
        'jenis_mitra' => 'Kategori B',
        'alamat' => 'Jl. B',
        'email' => 'b@mitra.com',
        'nomor_telepon' => '0812',
        'pic' => 'PIC B',
        'nomor_mou' => 'MOU-B',
        'tanggal_mulai' => '2025-02-01',
        'tanggal_berakhir' => '2026-02-01',
    ]);

    // Request with no filters - shows both
    $response = $this->actingAs($admin)->get(route('kerja-sama.index'));
    $response->assertSee('Mitra A');
    $response->assertSee('Mitra B');

    // Filter by category
    $response = $this->actingAs($admin)->get(route('kerja-sama.index', ['jenis_mitra' => 'Kategori A']));
    $response->assertSee('Mitra A');
    $response->assertDontSee('Mitra B');

    // Filter by year
    $response = $this->actingAs($admin)->get(route('kerja-sama.index', ['year' => '2025']));
    $response->assertSee('Mitra B');
    $response->assertDontSee('Mitra A');
});

test('authorized users can manage partner categories', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

    // 1. Add Category
    $this->actingAs($admin)
        ->post(route('setting.kategori.store'), ['nama' => 'Kategori Baru'])
        ->assertRedirect(route('setting.index'));

    $this->assertDatabaseHas('kategori_mitra', ['nama' => 'Kategori Baru']);

    $category = KategoriMitra::where('nama', 'Kategori Baru')->first();

    // 2. Delete Category
    $this->actingAs($admin)
        ->delete(route('setting.kategori.destroy', $category->id))
        ->assertRedirect(route('setting.index'));

    $this->assertDatabaseMissing('kategori_mitra', ['nama' => 'Kategori Baru']);
});

test('authorized users can paginate Kerja Sama list', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
    $cat = KategoriMitra::create(['nama' => 'Kategori Test']);

    // Create 15 Kerja Sama records
    for ($i = 1; $i <= 15; $i++) {
        $ks = new KerjaSama([
            'kategori_mitra_id' => $cat->id,
            'nama_mitra' => "Mitra Ke-$i",
            'jenis_mitra' => 'Kategori Test',
            'alamat' => 'Alamat ' . $i,
            'email' => "mitra$i@test.com",
            'nomor_telepon' => '0812' . $i,
            'pic' => 'PIC ' . $i,
            'nomor_mou' => "MOU-$i",
            'tanggal_mulai' => '2024-01-01',
            'tanggal_berakhir' => '2025-01-01',
        ]);
        $ks->created_at = now()->addSeconds($i);
        $ks->save();
    }

    // Request page 1
    $response = $this->actingAs($admin)->get(route('kerja-sama.index'));
    $response->assertStatus(200);
    // Since it's latest(), page 1 should contain Mitra Ke-15 down to Mitra Ke-6
    $response->assertSee('Mitra Ke-15');
    $response->assertSee('Mitra Ke-6');
    $response->assertDontSee('Mitra Ke-5');

    // Request page 2
    $response = $this->actingAs($admin)->get(route('kerja-sama.index', ['page' => 2]));
    $response->assertStatus(200);
    // Page 2 should contain Mitra Ke-5 down to Mitra Ke-1
    $response->assertSee('Mitra Ke-5');
    $response->assertSee('Mitra Ke-1');
    $response->assertDontSee('Mitra Ke-15');
});
