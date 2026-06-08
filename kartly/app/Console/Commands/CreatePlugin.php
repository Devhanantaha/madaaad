<?php

namespace App\Console\Commands;

use Core\Models\Plugin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreatePlugin extends Command
{
    protected $signature = 'create:plugin';
    protected $description = 'Create a new plugin structure';

    public function handle()
    {
        //Ask for plugin details
        $pluginName = $this->ask('What is the plugin name?');
        $author     = $this->ask('Who is the author?');
        $url        = $this->ask('Provide the author link (GitHub/Website)');
        $description = $this->ask('Provide a short description for the plugin');
        $isRequireLicense = $this->confirm('Will this plugin have a license?', true);

        //Normalize folder + namespace
        $location   = Str::lower(str_replace(' ', '_', $pluginName));
        $folderName = ltrim(preg_replace('/\s+/', '', Str::camel($pluginName)), '\\');
        $tempNameSpaced = Str::studly($folderName);
        $namespace  = "Plugin\\{$tempNameSpaced}\\";

        $pluginPath = base_path("plugins/{$location}");

        //Check if plugin exists
        if (File::exists($pluginPath)) {
            $this->error("Plugin '{$location}' already exists!");
            return 1;
        }

        // Create directory structure
        File::makeDirectory($pluginPath, 0755, true, true);
        File::makeDirectory("{$pluginPath}/config", 0755, true, true);

        File::makeDirectory("{$pluginPath}/helpers", 0755, true, true);

        //Create routes
        File::makeDirectory("{$pluginPath}/routes", 0755, true, true);
        File::put("{$pluginPath}/routes/web.php", "<?php\n\nuse Illuminate\Support\Facades\Route;\n\n// Web routes for {$folderName} plugin\n");
        File::put("{$pluginPath}/routes/api.php", "<?php\n\nuse Illuminate\Support\Facades\Route;\n\n// API routes for {$folderName} plugin\n");


        File::makeDirectory("{$pluginPath}/src/Http/Controllers", 0755, true, true);

        //Create a sample controller
        File::put(
            "{$pluginPath}/src/Http/Controllers/PluginController.php",
            <<<EOF
            <?php

            namespace {$namespace}Http\Controllers;
            use App\Http\Controllers\Controller;
            use Illuminate\Http\Request;

            class PluginController extends Controller
            {
                /**
                 * Display a listing of the resource.
                 *
                 * @return \Illuminate\Http\Response
                 */
                public function index()
                {
                    return view('plugin/{$location}::demo');
                }
            }
            EOF
        );

        //Create Models directory
        File::makeDirectory("{$pluginPath}/src/Models", 0755, true, true);



        //Create views
        File::makeDirectory("{$pluginPath}/views", 0755, true, true);
        File::makeDirectory("{$pluginPath}/views/includes", 0755, true, true);
        File::put("{$pluginPath}/views/demo.blade.php", "@extends('core::base.layouts.master')");

        //Create plugin.json
        $pluginJson = [
            'name'        => $pluginName,
            'location'    => $location,
            'namespace'   => $namespace,
            'version'     => '1.0.0',
            'author'      => $author,
            'url'         => $url,
            'description' => $description,
            'license'     => $isRequireLicense,
        ];

        File::put("{$pluginPath}/plugin.json", json_encode($pluginJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        //store plugin in database
        $plugin = new Plugin();
        $plugin->name = $pluginName;
        $plugin->location = $location;
        $plugin->namespace = $namespace;
        $plugin->version = '1.0.0';
        $plugin->author = $author;
        $plugin->url = $url;
        $plugin->description = $description;
        $plugin->is_activated = $isRequireLicense ? 2 : 1;
        $plugin->save();

        //Done
        $this->info("Plugin '{$pluginName}' created successfully!");
        $this->line("Location : plugins/{$location}");
        $this->line("Namespace: {$namespace}");
        $this->line("Author   : {$author}");
        $this->line("URL      : {$url}");

        return 0;
    }
}