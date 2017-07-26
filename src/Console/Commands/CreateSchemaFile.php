<?php

namespace NachoFassini\LaravelDatabaseStructure\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Thedevsaddam\LaravelSchema\Schema\Helper;
use Thedevsaddam\LaravelSchema\Schema\Schema;
use Symfony\Component\Console\Input\InputOption;

class CreateSchemaFile extends Command
{
    use Helper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'schema:file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the file schema.php with a list of tables and their fields and types';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The Schema instance.
     *
     * @var \Thedevsaddam\LaravelSchema\Schema\Schema
     */
    protected $schema;

    public function __construct(Filesystem $files, Schema $schema)
    {
        parent::__construct();
        $this->files = $files;
        $this->schema = $schema;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Generating schema file');
        if ($this->option('c')) {
            $this->schema->setConnection($this->option('c'));
            $this->schema->switchWrapper();
        }

        $tables = $this->schema->databaseWrapper->getTables();
        if (!count($tables)) {
            $this->warn('Database does not contain any table');
            return;
        }

        $tables = [];
        $bar = $this->output->createProgressBar(count($this->schema->databaseWrapper->getSchema()));
        foreach ($this->schema->databaseWrapper->getSchema() as $table => $attributes) {
            $tableData = [];
            foreach ($attributes['attributes'] as $attribute) {
                $tableData[] = $attribute['Field'];
            }
            $tables[$table] = $tableData;
            $bar->advance();
        }
        $this->create($tables);
        $bar->finish();
        $this->line('');
        $this->info('Schema file generated successfully');
    }

    /**
     * Create a new migration at the given path.
     *
     * @param  array $tables
     * @return string
     * @throws \Exception
     */
    public function create($tables)
    {
        $path = database_path('schema.php');

        $stub = $this->getStub();

        $this->files->put($path, $this->populateStub($tables, $stub));

        return $path;
    }

    /**
     * Get the schema stub file.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->files->get($this->getStubPath() . '/schema.stub');
    }

    /**
     * Populate the place-holders in the migration stub.
     *
     * @param  array $tables
     * @param  string $stub
     * @return string
     */
    protected function populateStub($tables, $stub)
    {
        $tables = $this->transformToString($tables);
        return str_replace('$tables', $tables, $stub);
    }

    /**
     * @param array $tables
     * @return string
     */
    protected function transformToString(array $tables)
    {
        $stringTables = 'return [';
        $stringTables .= $this->lineSeparator();
        foreach ($tables as $name => $data) {
            $stringTables .= "    '{$name}' => [";
            $stringTables .= $this->lineSeparator();
            foreach ($data as $column) {
                $stringTables .= "        '{$column}',";
                $stringTables .= $this->lineSeparator();
            }
            $stringTables .= "    ],";
            $stringTables .= $this->lineSeparator();
            $stringTables .= $this->lineSeparator();
        }
        $stringTables .= "];";
        return $stringTables;
    }

    /**
     * Get the default line separator
     *
     * @return string
     */
    protected function lineSeparator()
    {
        return "\n";
    }

    /**
     * Get the path to the stubs.
     *
     * @return string
     */
    public function getStubPath()
    {
        return __DIR__ . '../stubs';
    }

    protected function getOptions()
    {
        return [
            ['c', 'c', InputOption::VALUE_OPTIONAL, 'Connection name'],
        ];
    }
}
