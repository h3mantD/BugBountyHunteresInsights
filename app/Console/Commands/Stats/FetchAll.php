<?php

declare(strict_types=1);

namespace App\Console\Commands\Stats;

use App\Models\UserPlatform;
use Illuminate\Console\Command;

final class FetchAll extends Command
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
    public function handle(): void
    {
        $userPlatforms = UserPlatform::withoutGlobalScope('ofUser')->get();
        foreach ($userPlatforms as $userPlatform) {
            $username = $userPlatform->username;
            $platform = $userPlatform->platform->value;

            // execute node command
            $basePath = str(string: base_path('scrapper'))->replace(search: '\\', replace: '/');
            $command = "bash -c 'cd {$basePath}; node index.js --username={$username} --platform={$platform}'";

            $output = shell_exec($command);
        }
    }
}
