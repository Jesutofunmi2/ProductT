<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController
{
    public function __construct(protected TokenService $service) {}
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $user = $this->authenticateUser($request);

        $ip = $request->ip();
        $user_agent = $request->userAgent();
        $token = $this->service->createTokenUser(
            $user,
            'device_name',
            $ip,
            $user_agent
        );
        $user->token = $token;
        return response()->json([
            'message' => 'Login Successful',
            'data' => UserResource::make($user)
        ]);
    }


    protected function authenticateUser(LoginRequest $request): User
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            throw ValidationException::withMessages(['credentials' => __('auth.failed')]);
        }

        return $request->user();
    }
}
