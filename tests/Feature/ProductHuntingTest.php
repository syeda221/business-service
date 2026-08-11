<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductHuntingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can access product sourcing page.
     */
    public function test_admin_can_access_product_sourcing_page(): void
    {
        $admin = \App\Models\User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get('/services/product-hunting');
        $response->assertStatus(200);
        $response->assertSee('Product Sourcing Console');
    }

    /**
     * Test Step 1 validations for requirements form.
     */
    public function test_product_hunting_step_1_validation(): void
    {
        $admin = \App\Models\User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin)->get('/services/product-hunting');

        $response = $this->actingAs($admin)->post('/services/product-hunting/save-step', [
            'step' => 1,
            'action' => 'save_continue',
            // Missing fields
        ]);

        $response->assertSessionHasErrors(['product_category', 'product_idea', 'product_description', 'target_market', 'target_customer', 'selling_price', 'product_cost', 'profit_margin', 'initial_moq', 'sourcing_type', 'customization_required']);
    }

    /**
     * Test Step 5 Pricing calculations occur reliably in database.
     */
    public function test_product_hunting_step_5_pricing_calculations(): void
    {
        $admin = \App\Models\User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin)->get('/services/product-hunting');

        $response = $this->actingAs($admin)->post('/services/product-hunting/save-step', [
            'step' => 5,
            'action' => 'save_continue',
            'est_product_cost' => 10.00,
            'est_manufacturing_cost' => 2.00,
            'est_packaging_cost' => 0.50,
            'est_shipping_cost' => 1.50,
            'est_import_duties' => 0.80,
            'est_marketplace_fees' => 3.00,
            'est_advertising_cost' => 1.20,
            'est_other_costs' => 1.00,
            'est_selling_price' => 30.00,
        ]);

        $response->assertRedirect('/services/product-hunting');

        $progress = \App\Models\ServiceProgress::where('user_id', $admin->id)
            ->where('service_key', 'product-hunting')
            ->first();

        // Calculations:
        // Total Cost: 10 + 2 + 0.5 + 1.5 + 0.8 + 3 + 1.2 + 1 = 20.00
        // Expected Profit: 30 - 20 = 10.00
        // Profit Margin: (10 / 30) * 100 = 33.33%
        // ROI: (10 / 20) * 100 = 50.00%
        $this->assertEquals(20.00, $progress->payload['cal_total_cost']);
        $this->assertEquals(10.00, $progress->payload['cal_expected_profit']);
        $this->assertEquals(33.33, $progress->payload['cal_profit_margin']);
        $this->assertEquals(50.00, $progress->payload['cal_roi']);
    }

    /**
     * Test Step 6 validation scores calculation.
     */
    public function test_product_hunting_step_6_score_calculations(): void
    {
        $admin = \App\Models\User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin)->get('/services/product-hunting');

        $response = $this->actingAs($admin)->post('/services/product-hunting/save-step', [
            'step' => 6,
            'action' => 'save_continue',
            'validation_status' => 'Under Review',
            'potential_level' => 'High',
            'val_demand_score' => 80,
            'val_competition_score' => 60,
            'val_profitability_score' => 70,
            'validation_checklist' => ['Demand validated'],
            'final_recommendation' => 'Proceed',
        ]);

        $response->assertRedirect('/services/product-hunting');

        $progress = \App\Models\ServiceProgress::where('user_id', $admin->id)
            ->where('service_key', 'product-hunting')
            ->first();

        // Overall Score = (80 + 60 + 70) / 3 = 70
        $this->assertEquals(70, $progress->payload['cal_overall_score']);
    }

    /**
     * Test Step 8 supplier comparison scores calculation.
     */
    public function test_product_hunting_step_8_supplier_comparison(): void
    {
        $admin = \App\Models\User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin)->get('/services/product-hunting');

        $response = $this->actingAs($admin)->post('/services/product-hunting/save-step', [
            'step' => 8,
            'action' => 'save_continue',
            'ratings' => [
                'supplier-a' => [
                    'price' => 5,
                    'quality' => 4,
                    'moq' => 5,
                    'lead_time' => 3,
                    'communication' => 4,
                    'reliability' => 4,
                ]
            ],
            'preferred_supplier' => 'Supplier A',
            'backup_supplier' => 'Supplier B',
        ]);

        $response->assertRedirect('/services/product-hunting');

        $progress = \App\Models\ServiceProgress::where('user_id', $admin->id)
            ->where('service_key', 'product-hunting')
            ->first();

        // Ratings score: (5+4+5+3+4+4) = 25 / 30 = 83.33%
        $this->assertEquals(83.33, $progress->payload['cal_supplier_ratings']['supplier-a']['overall_score']);
    }

    /**
     * Test Step 13 final approval updates service status to completed.
     */
    public function test_product_hunting_step_13_final_approval(): void
    {
        $admin = \App\Models\User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin)->get('/services/product-hunting');

        $response = $this->actingAs($admin)->post('/services/product-hunting/save-step', [
            'step' => 13,
            'action' => 'save_continue',
            'final_approval_status' => 'Approved',
            'final_decision' => 'Approve Product',
        ]);

        $response->assertRedirect('/services/product-hunting');

        $progress = \App\Models\ServiceProgress::where('user_id', $admin->id)
            ->where('service_key', 'product-hunting')
            ->first();

        $this->assertEquals('completed', $progress->status);
    }
}
