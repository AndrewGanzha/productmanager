<?php

namespace App\Services\Auth;

use App\DTO\Auth\AuthResponseDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Register a new user and create an authentication token.
     *
     * @param array<string, mixed> $data
     * @return AuthResponseDTO
     */
    public function register(array $data): AuthResponseDTO
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return new AuthResponseDTO($user, $token);
    }

    /**
     * Login an existing user and create an authentication token.
     *
     * @param array<string, mixed> $credentials
     * @return AuthResponseDTO
     * @throws ValidationException
     */
    public function login(array $credentials): AuthResponseDTO
    {
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Неверный email или пароль.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return new AuthResponseDTO($user, $token);
    }

    /**
     * Logout the user by deleting their current access token.
     *
     * @param User $user
     * @return void
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
