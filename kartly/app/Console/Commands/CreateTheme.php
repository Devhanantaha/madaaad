<?php

namespace App\Console\Commands;

use Core\Models\Themes;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateTheme extends Command
{
    protected $signature = 'create:theme';
    protected $description = 'Create a new theme structure';

    public function handle()
    {
        //Ask for theme details
        $themeName = $this->ask('What is the theme name?');
        $author     = $this->ask('Who is the author?');
        $url        = $this->ask('Provide the author link (GitHub/Website)');
        $description = $this->ask('Provide a short description for the theme');
        $isRequireLicense = $this->confirm('Will this theme have a license?', true);

        //Normalize folder + namespace
        $location   = Str::lower(str_replace(' ', '_', $themeName));
        $folderName = ltrim(preg_replace('/\s+/', '', Str::camel($themeName)), '\\');
        $tempNameSpaced = Str::studly($folderName);
        $namespace  = "Theme\\{$tempNameSpaced}\\";

        $themePath = base_path("themes/{$location}");

        //Check if plugin exists
        if (File::exists($themePath)) {
            $this->error("Theme '{$location}' already exists!");
            return 1;
        }

        // Create directory structure
        File::makeDirectory($themePath, 0755, true, true);
        File::makeDirectory("{$themePath}/config", 0755, true, true);

        File::makeDirectory("{$themePath}/helpers", 0755, true, true);

        //Create routes
        File::makeDirectory("{$themePath}/routes", 0755, true, true);
        File::put("{$themePath}/routes/web.php", "<?php\n\nuse Illuminate\Support\Facades\Route;\n\n// Web routes for {$folderName} theme\n");
        File::put("{$themePath}/routes/api.php", "<?php\n\nuse Illuminate\Support\Facades\Route;\n\n// API routes for {$folderName} theme\n");

        File::makeDirectory("{$themePath}/src/Database/Migrations", 0755, true, true);
        File::makeDirectory("{$themePath}/src/Http/Controllers", 0755, true, true);

        //Create a sample controller
        File::put(
            "{$themePath}/src/Http/Controllers/ThemeController.php",
            <<<EOF
            <?php

            namespace {$namespace}Http\Controllers;
            use App\Http\Controllers\Controller;
            use Illuminate\Http\Request;

            class ThemeController extends Controller
            {
                /**
                 * Display a listing of the resource.
                 *
                 * @return \Illuminate\Http\Response
                 */
                public function index()
                {
                    return view('theme/{$location}::demo');
                }
            }
            EOF
        );

        //Create Models directory
        File::makeDirectory("{$themePath}/src/Models", 0755, true, true);

        //create public path
        File::makeDirectory("{$themePath}/public", 0755, true, true);

        //Create views
        File::makeDirectory("{$themePath}/resources/views", 0755, true, true);
        File::put("{$themePath}/resources/views/demo.blade.php", "<h1>This is a demo view of {$themeName}</h1>");

        File::makeDirectory("{$themePath}/resources/js", 0755, true, true);
        File::makeDirectory("{$themePath}/resources/css", 0755, true, true);

        //Create plugin.json
        $themeJson = [
            'name'        => $themeName,
            'location'    => $location,
            'namespace'   => $namespace,
            'version'     => '1.0.0',
            'author'      => $author,
            'url'         => $url,
            'description' => $description,
            'license'     => $isRequireLicense,
        ];

        File::put("{$themePath}/theme.json", json_encode($themeJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        //store plugin in database
        $theme = new Themes();
        $theme->name = $themeName;
        $theme->location = $location;
        $theme->namespace = $namespace;
        $theme->version = '1.0.0';
        $theme->author = $author;
        $theme->url = $url;
        $theme->description = $description;
        $theme->is_activated = 2;
        $theme->save();

        //Done
        $this->info("Theme '{$themeName}' created successfully!");
        $this->line("Location : themes/{$location}");
        $this->line("Namespace: {$namespace}");
        $this->line("Author   : {$author}");
        $this->line("URL      : {$url}");

        return 0;
    }
}
