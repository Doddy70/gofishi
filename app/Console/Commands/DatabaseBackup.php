<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database to a file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = "backup-" . Carbon::now()->format('Y-m-d_H-i-s') . ".sql";
        
        // Ensure backup directory exists
        $storagePath = storage_path('app/backups');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $command = sprintf(
            '/usr/local/bin/mysqldump --user=%s --password=%s --socket=/tmp/mysql.sock %s > %s',
            escapeshellarg(config('database.connections.mysql.username')),
            escapeshellarg(config('database.connections.mysql.password')),
            escapeshellarg(config('database.connections.mysql.database')),
            escapeshellarg($storagePath . '/' . $filename)
        );

        $returnVar = NULL;
        $output = NULL;

        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $this->info("Backup successfully created: {$filename}");
            $this->info("Location: storage/app/backups/{$filename}");
        } else {
            $this->error("The backup process failed!");
            // Check if mysqldump exists
            $this->warn("Note: Ensure 'mysqldump' is installed and accessible in your system's PATH.");
        }
    }
}
