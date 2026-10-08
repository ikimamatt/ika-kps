<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Alumni');
    }

    public function test_new_users_can_register_and_get_alumni_role_by_default(): void
    {
        $response = $this->post('/register', [
            'name' => 'Dimas Surya',
            'email' => 'dimas.surya@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/profil');

        $user = User::where('email', 'dimas.surya@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('alumni', $user->role);
        $this->assertEquals('pending', $user->status);
        $this->assertEquals('081234567890', $user->phone);

        $this->assertDatabaseHas('alumni', [
            'user_id' => $user->id,
            'name' => 'Dimas Surya',
            'is_verified' => false,
        ]);
    }

    public function test_registration_fails_with_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->from('/register')->post('/register', [
            'name' => 'Duplikat User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_registration_fails_when_password_confirmation_mismatches(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('password');
    }
}
