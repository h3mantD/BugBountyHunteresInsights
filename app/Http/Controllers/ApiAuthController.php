<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ApiResponses;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Queries\User as QueriesUser;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class ApiAuthController extends Controller
{
    use ApiResponses;

    public function login(LoginRequest $request, QueriesUser $user): JsonResponse
    {
        $request->authenticate();
        $token = $user->createToken(userId: Auth::user()->id, name: $request->userAgent());

        return $this->successResponse(
            data: ['status' => true, 'message' => 'Authenticated Successfully!', 'token' => $token->plainTextToken]
        );
    }

    public function logout(): void
    {
        // code
    }

    public function register(RegisterRequest $registerRequest, UserService $userService): JsonResponse
    {
        $validatedData = $registerRequest->validated();
        $userService->create(userData: $validatedData);

        return $this->successResponse(
            data: ['status' => true, 'message' => 'User created successfully!']
        );
    }
}
