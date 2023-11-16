<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\UserPlatform;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

final class UpdateUserStats implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private UserPlatform $userPlatform)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Artisan::call(
            command: 'stats:fetch-user',
            parameters: [
                'platform' => $this->userPlatform->platform->value,
                'username' => $this->userPlatform->username,
            ]
        );
    }
}
