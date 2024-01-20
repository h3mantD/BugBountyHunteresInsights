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
use Throwable;

final class PlatformController extends Controller
{
    use ApiResponses;

    /**
     * Returns all platforms of a user.
     *
     * @authenticated
     *
     * @response array{status: bool, platforms: array<string, string>}
     */
    public function all(User $user): JsonResponse
    {
        return $this->successResponse(
            data: ['status' => true, 'platforms' => $user->userPlatforms(Auth::user()->id)->toArray()]
        );
    }

    /**
     * Add a platform to a user.
     *
     * @authenticated
     *
     * @response array{status: bool, message: 'Platform added successfully! Otp sent to your email.'}
     */
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

    /**
     * Delete a platform of a user.
     *
     * @authenticated
     *
     * @response array{status: bool, message: 'Platform deleted successfully!'}
     */
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

    /**
     * Validated Otp
     *
     * @authenticated
     *
     * @param  string  $platform Platform name.
     *
     * @response array{status: bool, message: 'Otp validated successfully!'}
     */
    public function validateOtp(string $platform, Request $request, OtpService $otpService): JsonResponse
    {
        $request->validate(rules: [
            // OTP
            'otp' => ['required', 'string'],
        ]);
        $result = $otpService->validateOtp(platform: BBPlatform::tryFrom($platform), otp: $request->get(key: 'otp'));

        if ($result['status']) {
            return $this->successResponse(data: ['status' => true, 'message' => $result['message']]);
        }

        return $this->errorResponse(data: ['status' => false, 'message' => $result['message']]);
    }

    /**
     * Update stats of a platform.
     *
     * @authenticated
     *
     * @response array{status: bool, message: 'We are processing your stats update request!'}
     */
    public function updateStats(Request $request): JsonResponse
    {
        try {
            $request->validate(
                rules: [
                    // @var \App\Enums\BBPlatform Platform name.
                    'platform' => ['required', 'string'],
                    // Username of the platform.
                    'username' => ['required', 'string'],
                ]
            );

            return $this->successResponse(
                data: ['status' => true, 'message' => 'We are processing your stats update request!']
            );
        } catch (Throwable $th) {
            return $this->errorResponse(data: ['status' => false, 'message' => $th->getMessage()]);
        }
    }
}
