<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\LoginResource;
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
            $userData['device_name'] ?? 'test_device',
            $ip,
            $user_agent
        );

        return response()->json([
            'message' => 'Login Successful',
            'data' => LoginResource::make($user->withAccessToken($token))
        ]);
    }


    protected function authenticateUser(LoginRequest $request): User
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            throw ValidationException::withMessages(['credentials' => __('auth.failed')]);
        }

        $data = $request->validated();

        $user = User::whereEmail($data['email'])->first();

        abort_if(is_null($user), 401, 'Incorrect login details');

        if (!Hash::check($data['password'], $user->password)) {
            abort(401, 'Incorrect login details');
        }

        return $user;
    }
}
