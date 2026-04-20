<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AirbnbFooterIntegrationSeeder extends Seeder
{
    public function run()
    {
        $defaultLang = Language::where('is_default', 1)->first() ?? Language::first();
        if (!$defaultLang) return;

        // Truncate previous attempts to avoid mess
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('page_contents')->truncate();
        DB::table('pages')->truncate();
        DB::table('quick_links')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Target Footer Structure
        $supportPages = [
            'Pusat Bantuan', 'Kebijakan Privasi', 'Dukungan & Bantuan', 'Kebijakan Pembatalan'
        ];
        
        $gofishiPages = [
            'Tips Wisata Gofishi', 'Fitur Baru Gofishi', 'Karier di Gofishi', 'Gift Cards', 'Ketentuan'
        ];

        // Combine for page creation
        $allCustomPages = array_merge($supportPages, $gofishiPages);

        foreach ($allCustomPages as $title) {
            $pageId = DB::table('pages')->insertGetId([
                'status' => 1,
                'serial_number' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $content = '<div class="p-6"><h2>' . $title . '</h2><p>Halaman ' . $title . ' ini sudah terintegrasi penuh dengan sistem Gofishi.</p></div>';
            
            if ($title === 'Ketentuan') {
                $content = '
                <div class="prose max-w-none">
                    <h2 class="text-2xl font-bold mb-4">Ketentuan Layanan Gofishi</h2>
                    <p class="mb-4">Selamat datang di Gofishi. Dengan menggunakan layanan kami, Anda menyetujui ketentuan berikut:</p>
                    <ol class="list-decimal pl-6 space-y-3">
                        <li><strong>Penyewaan Perahu:</strong> Seluruh penyewaan melalui platform Gofishi harus mematuhi jadwal yang telah disepakati oleh vendor/pemilik kapal.</li>
                        <li><strong>Keselamatan Kelautan:</strong> Penyewa wajib mengikuti instruksi kapten kapal dan menggunakan fasilitas keselamatan (life jacket) selama berada di air.</li>
                        <li><strong>Pembayaran:</strong> Pembayaran dianggap sah hanya jika dilakukan melalui gerbang pembayaran resmi Gofishi.</li>
                        <li><strong>Kebijakan Lingkungan:</strong> Dilarang membuang sampah ke laut atau merusak ekosistem terumbu karang selama perjalanan memancing/wisata.</li>
                        <li><strong>Batasan Tanggung Jawab:</strong> Gofishi bertindak sebagai perantara dan tidak bertanggung jawab atas insiden di luar kendali platform, namun kami akan membantu mediasi jika terjadi perselisihan.</li>
                    </ol>
                </div>';
            }

            if ($title === 'Kebijakan Privasi') {
                $content = '
                <div class="prose max-w-none">
                    <h2 class="text-2xl font-bold mb-4">Kebijakan Privasi</h2>
                    <p class="mb-4">Informasi pribadi Anda aman bersama kami. Kami menggunakan data Anda untuk:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Memproses verifikasi identitas sebagai vendor atau penyewa.</li>
                        <li>Mengelola sistem pemesanan dan notifikasi WhatsApp.</li>
                        <li>Meningkatkan pengalaman pencarian spot memancing Anda.</li>
                    </ul>
                </div>';
            }

            DB::table('page_contents')->insert([
                'page_id' => $pageId,
                'language_id' => $defaultLang->id,
                'title' => $title,
                'slug' => Str::slug($title),
                'content' => $content,
                'meta_keywords' => $title . ', Gofishi',
                'meta_description' => 'Informasi mengenai ' . $title . ' di Gofishi.'
            ]);
        }

        // Hosting Column (Quick Links)
        $hostingLinks = [
            'Menjadi Host Gofishi' => '/user/login',
            'Host Resources' => '#',
            'Forum Komunitas' => '#',
            'Host Gofishi' => '#'
        ];

        foreach ($hostingLinks as $title => $url) {
            DB::table('quick_links')->insert([
                'language_id' => $defaultLang->id,
                'title' => $title,
                'url' => $url,
                'serial_number' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
