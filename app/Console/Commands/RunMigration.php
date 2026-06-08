<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunMigration extends Command
{
    protected $signature = 'exec:migrate {type} {name?}';
    protected $description = 'Run a migration file for a specific plugin or theme or core application';

    public function handle()
    {
        $type = $this->argument('type');
        $name = $this->argument('name');

        //core migration
        if ($type == 'core') {
            $path = 'Core/Database/migrations';
        }

        //Plugin migration
        if ($type == 'plugin') {
            $path = "plugins/$name/src/Database/migrations";
        }

        //Theme migration
        if ($type == 'theme') {
            $path = "themes/$name/src/Database/migrations";
        }

        if (!in_array($type, ['core', 'plugin', 'theme'])) {
            $this->error('Invalid type. Use "core" or "plugin" or "theme".');
            return 1;
        }

        $fullPath = base_path($path);

        // Ensure directory exists
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        // Get all migration files
        $files = glob($fullPath . '/*.php');
        if (empty($files)) {
            $this->warn("No migration files found in: $path");
            return 0;
        }

        $this->info("Found " . count($files) . " migration files in: $path");

        Artisan::call('migrate', [
            '--path'  => $path,
            '--force' => true,
        ]);
        $this->line(Artisan::output());

        $this->info("All migrations executed");
    }
}