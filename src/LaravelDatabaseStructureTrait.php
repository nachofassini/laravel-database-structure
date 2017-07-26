<?php

namespace NachoFassini\LaravelDatabaseStructure;

trait LaravelDatabaseStructureTrait
{
    /**
     * Terminate the application.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  int  $status
     * @return void
     */
    public function terminate($input, $status)
    {
        if ($this->isMigrating($input)) {
            $this->generateSchemaFile();
        }

        parent::terminate($input, $status);
    }

    /**
     * Detect if the command is a migration
     *
     * @param $input
     * @return boolean
     */
    public function isMigrating($input)
    {
        return starts_with($input->getArgument('command'), 'migrate');
    }

    /**
     * Generate the schema file
     *
     * @return void
     */
    protected function generateSchemaFile()
    {
        $this->call('schema:file');
    }
}
