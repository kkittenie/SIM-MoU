<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can access public landing page', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

test('guest is redirected to login page when accessing dashboard', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('authenticated user can access dashboard', function () {
    $user = User::factory()->create(['status' => 'aktif']);
    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(200);
});
