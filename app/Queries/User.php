<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\User as ModelsUser;
use Illuminate\Support\Collection;
use Laravel\Sanctum\NewAccessToken;

final class User
{
    public function getById(string $id): ModelsUser
    {
        return ModelsUser::findOrFail($id);
    }

    public function createToken(string $userId, string $name, array $abilities = ['*']): NewAccessToken
    {
        return $this->getById($userId)->createToken(name: $name, abilities: $abilities);
    }

    public function userPlatforms(string $userId): Collection
    {
        return $this->getById($userId)->platforms;
    }
}
