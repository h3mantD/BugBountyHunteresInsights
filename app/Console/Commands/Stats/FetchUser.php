<?php

namespace App\Console\Commands\Stats;

use Illuminate\Console\Command;

class FetchUser extends Command
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
    public function handle()
    {
        $username = $this->argument('username');
        $platform = $this->argument('platform');

        $this->info("Fetching stats for {$username} on {$platform}...");

        // execute node command
        $command = "node " . base_path("scrapper/index.js") . " --username={$username} --platform={$platform}";

        $output = shell_exec($command);
    }
}
