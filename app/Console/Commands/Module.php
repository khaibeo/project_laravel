<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class Module extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new module CLI';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        if(File::exists(base_path('modules/' . $name) )){
            $this->error('Module already exist');
            return 1;
        }

        //Tạo file nếu không tồn tại
        File::makeDirectory(base_path('modules/' . $name),0755,true,true);

        //config
        $configPath = base_path('modules/' . $name . "/config");
        File::makeDirectory($configPath,0755, true, true);

        //helpers
        $helperPath = base_path('modules/' . $name . "/Helpers");
        File::makeDirectory($helperPath,0755, true, true);

        //migrations
        $migrationPath = base_path('modules/' . $name . "/migrations");
        File::makeDirectory($migrationPath,0755, true, true);

        //resources
        $resourcePath = base_path('modules/' . $name . "/resources");
        File::makeDirectory($resourcePath,0755, true, true);
        $lang = base_path('modules/' . $name . "/resources/lang");
        File::makeDirectory($lang,0755, true, true);
        $views = base_path('modules/' . $name . "/resources/views");
        File::makeDirectory($views,0755, true, true);

        //routes
        $routePath = base_path('modules/' . $name . "/routes");
        File::makeDirectory($routePath,0755, true, true);
        $routeFile = base_path('modules/' . $name . "/routes/routes.php");
        File::put($routeFile, "<?php \nuse Illuminate\Support\Facades\Route;");

        //src
        $srcPath = base_path('modules/' . $name . "/src");
        File::makeDirectory($srcPath,0755, true, true);

        $command = base_path('modules/' . $name . "/src/Commands");
        File::makeDirectory($command,0755, true, true);

        $http = base_path('modules/' . $name . "/src/Http");
        File::makeDirectory($http,0755, true, true);

        $controller = $http . "/Controllers";
        File::makeDirectory($controller,0755, true, true);

        $middleware = $http . "/Middlewares";
        File::makeDirectory($middleware,0755, true, true);

        $model = base_path('modules/' . $name . "/src/Models");
        File::makeDirectory($model,0755, true, true);

        //Repositories
        $repositories = base_path('modules/' . $name . "/src/Repositories");
        File::makeDirectory($repositories,0755, true, true);

        $repositoryFile = base_path('modules/' . $name . "/src/Repositories/{$name}Repository.php");
        $repositoryContent = file_get_contents(app_path('Console/Commands/Templates/ModuleRepository.txt'));
        $repositoryContent = str_replace('{name}',$name,$repositoryContent);
        File::put($repositoryFile, $repositoryContent);

        $repositoryInterfaceFile = base_path('modules/' . $name . "/src/Repositories/{$name}RepositoryInterface.php");
        $repositoryInterFaceContent = file_get_contents(app_path('Console/Commands/Templates/ModuleRepositoryInterface.txt'));
        $repositoryInterFaceContent = str_replace('{name}',$name,$repositoryInterFaceContent);
        File::put($repositoryInterfaceFile, $repositoryInterFaceContent);

        // Success
        $this->info('Create module success !');
    }
}
