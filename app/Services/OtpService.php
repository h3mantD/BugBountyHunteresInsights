<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\BBPlatform;
use App\Mail\OtpMail;
use App\Models\UserPlatform;
use App\Queries\UserPlatform as QueriesUserPlatform;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Mail;

final class OtpService
{
    public function __construct(private QueriesUserPlatform $user)
    {
    }

    public function generateOtp(UserPlatform $userPlatform): void
    {
        $otp = Otp::generate(identifier: $userPlatform->email, digits: 6, validity: 10);

        Mail::to($userPlatform->email)->send(new OtpMail(otp: $otp->token));
    }

    public function validateOtp(BBPlatform $platform, string $otp): array
    {
        $userPlatform = $this->user->getByPlatform(platform: $platform);

        $otp = Otp::validate(identifier: $userPlatform->email, token: $otp);

        if ($otp->status) {
            $userPlatform->update(['verified' => true]);
        }

        return ['status' => $otp->status, 'message' => $otp->message];
    }
}
