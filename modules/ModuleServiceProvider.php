<?php
namespace Modules;

use Modules\User\src\Repositories\UserRepository;
use Closure;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Modules\User\src\Commands\Test;
use Modules\User\src\Http\Middlewares\DemoMiddleware;

class ModuleServiceProvider extends ServiceProvider
{
    private $middlewares = [
        'demo' => DemoMiddleware::class,
    ];

    private $commands = [
        Test::class,
    ];

    public function boot()
    {
       $dir = $this->getModule();

       if(!empty($dir)){
            foreach ($dir as $directory) {
                $this->registerModule($directory);
            }
       }
    }

    public function register()
    {
        //config
        $dir = $this->getModule();

       if(!empty($dir)){
            foreach ($dir as $directory) {
                $this->registerConfig($directory);
            }
       }

       //midddleware
       $middlewares = $this->middlewares;

       if(!empty($middlewares)){
        $this->registerMiddleware($middlewares);
       }

       //command
       $this->commands($this->commands);

       $this->app->singleton(
        UserRepository::class
        );
    }

    private function registerMiddleware($middlewares){
        foreach ($middlewares as $key => $value) {
            $this->app['router']->pushMiddlewareToGroup($key,$value);
           }
    }

    private function getModule(){
        $dir = array_map('basename',File::directories(__DIR__));

        return $dir;
    }

    private function registerConfig($directory){
        $configPath = __DIR__ . "/". $directory . "/config";

        if(File::exists($configPath)){
            $configFiles = array_map('basename',File::allFiles($configPath));
            foreach ($configFiles as $config) {
                $alias = basename($config, '.php');
                $this->mergeConfigFrom($configPath . "/$config",$alias);
            }
        }    
    }

    private function registerModule($moduleName){
        $modulePath = __DIR__ . "/$moduleName";

        //Khai báo route
        if(File::exists($modulePath . "/routes/routes.php")){
            $this->loadRoutesFrom($modulePath . "/routes/routes.php");
        }
        
        //khai báo migration
        if(File::exists($modulePath . "/migrations")){
            $this->loadMigrationsFrom($modulePath . "/migrations");
        }
        //khai báo languages
        if(File::exists($modulePath . "/resources/lang")){
            $this->loadTranslationsFrom($modulePath . "/resources/lang",$moduleName);
            $this->loadJsonTranslationsFrom($modulePath . "/resources/lang");
        }

        //khai báo view
        if(File::exists($modulePath . "/resources/views")){
            $this->loadViewsFrom($modulePath . "/resources/views",$moduleName);
        }
        //khai báo helpers
        if(File::exists($modulePath . "/helpers")){
            $allFile = File::allFiles($modulePath . "/helpers");

            foreach ($allFile as $value) {
                $file = $value->getPathname();
                require $file;
            }
        }
    }
}