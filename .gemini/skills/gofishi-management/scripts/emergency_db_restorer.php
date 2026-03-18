<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

/**
 * GO FISHI - EMERGENCY DATABASE RESTORER
 * Menjalankan SQL Dump langsung melalui koneksi Laravel (Bypass Access Denied CLI)
 */

$sqlPath = public_path('installer/database.sql');
if (!File::exists($sqlPath)) {
    die("File SQL tidak ditemukan di: $sqlPath\n");
}

echo "--- MEMULAI PEMULIHAN DATABASE GO FISHI ---\n";

// Disable foreign key checks agar import lancar
DB::statement('SET FOREIGN_KEY_CHECKS=0;');

$sql = File::get($sqlPath);

// Membersihkan SQL dari komentar agar tidak error saat di-parse
$sql = preg_replace('/--.*\n/', '', $sql);
$sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

// Membagi berdasarkan semicolon, tapi sangat hati-masing dengan string ber-semicolon
$queries = explode(";\n", $sql);

$success = 0;
$errors = 0;

foreach ($queries as $query) {
    $query = trim($query);
    if (empty($query)) continue;

    try {
        DB::unprepared($query . ';');
        $success++;
    } catch (\Exception $e) {
        $errors++;
        // Tampilkan hanya error yang bukan "Table already exists"
        if (!str_contains($e->getMessage(), 'already exists')) {
            echo "Gagal pada query: " . substr($query, 0, 50) . "...\n";
            echo "Error: " . $e->getMessage() . "\n\n";
        }
    }
}

// Re-enable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "--- HASIL PEMULIHAN ---\n";
echo "Berhasil: $success query\n";
echo "Gagal/Lewat: $errors query\n";
echo "-----------------------\n";
echo "Sekarang jalankan: php artisan migrate\n";
