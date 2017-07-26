# Package that generates a schema file

## Install

You can pull in the package via composer:
``` bash
    composer require nachofassini/laravel-database-structure
```

Next up, the service provider must be registered:

```php
    // config/app.php
    'providers' => [
        ...
        NachoFassini\LaravelDatabaseStructure\ServiceProvider::class,
        
    ];
```

## How to generate schema file 

To generate schema file just type in the console:

``` bash
    php artisan schema:file
```

The file should be generated at your database path like ```schema.php``` and would look like these:

``` php
    $tables => [
        'users' => [
            'id',
            'name',
            'email',
            'created_at',
            ...
        ],
        'posts' => [
            'user_id',
            'title',
            ...
        ]
    ];
```

There are no conventions about this, but I think this file should be ignored.

## Automatic update on migrations

If you want the file to be updated every time you change the database through any migrate command, just add the following:

```php
    // app/Console/Kernel
    use NachoFassini\LaravelDatabaseStructure\LaravelDatabaseStructureTrait;
    
    class Kernel extends ConsoleKernel
    {
        use LaravelDatabaseStructureTrait;
```

This will make that after executing any kind of migration command, the schema file gets up to date with the final database structure.

## Contributing

I think this can be much more useful, any collaboration is appreciated.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
