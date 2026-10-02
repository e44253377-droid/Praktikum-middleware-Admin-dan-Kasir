<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('transactions.index', absolute: false));
    }

    public function test_admins_are_redirected_to_the_dashboard_after_login_even_if_email_is_unverified(): void
    {
        $admin = User::factory()->unverified()->create(['role' => 'admin']);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->get(route('dashboard'))->assertOk();
    }

    public function test_cashier_login_ignores_an_admin_intended_url(): void
    {
        $cashier = User::factory()->unverified()->create(['role' => 'cashier']);

        $response = $this->withSession([
            'url.intended' => route('dashboard', absolute: false),
        ])->post('/login', [
            'email' => $cashier->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($cashier);
        $response->assertRedirect(route('transactions.index', absolute: false));
        $this->get(route('transactions.index'))->assertOk();
    }

    public function test_admin_login_ignores_a_cashier_intended_url(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->withSession([
            'url.intended' => route('transactions.index', absolute: false),
        ])->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->get(route('dashboard'))->assertOk();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
