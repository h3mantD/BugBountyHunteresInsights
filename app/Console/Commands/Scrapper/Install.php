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

        // check if .env file exists in scrapper directory
        if (! file_exists(filename: "{$basePath}/.env")) {
            $this->info(string: 'Creating .env file...');

            // create .env file from .env.sample
            $command = "bash -c 'cd {$basePath}; cp .env.sample .env'";
            $output = shell_exec(command: $command);
        }

        // fetch the content from the .env file
        $env = file_get_contents(filename: "{$basePath}/.env");
        $lines = explode(separator: "\n", string: $env);
        foreach ($lines as &$line) {
            // Skip empty lines and comments
            if (empty($line) || str($line)->startsWith(needles: '#')) {
                continue;
            }

            $data = explode(separator: '=', string: $line, limit: 2);
            $key = $data[0];
            $data[1] = env(key: $key, default: '');

            $line = implode(separator: '=', array: $data);
        }

        $this->info(string: 'Updating .env file...');
        // save the content to the .env file
        file_put_contents(filename: "{$basePath}/.env", data: implode(separator: "\n", array: $lines));

        $this->info(string: 'Scrapper installed successfully.');
    }
}
