<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the login page is accessible.
     */
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sign in to portal');
    }

    /**
     * Test guests are redirected to login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test non-admin user cannot access dashboard.
     */
    public function test_non_admin_user_cannot_access_dashboard(): void
    {
        $user = \App\Models\User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test admin user can access dashboard.
     */
    public function test_admin_user_can_access_dashboard(): void
    {
        $admin = \App\Models\User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Overview');
        $response->assertSee('Welcome back');
    }

    /**
     * Test authenticating admin with credentials.
     */
    public function test_admin_can_login_with_correct_credentials(): void
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'testadmin@admin.com',
            'password' => bcrypt('secret-pass'),
            'is_admin' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'testadmin@admin.com',
            'password' => 'secret-pass',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * Test non-admin cannot authenticate.
     */
    public function test_non_admin_cannot_login(): void
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'regular@user.com',
            'password' => bcrypt('secret-pass'),
            'is_admin' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'regular@user.com',
            'password' => 'secret-pass',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
