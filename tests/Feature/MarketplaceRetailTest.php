<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\ServiceProgress;

class MarketplaceRetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest redirection.
     */
    public function test_guest_cannot_access_marketplace_retail(): void
    {
        $response = $this->get('/services/marketplace-retail');
        $response->assertRedirect('/login');
    }

    /**
     * Test admin can access and auto-create default company.
     */
    public function test_admin_can_access_marketplace_retail_loads_default_company(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/services/marketplace-retail');
        $response->assertStatus(200);
        $response->assertSee('Marketplace & Retail Services');
        
        // Assert company was created automatically
        $company = Company::where('user_id', $admin->id)->first();
        $this->assertNotNull($company);
        $this->assertEquals('Default Company', $company->name);

        // Assert service progress was created for the company
        $progress = ServiceProgress::where('user_id', $admin->id)
            ->where('service_key', 'marketplace-retail')
            ->where('company_id', $company->id)
            ->first();
        $this->assertNotNull($progress);
    }

    /**
     * Test Step 1 validation.
     */
    public function test_marketplace_retail_step_1_validation(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        // Access to initialize
        $this->actingAs($admin)->get('/services/marketplace-retail');

        $response = $this->actingAs($admin)->post('/services/marketplace-retail/save-step', [
            'step' => 1,
            'action' => 'save_continue',
        ]);

        $response->assertSessionHasErrors([
            'selected_marketplaces',
            'selling_models',
            'target_countries',
            'primary_marketplace',
            'expected_launch_date',
            'goals'
        ]);
    }

    /**
     * Test Step 1 save draft.
     */
    public function test_marketplace_retail_step_1_save_draft(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/services/marketplace-retail');

        $response = $this->actingAs($admin)->post('/services/marketplace-retail/save-step', [
            'step' => 1,
            'action' => 'save_draft',
            'primary_marketplace' => 'Amazon',
        ]);

        $response->assertRedirect('/services/marketplace-retail');

        $company = Company::where('user_id', $admin->id)->first();
        $progress = ServiceProgress::where('user_id', $admin->id)
            ->where('service_key', 'marketplace-retail')
            ->where('company_id', $company->id)
            ->first();

        $this->assertEquals('Amazon', $progress->payload['primary_marketplace']);
        $this->assertEquals(1, $progress->current_step);
    }

    /**
     * Test company isolation.
     */
    public function test_marketplace_retail_company_data_isolation(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/services/marketplace-retail');

        $companyA = Company::where('user_id', $admin->id)->first();
        
        // Save some data for Company A
        $this->actingAs($admin)->post('/services/marketplace-retail/save-step', [
            'step' => 1,
            'action' => 'save_draft',
            'primary_marketplace' => 'Amazon',
        ]);

        // Create Company B and switch to it
        $companyB = Company::create([
            'user_id' => $admin->id,
            'name' => 'Company B'
        ]);

        $response = $this->actingAs($admin)->post('/companies/switch', [
            'company_id' => $companyB->id
        ]);
        $response->assertRedirect();

        // Access marketplace-retail again for Company B
        $response = $this->actingAs($admin)->get('/services/marketplace-retail');
        $response->assertStatus(200);

        // Progress for Company B should be new/not_started and payload should not contain Company A's data
        $progressB = ServiceProgress::where('company_id', $companyB->id)->first();
        $this->assertNotNull($progressB);
        $this->assertEmpty($progressB->payload);
    }
}
