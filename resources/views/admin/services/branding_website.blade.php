@extends('layouts.dashboard')

@section('title', 'Branding & Website Development')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
@endsection

@section('content')
<!-- Breadcrumbs -->
<nav class="breadcrumbs" style="margin-bottom: var(--spacing-2); margin-top: 0;">
    <a href="{{ route('admin.dashboard') }}">Console</a>
    <a href="{{ route('services.index') }}">Services</a>
    <span>Branding & Website Development</span>
</nav>

<!-- Success / Error Messages -->
@if(session('success'))
    <div class="alert alert-success" style="margin-top: var(--spacing-3);">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger" style="margin-top: var(--spacing-3);">
        <ul style="padding-left: var(--spacing-4); margin: 0;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    // Read selected services to determine visibility of steps 2, 3, 4
    $selected = $payload['services'] ?? [];
    
    $hasBranding = !empty(array_intersect($selected, ['logo_design', 'brand_identity', 'colors_typography', 'label_design', 'packaging_design', 'marketing_materials', 'brand_guidelines']));
    $hasWebsite = !empty(array_intersect($selected, ['shopify_dev', 'wordpress_dev', 'ecommerce_web', 'product_pages', 'landing_page', 'payment_shipping', 'mobile_friendly', 'basic_seo', 'website_maintenance']));
    $hasAdvertising = !empty(array_intersect($selected, ['meta_ads', 'google_ads', 'tiktok_ads', 'pinterest_ads', 'other_ads']));
    
    // Total steps visible tracker
    $stepsVisible = [
        1 => true,
        2 => $hasBranding,
        3 => $hasWebsite,
        4 => $hasAdvertising,
        5 => true
    ];

    $visibleCount = 0;
    $completedCount = 0;
    
    if ($stepsVisible[1]) {
        $visibleCount++;
        if (!empty($selected)) $completedCount++;
    }
    if ($stepsVisible[2]) {
        $visibleCount++;
        if (!empty($payload['brand_name'])) $completedCount++;
    }
    if ($stepsVisible[3]) {
        $visibleCount++;
        if (!empty($payload['website_platform'])) $completedCount++;
    }
    if ($stepsVisible[4]) {
        $visibleCount++;
        if (!empty($payload['ad_platforms'])) $completedCount++;
    }
    if ($stepsVisible[5]) {
        $visibleCount++;
        if ($status === 'completed') $completedCount++;
    }
    
    $percentage = $visibleCount > 0 ? round(($completedCount / $visibleCount) * 100) : 0;
@endphp

<!-- Completed banner -->
@if($status === 'completed')
    <div class="card" style="border-color: var(--color-success); background-color: var(--color-success-light); margin-bottom: var(--spacing-4); text-align: center; padding: var(--spacing-6);">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 48px; height: 48px; color: var(--color-success); margin: 0 auto var(--spacing-3);">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0110 21a3.745 3.745 0 01-3.068-1.593 3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0114 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
        </svg>
        <h2 style="color: var(--color-success-dark); font-size: var(--fs-xl); font-weight: var(--fw-bold);">Requirements Completed</h2>
        <p style="color: var(--color-success-dark); font-size: var(--fs-sm); margin-top: 2px;">Service status is now: <strong style="text-transform: uppercase;">Completed / Ready for Project</strong></p>
    </div>
@endif

<!-- Tabs Navigation -->
<div class="tabs-navigation" style="margin-bottom: var(--spacing-3);">
    <button class="tab-btn {{ $status !== 'completed' ? 'active' : '' }}" id="tab-btn-wizard" onclick="switchMainTab('wizard')" style="{{ $status === 'completed' ? 'display: none;' : '' }}">Setup Stepper</button>
    <button class="tab-btn {{ $status === 'completed' ? 'active' : '' }}" id="tab-btn-overview" onclick="switchMainTab('overview')" style="{{ $status !== 'completed' ? 'display: none;' : '' }}">Overview Dashboard</button>
</div>

<!-- TAB: STEPS WIZARD -->
<div id="tab-content-wizard" class="tab-content {{ $status !== 'completed' ? 'active' : '' }}">

    <!-- Mobile Step Status Bar -->
    <div class="mobile-step-indicator" style="display: flex; align-items: center; justify-content: space-between; background-color: #ffffff; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 10px 14px; margin-bottom: var(--spacing-3); font-size: var(--fs-xs); box-shadow: var(--shadow-card);">
        <div style="display: flex; align-items: center; gap: 6px;">
            <span style="color: var(--color-text-muted);">Step {{ $currentStep }} of 5:</span>
            <strong style="color: var(--color-primary);" id="mobile-step-name">
                @if($currentStep == 1) Service Selection
                @elseif($currentStep == 2) Branding Requirements
                @elseif($currentStep == 3) Website Setup
                @elseif($currentStep == 4) Digital Advertising
                @elseif($currentStep == 5) Review & Finish
                @endif
            </strong>
        </div>
        <span class="badge badge-success">{{ $percentage }}% Done</span>
    </div>

    <!-- Dynamic Stepper -->
    <div class="stepper-container" id="stepper-container-div" style="margin-bottom: var(--spacing-4);">
        <ol class="stepper" id="stepper-list">
            <!-- Step 1 -->
            <li class="step-item {{ $currentStep == 1 ? 'in-progress' : ($currentStep > 1 ? 'completed' : 'not-started') }}" id="step-nav-1" onclick="jumpToStep(1)">
                <div class="step-circle">01</div>
                <span class="step-title">Service Selection</span>
                <span class="step-status">@if($currentStep > 1) ✓ Done @else ● Active @endif</span>
            </li>
            
            <!-- Step 2 -->
            @if($hasBranding)
                <li class="step-item {{ $currentStep == 2 ? 'in-progress' : ($currentStep > 2 ? 'completed' : 'not-started') }}" id="step-nav-2" onclick="jumpToStep(2)">
                    <div class="step-circle">02</div>
                    <span class="step-title">Branding</span>
                    <span class="step-status">@if($currentStep > 2) ✓ Done @elseif($currentStep == 2) ● Active @else ○ Wait @endif</span>
                </li>
            @endif

            <!-- Step 3 -->
            @if($hasWebsite)
                <li class="step-item {{ $currentStep == 3 ? 'in-progress' : ($currentStep > 3 ? 'completed' : 'not-started') }}" id="step-nav-3" onclick="jumpToStep(3)">
                    <div class="step-circle">03</div>
                    <span class="step-title">Website</span>
                    <span class="step-status">@if($currentStep > 3) ✓ Done @elseif($currentStep == 3) ● Active @else ○ Wait @endif</span>
                </li>
            @endif

            <!-- Step 4 -->
            @if($hasAdvertising)
                <li class="step-item {{ $currentStep == 4 ? 'in-progress' : ($currentStep > 4 ? 'completed' : 'not-started') }}" id="step-nav-4" onclick="jumpToStep(4)">
                    <div class="step-circle">04</div>
                    <span class="step-title">Advertising</span>
                    <span class="step-status">@if($currentStep > 4) ✓ Done @elseif($currentStep == 4) ● Active @else ○ Wait @endif</span>
                </li>
            @endif

            <!-- Step 5 -->
            <li class="step-item {{ $currentStep == 5 ? 'in-progress' : ($status === 'completed' ? 'completed' : 'not-started') }}" id="step-nav-5" onclick="jumpToStep(5)">
                <div class="step-circle">05</div>
                <span class="step-title">Review & Finish</span>
                <span class="step-status">@if($status === 'completed') ✓ Completed @elseif($currentStep == 5) ● Active @else ○ Wait @endif</span>
            </li>
        </ol>
    </div>

    <!-- Forms Container -->
    <div class="card" style="padding: var(--spacing-4) var(--spacing-4);">

    <!-- ================== STEP 1: SERVICE SELECTION ================== -->
    <div id="step-form-container-1" class="step-form-content {{ $currentStep == 1 ? 'active' : '' }}" style="display: {{ $currentStep == 1 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Select Required Services</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Choose the modules you require. Downstream steps will dynamically adapt based on your selections.</p>

        <form action="{{ route('services.save_step', 'branding-website') }}" method="POST">
            @csrf
            <input type="hidden" name="step" value="1">
            <input type="hidden" name="action" id="step-1-action" value="save_continue">

            <!-- SECTION 1: BRANDING -->
            <div class="selection-section-title">Branding Requirements</div>
            <div class="selection-grid">
                @foreach([
                    'logo_design' => ['Logo Design', 'Custom corporate logo'],
                    'brand_identity' => ['Brand Identity', 'Style boards and assets'],
                    'colors_typography' => ['Colors & Typography', 'Consistent styling palette'],
                    'label_design' => ['Product Label Design', 'Label configurations'],
                    'packaging_design' => ['Packaging Design', 'SaaS packaging look'],
                    'marketing_materials' => ['Marketing Materials', 'Leaflets, cards design'],
                    'brand_guidelines' => ['Brand Guidelines', 'Typography & spacing rules']
                ] as $key => $meta)
                    <div class="selection-card {{ in_array($key, $selected) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'srv-{{ $key }}')">
                        <input type="checkbox" name="services[]" id="srv-{{ $key }}" value="{{ $key }}" class="selection-checkbox" {{ in_array($key, $selected) ? 'checked' : '' }}>
                        <div class="selection-card-details">
                            <span class="selection-card-title">{{ $meta[0] }}</span>
                            <span class="selection-card-desc">{{ $meta[1] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- SECTION 2: WEBSITE DEVELOPMENT -->
            <div class="selection-section-title">Website Development</div>
            <div class="selection-grid">
                @foreach([
                    'shopify_dev' => ['Shopify Store Development', 'Custom Shopify builder'],
                    'wordpress_dev' => ['WordPress Development', 'Dynamic CMS systems'],
                    'ecommerce_web' => ['eCommerce Website', 'Cart & shopping checkout'],
                    'product_pages' => ['Product Pages', 'High conversion listings'],
                    'landing_page' => ['Landing Page', 'Single conversion layouts'],
                    'payment_shipping' => ['Payment & Shipping Setup', 'API integrations'],
                    'mobile_friendly' => ['Mobile-Friendly Design', 'Fluid layouts'],
                    'basic_seo' => ['Basic SEO', 'Metatags, crawl setup'],
                    'website_maintenance' => ['Website Maintenance', 'SaaS updates operations']
                ] as $key => $meta)
                    <div class="selection-card {{ in_array($key, $selected) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'srv-{{ $key }}')">
                        <input type="checkbox" name="services[]" id="srv-{{ $key }}" value="{{ $key }}" class="selection-checkbox" {{ in_array($key, $selected) ? 'checked' : '' }}>
                        <div class="selection-card-details">
                            <span class="selection-card-title">{{ $meta[0] }}</span>
                            <span class="selection-card-desc">{{ $meta[1] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- SECTION 3: DIGITAL ADVERTISING -->
            <div class="selection-section-title">Digital Advertising</div>
            <div class="selection-grid">
                @foreach([
                    'meta_ads' => ['Meta Ads', 'Facebook & Instagram reach'],
                    'google_ads' => ['Google Ads', 'Search & display triggers'],
                    'tiktok_ads' => ['TikTok Ads', 'Social feed conversion'],
                    'pinterest_ads' => ['Pinterest Ads', 'Visual board ads'],
                    'other_ads' => ['Other platforms', 'Custom portals ads']
                ] as $key => $meta)
                    <div class="selection-card {{ in_array($key, $selected) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'srv-{{ $key }}')">
                        <input type="checkbox" name="services[]" id="srv-{{ $key }}" value="{{ $key }}" class="selection-checkbox" {{ in_array($key, $selected) ? 'checked' : '' }}>
                        <div class="selection-card-details">
                            <span class="selection-card-title">{{ $meta[0] }}</span>
                            <span class="selection-card-desc">{{ $meta[1] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="form-navigation">
                <div></div>
                <div>
                    <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-1-action').value='save_draft'">Save Draft</button>
                    <button type="submit" class="btn btn-primary">Save & Continue</button>
                </div>
            </div>
        </form>
    </div>

    <!-- ================== STEP 2: BRANDING ================== -->
    @if($hasBranding)
        <div id="step-form-container-2" class="step-form-content {{ $currentStep == 2 ? 'active' : '' }}" style="display: {{ $currentStep == 2 ? 'block' : 'none' }};">
            <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Branding Requirements</h2>
            <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Enter instructions and specifications for branding design modules.</p>

            <form action="{{ route('services.save_step', 'branding-website') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="2">
                <input type="hidden" name="action" id="step-2-action" value="save_continue">

                <div class="form-group">
                    <label class="form-label">Existing Logo? <span style="color: var(--color-danger);">*</span></label>
                    <div class="segmented-control">
                        @php $hasLogo = old('has_existing_logo', $payload['has_existing_logo'] ?? 'no'); @endphp
                        <input type="radio" name="has_existing_logo" id="logo_yes" value="yes" class="segmented-option" {{ $hasLogo == 'yes' ? 'checked' : '' }} onchange="toggleLogoUpload('yes')">
                        <label for="logo_yes" class="segmented-label">Yes</label>

                        <input type="radio" name="has_existing_logo" id="logo_no" value="no" class="segmented-option" {{ $hasLogo == 'no' ? 'checked' : '' }} onchange="toggleLogoUpload('no')">
                        <label for="logo_no" class="segmented-label">No</label>
                    </div>
                </div>

                <div class="form-group" id="logo-upload-container" style="display: {{ $hasLogo == 'yes' ? 'block' : 'none' }};">
                    <label class="form-label">Logo Reference File</label>
                    <p style="font-size: var(--fs-xs); color: var(--color-text-muted); margin-bottom: var(--spacing-2);">Please manage branding uploads inside your documents or describe reference URLs in instructions.</p>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="brand_name" class="form-label">Brand Name <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" name="brand_name" id="brand_name" class="form-control" value="{{ old('brand_name', $payload['brand_name'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="brand_slogan" class="form-label">Brand Slogan / Tagline <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                        <input type="text" name="brand_slogan" id="brand_slogan" class="form-control" value="{{ old('brand_slogan', $payload['brand_slogan'] ?? '') }}">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="preferred_colors" class="form-label">Preferred Colors <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                        <input type="text" name="preferred_colors" id="preferred_colors" class="form-control" placeholder="e.g. Indigo and Soft Gold (#4F46E5, #D97706)" value="{{ old('preferred_colors', $payload['preferred_colors'] ?? '') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="preferred_style" class="form-label">Preferred Style <span style="color: var(--color-danger);">*</span></label>
                        <select name="preferred_style" id="preferred_style" class="form-control" required>
                            <option value="Modern" {{ old('preferred_style', $payload['preferred_style'] ?? '') == 'Modern' ? 'selected' : '' }}>Modern</option>
                            <option value="Minimal" {{ old('preferred_style', $payload['preferred_style'] ?? '') == 'Minimal' ? 'selected' : '' }}>Minimal</option>
                            <option value="Luxury" {{ old('preferred_style', $payload['preferred_style'] ?? '') == 'Luxury' ? 'selected' : '' }}>Luxury</option>
                            <option value="Professional" {{ old('preferred_style', $payload['preferred_style'] ?? '') == 'Professional' ? 'selected' : '' }}>Professional</option>
                            <option value="Creative" {{ old('preferred_style', $payload['preferred_style'] ?? '') == 'Creative' ? 'selected' : '' }}>Creative</option>
                            <option value="Other" {{ old('preferred_style', $payload['preferred_style'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="branding_requirements" class="form-label">Additional Branding Requirements <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="branding_requirements" id="branding_requirements" rows="4" class="form-control" style="height: auto;">{{ old('branding_requirements', $payload['branding_requirements'] ?? '') }}</textarea>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToStep(1)">Back</button>
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-2-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    <!-- ================== STEP 3: WEBSITE ================== -->
    @if($hasWebsite)
        <div id="step-form-container-3" class="step-form-content {{ $currentStep == 3 ? 'active' : '' }}" style="display: {{ $currentStep == 3 ? 'block' : 'none' }};">
            <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Website Setup Details</h2>
            <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Detail your development and design platform requirements.</p>

            <form action="{{ route('services.save_step', 'branding-website') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="3">
                <input type="hidden" name="action" id="step-3-action" value="save_continue">

                <div class="form-group">
                    <label class="form-label">Do you already have a website? <span style="color: var(--color-danger);">*</span></label>
                    <div class="segmented-control">
                        @php $hasWebsiteField = old('has_existing_website', $payload['has_existing_website'] ?? 'no'); @endphp
                        <input type="radio" name="has_existing_website" id="web_yes" value="yes" class="segmented-option" {{ $hasWebsiteField == 'yes' ? 'checked' : '' }} onchange="toggleWebsiteUrl('yes')">
                        <label for="web_yes" class="segmented-label">Yes</label>

                        <input type="radio" name="has_existing_website" id="web_no" value="no" class="segmented-option" {{ $hasWebsiteField == 'no' ? 'checked' : '' }} onchange="toggleWebsiteUrl('no')">
                        <label for="web_no" class="segmented-label">No</label>
                    </div>
                </div>

                <div class="form-group" id="website-url-container" style="display: {{ $hasWebsiteField == 'yes' ? 'block' : 'none' }};">
                    <label for="existing_website_url" class="form-label">Existing Website URL <span style="color: var(--color-danger);">*</span></label>
                    <input type="url" name="existing_website_url" id="existing_website_url" class="form-control" placeholder="https://example.com" value="{{ old('existing_website_url', $payload['existing_website_url'] ?? '') }}">
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="website_platform" class="form-label">Website Platform <span style="color: var(--color-danger);">*</span></label>
                        <select name="website_platform" id="website_platform" class="form-control" required>
                            <option value="Shopify" {{ old('website_platform', $payload['website_platform'] ?? '') == 'Shopify' ? 'selected' : '' }}>Shopify</option>
                            <option value="WordPress" {{ old('website_platform', $payload['website_platform'] ?? '') == 'WordPress' ? 'selected' : '' }}>WordPress</option>
                            <option value="Custom" {{ old('website_platform', $payload['website_platform'] ?? '') == 'Custom' ? 'selected' : '' }}>Custom Development</option>
                            <option value="Not Sure" {{ old('website_platform', $payload['website_platform'] ?? '') == 'Not Sure' ? 'selected' : '' }}>Not Sure</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="domain_name" class="form-label">Domain Name <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                        <input type="text" name="domain_name" id="domain_name" class="form-control" placeholder="e.g. mybrandname.com" value="{{ old('domain_name', $payload['domain_name'] ?? '') }}">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="number_products" class="form-label">Number of Products <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" name="number_products" id="number_products" class="form-control" min="0" value="{{ old('number_products', $payload['number_products'] ?? 0) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Required Pages <span style="color: var(--color-danger);">*</span></label>
                    @php $pages = old('required_pages', $payload['required_pages'] ?? []); @endphp
                    <div class="custom-multiselect-container" id="required_pages_container">
                        <div class="custom-multiselect-trigger">
                            <span class="multiselect-placeholder">Select required pages...</span>
                        </div>
                        <div class="custom-multiselect-dropdown">
                            @foreach(['Home', 'About', 'Contact', 'Shop', 'Product', 'Blog', 'FAQ', 'Privacy Policy', 'Terms & Conditions'] as $pg)
                                <div class="multiselect-option {{ in_array($pg, $pages) ? 'selected' : '' }}" data-value="{{ $pg }}">
                                    <input type="checkbox" class="multiselect-checkbox" {{ in_array($pg, $pages) ? 'checked' : '' }}>
                                    <span>{{ $pg }}</span>
                                </div>
                            @endforeach
                        </div>
                        <select name="required_pages[]" id="required_pages" style="display: none;" multiple required>
                            @foreach(['Home', 'About', 'Contact', 'Shop', 'Product', 'Blog', 'FAQ', 'Privacy Policy', 'Terms & Conditions'] as $pg)
                                <option value="{{ $pg }}" {{ in_array($pg, $pages) ? 'selected' : '' }}>{{ $pg }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Payment Gateway Required? <span style="color: var(--color-danger);">*</span></label>
                        <div class="segmented-control">
                            @php $payGateway = old('payment_gateway', $payload['payment_gateway'] ?? 'no'); @endphp
                            <input type="radio" name="payment_gateway" id="pay_yes" value="yes" class="segmented-option" {{ $payGateway == 'yes' ? 'checked' : '' }}>
                            <label for="pay_yes" class="segmented-label">Yes</label>
                            
                            <input type="radio" name="payment_gateway" id="pay_no" value="no" class="segmented-option" {{ $payGateway == 'no' ? 'checked' : '' }}>
                            <label for="pay_no" class="segmented-label">No</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Shipping Setup Required? <span style="color: var(--color-danger);">*</span></label>
                        <div class="segmented-control">
                            @php $shipGateway = old('shipping_setup', $payload['shipping_setup'] ?? 'no'); @endphp
                            <input type="radio" name="shipping_setup" id="ship_yes" value="yes" class="segmented-option" {{ $shipGateway == 'yes' ? 'checked' : '' }}>
                            <label for="ship_yes" class="segmented-label">Yes</label>
                            
                            <input type="radio" name="shipping_setup" id="ship_no" value="no" class="segmented-option" {{ $shipGateway == 'no' ? 'checked' : '' }}>
                            <label for="ship_no" class="segmented-label">No</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="website_requirements" class="form-label">Website Requirements & Details <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="website_requirements" id="website_requirements" rows="4" class="form-control" style="height: auto;">{{ old('website_requirements', $payload['website_requirements'] ?? '') }}</textarea>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToPreviousStep(3)">Back</button>
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-3-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    <!-- ================== STEP 4: DIGITAL ADVERTISING ================== -->
    @if($hasAdvertising)
        <div id="step-form-container-4" class="step-form-content {{ $currentStep == 4 ? 'active' : '' }}" style="display: {{ $currentStep == 4 ? 'block' : 'none' }};">
            <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Digital Advertising Options</h2>
            <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Outline target audiences, budget profiles, and advertising triggers.</p>

            <form action="{{ route('services.save_step', 'branding-website') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="4">
                <input type="hidden" name="action" id="step-4-action" value="save_continue">

                <div class="form-group">
                    <label class="form-label">Advertising Platforms <span style="color: var(--color-danger);">*</span></label>
                    @php $adPlats = old('ad_platforms', $payload['ad_platforms'] ?? []); @endphp
                    <div class="custom-multiselect-container" id="ad_platforms_container">
                        <div class="custom-multiselect-trigger">
                            <span class="multiselect-placeholder">Select advertising platforms...</span>
                        </div>
                        <div class="custom-multiselect-dropdown">
                            @foreach(['Meta', 'Google', 'TikTok', 'Pinterest', 'Other'] as $ap)
                                <div class="multiselect-option {{ in_array($ap, $adPlats) ? 'selected' : '' }}" data-value="{{ $ap }}">
                                    <input type="checkbox" class="multiselect-checkbox" {{ in_array($ap, $adPlats) ? 'checked' : '' }}>
                                    <span>{{ $ap }}</span>
                                </div>
                            @endforeach
                        </div>
                        <select name="ad_platforms[]" id="ad_platforms" style="display: none;" multiple required>
                            @foreach(['Meta', 'Google', 'TikTok', 'Pinterest', 'Other'] as $ap)
                                <option value="{{ $ap }}" {{ in_array($ap, $adPlats) ? 'selected' : '' }}>{{ $ap }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Do you have existing ad accounts? <span style="color: var(--color-danger);">*</span></label>
                        <div class="segmented-control">
                            @php $hasAdAcc = old('has_ad_accounts', $payload['has_ad_accounts'] ?? 'no'); @endphp
                            <input type="radio" name="has_ad_accounts" id="adacc_yes" value="yes" class="segmented-option" {{ $hasAdAcc == 'yes' ? 'checked' : '' }}>
                            <label for="adacc_yes" class="segmented-label">Yes</label>
                            
                            <input type="radio" name="has_ad_accounts" id="adacc_no" value="no" class="segmented-option" {{ $hasAdAcc == 'no' ? 'checked' : '' }}>
                            <label for="adacc_no" class="segmented-label">No</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Google Ads Account? <span style="color: var(--color-danger);">*</span></label>
                        <div class="segmented-control">
                            @php $googleAds = old('google_ads', $payload['google_ads'] ?? 'no'); @endphp
                            <input type="radio" name="google_ads" id="gads_yes" value="yes" class="segmented-option" {{ $googleAds == 'yes' ? 'checked' : '' }}>
                            <label for="gads_yes" class="segmented-label">Yes</label>
                            
                            <input type="radio" name="google_ads" id="gads_no" value="no" class="segmented-option" {{ $googleAds == 'no' ? 'checked' : '' }}>
                            <label for="gads_no" class="segmented-label">No</label>
                        </div>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="fb_page_url" class="form-label">Facebook / Instagram Page URL <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                        <input type="url" name="fb_page_url" id="fb_page_url" class="form-control" value="{{ old('fb_page_url', $payload['fb_page_url'] ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="tiktok_account" class="form-label">TikTok Account URL <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                        <input type="url" name="tiktok_account" id="tiktok_account" class="form-control" value="{{ old('tiktok_account', $payload['tiktok_account'] ?? '') }}">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="pinterest_account" class="form-label">Pinterest Account URL <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                        <input type="url" name="pinterest_account" id="pinterest_account" class="form-control" value="{{ old('pinterest_account', $payload['pinterest_account'] ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="ad_budget" class="form-label">Monthly Advertising Budget ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" name="ad_budget" id="ad_budget" class="form-control" min="0" value="{{ old('ad_budget', $payload['ad_budget'] ?? 0) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Target Countries / Regions <span style="color: var(--color-danger);">*</span></label>
                    @php $regions = old('target_regions', $payload['target_regions'] ?? []); @endphp
                    <div class="custom-multiselect-container" id="target_regions_container">
                        <div class="custom-multiselect-trigger">
                            <span class="multiselect-placeholder">Select target countries...</span>
                        </div>
                        <div class="custom-multiselect-dropdown">
                            @foreach(['United States', 'Canada', 'United Kingdom', 'European Union', 'Australia', 'Other'] as $rg)
                                <div class="multiselect-option {{ in_array($rg, $regions) ? 'selected' : '' }}" data-value="{{ $rg }}">
                                    <input type="checkbox" class="multiselect-checkbox" {{ in_array($rg, $regions) ? 'checked' : '' }}>
                                    <span>{{ $rg }}</span>
                                </div>
                            @endforeach
                        </div>
                        <select name="target_regions[]" id="target_regions" style="display: none;" multiple required>
                            @foreach(['United States', 'Canada', 'United Kingdom', 'European Union', 'Australia', 'Other'] as $rg)
                                <option value="{{ $rg }}" {{ in_array($rg, $regions) ? 'selected' : '' }}>{{ $rg }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="target_audience" class="form-label">Target Audience Description <span style="color: var(--color-danger);">*</span></label>
                    <textarea name="target_audience" id="target_audience" rows="3" class="form-control" style="height: auto;" placeholder="Describe demographics, interests, and targets..." required>{{ old('target_audience', $payload['target_audience'] ?? '') }}</textarea>
                </div>

                <!-- Selectable Cards for Advertising Goals -->
                <div class="form-group">
                    <label class="form-label">Advertising Goals <span style="color: var(--color-danger);">*</span></label>
                    <div class="selection-grid">
                        @php $goals = old('ad_goals', $payload['ad_goals'] ?? []); @endphp
                        
                        <div class="selection-card {{ in_array('Sales', $goals) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'goal-sales')">
                            <input type="checkbox" name="ad_goals[]" id="goal-sales" value="Sales" class="selection-checkbox" {{ in_array('Sales', $goals) ? 'checked' : '' }}>
                            <div class="selection-card-details">
                                <span class="selection-card-title">Sales</span>
                                <span class="selection-card-desc">Conversions and orders</span>
                            </div>
                        </div>

                        <div class="selection-card {{ in_array('Leads', $goals) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'goal-leads')">
                            <input type="checkbox" name="ad_goals[]" id="goal-leads" value="Leads" class="selection-checkbox" {{ in_array('Leads', $goals) ? 'checked' : '' }}>
                            <div class="selection-card-details">
                                <span class="selection-card-title">Leads</span>
                                <span class="selection-card-desc">Signups and subscriptions</span>
                            </div>
                        </div>

                        <div class="selection-card {{ in_array('Website Traffic', $goals) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'goal-traffic')">
                            <input type="checkbox" name="ad_goals[]" id="goal-traffic" value="Website Traffic" class="selection-checkbox" {{ in_array('Website Traffic', $goals) ? 'checked' : '' }}>
                            <div class="selection-card-details">
                                <span class="selection-card-title">Website Traffic</span>
                                <span class="selection-card-desc">Pageviews and search reach</span>
                            </div>
                        </div>

                        <div class="selection-card {{ in_array('Brand Awareness', $goals) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'goal-brand')">
                            <input type="checkbox" name="ad_goals[]" id="goal-brand" value="Brand Awareness" class="selection-checkbox" {{ in_array('Brand Awareness', $goals) ? 'checked' : '' }}>
                            <div class="selection-card-details">
                                <span class="selection-card-title">Brand Awareness</span>
                                <span class="selection-card-desc">Social metrics & brand lift</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ad_requirements" class="form-label">Additional Requirements <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="ad_requirements" id="ad_requirements" rows="3" class="form-control" style="height: auto;">{{ old('ad_requirements', $payload['ad_requirements'] ?? '') }}</textarea>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToPreviousStep(4)">Back</button>
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-4-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    <!-- ================== STEP 5: REVIEW ================== -->
    <div id="step-form-container-5" class="step-form-content {{ $currentStep == 5 ? 'active' : '' }}" style="display: {{ $currentStep == 5 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Review Selections & Requirements</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Verify all configurations before submitting setup details.</p>

        <div style="display: flex; flex-direction: column; gap: var(--spacing-6); margin-bottom: var(--spacing-6);">
            
            <!-- Category 1: Services Selection -->
            <div style="border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--spacing-4);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-3); border-bottom: 1px solid var(--color-border); padding-bottom: var(--spacing-2);">
                    <h3 style="font-size: var(--fs-sm); font-weight: var(--fw-bold);">Selected Services</h3>
                    <button class="btn btn-secondary" style="height: 28px; padding: 0 var(--spacing-3); font-size: var(--fs-xs);" onclick="jumpToStep(1)">Edit</button>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: var(--spacing-2);">
                    @forelse($selected as $srv)
                        <span class="badge" style="background-color: var(--color-primary-light); color: var(--color-primary); font-size: var(--fs-xs);">
                            {{ ucwords(str_replace('_', ' ', $srv)) }}
                        </span>
                    @empty
                        <span style="font-size: var(--fs-xs); color: var(--color-text-muted);">No services selected.</span>
                    @endforelse
                </div>
            </div>

            <!-- Category 2: Branding -->
            @if($hasBranding)
                <div style="border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--spacing-4);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-3); border-bottom: 1px solid var(--color-border); padding-bottom: var(--spacing-2);">
                        <h3 style="font-size: var(--fs-sm); font-weight: var(--fw-bold);">Branding Details</h3>
                        <button class="btn btn-secondary" style="height: 28px; padding: 0 var(--spacing-3); font-size: var(--fs-xs);" onclick="jumpToStep(2)">Edit</button>
                    </div>
                    <table style="width: 100%; border-collapse: collapse; font-size: var(--fs-xs);">
                        <tr style="border-bottom: 1px solid var(--color-bg-base);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary); width: 30%;">Brand Name:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-medium);">{{ $payload['brand_name'] ?? 'N/A' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--color-bg-base);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Tagline / Slogan:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-medium);">{{ $payload['brand_slogan'] ?? 'N/A' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--color-bg-base);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Style:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-medium);">{{ $payload['preferred_style'] ?? 'N/A' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--color-bg-base);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Colors:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-medium);">{{ $payload['preferred_colors'] ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            @endif

            <!-- Category 3: Website -->
            @if($hasWebsite)
                <div style="border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--spacing-4);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-3); border-bottom: 1px solid var(--color-border); padding-bottom: var(--spacing-2);">
                        <h3 style="font-size: var(--fs-sm); font-weight: var(--fw-bold);">Website Details</h3>
                        <button class="btn btn-secondary" style="height: 28px; padding: 0 var(--spacing-3); font-size: var(--fs-xs);" onclick="jumpToStep(3)">Edit</button>
                    </div>
                    <table style="width: 100%; border-collapse: collapse; font-size: var(--fs-xs);">
                        <tr style="border-bottom: 1px solid var(--color-bg-base);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary); width: 30%;">Existing Website?</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-medium);">{{ strtoupper($payload['has_existing_website'] ?? 'no') }}</td>
                        </tr>
                        @if(($payload['has_existing_website'] ?? 'no') === 'yes')
                            <tr style="border-bottom: 1px solid var(--color-bg-base);">
                                <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Website URL:</td>
                                <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-medium);">{{ $payload['existing_website_url'] ?? 'N/A' }}</td>
                            </tr>
                        @endif
                        <tr style="border-bottom: 1px solid var(--color-bg-base);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Target Platform:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-medium);">{{ $payload['website_platform'] ?? 'N/A' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--color-bg-base);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Product Count:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-medium);">{{ $payload['number_products'] ?? 0 }} Products</td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--color-bg-base);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Gateways & Ship:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-medium);">
                                Payments: {{ strtoupper($payload['payment_gateway'] ?? 'no') }} • Shipping: {{ strtoupper($payload['shipping_setup'] ?? 'no') }}
                            </td>
                        </tr>
                    </table>
                </div>
            @endif

            <!-- Category 4: Advertising -->
            @if($hasAdvertising)
                <div style="border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--spacing-4);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-3); border-bottom: 1px solid var(--color-border); padding-bottom: var(--spacing-2);">
                        <h3 style="font-size: var(--fs-sm); font-weight: var(--fw-bold);">Advertising Details</h3>
                        <button class="btn btn-secondary" style="height: 28px; padding: 0 var(--spacing-3); font-size: var(--fs-xs);" onclick="jumpToStep(4)">Edit</button>
                    </div>
                    <table style="width: 100%; border-collapse: collapse; font-size: var(--fs-xs);">
                        <tr style="border-bottom: 1px solid var(--color-bg-base);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary); width: 30%;">Target Platforms:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-medium);">
                                {{ implode(', ', $payload['ad_platforms'] ?? []) }}
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--color-bg-base);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Monthly Budget:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-medium); color: var(--color-primary); font-weight: var(--fw-semibold);">
                                ${{ number_format($payload['ad_budget'] ?? 0, 2) }}
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--color-bg-base);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Ad Goals:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-medium);">
                                {{ implode(', ', $payload['ad_goals'] ?? []) }}
                            </td>
                        </tr>
                    </table>
                </div>
            @endif

        </div>

        <form action="{{ route('services.save_step', 'branding-website') }}" method="POST">
            @csrf
            <input type="hidden" name="step" value="5">
            <input type="hidden" name="action" value="save_continue">

            <div class="form-navigation">
                <button type="button" class="btn btn-secondary" onclick="jumpToPreviousStep(5)">Back</button>
                <div>
                    @if($status !== 'completed')
                        <button type="submit" class="btn btn-primary">Complete Requirements</button>
                    @else
                        <a href="{{ route('services.index') }}" class="btn btn-secondary">Return to Overview</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

</div>

</div>{{-- /tab-content-wizard --}}

<!-- TAB: OVERVIEW DASHBOARD -->
<div id="tab-content-overview" class="tab-content {{ $status === 'completed' ? 'active' : '' }}">
    
    @if($status === 'completed')
        <div class="card" style="padding: var(--spacing-4);">
            <h3 style="font-size: var(--fs-base); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4);">Completed Service Details</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--color-border);">
                            <th style="padding: var(--spacing-3) var(--spacing-2); color: var(--color-text-secondary); font-weight: var(--fw-semibold); font-size: var(--fs-sm);">Step Name</th>
                            <th style="padding: var(--spacing-3) var(--spacing-2); color: var(--color-text-secondary); font-weight: var(--fw-semibold); font-size: var(--fs-sm);">Status</th>
                            <th style="padding: var(--spacing-3) var(--spacing-2); color: var(--color-text-secondary); font-weight: var(--fw-semibold); font-size: var(--fs-sm); text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $allStepsList = [
                                1 => 'Service Selection',
                                2 => 'Branding',
                                3 => 'Website',
                                4 => 'Advertising',
                                5 => 'Final Review'
                            ];
                        @endphp
                        @foreach($allStepsList as $stepNum => $stepTitle)
                            @if($stepsVisible[$stepNum] ?? false)
                                <tr style="border-bottom: 1px solid var(--color-border-light);">
                                    <td style="padding: var(--spacing-3) var(--spacing-2); font-weight: var(--fw-medium); font-size: var(--fs-sm);">Step {{ $stepNum }}: {{ $stepTitle }}</td>
                                    <td style="padding: var(--spacing-3) var(--spacing-2);"><span class="badge badge-success">Completed</span></td>
                                    <td style="padding: var(--spacing-3) var(--spacing-2); text-align: right; white-space: nowrap;">
                                        <button type="button" class="btn btn-secondary" style="font-size: 11px; padding: 4px 8px; height: auto; margin-right: 4px;" onclick="openViewModal()">View</button>
                                        <button type="button" class="btn btn-primary" style="font-size: 11px; padding: 4px 8px; height: auto;" onclick="startEditMode({{ $stepNum }})">Edit</button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card" style="padding: var(--spacing-6);">
            <h2 style="font-size: var(--fs-xl); font-weight: var(--fw-bold); margin-bottom: var(--spacing-1);">Branding & Website Overview</h2>
            <p style="color: var(--color-text-secondary); margin-bottom: var(--spacing-6);">Summary of your branding, website and advertising setup progress.</p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: var(--spacing-4); margin-bottom: var(--spacing-6);">
                <div style="background: var(--color-bg-base); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--spacing-4);">
                    <div style="font-size: 10px; color: var(--color-text-secondary); font-weight: var(--fw-semibold); text-transform: uppercase; letter-spacing: 0.05em;">Current Step</div>
                    <div style="font-size: var(--fs-2xl); font-weight: var(--fw-bold); color: var(--color-primary); margin-top: 4px;">{{ $currentStep }}/5</div>
                </div>
                <div style="background: var(--color-bg-base); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--spacing-4);">
                    <div style="font-size: 10px; color: var(--color-text-secondary); font-weight: var(--fw-semibold); text-transform: uppercase; letter-spacing: 0.05em;">Status</div>
                    <div style="font-size: var(--fs-lg); font-weight: var(--fw-bold); color: var(--color-text-primary); margin-top: 4px; text-transform: capitalize;">{{ str_replace('_', ' ', $status) }}</div>
                </div>
                <div style="background: var(--color-bg-base); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--spacing-4);">
                    <div style="font-size: 10px; color: var(--color-text-secondary); font-weight: var(--fw-semibold); text-transform: uppercase; letter-spacing: 0.05em;">Branding</div>
                    <div style="font-size: var(--fs-lg); font-weight: var(--fw-bold); margin-top: 4px; color: {{ $hasBranding ? 'var(--color-success)' : 'var(--color-text-muted)' }};">{{ $hasBranding ? 'Selected' : 'Not Selected' }}</div>
                </div>
                <div style="background: var(--color-bg-base); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--spacing-4);">
                    <div style="font-size: 10px; color: var(--color-text-secondary); font-weight: var(--fw-semibold); text-transform: uppercase; letter-spacing: 0.05em;">Website</div>
                    <div style="font-size: var(--fs-lg); font-weight: var(--fw-bold); margin-top: 4px; color: {{ $hasWebsite ? 'var(--color-success)' : 'var(--color-text-muted)' }};">{{ $hasWebsite ? 'Selected' : 'Not Selected' }}</div>
                </div>
                <div style="background: var(--color-bg-base); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--spacing-4);">
                    <div style="font-size: 10px; color: var(--color-text-secondary); font-weight: var(--fw-semibold); text-transform: uppercase; letter-spacing: 0.05em;">Advertising</div>
                    <div style="font-size: var(--fs-lg); font-weight: var(--fw-bold); margin-top: 4px; color: {{ $hasAdvertising ? 'var(--color-success)' : 'var(--color-text-muted)' }};">{{ $hasAdvertising ? 'Selected' : 'Not Selected' }}</div>
                </div>
            </div>

            @if(!empty($payload['brand_name']))
            <div style="margin-bottom: var(--spacing-4); padding: var(--spacing-4); background: var(--color-bg-base); border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                <div style="font-size: var(--fs-sm); font-weight: var(--fw-semibold); color: var(--color-text-secondary); margin-bottom: var(--spacing-2);">Brand Details</div>
                <div><strong>Brand Name:</strong> {{ $payload['brand_name'] ?? '—' }}</div>
                @if(!empty($payload['brand_slogan']))<div style="margin-top: 4px;"><strong>Slogan:</strong> {{ $payload['brand_slogan'] }}</div>@endif
                @if(!empty($payload['preferred_style']))<div style="margin-top: 4px;"><strong>Style:</strong> {{ $payload['preferred_style'] }}</div>@endif
            </div>
            @endif

            @if(!empty($payload['website_platform']))
            <div style="margin-bottom: var(--spacing-4); padding: var(--spacing-4); background: var(--color-bg-base); border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                <div style="font-size: var(--fs-sm); font-weight: var(--fw-semibold); color: var(--color-text-secondary); margin-bottom: var(--spacing-2);">Website Details</div>
                <div><strong>Platform:</strong> {{ $payload['website_platform'] ?? '—' }}</div>
                @if(!empty($payload['domain_name']))<div style="margin-top: 4px;"><strong>Domain:</strong> {{ $payload['domain_name'] }}</div>@endif
                @if(!empty($payload['number_products']))<div style="margin-top: 4px;"><strong>Products:</strong> {{ $payload['number_products'] }}</div>@endif
            </div>
            @endif

            <div style="text-align: center; margin-top: var(--spacing-4);">
                <button class="btn btn-primary" onclick="switchMainTab('wizard')">Go to Setup Stepper</button>
            </div>
        </div>
    @endif
</div>{{-- /tab-content-overview --}}
<!-- VIEW DETAILS MODAL -->
<div id="view-details-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-content" style="background: var(--color-bg-base); width: 95%; max-width: 850px; max-height: 90vh; border-radius: var(--radius-xl); padding: 0; overflow: hidden; position: relative; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); display: flex; flex-direction: column;">
        
        <!-- Modal Header -->
        <div style="padding: var(--spacing-5) var(--spacing-6); border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; background: var(--color-bg-alt);">
            <div>
                <h2 style="font-size: var(--fs-lg); font-weight: var(--fw-bold); color: var(--color-text-primary); margin: 0;">Branding & Website Summary</h2>
                <p style="font-size: var(--fs-sm); color: var(--color-text-secondary); margin: 4px 0 0 0;">Review all submitted details for this service.</p>
            </div>
            <button type="button" onclick="document.getElementById('view-details-modal').style.display='none'" style="background: var(--color-bg-base); border: 1px solid var(--color-border); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: var(--fs-lg); cursor: pointer; color: var(--color-text-secondary); transition: all 0.2s ease;">&times;</button>
        </div>

        <!-- Modal Body -->
        <div style="padding: var(--spacing-6); overflow-y: auto; background: var(--color-bg-base); display: flex; flex-direction: column; gap: var(--spacing-5);">
            
            <!-- Step 1: Service Selection -->
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">1</div>
                    Service Selection
                </h3>
                <div style="display: grid; grid-template-columns: 1fr; gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div>
                        <div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Selected Services</div>
                        <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                            @if(isset($payload['services']) && is_array($payload['services']))
                                @foreach($payload['services'] as $svc)
                                    <span class="badge badge-primary">{{ ucwords(str_replace('_', ' ', $svc)) }}</span>
                                @endforeach
                            @else
                                <span class="badge badge-secondary">None</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Branding -->
            @if($hasBranding)
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">2</div>
                    Branding
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Brand Name</div><div style="font-weight: var(--fw-medium);">{{ $payload['brand_name'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Slogan</div><div style="font-weight: var(--fw-medium);">{{ $payload['brand_slogan'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Has Logo?</div><div><span class="badge {{ ($payload['has_logo'] ?? '') === 'yes' ? 'badge-success' : 'badge-secondary' }}">{{ ucfirst($payload['has_logo'] ?? 'N/A') }}</span></div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Preferred Style</div><div style="font-weight: var(--fw-medium);">{{ $payload['preferred_style'] ?? 'N/A' }}</div></div>
                    <div style="grid-column: 1 / -1;"><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Brand Colors</div><div style="font-weight: var(--fw-medium);">{{ $payload['brand_colors'] ?? 'N/A' }}</div></div>
                </div>
            </div>
            @endif

            <!-- Step 3: Website -->
            @if($hasWebsite)
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">3</div>
                    Website Development
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Has Domain?</div><div><span class="badge {{ ($payload['has_domain'] ?? '') === 'yes' ? 'badge-success' : 'badge-secondary' }}">{{ ucfirst($payload['has_domain'] ?? 'N/A') }}</span></div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Domain Name</div><div style="font-weight: var(--fw-medium);">{{ $payload['domain_name'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Website Platform</div><div style="font-weight: var(--fw-medium);">{{ $payload['website_platform'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Number of Products</div><div style="font-weight: var(--fw-medium);">{{ $payload['number_products'] ?? 'N/A' }}</div></div>
                    <div style="grid-column: 1 / -1;"><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Reference Websites</div><div style="font-weight: var(--fw-medium);">{{ $payload['reference_websites'] ?? 'N/A' }}</div></div>
                </div>
            </div>
            @endif

            <!-- Step 4: Advertising -->
            @if($hasAdvertising)
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">4</div>
                    Advertising
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Monthly Budget</div><div style="font-weight: var(--fw-medium);">{{ $payload['monthly_budget'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Primary Goal</div><div style="font-weight: var(--fw-medium);">{{ $payload['primary_goal'] ?? 'N/A' }}</div></div>
                    <div style="grid-column: 1 / -1;"><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Target Audience</div><div style="font-weight: var(--fw-medium);">{{ $payload['target_audience'] ?? 'N/A' }}</div></div>
                </div>
            </div>
            @endif

            <!-- Step 5: Final Review -->
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">5</div>
                    Additional Notes
                </h3>
                <div style="display: grid; grid-template-columns: 1fr; gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Additional Info</div><div style="font-weight: var(--fw-medium); line-height: 1.5;">{{ $payload['additional_notes'] ?? 'None provided.' }}</div></div>
                </div>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div style="padding: var(--spacing-4) var(--spacing-6); border-top: 1px solid var(--color-border); background: var(--color-bg-base); display: flex; justify-content: flex-end;">
            <button class="btn btn-secondary" onclick="document.getElementById('view-details-modal').style.display='none'">Close</button>
        </div>
    </div>
</div>

@endsection

@section('dashboard_scripts')
<script>
    let isEditMode = false;
    let editModeStep = null;

    function openViewModal() {
        const modal = document.getElementById('view-details-modal');
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    function startEditMode(stepNumber) {
        isEditMode = true;
        editModeStep = stepNumber;
        switchMainTab('wizard');
        jumpToStep(stepNumber);
        
        // Disable all other step navigations
        for (let i = 1; i <= 5; i++) {
            const item = document.getElementById('step-nav-' + i);
            if (item) {
                if (i !== stepNumber) {
                    item.style.pointerEvents = 'none';
                    item.style.opacity = '0.4';
                } else {
                    item.style.pointerEvents = 'auto';
                    item.style.opacity = '1';
                }
            }
            // Update submit button text to Save & Return
            const formContainer = document.getElementById('step-form-container-' + i);
            if (formContainer && i === stepNumber) {
                const submitBtns = formContainer.querySelectorAll('button[type="submit"].btn-primary');
                submitBtns.forEach(btn => btn.innerText = 'Save & Return');
            }
        }
    }

    // Visibility Map of steps
    const stepsVisible = @json($stepsVisible);

    // Toggle selected card style
    function toggleCheckboxCard(cardElement, checkboxId) {
        const checkbox = document.getElementById(checkboxId);
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            if (checkbox.checked) {
                cardElement.classList.add('selected');
            } else {
                cardElement.classList.remove('selected');
            }
        }
    }

    // Toggle conditional existing logo upload field
    function toggleLogoUpload(hasLogo) {
        const container = document.getElementById('logo-upload-container');
        if (container) {
            container.style.display = (hasLogo === 'yes') ? 'block' : 'none';
        }
    }

    // Toggle conditional existing website URL field
    function toggleWebsiteUrl(hasWebsite) {
        const container = document.getElementById('website-url-container');
        const urlInput = document.getElementById('existing_website_url');
        if (container) {
            container.style.display = (hasWebsite === 'yes') ? 'block' : 'none';
            if (urlInput) urlInput.required = (hasWebsite === 'yes');
        }
    }

    const stepNames = {
        1: 'Service Selection',
        2: 'Branding Requirements',
        3: 'Website Setup',
        4: 'Digital Advertising',
        5: 'Review & Finish'
    };

    // Step switching within Wizard
    function jumpToStep(stepNumber) {
        if (isEditMode && stepNumber !== editModeStep) return;
        
        // Ensure requested step is visible based on conditional logic
        for (let i = 1; i <= 5; i++) {
            const form = document.getElementById('step-form-container-' + i);
            if (form) {
                form.style.display = (i === stepNumber) ? 'block' : 'none';
            }
            
            const item = document.getElementById('step-nav-' + i);
            if (item) {
                if (i === stepNumber) {
                    item.classList.add('in-progress');
                    // Smoothly scroll active step into view on touch devices
                    item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                } else {
                    item.classList.remove('in-progress');
                }
            }
        }

        // Update mobile indicator text
        const mobileIndicator = document.getElementById('mobile-step-name');
        if (mobileIndicator && stepNames[stepNumber]) {
            mobileIndicator.innerText = stepNames[stepNumber];
        }
    }

    // Handle back action skipping non-selected steps
    function jumpToPreviousStep(currentStepNum) {
        let prevStep = currentStepNum - 1;
        while (prevStep > 1 && !stepsVisible[prevStep]) {
            prevStep--;
        }
        jumpToStep(prevStep);
    }

    // Initialize custom multi-select dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        initCustomMultiselect('required_pages_container');
        initCustomMultiselect('ad_platforms_container');
        initCustomMultiselect('target_regions_container');
    });
    // Switch main tabs
    function switchMainTab(tab) {
        const wizardContent = document.getElementById('tab-content-wizard');
        const overviewContent = document.getElementById('tab-content-overview');
        const wizardBtn = document.getElementById('tab-btn-wizard');
        const overviewBtn = document.getElementById('tab-btn-overview');
        if (tab === 'wizard') {
            wizardContent.style.display = 'block';
            overviewContent.style.display = 'none';
            wizardBtn.classList.add('active');
            overviewBtn.classList.remove('active');
        } else {
            wizardContent.style.display = 'none';
            overviewContent.style.display = 'block';
            wizardBtn.classList.remove('active');
            overviewBtn.classList.add('active');
        }
    }

</script>
@endsection
