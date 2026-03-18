<?php

namespace Database\Seeders;

use App\Models\Journal\Blog;
use App\Models\Journal\BlogCategory;
use App\Models\Journal\BlogInformation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyArticleSeeder extends Seeder
{
    public function run()
    {
        $categoriesData = [
            'Tips Memancing',
            'Wisata Pulau',
            'Review Perahu',
        ];

        $categories = [];
        // DB Schema issue with blog_categories having missing columns, use fake IDs for now
        $fakeCatId = 1;

        $articles = [
            [
                'title' => 'Spot Memancing Terbaik di Kepulauan Seribu',
                'category' => 1,
                'content' => 'Kepulauan Seribu menyimpan banyak spot memancing rahasia yang kerap dikunjungi oleh para pemancing profesional. Dari spot dangkal hingga karang dalam...'
            ],
            [
                'title' => 'Panduan Memilih Charter Yacht Mewah di Jakarta',
                'category' => 2,
                'content' => 'Bagi Anda yang ingin merayakan acara spesial, menyewa yacht mewah dari Marina Ancol adalah pilihan tak terlupakan. Berikut adalah tips memilihnya...'
            ],
            [
                'title' => 'Review: Sensasi Speedboat Manta Ray',
                'category' => 3,
                'content' => 'Speedboat Manta Ray menembus ombak Teluk Jakarta dengan kecepatan tinggi. Keselamatan dan kenyamanan tetap nomor satu dalam review kali ini...'
            ]
        ];

        foreach ($articles as $index => $art) {
            $blogExists = BlogInformation::where('title', $art['title'])->first();
            if ($blogExists) continue;

            $blog = Blog::create([
                'image' => "https://picsum.photos/seed/blog{$index}/800/400",
                'status' => 1,
                'serial_number' => $index + 1,
            ]);

            foreach ([1, 2] as $langId) {
                BlogInformation::create([
                    'blog_id' => $blog->id,
                    'language_id' => $langId,
                    'title' => $art['title'],
                    'slug' => Str::slug($art['title']),
                    'content' => $art['content'],
                    'author' => 'Go Fishi Editor',
                ]);
            }
        }
    }
}
