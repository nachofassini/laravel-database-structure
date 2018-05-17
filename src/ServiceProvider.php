<?php

namespace NachoFassini\LaravelDatabaseStructure;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use NachoFassini\LaravelDatabaseStructure\Console\Commands\CreateSchemaFile;
use NachoFassini\LaravelDatabaseStructure\Listeners\MigrationListener;

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

        if (env('AUTOMATIC_SCHEMA_FILE', false)) {
            Event::listen(CommandFinished::class, MigrationListener::class);
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
