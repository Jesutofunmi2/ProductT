<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [];

        if (is_null($this->resource)) {
            return [];
        }

        $user = $request->user();

        if ($user && $user->id === $this->id) {
            $data = [
                'email' => $this->email,
                'email_verified_at' => $this->email_verified_at,
            ];
        }

        return $data;
    }
}
