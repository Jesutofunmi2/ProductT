<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController
{
    /**
     * Create a new controller instance.
     *
     * @param TokenService $service
     */
    public function __construct(protected TokenService $service) {}

    /**
     * Handle an incoming login request.
     *
     * Authenticates the user, creates an authentication token, and returns a JSON response with the user data.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
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

    /**
     * Authenticate the user based on the provided credentials.
     *
     * @param LoginRequest $request
     * @return User
     * @throws ValidationException
     */
    protected function authenticateUser(LoginRequest $request): User
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            throw ValidationException::withMessages(['credentials' => __('auth.failed')]);
        }

        return $request->user();
    }
}
