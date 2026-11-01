<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test guest can access login and register pages.
     */
    public function test_guest_can_access_login_and_register_pages(): void
    {
        $this->get(route('login'))->assertStatus(200)->assertSee('Masuk');
        $this->get(route('register'))->assertStatus(200)->assertSee('Daftar Akun Baru');
    }

    /**
     * Test login for User role.
     */
    public function test_login_user_role_redirects_to_preferensi(): void
    {
        $response = $this->post(route('login.post'), [
            'email'    => 'user@gmail.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('toko.index'));
        $this->assertAuthenticated();
    }

    /**
     * Test login for Admin role.
     */
    public function test_login_admin_role_redirects_to_admin_dashboard(): void
    {
        $response = $this->post(route('login.post'), [
            'email'    => 'admin@gmail.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    /**
     * Test login for Pemilik Toko role.
     */
    public function test_login_pemilik_role_redirects_to_pemilik_dashboard(): void
    {
        $response = $this->post(route('login.post'), [
            'email'    => 'pemilik@toko.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('pemilik.dashboard'));
        $this->assertAuthenticated();
    }

    /**
     * Test login with invalid credentials fails.
     */
    public function test_login_with_invalid_credentials_fails(): void
    {
        $response = $this->post(route('login.post'), [
            'email'    => 'user@toko.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test register new User account.
     */
    public function test_register_new_user_account(): void
    {
        $response = $this->post(route('register.post'), [
            'name'                  => 'User Baru Test',
            'email'                 => 'userbaru@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'user',
        ]);

        $response->assertRedirect(route('user.preferensi'));
        $this->assertDatabaseHas('users', ['email' => 'userbaru@test.com', 'role' => 'user']);
    }

    /**
     * Test register new Pemilik Toko account.
     */
    public function test_register_new_pemilik_account(): void
    {
        $response = $this->post(route('register.post'), [
            'name'                  => 'Pemilik Baru Test',
            'email'                 => 'pemilikbaru@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'pemilik_toko',
        ]);

        $response->assertRedirect(route('pemilik.dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'pemilikbaru@test.com', 'role' => 'pemilik_toko']);
    }

    /**
     * Test guest cannot access protected routes and is redirected to login.
     */
    public function test_guest_cannot_access_protected_routes(): void
    {
        $this->get(route('user.preferensi'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('pemilik.dashboard'))->assertRedirect(route('login'));
    }

    /**
     * Test role middleware restricts user role from admin and pemilik routes.
     */
    public function test_role_middleware_restricts_user_role(): void
    {
        $user = User::where('role', 'user')->first();

        $this->actingAs($user)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($user)->get(route('pemilik.dashboard'))->assertStatus(403);
    }

    /**
     * Test role middleware restricts pemilik role from admin and user routes.
     */
    public function test_role_middleware_restricts_pemilik_role(): void
    {
        $pemilik = User::where('role', 'pemilik_toko')->first();

        $this->actingAs($pemilik)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($pemilik)->get(route('user.preferensi'))->assertStatus(403);
    }

    /**
     * Test role middleware restricts admin role from user and pemilik routes.
     */
    public function test_role_middleware_restricts_admin_role(): void
    {
        $admin = User::where('role', 'admin')->first();

        $this->actingAs($admin)->get(route('user.preferensi'))->assertStatus(403);
        $this->actingAs($admin)->get(route('pemilik.dashboard'))->assertStatus(403);
    }

    /**
     * Test user logout.
     */
    public function test_user_can_logout(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)->post(route('logout'));
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
