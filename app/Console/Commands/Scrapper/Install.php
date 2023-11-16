<?php

declare(strict_types=1);

namespace App\Console\Commands\Scrapper;

use Illuminate\Console\Command;

final class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrapper:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install scrapper';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $basePath = str(string: base_path('scrapper'))->replace(search: '\\', replace: '/');
        $command = "bash -c 'cd {$basePath}; npm install'";

        $this->info(string: 'Installing scrapper...');

        $output = shell_exec(command: $command);
    }
}
