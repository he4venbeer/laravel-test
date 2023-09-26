<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class AppServiceProvider extends ServiceProvider
{
    protected string $modulePath = '';
    protected string $moduleName = '';

    /**
     * @param $app
     */
    public function __construct($app)
    {
        $reflector = new ReflectionClass(get_class($this));
        $name = $reflector->getFileName();

        $this->modulePath = dirname($name, 2);
        $this->moduleName = last(explode('/', $this->modulePath));

        parent::__construct($app);
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerRoutes();
    }

    /**
     * @return void
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFromPath($this->modulePath . '/Routes');
    }

    /**
     * @param string $path
     * @return void
     */
    protected function loadRoutesFromPath(string $path): void
    {
        $routeFiles = $this->getFilesFromPath($path);

        if ($routeFiles->isEmpty()) {
            return;
        }

        $router = $this->app->router ?? $this->app;

        $routeFiles->each(function ($file) use ($router, $path) {
            $router->group(
                [$file],
                function () use ($router, $file, $path) {
                    require $path . '/' . $file;
                }
            );
        });
    }

    /**
     * @param string $path
     * @return Collection
     */
    protected function getFilesFromPath(string $path): Collection
    {
        if (!is_dir($path)) {
            return collect();
        }

        return collect(scandir($path))->filter(function ($file) {
            if (in_array($file, ['.', '..'])) {
                return false;
            }

            if (!str_contains($file, '.php')) {
                return false;
            }

            return true;
        });
    }
}
