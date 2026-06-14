<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$customer = \App\Models\User::where('email', 'customersilariz@gmail.com')->first();
auth()->login($customer);

$request = \Illuminate\Http\Request::create('/customer/orders/create', 'GET');

try {
    echo "Handling request...\n";
    $response = $kernel->handle($request);
    echo "Handled!\n";
    $status = $response->getStatusCode();
    echo "HTTP Status: {$status}\n";
} catch (\Throwable $e) {
    echo "EXCEPTION: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
}
