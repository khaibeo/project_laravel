<?php

namespace Modules;

use Modules\User\src\Repositories\UserRepository;
use Closure;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use League\CommonMark\Parser\Block\DocumentBlockParser;
use Modules\Category\src\Repositories\CategoryRepository;
use Modules\Category\src\Repositories\CategoryRepositoryInterface;
use Modules\Courses\src\Repositories\CoursesRepository;
use Modules\Courses\src\Repositories\CoursesRepositoryInterface;
use Modules\Dashboard\src\Repositories\DashboardRepository;
use Modules\Document\src\Repositories\DocumentRepository;
use Modules\Document\src\Repositories\DocumentRepositoryInterface;
use Modules\Lessons\src\Repositories\LessonsRepository;
use Modules\Lessons\src\Repositories\LessonsRepositoryInterface;
use Modules\Students\src\Http\Middlewares\BlockStudentMiddleware;
use Modules\Students\src\Repositories\StudentsRepository;
use Modules\Students\src\Repositories\StudentsRepositoryInterface;
use Modules\Teacher\src\Repositories\TeacherRepository;
use Modules\Teacher\src\Repositories\TeacherRepositoryInterface;
use Modules\User\src\Commands\Test;
use Modules\User\src\Http\Middlewares\DemoMiddleware;
use Modules\User\src\Repositories\UserRepositoryInterface;
use Modules\Video\src\Repositories\VideoRepository;
use Modules\Video\src\Repositories\VideoRepositoryInterface;

class ModuleServiceProvider extends ServiceProvider
{
    private $middlewares = [
        'blockStudent' => BlockStudentMiddleware::class,
    ];

    private $commands = [
        // Test::class,
    ];

    public function boot()
    {
        $dir = $this->getModule();

        if (!empty($dir)) {
            foreach ($dir as $directory) {
                $this->registerModule($directory);
            }
        }

        $request = request();
        if($request->is('admin') || $request->is('admin/*')){
            $this->app['router']->pushMiddlewareToGroup('web', 'auth');
        }
    }

    public function register()
    {
        //config
        $dir = $this->getModule();

        if (!empty($dir)) {
            foreach ($dir as $directory) {
                $this->registerConfig($directory);
            }
        }

        //midddleware
        $middlewares = $this->middlewares;

        if (!empty($middlewares)) {
            $this->registerMiddleware($middlewares);
        }

        //command
        $this->commands($this->commands);

        $this->getRepository();
    }

    private function registerMiddleware($middlewares)
    {
        foreach ($middlewares as $key => $value) {
            $this->app['router']->pushMiddlewareToGroup($key, $value);
        }
    }

    private function getRepository()
    {
        $this->app->singleton(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->singleton(
            TeacherRepositoryInterface::class,
            TeacherRepository::class
        );

        $this->app->singleton(
            TeacherRepositoryInterface::class,
            TeacherRepository::class
        );

        $this->app->singleton(
            CoursesRepositoryInterface::class,
            CoursesRepository::class
        );

        $this->app->singleton(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->singleton(
            LessonsRepositoryInterface::class,
            LessonsRepository::class
        );

        $this->app->singleton(
            VideoRepositoryInterface::class,
            VideoRepository::class
        );

        $this->app->singleton(
            DocumentRepositoryInterface::class,
            DocumentRepository::class
        );

        $this->app->singleton(
            StudentsRepositoryInterface::class,
            StudentsRepository::class
        );
    }

    private function getModule()
    {
        $dir = array_map('basename', File::directories(__DIR__));

        return $dir;
    }

    private function registerConfig($directory)
    {
        $configPath = __DIR__ . "/" . $directory . "/config";

        if (File::exists($configPath)) {
            $configFiles = array_map('basename', File::allFiles($configPath));
            foreach ($configFiles as $config) {
                $alias = basename($config, '.php');
                $this->mergeConfigFrom($configPath . "/$config", $alias);
            }
        }
    }

    private function registerModule($moduleName)
    {
        $modulePath = __DIR__ . "/$moduleName";

        //Khai báo route web
        Route::group(['namespace' => "Modules\\{$moduleName}\src\Http\Controllers", 'middleware' => 'web'], function () use ($modulePath) {
            if (File::exists($modulePath . "/routes/web.php")) {
                $this->loadRoutesFrom($modulePath . "/routes/web.php");
            }
        });

        //Khai báo route web Api
        Route::group(['namespace' => "Modules\\{$moduleName}\src\Http\Controllers", 'middleware' => 'api', 'prefix' => 'api'], function () use ($modulePath) {
            if (File::exists($modulePath . "/routes/api.php")) {
                $this->loadRoutesFrom($modulePath . "/routes/api.php");
            }
        });

        //khai báo migration
        if (File::exists($modulePath . "/migrations")) {
            $this->loadMigrationsFrom($modulePath . "/migrations");
        }

        //khai báo languages
        if (File::exists($modulePath . "/resources/lang")) {
            $this->loadTranslationsFrom($modulePath . "/resources/lang", $moduleName);
            $this->loadJsonTranslationsFrom($modulePath . "/resources/lang");
        }

        //khai báo view
        if (File::exists($modulePath . "/resources/views")) {
            $this->loadViewsFrom($modulePath . "/resources/views", $moduleName);
        }
        //khai báo helpers
        if (File::exists($modulePath . "/helpers")) {
            $allFile = File::allFiles($modulePath . "/helpers");

            foreach ($allFile as $value) {
                $file = $value->getPathname();
                require $file;
            }
        }
    }
}
