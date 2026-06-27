<?php

use App\Models\User;
use App\Models\AlumniWirausaha;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('bk and admin can access alumni wirausaha index and create wirausaha', function () {
    $bk = User::factory()->create(['role' => 'bk', 'status' => 'aktif']);
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
    $bkk = User::factory()->create(['role' => 'bkk', 'status' => 'aktif']);

    // 1. Access Index
    $response = $this->actingAs($bk)->get(route('bk.alumni-wirausaha.index'));
    $response->assertStatus(200);

    $response = $this->actingAs($admin)->get(route('bk.alumni-wirausaha.index'));
    $response->assertStatus(200);

    // BKK cannot access
    $response = $this->actingAs($bkk)->get(route('bk.alumni-wirausaha.index'));
    $response->assertStatus(403);

    // 2. Create Alumni Wirausaha
    $response = $this->actingAs($bk)->post(route('bk.alumni-wirausaha.store'), [
        'nama_alumni' => 'John Doe',
        'nama_usaha' => 'JD Coffee Shop',
        'bidang_usaha' => 'Kuliner',
        'lama_usaha' => '1 Tahun',
        'tahun_lulus' => 2024,
    ]);
    $response->assertRedirect(route('bk.alumni-wirausaha.index'));
    $this->assertDatabaseHas('alumni_wirausahas', ['nama_alumni' => 'John Doe']);
});
