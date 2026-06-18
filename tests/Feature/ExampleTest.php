<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest is redirected to login page', function () {
    $response = $this->get('/');
    $response->assertRedirect('/login');
});

test('authenticated user can access dashboard', function () {
    $user = User::factory()->create(['status' => 'aktif']);
    $response = $this->actingAs($user)->get('/');
    $response->assertStatus(200);
});


