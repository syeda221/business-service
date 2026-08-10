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
            'desc' => 'Product research, validation and supplier sourcing.',
            'steps_count' => 0,
            'view' => 'admin.services.placeholder'
        ],
        'marketplace-retail' => [
            'title' => 'Marketplace & Retail Services',
            'desc' => 'Marketplace setup, listings and retail support.',
            'steps_count' => 0,
            'view' => 'admin.services.placeholder'
        ],
        'fulfillment-logistics' => [
            'title' => 'Fulfillment & Logistics',
            'desc' => 'Warehousing, fulfillment and shipping operations.',
            'steps_count' => 0,
            'view' => 'admin.services.placeholder'
        ]
    ];

    /**
     * Display the services list overview.
     */
    public function index()
    {
        $userId = Auth::id();
        $services = [];

        foreach ($this->servicesMeta as $key => $meta) {
            $progress = ServiceProgress::firstOrCreate(
                ['user_id' => $userId, 'service_key' => $key],
                [
                    'status' => 'not_started',
                    'current_step' => 1,
                    'payload' => []
                ]
            );

            // Compute custom metrics for the business-setup overview
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
            } elseif ($key === 'branding-website') {
                $payload = $progress->payload ?? [];
                $completedSteps = 0;
                $totalSteps = 5;
                if (!empty($payload['selected_services'])) $completedSteps++; // Step 1
                if (!empty($payload['brand_name'])) $completedSteps++;        // Step 2
                if (!empty($payload['website_platform'])) $completedSteps++;  // Step 3
                if (!empty($payload['ad_budget'])) $completedSteps++;         // Step 4
                if ($progress->status === 'completed') $completedSteps = 5;
                
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

        $progress = ServiceProgress::firstOrCreate(
            ['user_id' => $userId, 'service_key' => $service_key],
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
        $progress = ServiceProgress::where('user_id', $userId)
            ->where('service_key', $service_key)
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
            }
        }

        // Validate
        if (!empty($rules)) {
            $validatedData = $request->validate($rules);
            // Merge validated data into payload
            $currentPayload = array_merge($currentPayload, $validatedData);
        } else {
            // If saving draft or no validation, merge all inputs except csrf, step, action
            $allInputs = $request->except(['_token', 'step', 'action']);
            $currentPayload = array_merge($currentPayload, $allInputs);
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
        $progress = ServiceProgress::where('user_id', $userId)
            ->where('service_key', $service_key)
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
        $progress = ServiceProgress::where('user_id', $userId)
            ->where('service_key', $service_key)
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
}
