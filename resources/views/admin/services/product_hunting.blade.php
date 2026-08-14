@extends('layouts.dashboard')

@section('title', 'Product Hunting & Sourcing')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
@endsection

@section('content')
<!-- Breadcrumbs -->
<nav class="breadcrumbs" style="margin-bottom: var(--spacing-2); margin-top: 0;">
    <a href="{{ route('admin.dashboard') }}">Console</a>
    <a href="{{ route('services.index') }}">Services</a>
    <span>Product Hunting & Sourcing</span>
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

<!-- Active Step Tracking -->
@php
    $categories = ['Apparel & Fashion', 'Beauty & Personal Care', 'Electronics', 'Home & Kitchen', 'Health & Wellness', 'Sports & Fitness', 'Baby & Kids', 'Pet Products', 'Accessories', 'Other'];
    $sourcingTypes = ['Manufacturer', 'Wholesaler', 'Supplier', 'Private Label', 'White Label', 'Factory'];
    
    // Dynamic defaults for JSON arrays
    $targetMarket = $payload['target_market'] ?? [];
    $sourcingType = $payload['sourcing_type'] ?? [];
    $researchMarket = $payload['research_market'] ?? [];
    $researchTypes = $payload['research_types'] ?? [];
    $competitorUrls = $payload['competitor_urls'] ?? [''];
    
    // Repeatable structures
    $competitorRecords = $payload['competitor_records'] ?? [];
    $supplierRecords = $payload['supplier_records'] ?? [];
    $ratings = $payload['ratings'] ?? [];
    $validationChecklist = $payload['validation_checklist'] ?? [];
    $qualityChecklist = $payload['quality_checklist'] ?? [];
    $adPlatforms = $payload['ad_platforms'] ?? [];
    $targetRegions = $payload['target_regions'] ?? [];
    $adGoals = $payload['ad_goals'] ?? [];
    
    $stepTitles = [
        1 => 'Product Requirements',
        2 => 'Market Research',
        3 => 'Demand Analysis',
        4 => 'Competitor Research',
        5 => 'Pricing & Profit',
        6 => 'Product Validation',
        7 => 'Supplier Research',
        8 => 'Supplier Comparison',
        9 => 'Sample Coordination',
        10 => 'Quality Check',
        11 => 'Negotiation',
        12 => 'Manufacturing',
        13 => 'Final Approval'
    ];

    $completedStepsCount = 0;
    if (!empty($payload['product_category'])) $completedStepsCount++;
    if (!empty($payload['niche'])) $completedStepsCount++;
    if (!empty($payload['market_trend'])) $completedStepsCount++;
    if (!empty($payload['competitor_records'])) $completedStepsCount++;
    if (!empty($payload['selling_price'])) $completedStepsCount++;
    if (!empty($payload['validation_notes'])) $completedStepsCount++;
    if (!empty($payload['supplier_records'])) $completedStepsCount++;
    if (!empty($payload['best_supplier'])) $completedStepsCount++;
    if (!empty($payload['sample_ordered'])) $completedStepsCount++;
    if (!empty($payload['quality_result'])) $completedStepsCount++;
    if (!empty($payload['negotiated_price'])) $completedStepsCount++;
    if (!empty($payload['production_status'])) $completedStepsCount++;
    if ($status === 'completed') $completedStepsCount++;
    
    $percentage = round(($completedStepsCount / 13) * 100);
@endphp

<!-- Completed Banner -->
@if($status === 'completed')
    <div class="card" style="border-color: var(--color-success); background-color: var(--color-success-light); margin-bottom: var(--spacing-4); text-align: center; padding: var(--spacing-6);">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 48px; height: 48px; color: var(--color-success); margin: 0 auto var(--spacing-3);">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0110 21a3.745 3.745 0 01-3.068-1.593 3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0114 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
        </svg>
        <h2 style="color: var(--color-success-dark); font-size: var(--fs-xl); font-weight: var(--fw-bold);">Product Sourcing Completed</h2>
        <p style="color: var(--color-success-dark); font-size: var(--fs-sm); margin-top: 2px;">This product has been fully researched, validated, negotiated, and manufacturing tracked.</p>
    </div>
@endif

<!-- Tabs Navigation -->
<div class="tabs-navigation" style="margin-bottom: var(--spacing-3);">
    <button class="tab-btn {{ $status !== 'completed' ? 'active' : '' }}" id="tab-btn-wizard" onclick="switchMainTab('wizard')" style="{{ $status === 'completed' ? 'display: none;' : '' }}">Setup Wizard</button>
    <button class="tab-btn {{ $status === 'completed' ? 'active' : '' }}" id="tab-btn-overview" onclick="switchMainTab('overview')" style="{{ $status !== 'completed' ? 'display: none;' : '' }}">Sourcing Dashboard</button>
</div>

<!-- ================== TAB 1: SETUP WIZARD ================== -->
<div id="tab-content-wizard" class="tab-content {{ $status !== 'completed' ? 'active' : '' }}">

    <!-- Mobile Step Status Bar -->
    <div class="mobile-step-indicator" style="display: flex; align-items: center; justify-content: space-between; background-color: #ffffff; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 10px 14px; margin-bottom: var(--spacing-3); font-size: var(--fs-xs); box-shadow: var(--shadow-card);">
        <div style="display: flex; align-items: center; gap: 6px;">
            <span style="color: var(--color-text-muted);">Step {{ $currentStep }} of 13:</span>
            <strong style="color: var(--color-primary);" id="mobile-step-name">
                {{ $stepTitles[$currentStep] ?? 'Step ' . $currentStep }}
            </strong>
        </div>
        <span class="badge badge-success">{{ $percentage }}% Done</span>
    </div>

    <!-- Horizontal Stepper -->
    <div class="stepper-container" id="stepper-scroll-container" style="margin-bottom: var(--spacing-4);">
        <ol class="stepper" id="stepper-list">
            @foreach($stepTitles as $stepNum => $title)
                <li class="step-item {{ $currentStep == $stepNum ? 'in-progress' : ($currentStep > $stepNum ? 'completed' : 'not-started') }}" id="step-nav-{{ $stepNum }}" onclick="jumpToStep({{ $stepNum }})">
                    <div class="step-circle">{{ str_pad($stepNum, 2, '0', STR_PAD_LEFT) }}</div>
                    <span class="step-title">{{ $title }}</span>
                </li>
            @endforeach
        </ol>
    </div>

    <!-- Wizard Form Cards -->
    <div class="card" style="padding: var(--spacing-4) var(--spacing-4); margin-bottom: 80px;">

        <!-- STEP 1: PRODUCT REQUIREMENTS -->
        <div id="step-form-container-1" class="step-form-content {{ $currentStep == 1 ? 'active' : '' }}" style="display: {{ $currentStep == 1 ? 'block' : 'none' }};">
            <div style="margin-bottom: var(--spacing-6);">
                <span class="badge badge-primary">Step 01</span>
                <h2 style="font-size: var(--fs-xl); margin-top: var(--spacing-1);">Product Requirements</h2>
                <p style="color: var(--color-text-secondary); font-size: var(--fs-sm);">Define product idea, target segment, price parameters, and sourcing models.</p>
            </div>

            <form action="{{ route('services.save_step', 'product-hunting') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="1">
                <input type="hidden" name="action" id="step-1-action" value="save_continue">

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="product_category" class="form-label">Product Category <span style="color: var(--color-danger);">*</span></label>
                        <select name="product_category" id="product_category" class="form-control" required>
                            <option value="">Choose category...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('product_category', $payload['product_category'] ?? '') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="product_idea" class="form-label">Product Type / Idea <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" name="product_idea" id="product_idea" class="form-control" placeholder="e.g. Ergonomic Silicon Spatula" value="{{ old('product_idea', $payload['product_idea'] ?? '') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="product_description" class="form-label">Product Description <span style="color: var(--color-danger);">*</span></label>
                    <textarea name="product_description" id="product_description" rows="3" class="form-control" style="height: auto;" placeholder="Brief details about build, design, material..." required>{{ old('product_description', $payload['product_description'] ?? '') }}</textarea>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Target Market <span style="color: var(--color-danger);">*</span></label>
                        <div class="custom-multiselect-container" id="target_market_container">
                            <div class="custom-multiselect-trigger">
                                <span class="multiselect-placeholder">Select target countries...</span>
                            </div>
                            <div class="custom-multiselect-dropdown">
                                @foreach(['USA', 'Canada', 'UK', 'UAE', 'Europe', 'Other'] as $mkt)
                                    <div class="multiselect-option {{ in_array($mkt, $targetMarket) ? 'selected' : '' }}" data-value="{{ $mkt }}">
                                        <input type="checkbox" class="multiselect-checkbox" {{ in_array($mkt, $targetMarket) ? 'checked' : '' }}>
                                        <span>{{ $mkt }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <select name="target_market[]" id="target_market" style="display: none;" multiple required>
                                @foreach(['USA', 'Canada', 'UK', 'UAE', 'Europe', 'Other'] as $mkt)
                                    <option value="{{ $mkt }}" {{ in_array($mkt, $targetMarket) ? 'selected' : '' }}>{{ $mkt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Preferred Sourcing Type <span style="color: var(--color-danger);">*</span></label>
                        <div class="custom-multiselect-container" id="sourcing_type_container">
                            <div class="custom-multiselect-trigger">
                                <span class="multiselect-placeholder">Select sourcing models...</span>
                            </div>
                            <div class="custom-multiselect-dropdown">
                                @foreach($sourcingTypes as $st)
                                    <div class="multiselect-option {{ in_array($st, $sourcingType) ? 'selected' : '' }}" data-value="{{ $st }}">
                                        <input type="checkbox" class="multiselect-checkbox" {{ in_array($st, $sourcingType) ? 'checked' : '' }}>
                                        <span>{{ $st }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <select name="sourcing_type[]" id="sourcing_type" style="display: none;" multiple required>
                                @foreach($sourcingTypes as $st)
                                    <option value="{{ $st }}" {{ in_array($st, $sourcingType) ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="target_customer" class="form-label">Target Customer Segment <span style="color: var(--color-danger);">*</span></label>
                    <textarea name="target_customer" id="target_customer" rows="2" class="form-control" style="height: auto;" placeholder="e.g. Home bakers, professional chefs looking for heat-resistance..." required>{{ old('target_customer', $payload['target_customer'] ?? '') }}</textarea>
                </div>

                <div class="form-grid-4">
                    <div class="form-group">
                        <label for="selling_price" class="form-label">Selling Price ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="selling_price" id="selling_price" class="form-control" value="{{ old('selling_price', $payload['selling_price'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="product_cost" class="form-label">Product Cost ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="product_cost" id="product_cost" class="form-control" value="{{ old('product_cost', $payload['product_cost'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="profit_margin" class="form-label">Profit Margin (%) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.1" name="profit_margin" id="profit_margin" class="form-control" value="{{ old('profit_margin', $payload['profit_margin'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="initial_moq" class="form-label">Initial MOQ <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" name="initial_moq" id="initial_moq" class="form-control" value="{{ old('initial_moq', $payload['initial_moq'] ?? '') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Customization Required? <span style="color: var(--color-danger);">*</span></label>
                    <div class="segmented-control" style="max-width: 200px;">
                        @php $customReq = old('customization_required', $payload['customization_required'] ?? 'no'); @endphp
                        <input type="radio" name="customization_required" id="custom_yes" value="yes" class="segmented-option" {{ $customReq == 'yes' ? 'checked' : '' }} onchange="toggleCustomizationDetails('yes')">
                        <label for="custom_yes" class="segmented-label">Yes</label>

                        <input type="radio" name="customization_required" id="custom_no" value="no" class="segmented-option" {{ $customReq == 'no' ? 'checked' : '' }} onchange="toggleCustomizationDetails('no')">
                        <label for="custom_no" class="segmented-label">No</label>
                    </div>
                </div>

                <div class="form-group" id="customization_details_container" style="display: {{ $customReq == 'yes' ? 'block' : 'none' }};">
                    <label for="customization_details" class="form-label">Customization Details <span style="color: var(--color-danger);">*</span></label>
                    <textarea name="customization_details" id="customization_details" rows="2" class="form-control" style="height: auto;" placeholder="Provide logo engraving, custom packaging, colors...">{{ old('customization_details', $payload['customization_details'] ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="additional_requirements" class="form-label">Additional Product Requirements <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="additional_requirements" id="additional_requirements" rows="2" class="form-control" style="height: auto;">{{ old('additional_requirements', $payload['additional_requirements'] ?? '') }}</textarea>
                </div>

                <!-- Sticky Footer navigation inside step -->
                <div class="form-navigation">
                    <div></div>
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-1-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- STEP 2: MARKET RESEARCH -->
        <div id="step-form-container-2" class="step-form-content {{ $currentStep == 2 ? 'active' : '' }}" style="display: {{ $currentStep == 2 ? 'block' : 'none' }};">
            <div style="margin-bottom: var(--spacing-6);">
                <span class="badge badge-primary">Step 02</span>
                <h2 style="font-size: var(--fs-xl); margin-top: var(--spacing-1);">Market Research</h2>
                <p style="color: var(--color-text-secondary); font-size: var(--fs-sm);">Evaluate market size, growth velocity, and seasonality factors.</p>
            </div>

            <form action="{{ route('services.save_step', 'product-hunting') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="2">
                <input type="hidden" name="action" id="step-2-action" value="save_continue">

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="niche" class="form-label">Target Niche / Category <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" name="niche" id="niche" class="form-control" placeholder="e.g. Eco-friendly Cookware" value="{{ old('niche', $payload['niche'] ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Research Market <span style="color: var(--color-danger);">*</span></label>
                        <div class="custom-multiselect-container" id="research_market_container">
                            <div class="custom-multiselect-trigger">
                                <span class="multiselect-placeholder">Select target countries...</span>
                            </div>
                            <div class="custom-multiselect-dropdown">
                                @foreach(['USA', 'Canada', 'UK', 'UAE', 'Europe', 'Other'] as $mkt)
                                    <div class="multiselect-option {{ in_array($mkt, $researchMarket) ? 'selected' : '' }}" data-value="{{ $mkt }}">
                                        <input type="checkbox" class="multiselect-checkbox" {{ in_array($mkt, $researchMarket) ? 'checked' : '' }}>
                                        <span>{{ $mkt }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <select name="research_market[]" id="research_market" style="display: none;" multiple required>
                                @foreach(['USA', 'Canada', 'UK', 'UAE', 'Europe', 'Other'] as $mkt)
                                    <option value="{{ $mkt }}" {{ in_array($mkt, $researchMarket) ? 'selected' : '' }}>{{ $mkt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Research Type <span style="color: var(--color-danger);">*</span></label>
                        <div class="custom-multiselect-container" id="research_types_container">
                            <div class="custom-multiselect-trigger">
                                <span class="multiselect-placeholder">Select research focuses...</span>
                            </div>
                            <div class="custom-multiselect-dropdown">
                                @foreach(['Product Demand', 'Market Size', 'Customer Interest', 'Product Trends', 'Seasonal Demand', 'Search Trends'] as $rt)
                                    <div class="multiselect-option {{ in_array($rt, $researchTypes) ? 'selected' : '' }}" data-value="{{ $rt }}">
                                        <input type="checkbox" class="multiselect-checkbox" {{ in_array($rt, $researchTypes) ? 'checked' : '' }}>
                                        <span>{{ $rt }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <select name="research_types[]" id="research_types" style="display: none;" multiple required>
                                @foreach(['Product Demand', 'Market Size', 'Customer Interest', 'Product Trends', 'Seasonal Demand', 'Search Trends'] as $rt)
                                    <option value="{{ $rt }}" {{ in_array($rt, $researchTypes) ? 'selected' : '' }}>{{ $rt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="customer_segment" class="form-label">Target Customer Segment <span style="color: var(--color-danger);">*</span></label>
                    <textarea name="customer_segment" id="customer_segment" rows="2" class="form-control" style="height: auto;" placeholder="Provide demographic data, preferences..." required>{{ old('customer_segment', $payload['customer_segment'] ?? '') }}</textarea>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="target_price_min" class="form-label">Target Price Min ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="target_price_min" id="target_price_min" class="form-control" value="{{ old('target_price_min', $payload['target_price_min'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="target_price_max" class="form-label">Target Price Max ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="target_price_max" id="target_price_max" class="form-control" value="{{ old('target_price_max', $payload['target_price_max'] ?? '') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="research_keywords" class="form-label">Research Keywords <span style="color: var(--color-danger);">*</span> <span style="font-size: var(--fs-xs); color: var(--color-text-muted); font-weight: normal;">(Separate keywords with commas)</span></label>
                    <input type="text" name="research_keywords" id="research_keywords" class="form-control" placeholder="e.g. silicon spatula, heat resistant spatula, kitchen utensil" value="{{ old('research_keywords', $payload['research_keywords'] ?? '') }}" required>
                </div>

                <!-- Repeatable Competitor URLs list -->
                <div class="form-group">
                    <label class="form-label">Competitor Listings / URLs <span style="color: var(--color-danger);">*</span></label>
                    <div id="competitor-urls-list" style="display: flex; flex-direction: column; gap: var(--spacing-2); margin-bottom: var(--spacing-2);">
                        @foreach($competitorUrls as $idx => $url)
                            <div style="display: flex; gap: var(--spacing-2);">
                                <input type="url" name="competitor_urls[]" class="form-control" placeholder="https://amazon.com/dp/..." value="{{ $url }}" required>
                                <button type="button" class="btn btn-secondary" style="color: var(--color-danger); border-color: rgba(239, 68, 68, 0.2);" onclick="removeCompetitorUrlRow(this)">Remove</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-secondary" style="font-size: var(--fs-xs); height: 32px;" onclick="addCompetitorUrlRow()">+ Add Competitor/Product URL</button>
                </div>

                <div class="form-group">
                    <label for="research_notes" class="form-label">Market Research Notes <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="research_notes" id="research_notes" rows="2" class="form-control" style="height: auto;">{{ old('research_notes', $payload['research_notes'] ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="research_findings" class="form-label">Research Findings & Summary <span style="color: var(--color-danger);">*</span></label>
                    <textarea name="research_findings" id="research_findings" rows="4" class="form-control" style="height: auto;" placeholder="Summarize key target metrics, estimated search volume, seasonal index trends..." required>{{ old('research_findings', $payload['research_findings'] ?? '') }}</textarea>
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

        <!-- STEP 3: DEMAND ANALYSIS -->
        <div id="step-form-container-3" class="step-form-content {{ $currentStep == 3 ? 'active' : '' }}" style="display: {{ $currentStep == 3 ? 'block' : 'none' }};">
            <div style="margin-bottom: var(--spacing-6);">
                <span class="badge badge-primary">Step 03</span>
                <h2 style="font-size: var(--fs-xl); margin-top: var(--spacing-1);">Product Demand Analysis</h2>
                <p style="color: var(--color-text-secondary); font-size: var(--fs-sm);">Quantify demand variables, search trends, seasonality indexes, and overall demand score.</p>
            </div>

            <form action="{{ route('services.save_step', 'product-hunting') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="3">
                <input type="hidden" name="action" id="step-3-action" value="save_continue">

                <div class="form-grid-3">
                    <div class="form-group">
                        <label for="demand_level" class="form-label">Demand Level <span style="color: var(--color-danger);">*</span></label>
                        <select name="demand_level" id="demand_level" class="form-control" required>
                            @foreach(['Very Low', 'Low', 'Medium', 'High', 'Very High'] as $dl)
                                <option value="{{ $dl }}" {{ old('demand_level', $payload['demand_level'] ?? '') == $dl ? 'selected' : '' }}>{{ $dl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="demand_trend" class="form-label">Demand Trend <span style="color: var(--color-danger);">*</span></label>
                        <select name="demand_trend" id="demand_trend" class="form-control" required>
                            @foreach(['Declining', 'Stable', 'Growing', 'Seasonal', 'Unknown'] as $dt)
                                <option value="{{ $dt }}" {{ old('demand_trend', $payload['demand_trend'] ?? '') == $dt ? 'selected' : '' }}>{{ $dt }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="search_interest" class="form-label">Search Interest <span style="color: var(--color-danger);">*</span></label>
                        <select name="search_interest" id="search_interest" class="form-control" required>
                            @foreach(['Low', 'Medium', 'High'] as $si)
                                <option value="{{ $si }}" {{ old('search_interest', $payload['search_interest'] ?? '') == $si ? 'selected' : '' }}>{{ $si }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="monthly_demand" class="form-label">Estimated Monthly Demand <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" name="monthly_demand" id="monthly_demand" class="form-control" value="{{ old('monthly_demand', $payload['monthly_demand'] ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="demand_score" class="form-label">Demand Score (0 - 100) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" min="0" max="100" name="demand_score" id="demand_score" class="form-control" value="{{ old('demand_score', $payload['demand_score'] ?? '') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Seasonality? <span style="color: var(--color-danger);">*</span></label>
                    <div class="segmented-control" style="max-width: 200px;">
                        @php $season = old('seasonality', $payload['seasonality'] ?? 'no'); @endphp
                        <input type="radio" name="seasonality" id="seas_yes" value="yes" class="segmented-option" {{ $season == 'yes' ? 'checked' : '' }} onchange="togglePeakSeason('yes')">
                        <label for="seas_yes" class="segmented-label">Yes</label>

                        <input type="radio" name="seasonality" id="seas_no" value="no" class="segmented-option" {{ $season == 'no' ? 'checked' : '' }} onchange="togglePeakSeason('no')">
                        <label for="seas_no" class="segmented-label">No</label>
                    </div>
                </div>

                <div class="form-group" id="peak-season-container" style="display: {{ $season == 'yes' ? 'block' : 'none' }};">
                    <label for="peak_season" class="form-label">Peak Season <span style="color: var(--color-danger);">*</span></label>
                    <input type="text" name="peak_season" id="peak_season" class="form-control" placeholder="e.g. Q4 / Nov-Dec" value="{{ old('peak_season', $payload['peak_season'] ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="demand_analysis" class="form-label">Demand Analysis Notes <span style="color: var(--color-danger);">*</span></label>
                    <textarea name="demand_analysis" id="demand_analysis" rows="3" class="form-control" style="height: auto;" placeholder="Summarize Google Trends index, Amazon listing insights, social media interest..." required>{{ old('demand_analysis', $payload['demand_analysis'] ?? '') }}</textarea>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToStep(2)">Back</button>
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-3-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- STEP 4: COMPETITOR RESEARCH -->
        <div id="step-form-container-4" class="step-form-content {{ $currentStep == 4 ? 'active' : '' }}" style="display: {{ $currentStep == 4 ? 'block' : 'none' }};">
            <div style="margin-bottom: var(--spacing-6);">
                <span class="badge badge-primary">Step 04</span>
                <h2 style="font-size: var(--fs-xl); margin-top: var(--spacing-1);">Competitor Research</h2>
                <p style="color: var(--color-text-secondary); font-size: var(--fs-sm);">Log competitor details inside a repeatable matrix, listing competitor metrics, strengths, and weaknesses.</p>
            </div>

            <form action="{{ route('services.save_step', 'product-hunting') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="4">
                <input type="hidden" name="action" id="step-4-action" value="save_continue">

                <!-- Repeatable Competitors Table -->
                <div class="form-group">
                    <label class="form-label" style="margin-bottom: var(--spacing-2); display: block;">Competitor Records</label>
                    <div style="overflow-x: auto; margin-bottom: var(--spacing-3);">
                        <table class="data-table" style="min-width: 1000px;" id="competitors-table">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Competitor</th>
                                    <th style="width: 15%;">Product Name</th>
                                    <th style="width: 15%;">Listing URL</th>
                                    <th style="width: 10%;">Price ($)</th>
                                    <th style="width: 8%;">Rating</th>
                                    <th style="width: 8%;">Reviews</th>
                                    <th style="width: 15%;">Key Features</th>
                                    <th style="width: 10%;">Position</th>
                                    <th style="width: 4%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($competitorRecords as $idx => $record)
                                    <tr>
                                        <td>
                                            <input type="text" name="competitor_records[{{ $idx }}][name]" class="form-control" style="height: 32px; font-size: var(--fs-xs);" value="{{ $record['name'] ?? '' }}" required>
                                        </td>
                                        <td>
                                            <input type="text" name="competitor_records[{{ $idx }}][product_name]" class="form-control" style="height: 32px; font-size: var(--fs-xs);" value="{{ $record['product_name'] ?? '' }}" required>
                                        </td>
                                        <td>
                                            <input type="url" name="competitor_records[{{ $idx }}][product_url]" class="form-control" style="height: 32px; font-size: var(--fs-xs);" value="{{ $record['product_url'] ?? '' }}" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="competitor_records[{{ $idx }}][selling_price]" class="form-control" style="height: 32px; font-size: var(--fs-xs);" value="{{ $record['selling_price'] ?? '' }}" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.1" min="0" max="5" name="competitor_records[{{ $idx }}][rating]" class="form-control" style="height: 32px; font-size: var(--fs-xs);" value="{{ $record['rating'] ?? '' }}" required>
                                        </td>
                                        <td>
                                            <input type="number" name="competitor_records[{{ $idx }}][reviews]" class="form-control" style="height: 32px; font-size: var(--fs-xs);" value="{{ $record['reviews'] ?? '' }}" required>
                                        </td>
                                        <td>
                                            <input type="text" name="competitor_records[{{ $idx }}][features]" class="form-control" style="height: 32px; font-size: var(--fs-xs);" value="{{ $record['features'] ?? '' }}" required>
                                        </td>
                                        <td>
                                            <input type="text" name="competitor_records[{{ $idx }}][position]" class="form-control" style="height: 32px; font-size: var(--fs-xs);" value="{{ $record['position'] ?? '' }}" required>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-secondary" style="color: var(--color-danger); height: 28px; padding: 0 var(--spacing-2);" onclick="removeTableRow(this)">X</button>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Fallback row -->
                                    <tr class="fallback-row">
                                        <td colspan="9" style="text-align: center; color: var(--color-text-muted); font-size: var(--fs-xs); padding: var(--spacing-4);">No competitor records added. Click add below to append.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-secondary" style="font-size: var(--fs-xs); height: 32px;" onclick="addCompetitorRecordRow()">+ Add Competitor</button>
                </div>

                <div class="form-group">
                    <label for="competitor_strengths" class="form-label">Competitor Strengths <span style="color: var(--color-danger);">*</span></label>
                    <textarea name="competitor_strengths" id="competitor_strengths" rows="2" class="form-control" style="height: auto;" placeholder="e.g. Excellent packaging, fast shipment, strong brand presence..." required>{{ old('competitor_strengths', $payload['competitor_strengths'] ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="competitor_weaknesses" class="form-label">Competitor Weaknesses <span style="color: var(--color-danger);">*</span></label>
                    <textarea name="competitor_weaknesses" id="competitor_weaknesses" rows="2" class="form-control" style="height: auto;" placeholder="e.g. High return rates due to snap handles, poor colors..." required>{{ old('competitor_weaknesses', $payload['competitor_weaknesses'] ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="competitive_advantage" class="form-label">Our Competitive Advantage <span style="color: var(--color-danger);">*</span></label>
                    <textarea name="competitive_advantage" id="competitive_advantage" rows="2" class="form-control" style="height: auto;" placeholder="e.g. Modified steel core handles, custom color choices..." required>{{ old('competitive_advantage', $payload['competitive_advantage'] ?? '') }}</textarea>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToStep(3)">Back</button>
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-4-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- STEP 5: PRICING & PROFIT -->
        <div id="step-form-container-5" class="step-form-content {{ $currentStep == 5 ? 'active' : '' }}" style="display: {{ $currentStep == 5 ? 'block' : 'none' }};">
            <div style="margin-bottom: var(--spacing-6);">
                <span class="badge badge-primary">Step 05</span>
                <h2 style="font-size: var(--fs-xl); margin-top: var(--spacing-1);">Pricing & Profit Analysis</h2>
                <p style="color: var(--color-text-secondary); font-size: var(--fs-sm);">Map manufacturing, shipment, customs, and marketing overheads. Calculations occur dynamically in the database.</p>
            </div>

            <form action="{{ route('services.save_step', 'product-hunting') }}" method="POST" id="pricing-form">
                @csrf
                <input type="hidden" name="step" value="5">
                <input type="hidden" name="action" id="step-5-action" value="save_continue">

                <div class="form-grid-3">
                    <div class="form-group">
                        <label for="est_product_cost" class="form-label">Estimated Product Cost ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="est_product_cost" id="est_product_cost" class="form-control calc-trigger" value="{{ old('est_product_cost', $payload['est_product_cost'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="est_manufacturing_cost" class="form-label">Manufacturing Cost ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="est_manufacturing_cost" id="est_manufacturing_cost" class="form-control calc-trigger" value="{{ old('est_manufacturing_cost', $payload['est_manufacturing_cost'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="est_packaging_cost" class="form-label">Packaging Cost ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="est_packaging_cost" id="est_packaging_cost" class="form-control calc-trigger" value="{{ old('est_packaging_cost', $payload['est_packaging_cost'] ?? '') }}" required>
                    </div>
                </div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label for="est_shipping_cost" class="form-label">Shipping Cost ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="est_shipping_cost" id="est_shipping_cost" class="form-control calc-trigger" value="{{ old('est_shipping_cost', $payload['est_shipping_cost'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="est_import_duties" class="form-label">Import / Duties Cost ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="est_import_duties" id="est_import_duties" class="form-control calc-trigger" value="{{ old('est_import_duties', $payload['est_import_duties'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="est_marketplace_fees" class="form-label">Marketplace Fees ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="est_marketplace_fees" id="est_marketplace_fees" class="form-control calc-trigger" value="{{ old('est_marketplace_fees', $payload['est_marketplace_fees'] ?? '') }}" required>
                    </div>
                </div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label for="est_advertising_cost" class="form-label">Advertising Cost ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="est_advertising_cost" id="est_advertising_cost" class="form-control calc-trigger" value="{{ old('est_advertising_cost', $payload['est_advertising_cost'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="est_other_costs" class="form-label">Other Costs ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="est_other_costs" id="est_other_costs" class="form-control calc-trigger" value="{{ old('est_other_costs', $payload['est_other_costs'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="est_selling_price" class="form-label">Target Selling Price ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="est_selling_price" id="est_selling_price" class="form-control calc-trigger" value="{{ old('est_selling_price', $payload['est_selling_price'] ?? '') }}" required>
                    </div>
                </div>

                <!-- Real-time Mathematical Calculation Card (SaaS theme) -->
                <div class="card" style="background-color: var(--color-bg-base); padding: var(--spacing-5); margin-bottom: var(--spacing-4); border-color: var(--color-border);">
                    <h3 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-3);">Calculated Profitability (Estimate)</h3>
                    <div class="form-grid-5" style="text-align: center;">
                        <div>
                            <span style="font-size: 10px; text-transform: uppercase; color: var(--color-text-secondary);">Target Price</span>
                            <div style="font-size: var(--fs-lg); font-weight: var(--fw-bold); color: var(--color-text-primary);" id="cal-price-display">
                                ${{ number_format($payload['est_selling_price'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div>
                            <span style="font-size: 10px; text-transform: uppercase; color: var(--color-text-secondary);">Total Cost</span>
                            <div style="font-size: var(--fs-lg); font-weight: var(--fw-bold); color: var(--color-text-primary);" id="cal-cost-display">
                                ${{ number_format($payload['cal_total_cost'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div>
                            <span style="font-size: 10px; text-transform: uppercase; color: var(--color-text-secondary);">Expected Profit</span>
                            <div style="font-size: var(--fs-lg); font-weight: var(--fw-bold); color: var(--color-success);" id="cal-profit-display">
                                ${{ number_format($payload['cal_expected_profit'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div>
                            <span style="font-size: 10px; text-transform: uppercase; color: var(--color-text-secondary);">Profit Margin</span>
                            <div style="font-size: var(--fs-lg); font-weight: var(--fw-bold); color: var(--color-success);" id="cal-margin-display">
                                {{ number_format($payload['cal_profit_margin'] ?? 0, 2) }}%
                            </div>
                        </div>
                        <div>
                            <span style="font-size: 10px; text-transform: uppercase; color: var(--color-text-secondary);">ROI</span>
                            <div style="font-size: var(--fs-lg); font-weight: var(--fw-bold); color: var(--color-primary);" id="cal-roi-display">
                                {{ number_format($payload['cal_roi'] ?? 0, 2) }}%
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToStep(4)">Back</button>
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-5-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- STEP 6: PRODUCT VALIDATION -->
        <div id="step-form-container-6" class="step-form-content {{ $currentStep == 6 ? 'active' : '' }}" style="display: {{ $currentStep == 6 ? 'block' : 'none' }};">
            <div style="margin-bottom: var(--spacing-6);">
                <span class="badge badge-primary">Step 06</span>
                <h2 style="font-size: var(--fs-xl); margin-top: var(--spacing-1);">Product Validation</h2>
                <p style="color: var(--color-text-secondary); font-size: var(--fs-sm);">Record validation scores (Demand, Competition, Profitability) and complete verification checklist.</p>
            </div>

            <form action="{{ route('services.save_step', 'product-hunting') }}" method="POST" id="validation-form">
                @csrf
                <input type="hidden" name="step" value="6">
                <input type="hidden" name="action" id="step-6-action" value="save_continue">

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="validation_status" class="form-label">Validation Status <span style="color: var(--color-danger);">*</span></label>
                        <select name="validation_status" id="validation_status" class="form-control" required>
                            @foreach(['Pending', 'Under Review', 'Approved', 'Rejected', 'Needs Changes'] as $vs)
                                <option value="{{ $vs }}" {{ old('validation_status', $payload['validation_status'] ?? '') == $vs ? 'selected' : '' }}>{{ $vs }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="potential_level" class="form-label">Product Potential <span style="color: var(--color-danger);">*</span></label>
                        <select name="potential_level" id="potential_level" class="form-control" required>
                            @foreach(['Low', 'Medium', 'High'] as $pl)
                                <option value="{{ $pl }}" {{ old('potential_level', $payload['potential_level'] ?? '') == $pl ? 'selected' : '' }}>{{ $pl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-grid-4">
                    <div class="form-group">
                        <label for="val_demand_score" class="form-label">Demand Score (0 - 100) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" min="0" max="100" name="val_demand_score" id="val_demand_score" class="form-control val-score-trigger" value="{{ old('val_demand_score', $payload['val_demand_score'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="val_competition_score" class="form-label">Competition Score (0 - 100) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" min="0" max="100" name="val_competition_score" id="val_competition_score" class="form-control val-score-trigger" value="{{ old('val_competition_score', $payload['val_competition_score'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="val_profitability_score" class="form-label">Profitability Score (0 - 100) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" min="0" max="100" name="val_profitability_score" id="val_profitability_score" class="form-control val-score-trigger" value="{{ old('val_profitability_score', $payload['val_profitability_score'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Overall Product Score</label>
                        <div class="form-control" style="background-color: var(--color-bg-base); font-weight: var(--fw-bold); border-color: var(--color-border); display: flex; align-items: center;" id="overall-score-display">
                            {{ $payload['cal_overall_score'] ?? '0.00' }} / 100
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Validation Checklist <span style="color: var(--color-danger);">*</span></label>
                    <div class="selection-grid">
                        @foreach([
                            'Demand validated', 'Competition researched', 'Profit margin acceptable',
                            'Supplier availability confirmed', 'Target price achievable', 'Product quality acceptable',
                            'Market opportunity identified'
                        ] as $chk)
                            <div class="selection-card {{ in_array($chk, $validationChecklist) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'chk-val-{{ Str::slug($chk) }}')">
                                <input type="checkbox" name="validation_checklist[]" id="chk-val-{{ Str::slug($chk) }}" value="{{ $chk }}" class="selection-checkbox" {{ in_array($chk, $validationChecklist) ? 'checked' : '' }}>
                                <div class="selection-card-details">
                                    <span class="selection-card-title" style="font-size: var(--fs-xs);">{{ $chk }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="final_recommendation" class="form-label">Final Recommendation <span style="color: var(--color-danger);">*</span></label>
                        <select name="final_recommendation" id="final_recommendation" class="form-control" required>
                            @foreach(['Proceed', 'Proceed with Changes', 'Research More', 'Reject'] as $fr)
                                <option value="{{ $fr }}" {{ old('final_recommendation', $payload['final_recommendation'] ?? '') == $fr ? 'selected' : '' }}>{{ $fr }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="validation_notes" class="form-label">Validation Notes <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="validation_notes" id="validation_notes" rows="3" class="form-control" style="height: auto;">{{ old('validation_notes', $payload['validation_notes'] ?? '') }}</textarea>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToStep(5)">Back</button>
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-6-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- STEP 7: SUPPLIER RESEARCH -->
        <div id="step-form-container-7" class="step-form-content {{ $currentStep == 7 ? 'active' : '' }}" style="display: {{ $currentStep == 7 ? 'block' : 'none' }};">
            <div style="margin-bottom: var(--spacing-6);">
                <span class="badge badge-primary">Step 07</span>
                <h2 style="font-size: var(--fs-xl); margin-top: var(--spacing-1);">Supplier Research</h2>
                <p style="color: var(--color-text-secondary); font-size: var(--fs-sm);">Research and log details for multiple manufacturers. They will automatically sync to step 8 comparison matrices.</p>
            </div>

            <form action="{{ route('services.save_step', 'product-hunting') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="7">
                <input type="hidden" name="action" id="step-7-action" value="save_continue">

                <!-- Repeatable Suppliers Card Matrix -->
                <div class="form-group">
                    <label class="form-label" style="margin-bottom: var(--spacing-3); display: block;">Registered Suppliers</label>
                    
                    <div id="suppliers-card-container" style="display: flex; flex-direction: column; gap: var(--spacing-6);">
                        @forelse($supplierRecords as $idx => $sup)
                            <div class="card supplier-entry-card" style="padding: var(--spacing-5); border-left: 4px solid var(--color-primary);" data-index="{{ $idx }}">
                                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); padding-bottom: var(--spacing-2); margin-bottom: var(--spacing-4);">
                                    <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); color: var(--color-primary);">Supplier #{{ $idx + 1 }}</h4>
                                    <button type="button" class="btn btn-secondary" style="color: var(--color-danger); height: 28px; padding: 0 var(--spacing-2);" onclick="removeSupplierCard(this)">Delete Supplier</button>
                                </div>
                                <div class="form-grid-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 11px;">Supplier Name *</label>
                                        <input type="text" name="supplier_records[{{ $idx }}][name]" class="form-control supplier-name-input" value="{{ $sup['name'] ?? '' }}" required style="height: 34px;">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 11px;">Supplier Type *</label>
                                        <select name="supplier_records[{{ $idx }}][type]" class="form-control" required style="height: 34px;">
                                            @foreach($sourcingTypes as $st)
                                                <option value="{{ $st }}" {{ ($sup['type'] ?? '') == $st ? 'selected' : '' }}>{{ $st }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 11px;">Country *</label>
                                        <input type="text" name="supplier_records[{{ $idx }}][country]" class="form-control" value="{{ $sup['country'] ?? '' }}" required style="height: 34px;">
                                    </div>
                                </div>
                                <div class="form-grid-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 11px;">Website *</label>
                                        <input type="url" name="supplier_records[{{ $idx }}][website]" class="form-control" value="{{ $sup['website'] ?? '' }}" required style="height: 34px;">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 11px;">Contact Person *</label>
                                        <input type="text" name="supplier_records[{{ $idx }}][contact_person]" class="form-control" value="{{ $sup['contact_person'] ?? '' }}" required style="height: 34px;">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 11px;">Email *</label>
                                        <input type="email" name="supplier_records[{{ $idx }}][email]" class="form-control" value="{{ $sup['email'] ?? '' }}" required style="height: 34px;">
                                    </div>
                                </div>
                                <div class="form-grid-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 11px;">Phone *</label>
                                        <input type="text" name="supplier_records[{{ $idx }}][phone]" class="form-control" value="{{ $sup['phone'] ?? '' }}" required style="height: 34px;">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 11px;">Product URL *</label>
                                        <input type="url" name="supplier_records[{{ $idx }}][product_url]" class="form-control" value="{{ $sup['product_url'] ?? '' }}" required style="height: 34px;">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 11px;">MOQ *</label>
                                        <input type="number" name="supplier_records[{{ $idx }}][moq]" class="form-control" value="{{ $sup['moq'] ?? '' }}" required style="height: 34px;">
                                    </div>
                                </div>
                                <div class="form-grid-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 11px;">Unit Price ($) *</label>
                                        <input type="number" step="0.01" name="supplier_records[{{ $idx }}][unit_price]" class="form-control" value="{{ $sup['unit_price'] ?? '' }}" required style="height: 34px;">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 11px;">Lead Time (days) *</label>
                                        <input type="text" name="supplier_records[{{ $idx }}][lead_time]" class="form-control" value="{{ $sup['lead_time'] ?? '' }}" required style="height: 34px;">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 11px;">Customization Available? *</label>
                                        <select name="supplier_records[{{ $idx }}][customization]" class="form-control" required style="height: 34px;">
                                            <option value="yes" {{ ($sup['customization'] ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                                            <option value="no" {{ ($sup['customization'] ?? '') == 'no' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-grid-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 11px;">Private Label Available? *</label>
                                        <select name="supplier_records[{{ $idx }}][private_label]" class="form-control" required style="height: 34px;">
                                            <option value="yes" {{ ($sup['private_label'] ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                                            <option value="no" {{ ($sup['private_label'] ?? '') == 'no' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 11px;">White Label Available? *</label>
                                        <select name="supplier_records[{{ $idx }}][white_label]" class="form-control" required style="height: 34px;">
                                            <option value="yes" {{ ($sup['white_label'] ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                                            <option value="no" {{ ($sup['white_label'] ?? '') == 'no' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" style="font-size: 11px;">Certifications *</label>
                                        <input type="text" name="supplier_records[{{ $idx }}][certifications][]" class="form-control" placeholder="CE, FDA, ISO (separate with commas)" value="{{ isset($sup['certifications']) ? implode(', ', (array)$sup['certifications']) : '' }}" required style="height: 34px;">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="font-size: 11px;">Supplier Notes</label>
                                    <textarea name="supplier_records[{{ $idx }}][notes]" rows="2" class="form-control" style="height: auto;">{{ $sup['notes'] ?? '' }}</textarea>
                                </div>
                            </div>
                        @empty
                            <div class="fallback-row" style="text-align: center; color: var(--color-text-muted); font-size: var(--fs-sm); padding: var(--spacing-6); border: 1px dashed var(--color-border); border-radius: var(--radius-md);">
                                No manufacturers logged yet. Click add below to log your first supplier details.
                            </div>
                        @endforelse
                    </div>

                    <button type="button" class="btn btn-secondary" style="font-size: var(--fs-xs); height: 36px; margin-top: var(--spacing-4);" onclick="addSupplierCard()">+ Add Supplier</button>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToStep(6)">Back</button>
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-7-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- STEP 8: SUPPLIER COMPARISON -->
        <div id="step-form-container-8" class="step-form-content {{ $currentStep == 8 ? 'active' : '' }}" style="display: {{ $currentStep == 8 ? 'block' : 'none' }};">
            <div style="margin-bottom: var(--spacing-6);">
                <span class="badge badge-primary">Step 08</span>
                <h2 style="font-size: var(--fs-xl); margin-top: var(--spacing-1);">Supplier Comparison</h2>
                <p style="color: var(--color-text-secondary); font-size: var(--fs-sm);">Rate registered suppliers (Price, Quality, MOQ, Lead Time, Reliability) out of 5 stars to calculate scores.</p>
            </div>

            <form action="{{ route('services.save_step', 'product-hunting') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="8">
                <input type="hidden" name="action" id="step-8-action" value="save_continue">

                @if(empty($supplierRecords))
                    <div style="text-align: center; padding: var(--spacing-8); border: 1px dashed var(--color-border); border-radius: var(--radius-md); margin-bottom: var(--spacing-6);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 48px; height: 48px; color: var(--color-text-muted); margin: 0 auto var(--spacing-3);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <h4 style="font-weight: var(--fw-bold);">No suppliers available for comparison</h4>
                        <p style="font-size: var(--fs-xs); color: var(--color-text-muted); margin-top: 2px;">Please return to Step 07 and register at least one supplier record.</p>
                        <button type="button" class="btn btn-primary" style="margin-top: var(--spacing-4);" onclick="jumpToStep(7)">Go to Step 7</button>
                    </div>
                @else
                    <!-- Repeatable Ratings for registered suppliers -->
                    <div style="display: flex; flex-direction: column; gap: var(--spacing-6); margin-bottom: var(--spacing-6);">
                        @foreach($supplierRecords as $sup)
                            @php
                                $sName = $sup['name'] ?? 'Unnamed Supplier';
                                $sKey = Str::slug($sName);
                                $storedRatings = $ratings[$sKey] ?? [];
                                $calRatings = $payload['cal_supplier_ratings'][$sKey] ?? [];
                            @endphp
                            <div class="card" style="padding: var(--spacing-5);">
                                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); padding-bottom: var(--spacing-2); margin-bottom: var(--spacing-4);">
                                    <h4 style="font-weight: var(--fw-bold); font-size: var(--fs-base);">{{ $sName }} <span style="font-weight: normal; color: var(--color-text-secondary); font-size: var(--fs-xs);">({{ $sup['country'] ?? '' }} • {{ $sup['type'] ?? '' }})</span></h4>
                                    <div style="font-size: var(--fs-sm); font-weight: var(--fw-bold); color: var(--color-primary);">
                                        Calculated Rating: {{ $calRatings['overall_score'] ?? '0.00' }}%
                                    </div>
                                </div>
                                <div class="form-grid-6">
                                    @foreach(['price' => 'Price', 'quality' => 'Quality', 'moq' => 'MOQ', 'lead_time' => 'Lead Time', 'communication' => 'Communication', 'reliability' => 'Reliability'] as $field => $label)
                                        <div class="form-group">
                                            <label class="form-label" style="font-size: 11px;">{{ $label }} (1-5)</label>
                                            <select name="ratings[{{ $sKey }}][{{ $field }}]" class="form-control rating-score-trigger" required style="height: 34px;">
                                                @for($i=1; $i<=5; $i++)
                                                    <option value="{{ $i }}" {{ ($storedRatings[$field] ?? 3) == $i ? 'selected' : '' }}>{{ $i }} Star</option>
                                                @endfor
                                            </select>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label for="preferred_supplier" class="form-label">Preferred Supplier <span style="color: var(--color-danger);">*</span></label>
                            <select name="preferred_supplier" id="preferred_supplier" class="form-control" required>
                                <option value="">Select preferred supplier...</option>
                                @foreach($supplierRecords as $sup)
                                    <option value="{{ $sup['name'] }}" {{ old('preferred_supplier', $payload['preferred_supplier'] ?? '') == $sup['name'] ? 'selected' : '' }}>{{ $sup['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="backup_supplier" class="form-label">Backup Supplier <span style="color: var(--color-danger);">*</span></label>
                            <select name="backup_supplier" id="backup_supplier" class="form-control" required>
                                <option value="">Select backup supplier...</option>
                                @foreach($supplierRecords as $sup)
                                    <option value="{{ $sup['name'] }}" {{ old('backup_supplier', $payload['backup_supplier'] ?? '') == $sup['name'] ? 'selected' : '' }}>{{ $sup['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="comparison_notes" class="form-label">Comparison Notes <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                        <textarea name="comparison_notes" id="comparison_notes" rows="3" class="form-control" style="height: auto;">{{ old('comparison_notes', $payload['comparison_notes'] ?? '') }}</textarea>
                    </div>
                @endif

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToStep(7)">Back</button>
                    <div>
                        @if(!empty($supplierRecords))
                            <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-8-action').value='save_draft'">Save Draft</button>
                            <button type="submit" class="btn btn-primary">Save & Continue</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- STEP 9: SAMPLE COORDINATION -->
        <div id="step-form-container-9" class="step-form-content {{ $currentStep == 9 ? 'active' : '' }}" style="display: {{ $currentStep == 9 ? 'block' : 'none' }};">
            <div style="margin-bottom: var(--spacing-6);">
                <span class="badge badge-primary">Step 09</span>
                <h2 style="font-size: var(--fs-xl); margin-top: var(--spacing-1);">Sample Coordination</h2>
                <p style="color: var(--color-text-secondary); font-size: var(--fs-sm);">Request, track, and pay for samples from chosen manufacturers. Log delivery schedules and carrier tracking details.</p>
            </div>

            <form action="{{ route('services.save_step', 'product-hunting') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="9">
                <input type="hidden" name="action" id="step-9-action" value="save_continue">

                <div class="form-group">
                    <label class="form-label">Sample Required? <span style="color: var(--color-danger);">*</span></label>
                    <div class="segmented-control" style="max-width: 200px;">
                        @php $sampReq = old('sample_required', $payload['sample_required'] ?? 'no'); @endphp
                        <input type="radio" name="sample_required" id="samp_yes" value="yes" class="segmented-option" {{ $sampReq == 'yes' ? 'checked' : '' }} onchange="toggleSampleFields('yes')">
                        <label for="samp_yes" class="segmented-label">Yes</label>

                        <input type="radio" name="sample_required" id="samp_no" value="no" class="segmented-option" {{ $sampReq == 'no' ? 'checked' : '' }} onchange="toggleSampleFields('no')">
                        <label for="samp_no" class="segmented-label">No</label>
                    </div>
                </div>

                <div id="sample-fields-container" style="display: {{ $sampReq == 'yes' ? 'block' : 'none' }};">
                    <div class="form-grid-3">
                        <div class="form-group">
                            <label for="sample_supplier" class="form-label">Supplier <span style="color: var(--color-danger);">*</span></label>
                            <select name="sample_supplier" id="sample_supplier" class="form-control">
                                <option value="">Choose supplier...</option>
                                @foreach($supplierRecords as $sup)
                                    <option value="{{ $sup['name'] }}" {{ old('sample_supplier', $payload['sample_supplier'] ?? '') == $sup['name'] ? 'selected' : '' }}>{{ $sup['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sample_qty" class="form-label">Sample Quantity <span style="color: var(--color-danger);">*</span></label>
                            <input type="number" name="sample_qty" id="sample_qty" class="form-control" value="{{ old('sample_qty', $payload['sample_qty'] ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="sample_cost" class="form-label">Sample Cost ($) <span style="color: var(--color-danger);">*</span></label>
                            <input type="number" step="0.01" name="sample_cost" id="sample_cost" class="form-control" value="{{ old('sample_cost', $payload['sample_cost'] ?? '') }}">
                        </div>
                    </div>

                    <div class="form-grid-3">
                        <div class="form-group">
                            <label for="sample_shipping_cost" class="form-label">Shipping Cost ($) <span style="color: var(--color-danger);">*</span></label>
                            <input type="number" step="0.01" name="sample_shipping_cost" id="sample_shipping_cost" class="form-control" value="{{ old('sample_shipping_cost', $payload['sample_shipping_cost'] ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="sample_request_date" class="form-label">Sample Request Date <span style="color: var(--color-danger);">*</span></label>
                            <input type="date" name="sample_request_date" id="sample_request_date" class="form-control" value="{{ old('sample_request_date', $payload['sample_request_date'] ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="sample_expected_date" class="form-label">Expected Delivery Date <span style="color: var(--color-danger);">*</span></label>
                            <input type="date" name="sample_expected_date" id="sample_expected_date" class="form-control" value="{{ old('sample_expected_date', $payload['sample_expected_date'] ?? '') }}">
                        </div>
                    </div>

                    <div class="form-grid-3">
                        <div class="form-group">
                            <label for="sample_status" class="form-label">Sample Status <span style="color: var(--color-danger);">*</span></label>
                            <select name="sample_status" id="sample_status" class="form-control">
                                @foreach(['Not Requested', 'Requested', 'In Production', 'Shipped', 'Received', 'Under Review', 'Approved', 'Rejected'] as $ss)
                                    <option value="{{ $ss }}" {{ old('sample_status', $payload['sample_status'] ?? '') == $ss ? 'selected' : '' }}>{{ $ss }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sample_tracking_number" class="form-label">Tracking Number <span style="font-size: var(--fs-xs); color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                            <input type="text" name="sample_tracking_number" id="sample_tracking_number" class="form-control" value="{{ old('sample_tracking_number', $payload['sample_tracking_number'] ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="sample_tracking_url" class="form-label">Tracking URL <span style="font-size: var(--fs-xs); color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                            <input type="url" name="sample_tracking_url" id="sample_tracking_url" class="form-control" value="{{ old('sample_tracking_url', $payload['sample_tracking_url'] ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="sample_notes" class="form-label">Sample Notes <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="sample_notes" id="sample_notes" rows="3" class="form-control" style="height: auto;">{{ old('sample_notes', $payload['sample_notes'] ?? '') }}</textarea>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToStep(8)">Back</button>
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-9-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- STEP 10: QUALITY CHECK -->
        <div id="step-form-container-10" class="step-form-content {{ $currentStep == 10 ? 'active' : '' }}" style="display: {{ $currentStep == 10 ? 'block' : 'none' }};">
            <div style="margin-bottom: var(--spacing-6);">
                <span class="badge badge-primary">Step 10</span>
                <h2 style="font-size: var(--fs-xl); margin-top: var(--spacing-1);">Quality Check</h2>
                <p style="color: var(--color-text-secondary); font-size: var(--fs-sm);">Inspect manufacturing outputs or prototype samples. Log defects, quality scores, and final decisions.</p>
            </div>

            <form action="{{ route('services.save_step', 'product-hunting') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="10">
                <input type="hidden" name="action" id="step-10-action" value="save_continue">

                <div class="form-grid-3">
                    <div class="form-group">
                        <label for="inspection_status" class="form-label">Inspection Status <span style="color: var(--color-danger);">*</span></label>
                        <select name="inspection_status" id="inspection_status" class="form-control" required>
                            @foreach(['Pending', 'In Progress', 'Passed', 'Failed', 'Needs Changes'] as $is)
                                <option value="{{ $is }}" {{ old('inspection_status', $payload['inspection_status'] ?? '') == $is ? 'selected' : '' }}>{{ $is }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="quality_score" class="form-label">Quality Score (0 - 100) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" min="0" max="100" name="quality_score" id="quality_score" class="form-control" value="{{ old('quality_score', $payload['quality_score'] ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="inspection_date" class="form-label">Inspection Date <span style="color: var(--color-danger);">*</span></label>
                        <input type="date" name="inspection_date" id="inspection_date" class="form-control" value="{{ old('inspection_date', $payload['inspection_date'] ?? '') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Quality Checklist <span style="color: var(--color-danger);">*</span></label>
                    <div class="selection-grid">
                        @foreach([
                            'Product Material', 'Product Size', 'Product Color',
                            'Product Functionality', 'Packaging', 'Labeling',
                            'Branding', 'Product Finish', 'Quantity'
                        ] as $qc)
                            <div class="selection-card {{ in_array($qc, $qualityChecklist) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'chk-qc-{{ Str::slug($qc) }}')">
                                <input type="checkbox" name="quality_checklist[]" id="chk-qc-{{ Str::slug($qc) }}" value="{{ $qc }}" class="selection-checkbox" {{ in_array($qc, $qualityChecklist) ? 'checked' : '' }}>
                                <div class="selection-card-details">
                                    <span class="selection-card-title" style="font-size: var(--fs-xs);">{{ $qc }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Defects Found? <span style="color: var(--color-danger);">*</span></label>
                    <div class="segmented-control" style="max-width: 200px;">
                        @php $defFound = old('defects_found', $payload['defects_found'] ?? 'no'); @endphp
                        <input type="radio" name="defects_found" id="def_yes" value="yes" class="segmented-option" {{ $defFound == 'yes' ? 'checked' : '' }} onchange="toggleDefectDetails('yes')">
                        <label for="def_yes" class="segmented-label">Yes</label>

                        <input type="radio" name="defects_found" id="def_no" value="no" class="segmented-option" {{ $defFound == 'no' ? 'checked' : '' }} onchange="toggleDefectDetails('no')">
                        <label for="def_no" class="segmented-label">No</label>
                    </div>
                </div>

                <div class="form-group" id="defect-details-container" style="display: {{ $defFound == 'yes' ? 'block' : 'none' }};">
                    <label for="defect_details" class="form-label">Defect Details <span style="color: var(--color-danger);">*</span></label>
                    <textarea name="defect_details" id="defect_details" rows="3" class="form-control" style="height: auto;">{{ old('defect_details', $payload['defect_details'] ?? '') }}</textarea>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="final_quality_decision" class="form-label">Final Quality Decision <span style="color: var(--color-danger);">*</span></label>
                        <select name="final_quality_decision" id="final_quality_decision" class="form-control" required>
                            @foreach(['Approved', 'Approved with Changes', 'Re-inspection Required', 'Rejected'] as $fqd)
                                <option value="{{ $fqd }}" {{ old('final_quality_decision', $payload['final_quality_decision'] ?? '') == $fqd ? 'selected' : '' }}>{{ $fqd }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inspector_notes" class="form-label">Inspector Notes <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="inspector_notes" id="inspector_notes" rows="3" class="form-control" style="height: auto;">{{ old('inspector_notes', $payload['inspector_notes'] ?? '') }}</textarea>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToStep(9)">Back</button>
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-10-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- STEP 11: PRICE & MOQ NEGOTIATION -->
        <div id="step-form-container-11" class="step-form-content {{ $currentStep == 11 ? 'active' : '' }}" style="display: {{ $currentStep == 11 ? 'block' : 'none' }};">
            <div style="margin-bottom: var(--spacing-6);">
                <span class="badge badge-primary">Step 11</span>
                <h2 style="font-size: var(--fs-xl); margin-top: var(--spacing-1);">Price & MOQ Negotiation</h2>
                <p style="color: var(--color-text-secondary); font-size: var(--fs-sm);">Negotiate with manufacturers, establishing target unit costs, MOQs, shipment, and payment schedules.</p>
            </div>

            <form action="{{ route('services.save_step', 'product-hunting') }}" method="POST" id="negotiation-form">
                @csrf
                <input type="hidden" name="step" value="11">
                <input type="hidden" name="action" id="step-11-action" value="save_continue">

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="neg_supplier" class="form-label">Supplier <span style="color: var(--color-danger);">*</span></label>
                        <select name="neg_supplier" id="neg_supplier" class="form-control" required>
                            <option value="">Choose supplier...</option>
                            @foreach($supplierRecords as $sup)
                                <option value="{{ $sup['name'] }}" {{ old('neg_supplier', $payload['neg_supplier'] ?? '') == $sup['name'] ? 'selected' : '' }}>{{ $sup['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="neg_status" class="form-label">Negotiation Status <span style="color: var(--color-danger);">*</span></label>
                        <select name="neg_status" id="neg_status" class="form-control" required>
                            @foreach(['Not Started', 'In Progress', 'Negotiating', 'Agreed', 'Failed'] as $ns)
                                <option value="{{ $ns }}" {{ old('neg_status', $payload['neg_status'] ?? '') == $ns ? 'selected' : '' }}>{{ $ns }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="neg_initial_price" class="form-label">Initial Unit Price ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="neg_initial_price" id="neg_initial_price" class="form-control neg-trigger" value="{{ old('neg_initial_price', $payload['neg_initial_price'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="neg_final_price" class="form-label">Negotiated Unit Price ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="neg_final_price" id="neg_final_price" class="form-control neg-trigger" value="{{ old('neg_final_price', $payload['neg_final_price'] ?? '') }}" required>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="neg_initial_moq" class="form-label">Initial MOQ <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" name="neg_initial_moq" id="neg_initial_moq" class="form-control neg-trigger" value="{{ old('neg_initial_moq', $payload['neg_initial_moq'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="neg_final_moq" class="form-label">Negotiated MOQ <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" name="neg_final_moq" id="neg_final_moq" class="form-control neg-trigger" value="{{ old('neg_final_moq', $payload['neg_final_moq'] ?? '') }}" required>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="neg_initial_lead_time" class="form-label">Initial Lead Time (days) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" name="neg_initial_lead_time" id="neg_initial_lead_time" class="form-control" value="{{ old('neg_initial_lead_time', $payload['neg_initial_lead_time'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="neg_final_lead_time" class="form-label">Negotiated Lead Time (days) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" name="neg_final_lead_time" id="neg_final_lead_time" class="form-control" value="{{ old('neg_final_lead_time', $payload['neg_final_lead_time'] ?? '') }}" required>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="payment_terms" class="form-label">Payment Terms <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" name="payment_terms" id="payment_terms" class="form-control" placeholder="e.g. 30% deposit, 70% before shipment" value="{{ old('payment_terms', $payload['payment_terms'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="shipping_terms" class="form-label">Shipping Terms <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" name="shipping_terms" id="shipping_terms" class="form-control" placeholder="e.g. FOB Shenzhen, DDP Chicago" value="{{ old('shipping_terms', $payload['shipping_terms'] ?? '') }}" required>
                    </div>
                </div>

                <!-- Comparison summary widget -->
                <div class="card" style="background-color: var(--color-bg-base); padding: var(--spacing-5); margin-bottom: var(--spacing-4); border-color: var(--color-border);">
                    <h3 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-3);">Negotiation Impact Summary</h3>
                    <div class="form-grid-2" style="text-align: center;">
                        <div style="padding: var(--spacing-2);">
                            <span style="font-size: 10px; text-transform: uppercase; color: var(--color-text-secondary);">Unit Cost Saving</span>
                            <div style="font-size: var(--fs-xl); font-weight: var(--fw-bold); color: var(--color-success);" id="neg-saving-display">
                                ${{ number_format(max(0, (float)(old('neg_initial_price', $payload['neg_initial_price'] ?? 0)) - (float)(old('neg_final_price', $payload['neg_final_price'] ?? 0))), 2) }}
                            </div>
                        </div>
                        <div style="padding: var(--spacing-2);">
                            <span style="font-size: 10px; text-transform: uppercase; color: var(--color-text-secondary);">MOQ Adjustment</span>
                            <div style="font-size: var(--fs-xl); font-weight: var(--fw-bold); color: var(--color-primary);" id="neg-moq-display">
                                {{ (int)(old('neg_initial_moq', $payload['neg_initial_moq'] ?? 0)) }} → {{ (int)(old('neg_final_moq', $payload['neg_final_moq'] ?? 0)) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="neg_notes" class="form-label">Negotiation Notes <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="neg_notes" id="neg_notes" rows="3" class="form-control" style="height: auto;">{{ old('neg_notes', $payload['neg_notes'] ?? '') }}</textarea>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToStep(10)">Back</button>
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-11-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- STEP 12: MANUFACTURING / SOURCING -->
        <div id="step-form-container-12" class="step-form-content {{ $currentStep == 12 ? 'active' : '' }}" style="display: {{ $currentStep == 12 ? 'block' : 'none' }};">
            <div style="margin-bottom: var(--spacing-6);">
                <span class="badge badge-primary">Step 12</span>
                <h2 style="font-size: var(--fs-xl); margin-top: var(--spacing-1);">Manufacturing / Sourcing</h2>
                <p style="color: var(--color-text-secondary); font-size: var(--fs-sm);">Trigger manufacturing operations. Track production queues, timelines, and branding requirements.</p>
            </div>

            <form action="{{ route('services.save_step', 'product-hunting') }}" method="POST" id="manufacturing-form">
                @csrf
                <input type="hidden" name="step" value="12">
                <input type="hidden" name="action" id="step-12-action" value="save_continue">

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="mfg_supplier" class="form-label">Selected Supplier <span style="color: var(--color-danger);">*</span></label>
                        <select name="mfg_supplier" id="mfg_supplier" class="form-control" required>
                            <option value="">Choose supplier...</option>
                            @foreach($supplierRecords as $sup)
                                <option value="{{ $sup['name'] }}" {{ old('mfg_supplier', $payload['mfg_supplier'] ?? '') == $sup['name'] ? 'selected' : '' }}>{{ $sup['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="mfg_product_type" class="form-label">Product Type <span style="color: var(--color-danger);">*</span></label>
                        <select name="mfg_product_type" id="mfg_product_type" class="form-control" required>
                            @foreach(['Standard Product', 'Private Label', 'White Label', 'Custom Product'] as $mpt)
                                <option value="{{ $mpt }}" {{ old('mfg_product_type', $payload['mfg_product_type'] ?? '') == $mpt ? 'selected' : '' }}>{{ $mpt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label for="mfg_quantity" class="form-label">Production Quantity <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" name="mfg_quantity" id="mfg_quantity" class="form-control mfg-trigger" value="{{ old('mfg_quantity', $payload['mfg_quantity'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="mfg_unit_cost" class="form-label">Final Unit Cost ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="mfg_unit_cost" id="mfg_unit_cost" class="form-control mfg-trigger" value="{{ old('mfg_unit_cost', $payload['mfg_unit_cost'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Production Cost</label>
                        <div class="form-control" style="background-color: var(--color-bg-base); font-weight: var(--fw-bold); border-color: var(--color-border); display: flex; align-items: center;" id="total-production-cost-display">
                            ${{ number_format($payload['cal_total_production_cost'] ?? 0, 2) }}
                        </div>
                    </div>
                </div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label for="mfg_start_date" class="form-label">Production Start Date <span style="color: var(--color-danger);">*</span></label>
                        <input type="date" name="mfg_start_date" id="mfg_start_date" class="form-control" value="{{ old('mfg_start_date', $payload['mfg_start_date'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="mfg_expected_date" class="form-label">Expected Completion Date <span style="color: var(--color-danger);">*</span></label>
                        <input type="date" name="mfg_expected_date" id="mfg_expected_date" class="form-control" value="{{ old('mfg_expected_date', $payload['mfg_expected_date'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="mfg_status" class="form-label">Production Status <span style="color: var(--color-danger);">*</span></label>
                        <select name="mfg_status" id="mfg_status" class="form-control" required>
                            @foreach(['Not Started', 'Confirmed', 'In Production', 'Quality Check', 'Completed', 'Delayed', 'Cancelled'] as $ms)
                                <option value="{{ $ms }}" {{ old('mfg_status', $payload['mfg_status'] ?? '') == $ms ? 'selected' : '' }}>{{ $ms }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="form-label">Packaging Required? <span style="color: var(--color-danger);">*</span></label>
                        <div class="segmented-control">
                            @php $pkgReq = old('mfg_packaging_required', $payload['mfg_packaging_required'] ?? 'no'); @endphp
                            <input type="radio" name="mfg_packaging_required" id="pkg_yes" value="yes" class="segmented-option" {{ $pkgReq == 'yes' ? 'checked' : '' }}>
                            <label for="pkg_yes" class="segmented-label">Yes</label>

                            <input type="radio" name="mfg_packaging_required" id="pkg_no" value="no" class="segmented-option" {{ $pkgReq == 'no' ? 'checked' : '' }}>
                            <label for="pkg_no" class="segmented-label">No</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Labeling Required? <span style="color: var(--color-danger);">*</span></label>
                        <div class="segmented-control">
                            @php $lblReq = old('mfg_labeling_required', $payload['mfg_labeling_required'] ?? 'no'); @endphp
                            <input type="radio" name="mfg_labeling_required" id="lbl_yes" value="yes" class="segmented-option" {{ $lblReq == 'yes' ? 'checked' : '' }}>
                            <label for="lbl_yes" class="segmented-label">Yes</label>

                            <input type="radio" name="mfg_labeling_required" id="lbl_no" value="no" class="segmented-option" {{ $lblReq == 'no' ? 'checked' : '' }}>
                            <label for="lbl_no" class="segmented-label">No</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Branding Required? <span style="color: var(--color-danger);">*</span></label>
                        <div class="segmented-control">
                            @php $brndReq = old('mfg_branding_required', $payload['mfg_branding_required'] ?? 'no'); @endphp
                            <input type="radio" name="mfg_branding_required" id="brnd_yes" value="yes" class="segmented-option" {{ $brndReq == 'yes' ? 'checked' : '' }}>
                            <label for="brnd_yes" class="segmented-label">Yes</label>

                            <input type="radio" name="mfg_branding_required" id="brnd_no" value="no" class="segmented-option" {{ $brndReq == 'no' ? 'checked' : '' }}>
                            <label for="brnd_no" class="segmented-label">No</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="mfg_notes" class="form-label">Manufacturing Notes <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="mfg_notes" id="mfg_notes" rows="3" class="form-control" style="height: auto;">{{ old('mfg_notes', $payload['mfg_notes'] ?? '') }}</textarea>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToStep(11)">Back</button>
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-12-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- STEP 13: FINAL PRODUCT APPROVAL -->
        <div id="step-form-container-13" class="step-form-content {{ $currentStep == 13 ? 'active' : '' }}" style="display: {{ $currentStep == 13 ? 'block' : 'none' }};">
            <div style="margin-bottom: var(--spacing-6);">
                <span class="badge badge-primary">Step 13</span>
                <h2 style="font-size: var(--fs-xl); margin-top: var(--spacing-1);">Final Product Approval</h2>
                <p style="color: var(--color-text-secondary); font-size: var(--fs-sm);">Summarize product variables and choose final approval status to complete Sourcing process.</p>
            </div>

            <!-- Pricing / Sourcing Summary Card -->
            <div class="card" style="background-color: var(--color-bg-base); padding: var(--spacing-5); margin-bottom: var(--spacing-6); border-color: var(--color-border);">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4);">Sourcing Summary Checklist</h3>
                <div class="form-grid-2">
                    <table style="width: 100%; border-collapse: collapse; font-size: var(--fs-sm);">
                        <tr style="border-bottom: 1px solid var(--color-border);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Product Name:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-bold);">{{ $payload['product_idea'] ?? 'N/A' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--color-border);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Target Platform:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-bold);">{{ $payload['product_category'] ?? 'N/A' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--color-border);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Selected Supplier:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-bold);">{{ $payload['mfg_supplier'] ?? 'N/A' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--color-border);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Final Unit Cost:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-bold); color: var(--color-primary);">${{ number_format($payload['mfg_unit_cost'] ?? 0, 2) }}</td>
                        </tr>
                    </table>
                    <table style="width: 100%; border-collapse: collapse; font-size: var(--fs-sm);">
                        <tr style="border-bottom: 1px solid var(--color-border);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Target Selling Price:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-bold);">${{ number_format($payload['est_selling_price'] ?? 0, 2) }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--color-border);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Expected Unit Profit:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-bold); color: var(--color-success);">${{ number_format($payload['cal_expected_profit'] ?? 0, 2) }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--color-border);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Estimated Margin:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-bold); color: var(--color-success);">{{ number_format($payload['cal_profit_margin'] ?? 0, 2) }}%</td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--color-border);">
                            <td style="padding: var(--spacing-2) 0; color: var(--color-text-secondary);">Validation Score:</td>
                            <td style="padding: var(--spacing-2) 0; font-weight: var(--fw-bold);">{{ $payload['cal_overall_score'] ?? '0.00' }}%</td>
                        </tr>
                    </table>
                </div>
            </div>

            <form action="{{ route('services.save_step', 'product-hunting') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="13">
                <input type="hidden" name="action" value="save_continue">

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="final_approval_status" class="form-label">Final Approval Status <span style="color: var(--color-danger);">*</span></label>
                        <select name="final_approval_status" id="final_approval_status" class="form-control" required>
                            @foreach(['Pending', 'Approved', 'Approved with Changes', 'Rejected'] as $fas)
                                <option value="{{ $fas }}" {{ old('final_approval_status', $payload['final_approval_status'] ?? '') == $fas ? 'selected' : '' }}>{{ $fas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="final_decision" class="form-label">Final Decision <span style="color: var(--color-danger);">*</span></label>
                        <select name="final_decision" id="final_decision" class="form-control" required>
                            @foreach(['Approve Product', 'Request Changes', 'Reject Product', 'Send Back for Research'] as $fd)
                                <option value="{{ $fd }}" {{ old('final_decision', $payload['final_decision'] ?? '') == $fd ? 'selected' : '' }}>{{ $fd }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="final_notes" class="form-label">Final Notes <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="final_notes" id="final_notes" rows="3" class="form-control" style="height: auto;">{{ old('final_notes', $payload['final_notes'] ?? '') }}</textarea>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToStep(12)">Back</button>
                    <div>
                        @if($status !== 'completed')
                            <button type="submit" class="btn btn-primary">Complete Product Sourcing</button>
                        @else
                            <a href="{{ route('services.index') }}" class="btn btn-secondary">Return to Overview</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- ================== TAB 2: OVERVIEW DASHBOARD ================== -->
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
                        @foreach($stepTitles as $stepNum => $stepTitle)
                            <tr style="border-bottom: 1px solid var(--color-border-light);">
                                <td style="padding: var(--spacing-3) var(--spacing-2); font-weight: var(--fw-medium); font-size: var(--fs-sm);">Step {{ $stepNum }}: {{ $stepTitle }}</td>
                                <td style="padding: var(--spacing-3) var(--spacing-2);"><span class="badge badge-success">Completed</span></td>
                                <td style="padding: var(--spacing-3) var(--spacing-2); text-align: right; white-space: nowrap;">
                                    <button type="button" class="btn btn-secondary" style="font-size: 11px; padding: 4px 8px; height: auto; margin-right: 4px;" onclick="openViewModal()">View</button>
                                    <button type="button" class="btn btn-primary" style="font-size: 11px; padding: 4px 8px; height: auto;" onclick="startEditMode({{ $stepNum }})">Edit</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        @php
            $completedStepsCount = 0;
            $totalStepsCount = 13;
            
            $checklist = [
                'Product Requirements' => !empty($payload['product_category']),
                'Market Research' => !empty($payload['customer_segment']),
                'Demand Analysis' => !empty($payload['demand_level']),
                'Competitor Research' => !empty($payload['competitor_records']),
                'Pricing & Profit' => !empty($payload['est_selling_price']),
                'Product Validation' => !empty($payload['validation_status']),
                'Supplier Research' => !empty($payload['supplier_records']),
                'Supplier Comparison' => !empty($payload['ratings']),
                'Sample Coordination' => !empty($payload['sample_status']),
                'Quality Check' => !empty($payload['inspection_status']),
                'Price & MOQ Negotiation' => !empty($payload['neg_status']),
                'Manufacturing / Sourcing' => !empty($payload['mfg_status']),
                'Final Product Approval' => ($status === 'completed'),
            ];

            foreach($checklist as $stepTitle => $isDone) {
                if ($isDone) $completedStepsCount++;
            }

            $percentage = round(($completedStepsCount / $totalStepsCount) * 100);
        @endphp

        <div class="progress-banner">
            <div class="progress-banner-header">
                <div>
                    <h3 style="font-size: var(--fs-lg); font-weight: var(--fw-bold); margin-bottom: 2px;">Product Sourcing Progress</h3>
                    <p style="font-size: var(--fs-sm); color: var(--color-text-secondary);">Overall research and validation checklist</p>
                </div>
                <div class="progress-percentage">{{ $percentage }}%</div>
            </div>
            <div class="progress-bar-outer">
                <div class="progress-bar-inner" style="width: {{ $percentage }}%;"></div>
            </div>
        </div>

        <!-- Calculations Grid -->
        <div class="form-grid-4" style="margin-bottom: var(--spacing-6);">
            <div class="card" style="padding: var(--spacing-4); text-align: center;">
                <span style="font-size: var(--fs-xs); color: var(--color-text-secondary); text-transform: uppercase;">Current Step</span>
                <h4 style="font-size: var(--fs-xl); font-weight: var(--fw-bold); margin-top: 2px; color: var(--color-primary);">Step {{ $currentStep }}</h4>
            </div>
            <div class="card" style="padding: var(--spacing-4); text-align: center;">
                <span style="font-size: var(--fs-xs); color: var(--color-text-secondary); text-transform: uppercase;">Completed Steps</span>
                <h4 style="font-size: var(--fs-xl); font-weight: var(--fw-bold); margin-top: 2px; color: var(--color-success);">{{ $completedStepsCount }} / 13</h4>
            </div>
            <div class="card" style="padding: var(--spacing-4); text-align: center;">
                <span style="font-size: var(--fs-xs); color: var(--color-text-secondary); text-transform: uppercase;">Pending Steps</span>
                <h4 style="font-size: var(--fs-xl); font-weight: var(--fw-bold); margin-top: 2px; color: var(--color-text-muted);">{{ 13 - $completedStepsCount }}</h4>
            </div>
            <div class="card" style="padding: var(--spacing-4); text-align: center;">
                <span style="font-size: var(--fs-xs); color: var(--color-text-secondary); text-transform: uppercase;">Validation Score</span>
                <h4 style="font-size: var(--fs-xl); font-weight: var(--fw-bold); margin-top: 2px; color: var(--color-primary);">{{ $payload['cal_overall_score'] ?? '0.00' }}%</h4>
            </div>
        </div>

        <!-- Step Progress Checklist -->
        <div class="card" style="padding: var(--spacing-6);">
            <h3 style="font-size: var(--fs-base); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4);">Sourcing Steps Checklist</h3>
            <div style="display: flex; flex-direction: column; gap: var(--spacing-3);">
                @php $stepNum = 1; @endphp
                @foreach($stepTitles as $stepKey => $stepTitle)
                    @php
                        $isDone = $checklist[$stepTitle] ?? false;
                        $itemClass = 'not-started';
                        if ($isDone) {
                            $itemClass = 'completed';
                        } elseif ($currentStep == $stepKey) {
                            $itemClass = 'in-progress';
                        }
                    @endphp
                    <div class="checklist-item {{ $itemClass }}" onclick="switchMainTab('wizard'); jumpToStep({{ $stepKey }})">
                        <div class="checklist-left">
                            <div class="checklist-marker">
                                @if($itemClass == 'completed')
                                    ✓
                                @elseif($itemClass == 'in-progress')
                                    ●
                                @else
                                    ○
                                @endif
                            </div>
                            <span class="checklist-name">Step {{ $stepNum }}: {{ $stepTitle }}</span>
                        </div>
                        <div>
                            @if($itemClass == 'completed')
                                <span class="badge badge-success">Completed</span>
                            @elseif($itemClass == 'in-progress')
                                <span class="badge badge-primary" style="background-color: var(--color-primary-light); color: var(--color-primary);">Active</span>
                            @else
                                <span class="badge" style="background-color: var(--color-bg-base); color: var(--color-text-muted); border: 1px solid var(--color-border)">Not Started</span>
                            @endif
                        </div>
                    </div>
                    @php $stepNum++; @endphp
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- VIEW DETAILS MODAL -->
<div id="view-details-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-content" style="background: var(--color-bg-base); width: 95%; max-width: 850px; max-height: 90vh; border-radius: var(--radius-xl); padding: 0; overflow: hidden; position: relative; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); display: flex; flex-direction: column;">
        
        <!-- Modal Header -->
        <div style="padding: var(--spacing-5) var(--spacing-6); border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; background: var(--color-bg-alt);">
            <div>
                <h2 style="font-size: var(--fs-lg); font-weight: var(--fw-bold); color: var(--color-text-primary); margin: 0;">Product Sourcing Summary</h2>
                <p style="font-size: var(--fs-sm); color: var(--color-text-secondary); margin: 4px 0 0 0;">Review all submitted details for this service.</p>
            </div>
            <button type="button" onclick="document.getElementById('view-details-modal').style.display='none'" style="background: var(--color-bg-base); border: 1px solid var(--color-border); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: var(--fs-lg); cursor: pointer; color: var(--color-text-secondary); transition: all 0.2s ease;">&times;</button>
        </div>

        <!-- Modal Body -->
        <div style="padding: var(--spacing-6); overflow-y: auto; background: var(--color-bg-base); display: flex; flex-direction: column; gap: var(--spacing-5);">
            
            <!-- Step 1: Requirements -->
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">1</div>
                    Product Requirements
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Product Category</div><div style="font-weight: var(--fw-medium);">{{ $payload['product_category'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Product Idea</div><div style="font-weight: var(--fw-medium);">{{ $payload['product_idea'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Est. Unit Cost</div><div style="font-weight: var(--fw-medium);">{{ $payload['est_unit_cost'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Est. Selling Price</div><div style="font-weight: var(--fw-medium);">{{ $payload['est_selling_price'] ?? 'N/A' }}</div></div>
                </div>
            </div>

            <!-- Validation Score -->
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">6</div>
                    Product Validation Score
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Overall Score</div><div style="font-weight: var(--fw-medium); font-size: var(--fs-lg); color: var(--color-primary);">{{ $payload['cal_overall_score'] ?? '0.00' }}%</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Validation Status</div><div><span class="badge {{ ($payload['validation_status'] ?? '') == 'Approved' ? 'badge-success' : 'badge-secondary' }}">{{ $payload['validation_status'] ?? 'N/A' }}</span></div></div>
                </div>
            </div>

            <!-- Step 12: Manufacturing -->
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">12</div>
                    Manufacturing / Sourcing
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Sourcing Agent</div><div style="font-weight: var(--fw-medium);">{{ $payload['mfg_agent'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Est. Time (Days)</div><div style="font-weight: var(--fw-medium);">{{ $payload['mfg_est_time'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Final Supplier</div><div style="font-weight: var(--fw-medium);">{{ $payload['mfg_supplier_name'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Status</div><div><span class="badge badge-primary">{{ $payload['mfg_status'] ?? 'N/A' }}</span></div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Total Est Cost</div><div style="font-weight: var(--fw-medium);">{{ $payload['cal_total_sourcing_cost'] ?? '0' }}</div></div>
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

    const stepNames = {
        1: 'Product Requirements',
        2: 'Market Research',
        3: 'Demand Analysis',
        4: 'Competitor Research',
        5: 'Pricing & Profit',
        6: 'Product Validation',
        7: 'Supplier Research',
        8: 'Supplier Comparison',
        9: 'Sample Coordination',
        10: 'Quality Check',
        11: 'Price & MOQ Negotiation',
        12: 'Manufacturing',
        13: 'Final Approval'
    };

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
        for (let i = 1; i <= 13; i++) {
            // Horizontal stepper items
            const stepperItems = document.querySelectorAll('.stepper .step-item');
            if (stepperItems.length >= i) {
                const item = stepperItems[i-1];
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

    // Initialize Custom Multiselects
    document.addEventListener('DOMContentLoaded', function() {
        initCustomMultiselect('target_market_container');
        initCustomMultiselect('sourcing_type_container');
        initCustomMultiselect('research_market_container');
        initCustomMultiselect('research_types_container');

        // Step 5 Profit Calculations
        const calcInputs = document.querySelectorAll('.calc-trigger');
        calcInputs.forEach(input => {
            input.addEventListener('input', calculateProfitability);
        });

        // Step 6 Validation Scores
        const valInputs = document.querySelectorAll('.val-score-trigger');
        valInputs.forEach(input => {
            input.addEventListener('input', calculateOverallValidationScore);
        });

        // Step 11 Negotiation Cost Differences
        const negInputs = document.querySelectorAll('.neg-trigger');
        negInputs.forEach(input => {
            input.addEventListener('input', calculateNegotiationSavings);
        });

        // Step 12 Sourcing Cost
        const mfgInputs = document.querySelectorAll('.mfg-trigger');
        mfgInputs.forEach(input => {
            input.addEventListener('input', calculateManufacturingCosts);
        });
    });

    // Tab switcher
    function switchMainTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

        document.getElementById('tab-content-' + tabName).classList.add('active');
        document.getElementById('tab-btn-' + tabName).classList.add('active');
    }

    // Step navigation jump
    function jumpToStep(stepNumber) {
        if (isEditMode && stepNumber !== editModeStep) return;

        for (let i = 1; i <= 13; i++) {
            const form = document.getElementById('step-form-container-' + i);
            if (form) {
                form.style.display = (i === stepNumber) ? 'block' : 'none';
            }
        }
        
        // Update active class in horizontal stepper
        const stepperItems = document.querySelectorAll('.stepper .step-item');
        stepperItems.forEach((item, idx) => {
            if (idx + 1 === stepNumber) {
                item.classList.add('in-progress');
                item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            } else {
                item.classList.remove('in-progress');
            }
        });

        // Update mobile step indicator
        const mobileIndicator = document.getElementById('mobile-step-name');
        if (mobileIndicator && stepNames[stepNumber]) {
            mobileIndicator.innerText = stepNames[stepNumber];
        }
    }

    // Step 1 Customization toggle
    function toggleCustomizationDetails(req) {
        const container = document.getElementById('customization-details-container');
        const input = document.getElementById('customization_details');
        if (container) {
            container.style.display = (req === 'yes') ? 'block' : 'none';
            if (input) input.required = (req === 'yes');
        }
    }

    // Step 2 Add/Remove Competitor URL Row
    function addCompetitorUrlRow() {
        const container = document.getElementById('competitor-urls-list');
        const row = document.createElement('div');
        row.style.display = 'flex';
        row.style.gap = 'var(--spacing-2)';
        row.innerHTML = `
            <input type="url" name="competitor_urls[]" class="form-control" placeholder="https://amazon.com/dp/..." required>
            <button type="button" class="btn btn-secondary" style="color: var(--color-danger); border-color: rgba(239, 68, 68, 0.2);" onclick="removeCompetitorUrlRow(this)">Remove</button>
        `;
        container.appendChild(row);
    }

    function removeCompetitorUrlRow(btn) {
        const rows = document.querySelectorAll('#competitor-urls-list > div');
        if (rows.length > 1) {
            btn.parentElement.remove();
        } else {
            alert('At least one competitor URL listing is required.');
        }
    }

    // Step 3 Seasonality peak selection toggle
    function togglePeakSeason(val) {
        const container = document.getElementById('peak-season-container');
        const input = document.getElementById('peak_season');
        if (container) {
            container.style.display = (val === 'yes') ? 'block' : 'none';
            if (input) input.required = (val === 'yes');
        }
    }

    // Step 4 Competitors dynamic card append
    function addCompetitorCard() {
        const container = document.getElementById('competitor-cards-container');
        const cards = container.querySelectorAll('.competitor-entry-card');
        const nextIndex = cards.length;

        const card = document.createElement('div');
        card.className = 'card competitor-entry-card';
        card.style.padding = 'var(--spacing-4)';
        card.style.borderLeft = '4px solid var(--color-primary)';
        card.setAttribute('data-index', nextIndex);
        card.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); padding-bottom: var(--spacing-2); margin-bottom: var(--spacing-3);">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold);">Competitor #${nextIndex + 1}</h4>
                <button type="button" class="btn btn-secondary" style="color: var(--color-danger); height: 26px; padding: 0 var(--spacing-2); font-size: 11px;" onclick="removeCompetitorCard(this)">Delete</button>
            </div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Competitor Name *</label>
                    <input type="text" name="competitor_records[${nextIndex}][name]" class="form-control" required style="height: 34px;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Product URL *</label>
                    <input type="url" name="competitor_records[${nextIndex}][url]" class="form-control" required style="height: 34px;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Price ($) *</label>
                    <input type="number" step="0.01" name="competitor_records[${nextIndex}][price]" class="form-control" required style="height: 34px;">
                </div>
            </div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Rating (0-5) *</label>
                    <input type="number" step="0.1" min="0" max="5" name="competitor_records[${nextIndex}][rating]" class="form-control" required style="height: 34px;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Reviews Count *</label>
                    <input type="number" name="competitor_records[${nextIndex}][reviews]" class="form-control" required style="height: 34px;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Est. Monthly Sales *</label>
                    <input type="number" name="competitor_records[${nextIndex}][est_sales]" class="form-control" required style="height: 34px;">
                </div>
            </div>
        `;
        container.appendChild(card);
    }

    function removeCompetitorCard(btn) {
        const container = document.getElementById('competitor-cards-container');
        const card = btn.closest('.competitor-entry-card');
        if (card) {
            card.remove();
        }
        const cards = container.querySelectorAll('.competitor-entry-card');
        cards.forEach((c, index) => {
            c.querySelector('h4').innerText = `Competitor #${index + 1}`;
            c.setAttribute('data-index', index);
            c.querySelectorAll('input, select, textarea').forEach(input => {
                const nameAttr = input.getAttribute('name');
                if (nameAttr) {
                    const updatedName = nameAttr.replace(/\[\d+\]/, `[${index}]`);
                    input.setAttribute('name', updatedName);
                }
            });
        });
    }

    // Step 5 Dynamic Sizing Calculator
    function calculateProfitability() {
        const prodCost = parseFloat(document.getElementById('est_product_cost').value) || 0;
        const mfgCost = parseFloat(document.getElementById('est_manufacturing_cost').value) || 0;
        const pkgCost = parseFloat(document.getElementById('est_packaging_cost').value) || 0;
        const shipCost = parseFloat(document.getElementById('est_shipping_cost').value) || 0;
        const dutiesCost = parseFloat(document.getElementById('est_import_duties').value) || 0;
        const feesCost = parseFloat(document.getElementById('est_marketplace_fees').value) || 0;
        const adCost = parseFloat(document.getElementById('est_advertising_cost').value) || 0;
        const otherCost = parseFloat(document.getElementById('est_other_costs').value) || 0;
        const sellingPrice = parseFloat(document.getElementById('est_selling_price').value) || 0;

        const totalCost = prodCost + mfgCost + pkgCost + shipCost + dutiesCost + feesCost + adCost + otherCost;
        const profit = sellingPrice - totalCost;
        const margin = sellingPrice > 0 ? (profit / sellingPrice) * 100 : 0;
        const roi = totalCost > 0 ? (profit / totalCost) * 100 : 0;

        document.getElementById('cal-cost-display').innerText = '$' + totalCost.toFixed(2);
        document.getElementById('cal-price-display').innerText = '$' + sellingPrice.toFixed(2);
        document.getElementById('cal-profit-display').innerText = '$' + profit.toFixed(2);
        document.getElementById('cal-margin-display').innerText = margin.toFixed(2) + '%';
        document.getElementById('cal-roi-display').innerText = roi.toFixed(2) + '%';

        // Toggle text color for negative profit/margins
        const profitEl = document.getElementById('cal-profit-display');
        const marginEl = document.getElementById('cal-margin-display');
        if (profit < 0) {
            profitEl.style.color = 'var(--color-danger)';
            marginEl.style.color = 'var(--color-danger)';
        } else {
            profitEl.style.color = 'var(--color-success)';
            marginEl.style.color = 'var(--color-success)';
        }
    }

    // Step 6 Calculate overall score
    function calculateOverallValidationScore() {
        const demand = parseFloat(document.getElementById('val_demand_score').value) || 0;
        const comp = parseFloat(document.getElementById('val_competition_score').value) || 0;
        const profit = parseFloat(document.getElementById('val_profitability_score').value) || 0;
        const overall = (demand + comp + profit) / 3;
        document.getElementById('overall-score-display').innerText = overall.toFixed(2) + ' / 100';
    }

    // Step 7 Supplier research dynamic append
    function addSupplierCard() {
        const container = document.getElementById('suppliers-card-container');
        const cards = container.querySelectorAll('.supplier-entry-card');
        const nextIndex = cards.length;

        // Remove fallback if exists
        const fallback = container.querySelector('.fallback-row');
        if (fallback) fallback.remove();

        const card = document.createElement('div');
        card.className = 'card supplier-entry-card';
        card.style.padding = 'var(--spacing-5)';
        card.style.borderLeft = '4px solid var(--color-primary)';
        card.setAttribute('data-index', nextIndex);
        card.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); padding-bottom: var(--spacing-2); margin-bottom: var(--spacing-4);">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); color: var(--color-primary);">Supplier #${nextIndex + 1}</h4>
                <button type="button" class="btn btn-secondary" style="color: var(--color-danger); height: 28px; padding: 0 var(--spacing-2);" onclick="removeSupplierCard(this)">Delete Supplier</button>
            </div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Supplier Name *</label>
                    <input type="text" name="supplier_records[${nextIndex}][name]" class="form-control supplier-name-input" required style="height: 34px;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Supplier Type *</label>
                    <select name="supplier_records[${nextIndex}][type]" class="form-control" required style="height: 34px;">
                        <option value="Manufacturer">Manufacturer</option>
                        <option value="Wholesaler">Wholesaler</option>
                        <option value="Supplier">Supplier</option>
                        <option value="Private Label">Private Label</option>
                        <option value="White Label">White Label</option>
                        <option value="Factory">Factory</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Country *</label>
                    <input type="text" name="supplier_records[${nextIndex}][country]" class="form-control" required style="height: 34px;">
                </div>
            </div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Website *</label>
                    <input type="url" name="supplier_records[${nextIndex}][website]" class="form-control" required style="height: 34px;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Contact Person *</label>
                    <input type="text" name="supplier_records[${nextIndex}][contact_person]" class="form-control" required style="height: 34px;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Email *</label>
                    <input type="email" name="supplier_records[${nextIndex}][email]" class="form-control" required style="height: 34px;">
                </div>
            </div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Phone *</label>
                    <input type="text" name="supplier_records[${nextIndex}][phone]" class="form-control" required style="height: 34px;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Product URL *</label>
                    <input type="url" name="supplier_records[${nextIndex}][product_url]" class="form-control" required style="height: 34px;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">MOQ *</label>
                    <input type="number" name="supplier_records[${nextIndex}][moq]" class="form-control" required style="height: 34px;">
                </div>
            </div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Unit Price ($) *</label>
                    <input type="number" step="0.01" name="supplier_records[${nextIndex}][unit_price]" class="form-control" required style="height: 34px;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Lead Time (days) *</label>
                    <input type="text" name="supplier_records[${nextIndex}][lead_time]" class="form-control" required style="height: 34px;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Customization Available? *</label>
                    <select name="supplier_records[${nextIndex}][customization]" class="form-control" required style="height: 34px;">
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Private Label Available? *</label>
                    <select name="supplier_records[${nextIndex}][private_label]" class="form-control" required style="height: 34px;">
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">White Label Available? *</label>
                    <select name="supplier_records[${nextIndex}][white_label]" class="form-control" required style="height: 34px;">
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 11px;">Certifications *</label>
                    <input type="text" name="supplier_records[${nextIndex}][certifications][]" class="form-control" placeholder="CE, FDA, ISO" required style="height: 34px;">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" style="font-size: 11px;">Supplier Notes</label>
                <textarea name="supplier_records[${nextIndex}][notes]" rows="2" class="form-control" style="height: auto;"></textarea>
            </div>
        `;
        container.appendChild(card);
    }

    function removeSupplierCard(btn) {
        const container = document.getElementById('suppliers-card-container');
        btn.closest('.supplier-entry-card').remove();

        const cards = container.querySelectorAll('.supplier-entry-card');
        if (cards.length === 0) {
            const fallback = document.createElement('div');
            fallback.className = 'fallback-row';
            fallback.style.textAlign = 'center';
            fallback.style.color = 'var(--color-text-muted)';
            fallback.style.fontSize = 'var(--fs-sm)';
            fallback.style.padding = 'var(--spacing-6)';
            fallback.style.border = '1px dashed var(--color-border)';
            fallback.style.borderRadius = 'var(--radius-md)';
            fallback.innerText = 'No manufacturers logged yet. Click add below to log your first supplier details.';
            container.appendChild(fallback);
        } else {
            cards.forEach((card, index) => {
                card.querySelector('h4').innerText = `Supplier #${index + 1}`;
                card.setAttribute('data-index', index);
                
                card.querySelectorAll('input, select, textarea').forEach(input => {
                    const nameAttr = input.getAttribute('name');
                    if (nameAttr) {
                        const updatedName = nameAttr.replace(/\[\d+\]/, `[${index}]`);
                        input.setAttribute('name', updatedName);
                    }
                });
            });
        }
    }

    // Step 9 Sample toggle
    function toggleSampleFields(val) {
        const container = document.getElementById('sample-fields-container');
        const inputs = container.querySelectorAll('input, select');
        if (container) {
            container.style.display = (val === 'yes') ? 'block' : 'none';
            inputs.forEach(input => {
                if (input.id !== 'sample_tracking_number' && input.id !== 'sample_tracking_url') {
                    input.required = (val === 'yes');
                }
            });
        }
    }

    // Step 10 Defect toggle
    function toggleDefectDetails(val) {
        const container = document.getElementById('defect-details-container');
        const input = document.getElementById('defect_details');
        if (container) {
            container.style.display = (val === 'yes') ? 'block' : 'none';
            if (input) input.required = (val === 'yes');
        }
    }

    // Step 11 Negotiation Calculations
    function calculateNegotiationSavings() {
        const initPrice = parseFloat(document.getElementById('neg_initial_price').value) || 0;
        const finalPrice = parseFloat(document.getElementById('neg_final_price').value) || 0;
        const initMoq = parseInt(document.getElementById('neg_initial_moq').value) || 0;
        const finalMoq = parseInt(document.getElementById('neg_final_moq').value) || 0;

        const saving = Math.max(0, initPrice - finalPrice);
        document.getElementById('neg-saving-display').innerText = '$' + saving.toFixed(2);
        document.getElementById('neg-moq-display').innerText = initMoq + ' → ' + finalMoq;
    }

    // Step 12 Manufacturing Total Cost
    function calculateManufacturingCosts() {
        const qty = parseInt(document.getElementById('mfg_quantity').value) || 0;
        const unitCost = parseFloat(document.getElementById('mfg_unit_cost').value) || 0;
        const total = qty * unitCost;
        document.getElementById('total-production-cost-display').innerText = '$' + total.toFixed(2);
    }

    // Scroll Stepper smoothly
    function scrollStepper(amount) {
        const container = document.getElementById('stepper-scroll-container');
        if (container) {
            container.scrollBy({
                left: amount,
                behavior: 'smooth'
            });
        }
    }
</script>
@endsection
