<?php

declare(strict_types=1);

namespace App\Enums;

enum BBPlatform: string
{
    case HACKERONE = 'hackerone';
    case BUGCROWD = 'bugcrowd';
    case YESWEHACK = 'yeswehack';

    public static function getValues(): array
    {
        return array_column(array: self::cases(), column_key: 'value');
    }

    public function statUrl(string $username)
    {
        return match ($this) {
            self::HACKERONE => "https://hackerone.com/{$username}",
            self::BUGCROWD => "https://bugcrowd.com/{$username}",
            self::YESWEHACK => "https://api.yeswehack.com/hunters/{$username}",
        };
    }

    public function email(string $username)
    {
        return match ($this) {
            self::HACKERONE => "{$username}@wearehackerone.com",
            self::BUGCROWD => "{$username}@bugcrowdninja.com",
            self::YESWEHACK => "{$username}@yeswehack.ninja",
        };
    }
}
