<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CreateMigration extends Command
{
    protected $signature = 'create:migration {type} {name} {--path= : The path/module folder}';
    protected $description = 'Create a new migration file for a specific path';

    public function handle()
    {
        $type = $this->argument('type');
        $name = $this->argument('name');
        $path = $this->option('path');

        //validate type
        if (!in_array($type, ['core', 'plugin', 'theme'])) {
            $this->error('Invalid type. Use "core" or "plugin" or "theme".');
            return 1;
        }


        //validate path for plugin and theme
        if (($type == 'plugin' || $type == 'theme') && !$path) {
            $this->error('Please provide the --path option.');
            return 1;
        }

        //core migration
        if ($type == 'core') {
            $path = 'Core/Database/migrations';
        }

        //Plugin migration
        if ($type == 'plugin') {
            $path = "plugins/$path/src/Database/migrations";
        }

        //Theme migration
        if ($type == 'theme') {
            $path = "themes/$path/src/Database/migrations";
        }


        // Ensure directory exists
        if (!is_dir(base_path($path))) {
            mkdir(base_path($path), 0755, true);
        }

        $this->info("Creating migration in: $path");

        Artisan::call('make:migration', [
            'name' => $name,
            '--path' => $path,
            '--realpath' => true,
        ]);

        $this->info(Artisan::output());
        return 0;
    }
}
