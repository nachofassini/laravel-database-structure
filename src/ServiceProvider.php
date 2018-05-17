<?php

namespace NachoFassini\LaravelDatabaseStructure;

use App\Listeners\MigrationListener;
use Illuminate\Support\Facades\Event;
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

        //dd('asdasd');

        Event::listen('event.CommandFinished', function ($event) {
            new MigrationListener($event);
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(\Thedevsaddam\LaravelSchema\LaravelSchemaServiceProvider::class);
    }
}
