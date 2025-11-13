<?php

namespace App\DTO\Auth;

use App\Models\User;

class AuthResponseDTO
{
    public function __construct(
        public readonly User $user,
        public readonly string $token
    ) {}

    /**
     * Convert DTO to array for JSON response.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'user' => $this->user,
            'token' => $this->token,
        ];
    }
}
