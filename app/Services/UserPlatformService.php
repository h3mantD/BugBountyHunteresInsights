<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\BBPlatform;
use App\Jobs\UpdateUserStats;
use App\Models\UserPlatform;
use App\Queries\UserPlatform as QueriesUserPlatform;

final class UserPlatformService
{
    public function __construct(private QueriesUserPlatform $userPlatform)
    {
    }

    public function create(array $userPlatformData): UserPlatform
    {
        return UserPlatform::create($userPlatformData);
    }

    public function update(UserPlatform $userPlatform, array $userPlatformData): UserPlatform
    {
        $userPlatform->update(attributes: $userPlatformData);

        return $userPlatform->refresh();
    }

    public function delete(BBPlatform $platform, string $username): void
    {
        UserPlatform::query()->ofPlatformAndUsername(platform: $platform, username: $username)->delete();
    }

    public function updateStats(BBPlatform $platform, string $username): void
    {
        $userPlatform = UserPlatform::query()
            ->ofPlatformAndUsername(
                platform: $platform->value,
                username: $username
            )->firstOrFail();
        dispatch(new UpdateUserStats(userPlatform: $userPlatform));
    }
}
