<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test authenticated user can logout.
     */
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Выход выполнен успешно.']);
    }

    /**
     * Test unauthenticated user cannot logout.
     */
    public function test_unauthenticated_user_cannot_logout(): void
    {
        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(401);
    }

    /**
     * Test logout deletes the current access token.
     */
    public function test_logout_deletes_current_access_token(): void
    {
        $user = User::factory()->create();
        $tokenModel = $user->createToken('auth-token');
        $token = $tokenModel->plainTextToken;

        // Verify token exists before logout
        $this->assertDatabaseHas('personal_access_tokens', [
            'id' => $tokenModel->accessToken->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200);

        // Verify the token is deleted from database
        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $tokenModel->accessToken->id,
        ]);
    }

    /**
     * Test logout only deletes current token, not all user tokens.
     */
    public function test_logout_only_deletes_current_token(): void
    {
        $user = User::factory()->create();

        $tokenModel1 = $user->createToken('auth-token-1');
        $token1 = $tokenModel1->plainTextToken;
        $tokenModel2 = $user->createToken('auth-token-2');
        $token2 = $tokenModel2->plainTextToken;

        // Logout with first token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token1,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200);

        // Verify first token is deleted from database
        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $tokenModel1->accessToken->id,
        ]);

        // Verify second token still exists in database
        $this->assertDatabaseHas('personal_access_tokens', [
            'id' => $tokenModel2->accessToken->id,
        ]);
    }
}
