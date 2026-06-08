<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class RollBackMigration extends Command
{
    protected $signature = 'rollback:migrate {type} {name?} {--reset}';
    protected $description = 'Rollback or reset migrations for a specific plugin, theme, or core application';

    public function handle()
    {
        $type = $this->argument('type');
        $name = $this->argument('name');

        // Resolve migration path
        if ($type === 'core') {
            $path = 'Core/Database/migrations';
        } elseif ($type === 'plugin') {
            $path = "plugins/$name/src/Database/migrations";
        } elseif ($type === 'theme') {
            $path = "themes/$name/src/Database/migrations";
        } else {
            $this->error('Invalid type. Use "core", "plugin", or "theme".');
            return 1;
        }

        $fullPath = base_path($path);

        if (!is_dir($fullPath)) {
            $this->warn("Migration directory not found: $path");
            return 0;
        }

        $this->info("➡ Found migration directory: $path");

        Schema::disableForeignKeyConstraints();

        if ($this->option('reset')) {
            $this->info("Running migrate:reset on: $path");
            Artisan::call('migrate:reset', [
                '--path'  => $path,
                '--force' => true,
            ]);
        } else {
            $this->info("Running migrate:rollback on: $path");
            Artisan::call('migrate:rollback', [
                '--path'  => $path,
                '--force' => true,
            ]);
        }

        $this->line(Artisan::output());
        Schema::enableForeignKeyConstraints();

        $this->info("✅ Rollback/reset complete for $type ($name)");
    }
}