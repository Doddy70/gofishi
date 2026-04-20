<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

use Illuminate\Support\Facades\Schedule;
use App\Models\Vendor;

Artisan::command('inspire', function () {
  $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Expert Scheduling: Automatic Database Hygiene
 * Cleanup unverified vendors older than 30 days every day.
 */
Schedule::call(function () {
    $count = Vendor::where('status', Vendor::STATUS_DEACTIVE)
        ->whereNull('email_verified_at')
        ->where('created_at', '<', now()->subDays(30))
        ->delete();
        
    Log::info("Scheduled Cleanup: Deleted {$count} unverified vendor accounts.");
})->daily();
