<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\User as ModelsUser;
use Laravel\Sanctum\NewAccessToken;

final class User
{
    public function createToken(string $userId, string $name, array $abilities = ['*']): NewAccessToken
    {
        return ModelsUser::find($userId)->createToken(name: $name, abilities: $abilities);
    }
}
