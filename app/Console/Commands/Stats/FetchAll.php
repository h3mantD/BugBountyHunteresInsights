<?php

namespace App\Console\Commands\Stats;

use App\Models\UserPlatform;
use Illuminate\Console\Command;

class FetchAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:fetch-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all user stats from all platforms';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userPlatforms = UserPlatform::withoutGlobalScope("ofUser")->get();
        foreach ($userPlatforms as $userPlatform) {
            $username = $userPlatform->username;
            $platform = $userPlatform->platform->value;

            // execute node command
            $command = "node " . base_path("scrapper/index.js") . " --username={$username} --platform={$platform}";
            $output = shell_exec($command);
        }
    }
}
