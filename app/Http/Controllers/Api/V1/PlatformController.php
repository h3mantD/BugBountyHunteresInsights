<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\BBPlatform;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponses;
use App\Http\Requests\V1\AddPlatformRequest;
use App\Http\Requests\V1\DeletePlatformRequest;
use App\Queries\User;
use App\Services\UserPlatformService;
use Illuminate\Http\JsonResponse;
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

    public function add(AddPlatformRequest $addPlatformRequest, UserPlatformService $userPlatformService): JsonResponse
    {
        $userPlatformData = $addPlatformRequest->validated();
        $userPlatform = $userPlatformService->create(userPlatformData: $userPlatformData);

        return $this->successResponse(
            data: ['status' => true, 'message' => 'Platform added successfully!', 'user_platform' => $userPlatform]
        );
    }

    public function delete(DeletePlatformRequest $deletePlatformRequest, UserPlatformService $userPlatformService): JsonResponse
    {
        $platformDetails = $deletePlatformRequest->validated();
        $userPlatformService->delete(
            platform: BBPlatform::tryFrom($platformDetails['platform']),
            username: $platformDetails['username']
        );

        return $this->successResponse(
            data: ['status' => true, 'message' => 'Platform deleted successfully!']
        );
    }
}
