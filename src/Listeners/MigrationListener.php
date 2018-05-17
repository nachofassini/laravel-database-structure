<?php

namespace App\Listeners;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Artisan;

class MigrationListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $event;

    /**
     * Create the event listener.
     *
     * @param CommandFinished $event
     * @return void
     */
    public function __construct(CommandFinished $event)
    {
        $this->event = $event;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($this->isMigrating($this->event->command)) {
            $this->generateSchemaFile();
        }
    }

    /**
     * Detect if the command is a migration
     *
     * @param string $command
     * @return bool
     */
    public function isMigrating(string $command): bool
    {
        return starts_with($command, 'migrate');
    }

    /**
     * Generate the schema file
     *
     * @return void
     */
    protected function generateSchemaFile(): void
    {
        Artisan::call('schema:file');
    }
}
