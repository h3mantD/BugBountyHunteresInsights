<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\BBPlatform;
use App\Models\UserPlatform as UserPlatformModel;

final class UserPlatform
{
    public function getUserNamesByPlatform(BBPlatform $platform): array
    {
        return UserPlatformModel::query()
            ->withoutGlobalScope('ofUser')
            ->where('platform', $platform->value)
            ->pluck('username')
            ->toArray();
    }

    public function isIfPlatformIsAttachedToUser(BBPlatform $platform, string $username): bool
    {
        return UserPlatformModel::query()
            ->ofPlatformAndUsername($platform, $username)
            ->exists();
    }
}
