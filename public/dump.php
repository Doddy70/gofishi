<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$hotels = \App\Models\Hotel::where('status', 1)->get();
foreach($hotels as $h) {
    echo "ID: {$h->id}, Title: " . $h->hotel_contents()->first()->title . "\n";
}
