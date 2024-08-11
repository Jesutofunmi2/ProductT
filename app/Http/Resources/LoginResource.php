<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'token' => gettype($token = $this->currentAccessToken()) === 'string' ? $token : null,            
        ];
    }
}
