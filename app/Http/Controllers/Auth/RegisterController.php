<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use App\Services\TokenService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __construct(protected TokenService $tokenService, protected UserService $userService) {}

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());

        $ip = $request->ip();
        $user_agent = $request->userAgent();

        $token = $this->tokenService->createTokenUser($user, 'device_name', $ip, $user_agent);
        
        $user->token = $token;
        
        return response()->json(
            [
                'message' => 'Registration successful.',
                'data' => UserResource::make($user)
            ],
            status: 201
        );
    }
}
