<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\BBPlatform;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponses;
use App\Http\Requests\V1\AddPlatformRequest;
use App\Http\Requests\V1\DeletePlatformRequest;
use App\Queries\User;
use App\Services\OtpService;
use App\Services\UserPlatformService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class PlatformController extends Controller
{
    use ApiResponses;

    public function all(User $user): JsonResponse
    {
        return $this->successResponse(
            data: ['status' => true, 'platforms' => $user->userPlatforms(Auth::user()->id)->toArray()]
        );
    }

    public function add(
        AddPlatformRequest $addPlatformRequest,
        UserPlatformService $userPlatformService,
        OtpService $otpService
    ): JsonResponse {
        $userPlatformData = $addPlatformRequest->validated();
        $userPlatform = $userPlatformService->create(userPlatformData: $userPlatformData);
        $otpService->generateOtp(userPlatform: $userPlatform);

        return $this->successResponse(
            data: ['status' => true, 'message' => 'Platform added successfully! Otp sent to your email.']
        );
    }

    public function delete(
        DeletePlatformRequest $deletePlatformRequest,
        UserPlatformService $userPlatformService
    ): JsonResponse {
        $platformDetails = $deletePlatformRequest->validated();
        $userPlatformService->delete(
            platform: BBPlatform::tryFrom($platformDetails['platform']),
            username: $platformDetails['username']
        );

        return $this->successResponse(
            data: ['status' => true, 'message' => 'Platform deleted successfully!']
        );
    }

    public function validateOtp(string $platform, Request $request, OtpService $otpService): JsonResponse
    {
        $request->validate(rules: ['otp' => ['required', 'string']]);
        $result = $otpService->validateOtp(platform: BBPlatform::tryFrom($platform), otp: $request->get(key: 'otp'));

        if ($result['status']) {
            return $this->successResponse(data: ['status' => true, 'message' => $result['message']]);
        }

        return $this->errorResponse(data: ['status' => false, 'message' => $result['message']]);
    }
}
