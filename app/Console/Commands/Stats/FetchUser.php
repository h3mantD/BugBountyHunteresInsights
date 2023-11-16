<?php

declare(strict_types=1);

namespace App\Console\Commands\Stats;

use Illuminate\Console\Command;

final class FetchUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:fetch-user {username} {platform}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch user stats from a platform';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $username = $this->argument('username');
        $platform = $this->argument('platform');

        $this->info("Fetching stats for {$username} on {$platform}...");

        // execute node command
        $basePath = str(string: base_path('scrapper'))->replace(search: '\\', replace: '/');
        $command = "bash -c 'cd {$basePath}; node index.js --username={$username} --platform={$platform}'";

        $output = shell_exec($command);
    }
}
