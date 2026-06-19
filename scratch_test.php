<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('role', 'bkk')->first();
auth()->login($user);

$request = Illuminate\Http\Request::create('/laporan-bkk', 'GET', ['tahun_lulus' => 2025]);
$response = app()->handle($request);

$content = $response->getContent();

// Check if 2025 graduates are in the HTML content
$has2025 = str_contains($content, 'Gita Gutawa') || str_contains($content, 'Utami Dewi');
$hasOther = str_contains($content, 'Aditya Pratama') || str_contains($content, 'Bambang Pamungkas');

echo "Has 2025 graduates: " . ($has2025 ? 'YES' : 'NO') . "\n";
echo "Has other year graduates: " . ($hasOther ? 'YES' : 'NO') . "\n";
