<?php
$_SERVER['REQUEST_URI'] = '/api/categories';
$_SERVER['REQUEST_METHOD'] = 'GET';
require __DIR__.'/vendor/autoload.php';
try {
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $request = Illuminate\Http\Request::create('/api/categories', 'GET');
    $response = $kernel->handle($request);
    echo "Status: " . $response->getStatusCode() . "\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
