<?php

namespace NachoFassini\LaravelDatabaseStructure;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Foundation\Application as LaravelApplication;
use NachoFassini\LaravelDatabaseStructure\Console\Commands\CreateSchemaFile;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateSchemaFile::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(\Thedevsaddam\LaravelSchema\LaravelSchemaServiceProvider::class);
    }

    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../resources/config/laravel-database-structure.php');

        if ($this->app instanceof LaravelApplication) {
            $this->publishes([$source => config_path('laravel-database-structure.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('laravel-database-structure');
        }
        $this->mergeConfigFrom($source, 'laravel-database-structure');
    }
}
