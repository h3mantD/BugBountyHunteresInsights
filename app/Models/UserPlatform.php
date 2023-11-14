<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BBPlatform;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use MongoDB\Laravel\Eloquent\Builder;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

// use Illuminate\Database\Eloquent\Model;

/**
 * @method \MongoDB\Laravel\Eloquent\Builder ofPlatformAndUsername(\App\Enums\BBPlatform $platform, string $username)
 */
final class UserPlatform extends Model
{
    use HasFactory;

    protected $collection = 'user_platforms';

    protected $casts = [
        'platform' => BBPlatform::class,
    ];

    protected $fillable = [
        'platform',
        'username',
        'user_id',
        'verified',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }

    public function statUrl(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => BBPlatform::from($attributes['platform'])
                ->statUrl(username: $attributes['username']),
        );
    }

    public function email(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => BBPlatform::from($attributes['platform'])
                ->email(username: $attributes['username']),
        );
    }

    public function scopeOfPlatformAndUsername(Builder $query, BBPlatform $platform, string $username): void
    {
        $query->where('user_id', Auth::user()?->id)->where('platform', $platform->value)->where('username', $username);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        self::addGlobalScope('ofUser', function (Builder $builder): void {
            $builder->where('user_id', Auth::user()?->id);
        });
    }
}
