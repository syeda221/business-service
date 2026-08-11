<?php

namespace App\Http\Controllers;

use App\Models\ServiceProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ServicesController extends Controller
{
    /**
     * Services map with step counts and descriptions.
     */
    protected $servicesMeta = [
        'business-setup' => [
            'title' => 'Business Setup & Compliance',
            'desc' => 'Business formation, EIN, banking and compliance.',
            'steps_count' => 7,
            'view' => 'admin.services.business_setup'
        ],
        'branding-website' => [
            'title' => 'Branding & Website Development',
            'desc' => 'Branding, website development and digital advertising.',
            'steps_count' => 5,
            'view' => 'admin.services.branding_website'
        ],
        'product-hunting' => [
            'title' => 'Product Hunting & Sourcing',
            'desc' => 'Research, validate and source profitable products from reliable suppliers and manufacturers.',
            'steps_count' => 13,
            'view' => 'admin.services.product_hunting'
        ],
        'marketplace-retail' => [
            'title' => 'Marketplace & Retail Services',
            'desc' => 'Manage marketplace setup, product listings, optimization, inventory, orders and physical retail preparation.',
            'steps_count' => 14,
            'view' => 'admin.services.marketplace_retail'
        ],
        'fulfillment-logistics' => [
            'title' => 'Fulfillment & Logistics',
            'desc' => 'Manage warehouse receiving, inventory storage, order fulfillment, shipping, tracking and returns.',
            'steps_count' => 16,
            'view' => 'admin.services.fulfillment_logistics'
        ]
    ];

    /**
     * Helper to get active company id if the service is company-wise.
     */
    private function getCompanyIdForService($service_key)
    {
        if ($service_key === 'marketplace-retail' || $service_key === 'fulfillment-logistics') {
            $companyId = session('active_company_id');
            if (!$companyId && Auth::check()) {
                $company = \App\Models\Company::firstOrCreate([
                    'user_id' => Auth::id(),
                    'name' => 'Default Company'
                ]);
                $companyId = $company->id;
                session(['active_company_id' => $companyId]);
            }
            return $companyId;
        }
        return null;
    }

    /**
     * Display the services list overview.
     */
    public function index()
    {
        $userId = Auth::id();
        $services = [];

        foreach ($this->servicesMeta as $key => $meta) {
            $companyId = $this->getCompanyIdForService($key);

            $progress = ServiceProgress::firstOrCreate(
                ['user_id' => $userId, 'service_key' => $key, 'company_id' => $companyId],
                [
                    'status' => 'not_started',
                    'current_step' => 1,
                    'payload' => []
                ]
            );

            // Compute custom metrics for the overview
            $statsInfo = [];
            if ($key === 'business-setup') {
                $payload = $progress->payload ?? [];
                $completedSteps = 0;
                $totalSteps = 7;
                
                // Estimate completed steps based on fields filled
                if (!empty($payload['business_name'])) $completedSteps++; // Step 1
                if (!empty($payload['has_llc'])) $completedSteps++;      // Step 2
                if (!empty($payload['has_ein'])) $completedSteps++;      // Step 3
                if (!empty($payload['documents'])) $completedSteps++;    // Step 4
                if (!empty($payload['has_bank'])) $completedSteps++;     // Step 5
                if (!empty($payload['tax_status'])) $completedSteps++;   // Step 6
                if (!empty($payload['business_model'])) $completedSteps++; // Step 7
                
                // Calculate percentage
                $percentage = round(($completedSteps / $totalSteps) * 100);
                $statsInfo = [
                    'percentage' => $percentage,
                    'completed' => $completedSteps,
                    'remaining' => $totalSteps - $completedSteps,
                ];
            } elseif ($key === 'product-hunting') {
                $payload = $progress->payload ?? [];
                $completedSteps = 0;
                $totalSteps = 13;
                if (!empty($payload['product_category'])) $completedSteps++;
                if (!empty($payload['customer_segment'])) $completedSteps++;
                if (!empty($payload['demand_level'])) $completedSteps++;
                if (!empty($payload['competitor_records'])) $completedSteps++;
                if (!empty($payload['est_selling_price'])) $completedSteps++;
                if (!empty($payload['validation_status'])) $completedSteps++;
                if (!empty($payload['supplier_records'])) $completedSteps++;
                if (!empty($payload['ratings'])) $completedSteps++;
                if (!empty($payload['sample_status'])) $completedSteps++;
                if (!empty($payload['inspection_status'])) $completedSteps++;
                if (!empty($payload['neg_status'])) $completedSteps++;
                if (!empty($payload['mfg_status'])) $completedSteps++;
                if ($progress->status === 'completed') $completedSteps = 13;
                
                $percentage = round(($completedSteps / $totalSteps) * 100);
                $statsInfo = [
                    'percentage' => $percentage,
                    'completed' => $completedSteps,
                    'remaining' => $totalSteps - $completedSteps,
                ];
            } elseif ($key === 'marketplace-retail') {
                $payload = $progress->payload ?? [];
                $completedSteps = 0;
                $totalSteps = 14;
                if (!empty($payload['selected_marketplaces'])) $completedSteps++;
                if (!empty($payload['accounts'])) $completedSteps++;
                if (!empty($payload['store_name'])) $completedSteps++;
                if (!empty($payload['verification_status'])) $completedSteps++;
                if (!empty($payload['products'])) $completedSteps++;
                if (!empty($payload['listings'])) $completedSteps++;
                if (!empty($payload['optimizations'])) $completedSteps++;
                if (!empty($payload['pricings'])) $completedSteps++;
                if (!empty($payload['inventories'])) $completedSteps++;
                if (!empty($payload['launch_status'])) $completedSteps++;
                if (!empty($payload['orders'])) $completedSteps++;
                if (!empty($payload['campaigns'])) $completedSteps++;
                if (isset($payload['physical_retail_required'])) $completedSteps++;
                if (!empty($payload['retailers']) || $progress->status === 'completed') $completedSteps = $totalSteps;
                
                $percentage = round(($completedSteps / $totalSteps) * 100);
                $statsInfo = [
                    'percentage' => $percentage,
                    'completed' => $completedSteps,
                    'remaining' => $totalSteps - $completedSteps,
                ];
            } elseif ($key === 'fulfillment-logistics') {
                $payload = $progress->payload ?? [];
                $completedSteps = 0;
                $totalSteps = 16;
                if (!empty($payload['service_types'])) $completedSteps++;
                if (isset($payload['planning_expected_qty'])) $completedSteps++;
                if (!empty($payload['shipments'])) $completedSteps++;
                if (!empty($payload['receivings'])) $completedSteps++;
                if (!empty($payload['verifications'])) $completedSteps++;
                if (!empty($payload['inspections'])) $completedSteps++;
                if (!empty($payload['storage_records'])) $completedSteps++;
                if (!empty($payload['inventories'])) $completedSteps++;
                if (!empty($payload['orders'])) $completedSteps++;
                if (!empty($payload['picks'])) $completedSteps++;
                if (!empty($payload['labels'])) $completedSteps++;
                if (!empty($payload['carriers'])) $completedSteps++;
                if (!empty($payload['trackings'])) $completedSteps++;
                if (!empty($payload['deliveries'])) $completedSteps++;
                if (!empty($payload['returns'])) $completedSteps++;
                if (!empty($payload['inventory_updates']) || $progress->status === 'completed') $completedSteps = $totalSteps;
                
                $percentage = round(($completedSteps / $totalSteps) * 100);
                $statsInfo = [
                    'percentage' => $percentage,
                    'completed' => $completedSteps,
                    'remaining' => $totalSteps - $completedSteps,
                ];
            }

            $services[] = [
                'key' => $key,
                'title' => $meta['title'],
                'desc' => $meta['desc'],
                'steps_count' => $meta['steps_count'],
                'status' => $progress->status,
                'current_step' => $progress->current_step,
                'stats' => $statsInfo
            ];
        }

        return view('admin.services.index', compact('services'));
    }

    /**
     * Display a specific service page.
     */
    public function show($service_key)
    {
        if (!array_key_exists($service_key, $this->servicesMeta)) {
            abort(404);
        }

        $meta = $this->servicesMeta[$service_key];
        $userId = Auth::id();
        $companyId = $this->getCompanyIdForService($service_key);

        $progress = ServiceProgress::firstOrCreate(
            ['user_id' => $userId, 'service_key' => $service_key, 'company_id' => $companyId],
            [
                'status' => 'not_started',
                'current_step' => 1,
                'payload' => []
            ]
        );

        // If it's a placeholder service, just return the placeholder view
        if ($meta['view'] === 'admin.services.placeholder') {
            return view($meta['view'], [
                'service_key' => $service_key,
                'title' => $meta['title'],
                'desc' => $meta['desc']
            ]);
        }

        $payload = $progress->payload ?? [];
        $currentStep = $progress->current_step;
        $status = $progress->status;

        // Custom parameters for views
        $states = [
            "AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "FL", "GA", 
            "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", 
            "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", 
            "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", 
            "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY"
        ];

        return view($meta['view'], compact('progress', 'payload', 'currentStep', 'status', 'states'));
    }

    /**
     * Process step submissions (both save-draft and save-continue).
     */
    public function saveStep(Request $request, $service_key)
    {
        if (!array_key_exists($service_key, $this->servicesMeta)) {
            abort(404);
        }

        $userId = Auth::id();
        $companyId = $this->getCompanyIdForService($service_key);
        $progress = ServiceProgress::where('user_id', $userId)
            ->where('service_key', $service_key)
            ->where('company_id', $companyId)
            ->firstOrFail();

        $currentPayload = $progress->payload ?? [];
        $step = (int) $request->input('step', 1);
        $action = $request->input('action', 'save_continue'); // 'save_draft' or 'save_continue'

        // Dynamic validation rules based on step and service key
        $rules = [];
        
        if ($action === 'save_continue') {
            if ($service_key === 'business-setup') {
                switch ($step) {
                    case 1:
                        $rules = [
                            'business_name' => 'required|string|max:255',
                            'business_type' => 'required|string',
                            'business_activity' => 'required|string',
                            'website_url' => 'nullable|url',
                            'business_email' => 'required|email',
                            'business_phone' => 'required|string',
                        ];
                        break;
                    case 2:
                        $rules = [
                            'has_llc' => 'required|in:yes,no',
                            'llc_name' => 'required_if:has_llc,yes|nullable|string|max:255',
                            'formation_date' => 'required_if:has_llc,yes|nullable|date',
                            'preferred_state' => 'required_if:has_llc,no|nullable|string',
                            'proposed_llc_name' => 'required_if:has_llc,no|nullable|string|max:255',
                            'llc_notes' => 'nullable|string',
                        ];
                        break;
                    case 3:
                        $rules = [
                            'has_ein' => 'required|in:yes,no',
                            'ein_number' => 'required_if:has_ein,yes|nullable|string',
                            'ein_status' => 'required|string',
                            'ein_notes' => 'nullable|string',
                        ];
                        break;
                    case 4:
                        // Documents: check if required uploads exist in the payload
                        $docs = $currentPayload['documents'] ?? [];
                        if (empty($docs['articles_of_organization']) || empty($docs['operating_agreement']) || empty($docs['ein_letter'])) {
                            return back()->withErrors(['documents' => 'Please upload all required files (Articles of Organization, Operating Agreement, and EIN Letter) to proceed.'])->withInput();
                        }
                        break;
                    case 5:
                        $rules = [
                            'has_bank' => 'required|in:yes,no',
                            'banking_type' => 'required|string',
                            'preferred_bank' => 'required|string',
                            'bank_status' => 'required|string',
                            'banking_notes' => 'nullable|string',
                        ];
                        break;
                    case 6:
                        $rules = [
                            'tax_status' => 'required|string',
                            'tax_professional' => 'required|in:yes,no',
                            'annual_report_status' => 'required|string',
                            'compliance_notes' => 'nullable|string',
                        ];
                        break;
                    case 7:
                        $rules = [
                            'business_model' => 'required|string',
                            'sales_channels' => 'required|array',
                            'target_states' => 'required|array',
                            'additional_requirements' => 'nullable|string',
                        ];
                        break;
                }
            } elseif ($service_key === 'branding-website') {
                switch ($step) {
                    case 1:
                        $rules = [
                            'services' => 'required|array|min:1',
                        ];
                        break;
                    case 2:
                        $rules = [
                            'has_existing_logo' => 'required|in:yes,no',
                            'brand_name' => 'required|string|max:255',
                            'brand_slogan' => 'nullable|string|max:255',
                            'preferred_colors' => 'nullable|string',
                            'preferred_style' => 'required|string',
                            'branding_requirements' => 'nullable|string',
                        ];
                        break;
                    case 3:
                        $rules = [
                            'has_existing_website' => 'required|in:yes,no',
                            'existing_website_url' => 'required_if:has_existing_website,yes|nullable|url',
                            'website_platform' => 'required|string',
                            'domain_name' => 'nullable|string',
                            'number_products' => 'required|integer|min:0',
                            'required_pages' => 'required|array|min:1',
                            'payment_gateway' => 'required|in:yes,no',
                            'shipping_setup' => 'required|in:yes,no',
                            'reference_websites' => 'nullable|array',
                            'website_requirements' => 'nullable|string',
                        ];
                        break;
                    case 4:
                        $rules = [
                            'ad_platforms' => 'required|array|min:1',
                            'has_ad_accounts' => 'required|in:yes,no',
                            'fb_page_url' => 'nullable|url',
                            'google_ads' => 'required|in:yes,no',
                            'tiktok_account' => 'nullable|url',
                            'pinterest_account' => 'nullable|url',
                            'ad_budget' => 'required|numeric|min:0',
                            'target_audience' => 'required|string',
                            'target_regions' => 'required|array',
                            'ad_goals' => 'required|array|min:1',
                            'ad_requirements' => 'nullable|string',
                        ];
                        break;
                }
            } elseif ($service_key === 'product-hunting') {
                switch ($step) {
                    case 1:
                        $rules = [
                            'product_category' => 'required|string',
                            'product_idea' => 'required|string|max:255',
                            'product_description' => 'required|string',
                            'target_market' => 'required|array',
                            'target_customer' => 'required|string',
                            'selling_price' => 'required|numeric|min:0',
                            'product_cost' => 'required|numeric|min:0',
                            'profit_margin' => 'required|numeric|min:0|max:100',
                            'initial_moq' => 'required|integer|min:1',
                            'sourcing_type' => 'required|array',
                            'customization_required' => 'required|in:yes,no',
                            'customization_details' => 'required_if:customization_required,yes|nullable|string',
                            'additional_requirements' => 'nullable|string',
                        ];
                        break;
                    case 2:
                        $rules = [
                            'research_market' => 'required|array',
                            'research_types' => 'required|array',
                            'customer_segment' => 'required|string',
                            'target_price_min' => 'required|numeric|min:0',
                            'target_price_max' => 'required|numeric|min:0|gte:target_price_min',
                            'research_keywords' => 'required|string',
                            'competitor_urls' => 'required|array',
                            'competitor_urls.*' => 'required|url',
                            'research_notes' => 'nullable|string',
                            'research_findings' => 'required|string',
                        ];
                        break;
                    case 3:
                        $rules = [
                            'demand_level' => 'required|string',
                            'demand_trend' => 'required|string',
                            'search_interest' => 'required|string',
                            'monthly_demand' => 'required|integer|min:0',
                            'seasonality' => 'required|in:yes,no',
                            'peak_season' => 'required_if:seasonality,yes|nullable|string',
                            'demand_analysis' => 'required|string',
                            'demand_score' => 'required|numeric|min:0|max:100',
                        ];
                        break;
                    case 4:
                        $rules = [
                            'competitor_records' => 'required|array',
                            'competitor_records.*.name' => 'required|string',
                            'competitor_records.*.product_name' => 'required|string',
                            'competitor_records.*.product_url' => 'required|url',
                            'competitor_records.*.selling_price' => 'required|numeric|min:0',
                            'competitor_records.*.rating' => 'required|numeric|min:0|max:5',
                            'competitor_records.*.reviews' => 'required|integer|min:0',
                            'competitor_records.*.features' => 'required|string',
                            'competitor_records.*.position' => 'required|string',
                            'competitor_records.*.notes' => 'nullable|string',
                            'competitor_strengths' => 'required|string',
                            'competitor_weaknesses' => 'required|string',
                            'competitive_advantage' => 'required|string',
                        ];
                        break;
                    case 5:
                        $rules = [
                            'est_product_cost' => 'required|numeric|min:0',
                            'est_manufacturing_cost' => 'required|numeric|min:0',
                            'est_packaging_cost' => 'required|numeric|min:0',
                            'est_shipping_cost' => 'required|numeric|min:0',
                            'est_import_duties' => 'required|numeric|min:0',
                            'est_marketplace_fees' => 'required|numeric|min:0',
                            'est_advertising_cost' => 'required|numeric|min:0',
                            'est_other_costs' => 'required|numeric|min:0',
                            'est_selling_price' => 'required|numeric|min:0',
                        ];
                        break;
                    case 6:
                        $rules = [
                            'validation_status' => 'required|string',
                            'potential_level' => 'required|string',
                            'val_demand_score' => 'required|numeric|min:0|max:100',
                            'val_competition_score' => 'required|numeric|min:0|max:100',
                            'val_profitability_score' => 'required|numeric|min:0|max:100',
                            'validation_checklist' => 'required|array',
                            'validation_notes' => 'nullable|string',
                            'final_recommendation' => 'required|string',
                        ];
                        break;
                    case 7:
                        $rules = [
                            'supplier_records' => 'required|array',
                            'supplier_records.*.name' => 'required|string',
                            'supplier_records.*.type' => 'required|string',
                            'supplier_records.*.country' => 'required|string',
                            'supplier_records.*.website' => 'required|url',
                            'supplier_records.*.contact_person' => 'required|string',
                            'supplier_records.*.email' => 'required|email',
                            'supplier_records.*.phone' => 'required|string',
                            'supplier_records.*.product_url' => 'required|url',
                            'supplier_records.*.moq' => 'required|integer|min:0',
                            'supplier_records.*.unit_price' => 'required|numeric|min:0',
                            'supplier_records.*.lead_time' => 'required|string',
                            'supplier_records.*.customization' => 'required|in:yes,no',
                            'supplier_records.*.private_label' => 'required|in:yes,no',
                            'supplier_records.*.white_label' => 'required|in:yes,no',
                            'supplier_records.*.certifications' => 'required|array',
                            'supplier_records.*.notes' => 'nullable|string',
                        ];
                        break;
                    case 8:
                        $rules = [
                            'ratings' => 'required|array',
                            'preferred_supplier' => 'required|string',
                            'backup_supplier' => 'required|string',
                            'comparison_notes' => 'nullable|string',
                        ];
                        break;
                    case 9:
                        $rules = [
                            'sample_required' => 'required|in:yes,no',
                            'sample_supplier' => 'required_if:sample_required,yes|nullable|string',
                            'sample_qty' => 'required_if:sample_required,yes|nullable|integer|min:1',
                            'sample_cost' => 'required_if:sample_required,yes|nullable|numeric|min:0',
                            'sample_shipping_cost' => 'required_if:sample_required,yes|nullable|numeric|min:0',
                            'sample_request_date' => 'required_if:sample_required,yes|nullable|date',
                            'sample_expected_date' => 'required_if:sample_required,yes|nullable|date',
                            'sample_status' => 'required|string',
                            'sample_tracking_number' => 'nullable|string',
                            'sample_tracking_url' => 'nullable|url',
                            'sample_notes' => 'nullable|string',
                        ];
                        break;
                    case 10:
                        $rules = [
                            'inspection_status' => 'required|string',
                            'quality_checklist' => 'required|array',
                            'defects_found' => 'required|in:yes,no',
                            'defect_details' => 'required_if:defects_found,yes|nullable|string',
                            'quality_score' => 'required|numeric|min:0|max:100',
                            'inspection_date' => 'required|date',
                            'inspector_notes' => 'nullable|string',
                            'final_quality_decision' => 'required|string',
                        ];
                        break;
                    case 11:
                        $rules = [
                            'neg_supplier' => 'required|string',
                            'neg_initial_price' => 'required|numeric|min:0',
                            'neg_final_price' => 'required|numeric|min:0',
                            'neg_initial_moq' => 'required|integer|min:1',
                            'neg_final_moq' => 'required|integer|min:1',
                            'neg_initial_lead_time' => 'required|integer|min:1',
                            'neg_final_lead_time' => 'required|integer|min:1',
                            'neg_status' => 'required|string',
                            'payment_terms' => 'required|string',
                            'shipping_terms' => 'required|string',
                            'neg_notes' => 'nullable|string',
                        ];
                        break;
                    case 12:
                        $rules = [
                            'mfg_supplier' => 'required|string',
                            'mfg_product_type' => 'required|string',
                            'mfg_quantity' => 'required|integer|min:1',
                            'mfg_unit_cost' => 'required|numeric|min:0',
                            'mfg_start_date' => 'required|date',
                            'mfg_expected_date' => 'required|date',
                            'mfg_status' => 'required|string',
                            'mfg_packaging_required' => 'required|in:yes,no',
                            'mfg_labeling_required' => 'required|in:yes,no',
                            'mfg_branding_required' => 'required|in:yes,no',
                            'mfg_notes' => 'nullable|string',
                        ];
                        break;
                    case 13:
                        $rules = [
                            'final_approval_status' => 'required|string',
                            'final_decision' => 'required|string',
                            'final_notes' => 'nullable|string',
                        ];
                        break;
                }
            } elseif ($service_key === 'marketplace-retail') {
                switch ($step) {
                    case 1:
                        $rules = [
                            'selected_marketplaces' => 'required|array|min:1',
                            'selling_models' => 'required|array|min:1',
                            'target_countries' => 'required|array|min:1',
                            'primary_marketplace' => 'required|string',
                            'expected_launch_date' => 'required|date',
                            'goals' => 'required|array|min:1',
                            'additional_requirements' => 'nullable|string',
                        ];
                        break;
                    case 2:
                        $rules = [
                            'accounts' => 'required|array',
                            'accounts.*.marketplace_name' => 'required|string',
                            'accounts.*.account_status' => 'required|string',
                            'accounts.*.seller_name' => 'required|string',
                            'accounts.*.account_email' => 'required|email',
                            'accounts.*.account_id' => 'required|string',
                            'accounts.*.store_url' => 'nullable|url',
                            'accounts.*.created_date' => 'required|date',
                            'accounts.*.notes' => 'nullable|string',
                            'accounts.*.documents' => 'nullable|array',
                        ];
                        break;
                    case 3:
                        $rules = [
                            'store_name' => 'required|string',
                            'store_url' => 'required|url',
                            'store_description' => 'required|string',
                            'store_category' => 'required|string',
                            'store_contact_email' => 'required|email',
                            'store_phone' => 'required|string',
                            'shipping_setup_required' => 'required|in:yes,no',
                            'return_policy_setup_required' => 'required|in:yes,no',
                            'store_setup_status' => 'required|string',
                            'store_notes' => 'nullable|string',
                            'store_logo' => 'nullable|array',
                            'store_banner' => 'nullable|array',
                            'brand_assets' => 'nullable|array',
                        ];
                        break;
                    case 4:
                        $rules = [
                            'verification_marketplace' => 'required|string',
                            'verification_status' => 'required|string',
                            'verification_submission_date' => 'required|date',
                            'verification_notes' => 'nullable|string',
                            'rejection_reason' => 'nullable|string',
                            'verification_documents' => 'nullable|array',
                        ];
                        break;
                    case 5:
                        $rules = [
                            'products' => 'required|array',
                            'products.*.product_name' => 'required|string',
                            'products.*.sku' => 'required|string',
                            'products.*.product_category' => 'required|string',
                            'products.*.brand_name' => 'required|string',
                            'products.*.product_description' => 'required|string',
                            'products.*.upc_gtin' => 'required|string',
                            'products.*.product_weight' => 'required|numeric|min:0',
                            'products.*.product_cost' => 'required|numeric|min:0',
                            'products.*.target_selling_price' => 'required|numeric|min:0',
                            'products.*.inventory_quantity' => 'required|integer|min:0',
                            'products.*.product_status' => 'required|string',
                            'products.*.variants' => 'nullable|string',
                            'products.*.dimensions_length' => 'nullable|numeric|min:0',
                            'products.*.dimensions_width' => 'nullable|numeric|min:0',
                            'products.*.dimensions_height' => 'nullable|numeric|min:0',
                            'products.*.images' => 'nullable|array',
                            'products.*.video' => 'nullable|array',
                        ];
                        break;
                    case 6:
                        $rules = [
                            'listings' => 'required|array',
                            'listings.*.product_sku' => 'required|string',
                            'listings.*.marketplace' => 'required|string',
                            'listings.*.listing_title' => 'required|string',
                            'listings.*.listing_description' => 'required|string',
                            'listings.*.category' => 'required|string',
                            'listings.*.sku' => 'required|string',
                            'listings.*.marketplace_product_id' => 'required|string',
                            'listings.*.listing_status' => 'required|string',
                            'listings.*.bullet_points' => 'nullable|array',
                            'listings.*.images' => 'nullable|array',
                        ];
                        break;
                    case 7:
                        $rules = [
                            'optimizations' => 'required|array',
                            'optimizations.*.listing_id' => 'required|string',
                            'optimizations.*.primary_keyword' => 'required|string',
                            'optimizations.*.optimized_title' => 'required|string',
                            'optimizations.*.optimized_description' => 'required|string',
                            'optimizations.*.image_optimization_status' => 'required|string',
                            'optimizations.*.keyword_optimization_status' => 'required|string',
                            'optimizations.*.optimization_score' => 'required|numeric|min:0|max:100',
                            'optimizations.*.optimization_notes' => 'nullable|string',
                            'optimizations.*.seo_keywords' => 'nullable|string',
                            'optimizations.*.secondary_keywords' => 'nullable|string',
                            'optimizations.*.optimized_bullet_points' => 'nullable|array',
                        ];
                        break;
                    case 8:
                        $rules = [
                            'pricings' => 'required|array',
                            'pricings.*.product_sku' => 'required|string',
                            'pricings.*.marketplace' => 'required|string',
                            'pricings.*.base_price' => 'required|numeric|min:0',
                            'pricings.*.marketplace_price' => 'required|numeric|min:0',
                            'pricings.*.sale_price' => 'required|numeric|min:0',
                            'pricings.*.minimum_price' => 'required|numeric|min:0',
                            'pricings.*.maximum_price' => 'required|numeric|min:0',
                            'pricings.*.discount_type' => 'required|string',
                            'pricings.*.discount_value' => 'required|numeric|min:0',
                            'pricings.*.start_date' => 'required|date',
                            'pricings.*.end_date' => 'required|date',
                            'pricings.*.pricing_status' => 'required|string',
                        ];
                        break;
                    case 9:
                        $rules = [
                            'inventories' => 'required|array',
                            'inventories.*.product_sku' => 'required|string',
                            'inventories.*.sku' => 'required|string',
                            'inventories.*.marketplace' => 'required|string',
                            'inventories.*.available_quantity' => 'required|integer|min:0',
                            'inventories.*.reserved_quantity' => 'required|integer|min:0',
                            'inventories.*.reorder_level' => 'required|integer|min:0',
                            'inventories.*.inventory_status' => 'required|string',
                            'inventories.*.warehouse_location' => 'required|string',
                            'inventories.*.auto_inventory_sync' => 'required|in:yes,no',
                            'inventories.*.notes' => 'nullable|string',
                        ];
                        break;
                    case 10:
                        $rules = [
                            'launch_date' => 'required|date',
                            'launch_status' => 'required|string',
                            'launch_notes' => 'nullable|string',
                        ];
                        break;
                    case 11:
                        $rules = [
                            'orders' => 'required|array',
                            'orders.*.marketplace' => 'required|string',
                            'orders.*.order_id' => 'required|string',
                            'orders.*.customer_name' => 'required|string',
                            'orders.*.order_date' => 'required|date',
                            'orders.*.product_sku' => 'required|string',
                            'orders.*.quantity' => 'required|integer|min:1',
                            'orders.*.order_amount' => 'required|numeric|min:0',
                            'orders.*.order_status' => 'required|string',
                            'orders.*.tracking_number' => 'nullable|string',
                            'orders.*.carrier' => 'nullable|string',
                            'orders.*.tracking_url' => 'nullable|url',
                            'orders.*.notes' => 'nullable|string',
                        ];
                        break;
                    case 12:
                        $rules = [
                            'campaigns' => 'required|array',
                            'campaigns.*.advertising_platform' => 'required|string',
                            'campaigns.*.campaign_name' => 'required|string',
                            'campaigns.*.marketplace' => 'required|string',
                            'campaigns.*.campaign_type' => 'required|string',
                            'campaigns.*.daily_budget' => 'required|numeric|min:0',
                            'campaigns.*.monthly_budget' => 'required|numeric|min:0',
                            'campaigns.*.start_date' => 'required|date',
                            'campaigns.*.end_date' => 'required|date',
                            'campaigns.*.campaign_goal' => 'required|string',
                            'campaigns.*.campaign_status' => 'required|string',
                            'campaigns.*.target_products' => 'nullable|array',
                            'campaigns.*.notes' => 'nullable|string',
                        ];
                        break;
                    case 13:
                        $rules = [
                            'physical_retail_required' => 'required|in:yes,no',
                            'retail_product_category' => 'required_if:physical_retail_required,yes|nullable|string',
                            'wholesale_price' => 'required_if:physical_retail_required,yes|nullable|numeric|min:0',
                            'suggested_retail_price' => 'required_if:physical_retail_required,yes|nullable|numeric|min:0',
                            'min_order_quantity' => 'required_if:physical_retail_required,yes|nullable|integer|min:0',
                            'retail_packaging_required' => 'required_if:physical_retail_required,yes|nullable|in:yes,no',
                            'retail_ready_packaging' => 'required_if:physical_retail_required,yes|nullable|in:yes,no',
                            'retail_requirements' => 'nullable|string',
                            'retail_catalog' => 'nullable|array',
                            'retail_price_list' => 'nullable|array',
                            'target_retailers' => 'nullable|array',
                            'target_locations' => 'nullable|array',
                        ];
                        break;
                    case 14:
                        $rules = [
                            'retailers' => 'nullable|array',
                            'retailers.*.retailer_name' => 'required|string',
                            'retailers.*.type' => 'required|string',
                            'retailers.*.contact_person' => 'required|string',
                            'retailers.*.email' => 'required|email',
                            'retailers.*.phone' => 'required|string',
                            'retailers.*.status' => 'required|string',
                            'retailers.*.website' => 'nullable|url',
                            'retailers.*.location' => 'nullable|string',
                            'retailers.*.products_interested' => 'nullable|array',
                            'retailers.*.moq' => 'nullable|integer|min:0',
                            'retailers.*.wholesale_price' => 'nullable|numeric|min:0',
                            'retailers.*.contact_date' => 'nullable|date',
                            'retailers.*.notes' => 'nullable|string',
                            'retailers.*.agreements' => 'nullable|array',
                        ];
                        break;
                }
            } elseif ($service_key === 'fulfillment-logistics') {
                switch ($step) {
                    case 1:
                        $rules = [
                            'service_types' => 'required|array|min:1',
                            'warehouse_required' => 'required|in:yes,no',
                            'preferred_location' => 'required|string',
                            'target_country' => 'required|string',
                            'expected_monthly_orders' => 'required|integer|min:0',
                            'expected_monthly_units' => 'required|integer|min:0',
                            'product_categories' => 'required|array|min:1',
                            'special_handling' => 'required|in:yes,no',
                            'handling_requirements' => 'required_if:special_handling,yes|nullable|string',
                            'temp_controlled' => 'required_if:special_handling,yes|nullable|in:yes,no',
                            'fragile_products' => 'required_if:special_handling,yes|nullable|in:yes,no',
                            'additional_requirements' => 'nullable|string',
                        ];
                        break;
                    case 2:
                        $rules = [
                            'planning_supplier' => 'required|string',
                            'planning_contact' => 'required|string',
                            'planning_country' => 'required|string',
                            'planning_warehouse' => 'required|string',
                            'planning_skus' => 'required|array|min:1',
                            'planning_expected_qty' => 'required|integer|min:0',
                            'planning_cartons' => 'required|integer|min:0',
                            'planning_ship_date' => 'required|date',
                            'planning_arrival_date' => 'required|date',
                            'planning_ship_method' => 'required|string',
                            'planning_instructions' => 'nullable|string',
                            'planning_documents' => 'nullable|array',
                        ];
                        break;
                    case 3:
                        $rules = [
                            'shipments' => 'required|array',
                            'shipments.*.reference' => 'required|string',
                            'shipments.*.supplier' => 'required|string',
                            'shipments.*.warehouse' => 'required|string',
                            'shipments.*.products' => 'required|array|min:1',
                            'shipments.*.quantity' => 'required|integer|min:0',
                            'shipments.*.cartons' => 'required|integer|min:0',
                            'shipments.*.method' => 'required|string',
                            'shipments.*.carrier' => 'required|string',
                            'shipments.*.tracking_number' => 'required|string',
                            'shipments.*.tracking_url' => 'nullable|url',
                            'shipments.*.ship_date' => 'required|date',
                            'shipments.*.arrival_date' => 'required|date',
                            'shipments.*.status' => 'required|string',
                            'shipments.*.documents' => 'nullable|array',
                            'shipments.*.notes' => 'nullable|string',
                        ];
                        break;
                    case 4:
                        $rules = [
                            'receivings' => 'required|array',
                            'receivings.*.shipment_ref' => 'required|string',
                            'receivings.*.warehouse' => 'required|string',
                            'receivings.*.receive_date' => 'required|date',
                            'receivings.*.received_by' => 'required|string',
                            'receivings.*.expected_qty' => 'required|integer|min:0',
                            'receivings.*.received_qty' => 'required|integer|min:0',
                            'receivings.*.cartons_expected' => 'required|integer|min:0',
                            'receivings.*.cartons_received' => 'required|integer|min:0',
                            'receivings.*.status' => 'required|string',
                            'receivings.*.notes' => 'nullable|string',
                            'receivings.*.documents' => 'nullable|array',
                            'receivings.*.photos' => 'nullable|array',
                            'receivings.*.diff_qty' => 'required|integer',
                            'receivings.*.diff_type' => 'required|string',
                        ];
                        break;
                    case 5:
                        $rules = [
                            'verifications' => 'required|array',
                            'verifications.*.shipment_ref' => 'required|string',
                            'verifications.*.sku' => 'required|string',
                            'verifications.*.expected_qty' => 'required|integer|min:0',
                            'verifications.*.received_qty' => 'required|integer|min:0',
                            'verifications.*.verified_qty' => 'required|integer|min:0',
                            'verifications.*.difference' => 'required|integer',
                            'verifications.*.status' => 'required|string',
                            'verifications.*.verified_by' => 'required|string',
                            'verifications.*.verify_date' => 'required|date',
                            'verifications.*.notes' => 'nullable|string',
                        ];
                        break;
                    case 6:
                        $rules = [
                            'inspections' => 'required|array',
                            'inspections.*.shipment_ref' => 'required|string',
                            'inspections.*.sku' => 'required|string',
                            'inspections.*.inspect_date' => 'required|date',
                            'inspections.*.inspector' => 'required|string',
                            'inspections.*.status' => 'required|string',
                            'inspections.*.decision' => 'required|string',
                            'inspections.*.score' => 'required|numeric|min:0|max:100',
                            'inspections.*.defect_qty' => 'required|integer|min:0',
                            'inspections.*.defects_found' => 'required|in:yes,no',
                            'inspections.*.defect_details' => 'nullable|string',
                            'inspections.*.checklist' => 'required|array',
                            'inspections.*.photos' => 'nullable|array',
                            'inspections.*.report' => 'nullable|array',
                        ];
                        break;
                    case 7:
                        $rules = [
                            'storage_records' => 'required|array',
                            'storage_records.*.warehouse' => 'required|string',
                            'storage_records.*.sku' => 'required|string',
                            'storage_records.*.quantity' => 'required|integer|min:0',
                            'storage_records.*.location_code' => 'required|string',
                            'storage_records.*.shelf_bin' => 'required|string',
                            'storage_records.*.storage_date' => 'required|date',
                            'storage_records.*.status' => 'required|string',
                            'storage_records.*.notes' => 'nullable|string',
                        ];
                        break;
                    case 8:
                        $rules = [
                            'inventories' => 'required|array',
                            'inventories.*.sku' => 'required|string',
                            'inventories.*.product_name' => 'required|string',
                            'inventories.*.warehouse' => 'required|string',
                            'inventories.*.location_code' => 'required|string',
                            'inventories.*.available' => 'required|integer|min:0',
                            'inventories.*.reserved' => 'required|integer|min:0',
                            'inventories.*.damaged' => 'required|integer|min:0',
                            'inventories.*.reorder_level' => 'required|integer|min:0',
                            'inventories.*.reorder_qty' => 'required|integer|min:0',
                            'inventories.*.status' => 'required|string',
                            'inventories.*.notes' => 'nullable|string',
                        ];
                        break;
                    case 9:
                        $rules = [
                            'orders' => 'required|array',
                            'orders.*.order_id' => 'required|string',
                            'orders.*.source' => 'required|string',
                            'orders.*.customer_name' => 'required|string',
                            'orders.*.order_date' => 'required|date',
                            'orders.*.products' => 'required|array',
                            'orders.*.warehouse' => 'required|string',
                            'orders.*.amount' => 'required|numeric|min:0',
                            'orders.*.status' => 'required|string',
                            'orders.*.priority' => 'required|string',
                            'orders.*.notes' => 'nullable|string',
                        ];
                        break;
                    case 10:
                        $rules = [
                            'picks' => 'required|array',
                            'picks.*.order_id' => 'required|string',
                            'picks.*.warehouse' => 'required|string',
                            'picks.*.sku' => 'required|string',
                            'picks.*.required_qty' => 'required|integer|min:1',
                            'picks.*.picked_qty' => 'required|integer|min:0',
                            'picks.*.location_code' => 'required|string',
                            'picks.*.pick_status' => 'required|string',
                            'picks.*.packed_by' => 'required|string',
                            'picks.*.pack_date' => 'required|date',
                            'picks.*.pkg_count' => 'required|integer|min:1',
                            'picks.*.pkg_weight' => 'required|numeric|min:0',
                            'picks.*.pkg_dims' => 'required|string',
                            'picks.*.pkg_type' => 'required|string',
                            'picks.*.notes' => 'nullable|string',
                            'picks.*.photos' => 'nullable|array',
                        ];
                        break;
                    case 11:
                        $rules = [
                            'labels' => 'required|array',
                            'labels.*.order_id' => 'required|string',
                            'labels.*.recipient_name' => 'required|string',
                            'labels.*.recipient_address' => 'required|string',
                            'labels.*.city' => 'required|string',
                            'labels.*.state' => 'required|string',
                            'labels.*.zip_code' => 'required|string',
                            'labels.*.country' => 'required|string',
                            'labels.*.pkg_weight' => 'required|numeric|min:0',
                            'labels.*.pkg_dims' => 'required|string',
                            'labels.*.shipping_service' => 'required|string',
                            'labels.*.label_status' => 'required|string',
                            'labels.*.tracking_number' => 'required|string',
                            'labels.*.tracking_url' => 'nullable|url',
                            'labels.*.label_file' => 'nullable|array',
                        ];
                        break;
                    case 12:
                        $rules = [
                            'carriers' => 'required|array',
                            'carriers.*.order_id' => 'required|string',
                            'carriers.*.carrier_name' => 'required|string',
                            'carriers.*.service_type' => 'required|string',
                            'carriers.*.tracking_number' => 'required|string',
                            'carriers.*.shipping_cost' => 'required|numeric|min:0',
                            'carriers.*.pickup_date' => 'required|date',
                            'carriers.*.est_delivery_date' => 'required|date',
                            'carriers.*.status' => 'required|string',
                            'carriers.*.notes' => 'nullable|string',
                        ];
                        break;
                    case 13:
                        $rules = [
                            'trackings' => 'required|array',
                            'trackings.*.order_id' => 'required|string',
                            'trackings.*.tracking_number' => 'required|string',
                            'trackings.*.carrier' => 'required|string',
                            'trackings.*.tracking_url' => 'nullable|url',
                            'trackings.*.status' => 'required|string',
                            'trackings.*.last_update' => 'required|date_format:Y-m-d\TH:i',
                            'trackings.*.est_delivery' => 'required|date',
                            'trackings.*.notes' => 'nullable|string',
                        ];
                        break;
                    case 14:
                        $rules = [
                            'deliveries' => 'required|array',
                            'deliveries.*.order_id' => 'required|string',
                            'deliveries.*.customer_name' => 'required|string',
                            'deliveries.*.delivery_date' => 'required|date',
                            'deliveries.*.status' => 'required|string',
                            'deliveries.*.received_by' => 'nullable|string',
                            'deliveries.*.notes' => 'nullable|string',
                            'deliveries.*.failure_reason' => 'nullable|string',
                            'deliveries.*.proof' => 'nullable|array',
                        ];
                        break;
                    case 15:
                        $rules = [
                            'returns' => 'required|array',
                            'returns.*.order_id' => 'required|string',
                            'returns.*.request_date' => 'required|date',
                            'returns.*.customer_name' => 'required|string',
                            'returns.*.sku' => 'required|string',
                            'returns.*.quantity' => 'required|integer|min:1',
                            'returns.*.reason' => 'required|string',
                            'returns.*.status' => 'required|string',
                            'returns.*.tracking_number' => 'nullable|string',
                            'returns.*.carrier' => 'nullable|string',
                            'returns.*.received_date' => 'nullable|date',
                            'returns.*.inspection_result' => 'nullable|string',
                            'returns.*.notes' => 'nullable|string',
                            'returns.*.photos' => 'nullable|array',
                        ];
                        break;
                    case 16:
                        $rules = [
                            'inventory_updates' => 'required|array',
                            'inventory_updates.*.sku' => 'required|string',
                            'inventory_updates.*.warehouse' => 'required|string',
                            'inventory_updates.*.transaction_type' => 'required|string',
                            'inventory_updates.*.quantity' => 'required|integer|min:1',
                            'inventory_updates.*.ref_type' => 'required|string',
                            'inventory_updates.*.ref_id' => 'required|string',
                            'inventory_updates.*.reason' => 'nullable|string',
                            'inventory_updates.*.updated_by' => 'required|string',
                            'inventory_updates.*.updated_date' => 'required|date_format:Y-m-d\TH:i',
                        ];
                        break;
                }
            }
        }

        // Process uploaded files if any
        $uploadedFiles = $this->processUploadedFiles($request->allFiles(), 'marketplace-retail');

        // Validate
        if (!empty($rules)) {
            $validatedData = $request->validate($rules);
            
            // Custom calculations for fulfillment-logistics Step 16 (Inventory Update)
            if ($service_key === 'fulfillment-logistics' && $step === 16) {
                // Fetch inventories from Step 8 payload
                $inventories = $currentPayload['inventories'] ?? [];
                $updates = $validatedData['inventory_updates'] ?? [];
                
                foreach ($updates as &$update) {
                    $sku = $update['sku'];
                    $warehouse = $update['warehouse'];
                    
                    // Find matching SKU in inventory
                    $invIndex = collect($inventories)->search(function($item) use ($sku, $warehouse) {
                        return $item['sku'] === $sku && $item['warehouse'] === $warehouse;
                    });
                    
                    $prevQty = ($invIndex !== false) ? (int)($inventories[$invIndex]['available'] ?? 0) : 0;
                    $qty = (int)$update['quantity'];
                    
                    $type = $update['transaction_type'];
                    if (in_array($type, ['Received', 'Returned', 'Adjusted'])) {
                        $newQty = $prevQty + $qty;
                    } else {
                        $newQty = max($prevQty - $qty, 0);
                    }
                    
                    $update['prev_quantity'] = $prevQty;
                    $update['new_quantity'] = $newQty;
                    
                    // Also update the active inventories record!
                    if ($invIndex !== false) {
                        $inventories[$invIndex]['available'] = $newQty;
                        // recalculate total = available + reserved + damaged
                        $avail = (int)$inventories[$invIndex]['available'];
                        $res = (int)($inventories[$invIndex]['reserved'] ?? 0);
                        $dmg = (int)($inventories[$invIndex]['damaged'] ?? 0);
                        $inventories[$invIndex]['total'] = $avail + $res + $dmg;
                        
                        // update status
                        if ($avail <= 0) {
                            $inventories[$invIndex]['status'] = 'Out of Stock';
                        } elseif ($avail <= (int)($inventories[$invIndex]['reorder_level'] ?? 10)) {
                            $inventories[$invIndex]['status'] = 'Low Stock';
                        } else {
                            $inventories[$invIndex]['status'] = 'In Stock';
                        }
                    }
                }
                
                $validatedData['inventory_updates'] = $updates;
                $currentPayload['inventories'] = $inventories;
            }
            
            // Merge validated data and files into payload
            $stepData = array_replace_recursive($validatedData, $uploadedFiles);
            $currentPayload = array_replace_recursive($currentPayload, $stepData);
        } else {
            // If saving draft or no validation, merge all inputs except csrf, step, action
            $allInputs = $request->except(['_token', 'step', 'action']);
            $stepData = array_replace_recursive($allInputs, $uploadedFiles);
            $currentPayload = array_replace_recursive($currentPayload, $stepData);
        }

        // Process dynamic calculations for product hunting
        if ($service_key === 'product-hunting') {
            if ($step === 5) {
                $totalCost = (float)($currentPayload['est_product_cost'] ?? 0) +
                             (float)($currentPayload['est_manufacturing_cost'] ?? 0) +
                             (float)($currentPayload['est_packaging_cost'] ?? 0) +
                             (float)($currentPayload['est_shipping_cost'] ?? 0) +
                             (float)($currentPayload['est_import_duties'] ?? 0) +
                             (float)($currentPayload['est_marketplace_fees'] ?? 0) +
                             (float)($currentPayload['est_advertising_cost'] ?? 0) +
                             (float)($currentPayload['est_other_costs'] ?? 0);
                
                $sellingPrice = (float)($currentPayload['est_selling_price'] ?? 0);
                $profit = $sellingPrice - $totalCost;
                $margin = $sellingPrice > 0 ? ($profit / $sellingPrice) * 100 : 0;
                $roi = $totalCost > 0 ? ($profit / $totalCost) * 100 : 0;
                
                $currentPayload['cal_total_cost'] = round($totalCost, 2);
                $currentPayload['cal_expected_profit'] = round($profit, 2);
                $currentPayload['cal_profit_margin'] = round($margin, 2);
                $currentPayload['cal_roi'] = round($roi, 2);
            } elseif ($step === 6) {
                $demand = (float)($currentPayload['val_demand_score'] ?? 0);
                $comp = (float)($currentPayload['val_competition_score'] ?? 0);
                $profit = (float)($currentPayload['val_profitability_score'] ?? 0);
                $currentPayload['cal_overall_score'] = round(($demand + $comp + $profit) / 3, 2);
            } elseif ($step === 8) {
                $ratings = $currentPayload['ratings'] ?? [];
                $calculatedRatings = [];
                foreach ($ratings as $supName => $scores) {
                    $sum = (int)($scores['price'] ?? 0) +
                           (int)($scores['quality'] ?? 0) +
                           (int)($scores['moq'] ?? 0) +
                           (int)($scores['lead_time'] ?? 0) +
                           (int)($scores['communication'] ?? 0) +
                           (int)($scores['reliability'] ?? 0);
                    $calculatedRatings[$supName] = [
                        'scores' => $scores,
                        'overall_score' => round(($sum / 30) * 100, 2)
                    ];
                }
                $currentPayload['cal_supplier_ratings'] = $calculatedRatings;
            } elseif ($step === 12) {
                $qty = (int)($currentPayload['mfg_quantity'] ?? 0);
                $unitCost = (float)($currentPayload['mfg_unit_cost'] ?? 0);
                $currentPayload['cal_total_production_cost'] = round($qty * $unitCost, 2);
            }
        }

        // Save progress payload
        $progress->payload = $currentPayload;

        if ($action === 'save_continue') {
            // Update status and steps
            $progress->status = 'in_progress';
            
            if ($service_key === 'business-setup') {
                if ($step < 7) {
                    $progress->current_step = $step + 1;
                } else {
                    $progress->status = 'completed';
                }
            } elseif ($service_key === 'branding-website') {
                // Stepper conditional check based on selected services in Step 1
                $selected = $currentPayload['services'] ?? [];
                
                $hasBranding = !empty(array_intersect($selected, ['logo_design', 'brand_identity', 'colors_typography', 'label_design', 'packaging_design', 'marketing_materials', 'brand_guidelines']));
                $hasWebsite = !empty(array_intersect($selected, ['shopify_dev', 'wordpress_dev', 'ecommerce_web', 'product_pages', 'landing_page', 'payment_shipping', 'mobile_friendly', 'basic_seo', 'website_maintenance']));
                $hasAdvertising = !empty(array_intersect($selected, ['meta_ads', 'google_ads', 'tiktok_ads', 'pinterest_ads', 'other_ads']));

                $nextStep = $step + 1;
                
                // Determine next step skipping if not selected
                if ($nextStep === 2 && !$hasBranding) $nextStep = 3;
                if ($nextStep === 3 && !$hasWebsite) $nextStep = 4;
                if ($nextStep === 4 && !$hasAdvertising) $nextStep = 5;

                if ($step < 5) {
                    $progress->current_step = $nextStep;
                } else {
                    $progress->status = 'completed';
                }
            } elseif ($service_key === 'product-hunting') {
                if ($step < 13) {
                    $progress->current_step = $step + 1;
                } else {
                    $progress->status = 'completed';
                }
            } elseif ($service_key === 'marketplace-retail') {
                if ($step < 14) {
                    $progress->current_step = $step + 1;
                } else {
                    $progress->status = 'completed';
                }
            } elseif ($service_key === 'fulfillment-logistics') {
                if ($step < 16) {
                    $progress->current_step = $step + 1;
                } else {
                    $progress->status = 'completed';
                }
            }
        } else {
            // Save Draft: keep current step but update status to in_progress
            $progress->status = 'in_progress';
        }

        $progress->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $progress->status,
                'current_step' => $progress->current_step,
            ]);
        }

        // Redirect back or to next step
        if ($progress->status === 'completed') {
            return redirect()->route('services.show', $service_key)
                ->with('success', 'Congratulations! Requirements have been successfully completed.');
        }

        return redirect()->route('services.show', $service_key)
            ->with('success', 'Progress saved successfully.');
    }

    /**
     * Upload a document for step 4/6 and save meta to payload.
     */
    public function uploadDocument(Request $request, $service_key)
    {
        $request->validate([
            'document' => 'required|file|max:10240', // 10MB Limit
            'field_name' => 'required|string',
            'step' => 'required|integer'
        ]);

        $userId = Auth::id();
        $companyId = $this->getCompanyIdForService($service_key);
        $progress = ServiceProgress::where('user_id', $userId)
            ->where('service_key', $service_key)
            ->where('company_id', $companyId)
            ->firstOrFail();

        $payload = $progress->payload ?? [];
        $fieldName = $request->input('field_name');

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $extension = $file->getClientOriginalExtension();
            
            // Store file securely in local storage
            $path = $file->store('public/documents');
            $publicUrl = Storage::url($path);

            $meta = [
                'original_name' => $originalName,
                'size' => $this->formatBytes($fileSize),
                'path' => $path,
                'url' => $publicUrl,
                'upload_date' => now()->format('M d, Y h:i A'),
                'status' => 'Uploaded'
            ];

            if ($fieldName === 'other_documents') {
                // Multi upload field
                $docsList = $payload['documents']['other_documents'] ?? [];
                $docsList[] = $meta;
                $payload['documents']['other_documents'] = $docsList;
            } else {
                // Single required field
                $payload['documents'][$fieldName] = $meta;
            }

            $progress->payload = $payload;
            $progress->status = 'in_progress';
            $progress->save();

            return redirect()->route('services.show', $service_key)
                ->with('success', 'File ' . $originalName . ' uploaded successfully.');
        }

        return back()->withErrors(['document' => 'File upload failed.']);
    }

    /**
     * Remove a document and delete file from storage.
     */
    public function removeDocument(Request $request, $service_key)
    {
        $request->validate([
            'field_name' => 'required|string',
            'index' => 'nullable|integer'
        ]);

        $userId = Auth::id();
        $companyId = $this->getCompanyIdForService($service_key);
        $progress = ServiceProgress::where('user_id', $userId)
            ->where('service_key', $service_key)
            ->where('company_id', $companyId)
            ->firstOrFail();

        $payload = $progress->payload ?? [];
        $fieldName = $request->input('field_name');
        $index = $request->input('index');

        if (isset($payload['documents'][$fieldName])) {
            if ($fieldName === 'other_documents' && $index !== null) {
                // Remove specific file from list
                $docsList = $payload['documents']['other_documents'] ?? [];
                if (isset($docsList[$index])) {
                    Storage::delete($docsList[$index]['path']);
                    unset($docsList[$index]);
                    $payload['documents']['other_documents'] = array_values($docsList); // re-index
                }
            } else {
                // Remove single document
                Storage::delete($payload['documents'][$fieldName]['path']);
                unset($payload['documents'][$fieldName]);
            }

            $progress->payload = $payload;
            $progress->save();

            return redirect()->route('services.show', $service_key)
                ->with('success', 'Document removed successfully.');
        }

        return back()->withErrors(['document' => 'Document could not be found.']);
    }

    /**
     * Helper to format bytes to Human Readable file size.
     */
    private function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Recursively find and process all uploaded files, returning metadata arrays.
     */
    private function processUploadedFiles($files, $prefix = 'documents')
    {
        $result = [];
        foreach ($files as $key => $value) {
            if ($value instanceof \Illuminate\Http\UploadedFile) {
                $path = $value->store('public/' . $prefix);
                $result[$key] = [
                    'name' => $value->getClientOriginalName(),
                    'path' => $path,
                    'url' => \Illuminate\Support\Facades\Storage::url($path),
                    'size' => $this->formatBytes($value->getSize()),
                    'upload_date' => now()->format('M d, Y h:i A'),
                    'status' => 'Uploaded'
                ];
            } elseif (is_array($value)) {
                $result[$key] = $this->processUploadedFiles($value, $prefix);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
