<?php

namespace NachoFassini\LaravelDatabaseStructure;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
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
}
