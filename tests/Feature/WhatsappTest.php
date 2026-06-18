<?php

use App\Models\User;
use App\Models\Setting;
use App\Models\KerjaSama;
use App\Models\WhatsappLog;
use App\Services\WhatsappService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('phone number is correctly formatted to international format', function () {
    $service = new WhatsappService();
    
    $reflection = new ReflectionClass(WhatsappService::class);
    $method = $reflection->getMethod('formatPhoneNumber');
    $method->setAccessible(true);
    
    expect($method->invoke($service, '08123456789'))->toBe('628123456789');
    expect($method->invoke($service, '0812-3456-7890'))->toBe('6281234567890');
    expect($method->invoke($service, '+62 812 3456 7890'))->toBe('6281234567890');
    expect($method->invoke($service, '6281234567890'))->toBe('6281234567890');
});

test('whatsapp notification is sent to PIC phone number and logged', function () {
    Http::fake([
        'api.fonnte.com/*' => Http::response(['status' => true], 200)
    ]);

    $settings = Setting::getSettings();
    $settings->update([
        'fonnte_token' => 'test-token',
        'whatsapp_active' => true,
    ]);

    $kerjaSama = KerjaSama::create([
        'nama_mitra' => 'PT Test Mitra',
        'jenis_mitra' => 'Perusahaan',
        'alamat' => 'Jl. Test No. 123',
        'email' => 'mitra@test.com',
        'nomor_telepon' => '08123456789',
        'pic' => 'Budi Sudarsono',
        'nomor_mou' => 'MOU/2026/001',
        'tanggal_mulai' => now()->toDateString(),
        'tanggal_berakhir' => now()->addDays(30)->toDateString(),
    ]);

    $service = new WhatsappService();
    $success = $service->sendNotification($kerjaSama, 'warning_30');

    expect($success)->toBeTrue();

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.fonnte.com/send' &&
            $request->header('Authorization')[0] === 'test-token' &&
            $request['target'] === '628123456789' &&
            str_contains($request['message'], 'PT Test Mitra') &&
            str_contains($request['message'], 'Budi Sudarsono');
    });

    $log = WhatsappLog::first();
    expect($log)->not->toBeNull();
    expect($log->recipient)->toBe('628123456789');
    expect($log->status)->toBe('success');
});

test('manual whatsapp reminder route requires admin/bkk and sends successfully', function () {
    Http::fake([
        'api.fonnte.com/*' => Http::response(['status' => true], 200)
    ]);

    $settings = Setting::getSettings();
    $settings->update([
        'fonnte_token' => 'test-token',
        'whatsapp_active' => true,
    ]);

    $kerjaSama = KerjaSama::create([
        'nama_mitra' => 'PT Test Mitra',
        'jenis_mitra' => 'Perusahaan',
        'alamat' => 'Jl. Test No. 123',
        'email' => 'mitra@test.com',
        'nomor_telepon' => '08123456789',
        'pic' => 'Budi Sudarsono',
        'nomor_mou' => 'MOU/2026/001',
        'tanggal_mulai' => now()->toDateString(),
        'tanggal_berakhir' => now()->addDays(30)->toDateString(),
    ]);

    $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
    $bk = User::factory()->create(['role' => 'bk', 'status' => 'aktif']);

    $this->post(route('kerja-sama.kirim-wa', $kerjaSama->id))
        ->assertRedirect('/login');

    $this->actingAs($bk)
        ->post(route('kerja-sama.kirim-wa', $kerjaSama->id))
        ->assertStatus(403);

    $this->actingAs($admin)
        ->post(route('kerja-sama.kirim-wa', $kerjaSama->id))
        ->assertRedirect();
});
