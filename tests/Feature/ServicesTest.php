<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServicesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guests cannot access services.
     */
    public function test_guest_cannot_access_services(): void
    {
        $response = $this->get('/services');
        $response->assertRedirect('/login');

        $response = $this->get('/services/business-setup');
        $response->assertRedirect('/login');
    }

    /**
     * Test admin can access services overview.
     */
    public function test_admin_can_access_services_overview(): void
    {
        $admin = \App\Models\User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get('/services');
        $response->assertStatus(200);
        $response->assertSee('Services');
        $response->assertSee('Business Setup & Compliance');
    }

    /**
     * Test step 1 validation for Business Setup.
     */
    public function test_business_setup_step_1_validation(): void
    {
        $admin = \App\Models\User::factory()->create([
            'is_admin' => true,
        ]);

        // Accessing page will initialize state
        $this->actingAs($admin)->get('/services/business-setup');

        $response = $this->actingAs($admin)->post('/services/business-setup/save-step', [
            'step' => 1,
            'action' => 'save_continue',
            // Missing required fields
        ]);

        $response->assertSessionHasErrors(['business_name', 'business_type', 'business_activity', 'business_email', 'business_phone']);
    }

    /**
     * Test saving draft for Business Setup.
     */
    public function test_business_setup_save_draft(): void
    {
        $admin = \App\Models\User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin)->get('/services/business-setup');

        $response = $this->actingAs($admin)->post('/services/business-setup/save-step', [
            'step' => 1,
            'action' => 'save_draft',
            'business_name' => 'Acme Labs',
            'business_type' => 'Corporation',
        ]);

        $response->assertRedirect('/services/business-setup');
        
        $progress = \App\Models\ServiceProgress::where('user_id', $admin->id)
            ->where('service_key', 'business-setup')
            ->first();

        $this->assertEquals('Acme Labs', $progress->payload['business_name']);
        $this->assertEquals('Corporation', $progress->payload['business_type']);
        $this->assertEquals(1, $progress->current_step); // Still step 1 since it was a draft
    }

    /**
     * Test saving and continuing to step 2 for Business Setup.
     */
    public function test_business_setup_save_and_continue(): void
    {
        $admin = \App\Models\User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin)->get('/services/business-setup');

        $response = $this->actingAs($admin)->post('/services/business-setup/save-step', [
            'step' => 1,
            'action' => 'save_continue',
            'business_name' => 'Acme Corp',
            'business_type' => 'LLC',
            'business_activity' => 'SaaS services operations',
            'business_email' => 'contact@acme.com',
            'business_phone' => '1234567890',
        ]);

        $response->assertRedirect('/services/business-setup');
        
        $progress = \App\Models\ServiceProgress::where('user_id', $admin->id)
            ->where('service_key', 'business-setup')
            ->first();

        $this->assertEquals('Acme Corp', $progress->payload['business_name']);
        $this->assertEquals(2, $progress->current_step); // Advanced to step 2
    }

    /**
     * Test conditional step skipping in Branding & Website Development.
     */
    public function test_branding_website_conditional_step_skipping(): void
    {
        $admin = \App\Models\User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin)->get('/services/branding-website');

        // Case 1: Select only Branding, should skip step 3 (website) and 4 (advertising) to review step 5
        $response = $this->actingAs($admin)->post('/services/branding-website/save-step', [
            'step' => 1,
            'action' => 'save_continue',
            'services' => ['logo_design'] // Only branding selected
        ]);

        $response->assertRedirect('/services/branding-website');

        $progress = \App\Models\ServiceProgress::where('user_id', $admin->id)
            ->where('service_key', 'branding-website')
            ->first();

        $this->assertEquals(2, $progress->current_step); // Step 2 (Branding) is next

        // Now save step 2 (Branding), it should skip directly to Step 5 (Review)
        $response = $this->actingAs($admin)->post('/services/branding-website/save-step', [
            'step' => 2,
            'action' => 'save_continue',
            'has_existing_logo' => 'no',
            'brand_name' => 'BrandX',
            'preferred_style' => 'Modern',
        ]);

        $response->assertRedirect('/services/branding-website');

        $progress->refresh();
        $this->assertEquals(5, $progress->current_step); // Skipped to Step 5!
    }
}
