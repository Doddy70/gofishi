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
            'Pusat Bantuan', 'Privacy Policy', 'Dukungan & Bantuan', 'Kebijakan Pembatalan'
        ];
        
        $gofishiPages = [
            'Tips Wisata Gofishi', 'Fitur Baru Gofishi', 'Karier di Gofishi', 'Gift Cards'
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

            DB::table('page_contents')->insert([
                'page_id' => $pageId,
                'language_id' => $defaultLang->id,
                'title' => $title,
                'slug' => Str::slug($title),
                'content' => '<div class="p-6"><h2>' . $title . '</h2><p>Halaman ' . $title . ' ini sudah terintegrasi penuh dengan sistem Gofishi.</p></div>',
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
