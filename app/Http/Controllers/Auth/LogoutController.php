<?php

namespace App\Http\Controllers\Auth;

use App\Services\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController
{
    public function __construct(protected TokenService $service) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->service->revokeCurrentToken($request->user());

        return response()->json([
            'message' => 'Token deleted successfully.',
            'data' => null
        ]);
    }
}
