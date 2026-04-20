<?php

namespace Database\Seeders;

use App\Models\BoatPackage;
use App\Models\Perahu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BoatPackageSeeder extends Seeder
{
    public function run()
    {
        DB::table('boat_packages')->truncate();

        $perahu = Perahu::select('id', 'nama_km', 'price_day_1', 'price_day_2', 'price_day_3')->get();

        foreach ($perahu as $boat) {
            $p1 = $boat->price_day_1;
            $p2 = $boat->price_day_2 ?: round($p1 * 1.9);
            $p3 = $boat->price_day_3 ?: round($p1 * 2.7);

            $packages = [
                [
                    'name'          => 'Paket 1 Hari – Harian',
                    'description'   => 'Trip seharian penuh dari pagi hingga sore. Cocok untuk pemancing harian yang ingin mengeksplorasi spot sekitar Jakarta dan Kepulauan Seribu.',
                    'price'         => $p1,
                    'duration_days' => 1,
                    'meeting_time'  => '06:00:00',
                    'return_time'   => '16:00:00',
                    'area'          => 'Perairan Jakarta – Kepulauan Seribu',
                    'status'        => 1,
                ],
                [
                    'name'          => 'Paket 1 Hari – Malam',
                    'description'   => 'Trip malam hari (night fishing) untuk memburu ikan pelagis aktif di malam hari. Berangkat sore, kembali dini hari.',
                    'price'         => round($p1 * 1.1),
                    'duration_days' => 1,
                    'meeting_time'  => '17:00:00',
                    'return_time'   => '04:00:00',
                    'area'          => 'Karang Dalam – Pulau Pramuka',
                    'status'        => 1,
                ],
                [
                    'name'          => 'Paket 2 Hari 1 Malam',
                    'description'   => 'Petualangan memancing 2 hari 1 malam. Bermalam di kapal atau di pulau, menjelajahi spot terbaik yang lebih jauh dari dermaga.',
                    'price'         => $p2,
                    'duration_days' => 2,
                    'meeting_time'  => '06:00:00',
                    'return_time'   => '16:00:00',
                    'area'          => 'Kepulauan Seribu – P. Tidung / P. Pari',
                    'status'        => 1,
                ],
                [
                    'name'          => 'Paket 3 Hari 2 Malam',
                    'description'   => 'Paket ekspedisi memancing eksklusif 3 hari 2 malam. Jangkauan lebih jauh, spot lebih matang, untuk ikan-ikan trophy. Termasuk konsumsi selama trip.',
                    'price'         => $p3,
                    'duration_days' => 3,
                    'meeting_time'  => '05:00:00',
                    'return_time'   => '17:00:00',
                    'area'          => 'Kepulauan Seribu – P. Pramuka / P. Harapan',
                    'status'        => 1,
                ],
            ];

            foreach ($packages as $pkg) {
                BoatPackage::create(array_merge($pkg, ['room_id' => $boat->id]));
            }

            $this->command->info("✅ 4 paket dibuat untuk: {$boat->nama_km}");
        }

        $this->command->info('');
        $this->command->info('Total paket dibuat: ' . BoatPackage::count());
    }
}
