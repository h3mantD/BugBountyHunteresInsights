<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

final class UserService
{
    public function create(array $userData): void
    {
        User::create($userData);
    }

    public function update(User $user, array $userData): void
    {
        $user->update(attributes: $userData);
    }
}
