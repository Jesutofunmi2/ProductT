<?php

namespace App\Http\Controllers\Auth;

use App\Services\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController
{   
    /**
     * Create a new controller instance.
     *
     * @param TokenService $service
     */
    public function __construct(protected TokenService $service) {}
    
    /**
     * Handle an incoming logout request.
     *
     * Revokes the current authentication token for the user and returns a JSON response.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->service->revokeCurrentToken($request->user());

        return response()->json([
            'message' => 'Token deleted successfully.',
            'data' => null
        ]);
    }
}
