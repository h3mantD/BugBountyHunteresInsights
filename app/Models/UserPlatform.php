<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BBPlatform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

// use Illuminate\Database\Eloquent\Model;

final class UserPlatform extends Model
{
    use HasFactory;

    protected $collection = 'user_platform';

    protected $casts = [
        'platform' => BBPlatform::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }
}
