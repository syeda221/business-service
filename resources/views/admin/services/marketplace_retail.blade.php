@extends('layouts.dashboard')

@section('title', 'Marketplace & Retail Services')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
    <style>
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--spacing-4);
            margin-bottom: var(--spacing-4);
        }
        .form-row-full {
            grid-column: span 2;
        }
        .selection-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: var(--spacing-4);
            margin-bottom: var(--spacing-6);
        }
        .selection-card {
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            padding: var(--spacing-4);
            cursor: pointer;
            transition: all var(--transition-fast);
            background-color: #ffffff;
            position: relative;
        }
        .selection-card:hover {
            border-color: var(--color-primary);
            box-shadow: var(--shadow-md);
        }
        .selection-card.selected {
            border-color: var(--color-primary);
            background-color: var(--color-primary-light);
        }
        .selection-checkbox {
            position: absolute;
            top: 12px;
            right: 12px;
            accent-color: var(--color-primary);
        }
        .selection-card-title {
            font-weight: var(--fw-bold);
            color: var(--color-text-primary);
            display: block;
            margin-bottom: 2px;
            font-size: var(--fs-sm);
        }
        .selection-card-desc {
            font-size: 11px;
            color: var(--color-text-secondary);
            line-height: 1.3;
        }
        .section-separator {
            height: 1px;
            background-color: var(--color-border);
            margin: var(--spacing-6) 0;
        }
        .stats-panel-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: var(--spacing-4);
            margin-bottom: var(--spacing-6);
        }
        .stat-card-mini {
            background-color: #ffffff;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            padding: var(--spacing-4);
            display: flex;
            flex-direction: column;
        }
        .stat-card-title {
            font-size: 10px;
            color: var(--color-text-secondary);
            font-weight: var(--fw-semibold);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .stat-card-value {
            font-size: var(--fs-lg);
            font-weight: var(--fw-bold);
            color: var(--color-text-primary);
            margin-top: 4px;
        }
        .inline-form-card {
            background-color: var(--color-bg-base);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            padding: var(--spacing-6);
            margin-bottom: var(--spacing-6);
        }
    </style>
@endsection

@section('content')
<!-- Breadcrumbs -->
<nav class="breadcrumbs">
    <a href="{{ route('admin.dashboard') }}">Console</a>
    <a href="{{ route('services.index') }}">Services</a>
    <span>Marketplace & Retail Services</span>
</nav>

<!-- Page Header -->
<div class="service-header">
    <div class="service-header-left">
        <h1 class="page-title" style="margin-bottom: var(--spacing-1);">Marketplace & Retail Services</h1>
        <p class="page-subtitle">Manage marketplace setup, product listings, optimization, inventory, orders and physical retail preparation.</p>
    </div>
    <div class="service-actions">
        <a href="{{ route('services.index') }}" class="btn btn-secondary">Back to Overview</a>
    </div>
</div>

<!-- Success / Error Messages -->
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul style="padding-left: var(--spacing-4); margin: 0;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Completed Banner -->
@if($status === 'completed')
    <div class="card" style="border-color: var(--color-success); background-color: var(--color-success-light); margin-bottom: var(--spacing-6); text-align: center; padding: var(--spacing-6);">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 48px; height: 48px; color: var(--color-success); margin: 0 auto var(--spacing-3);">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0110 21a3.745 3.745 0 01-3.068-1.593 3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0114 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
        </svg>
        <h2 style="color: var(--color-success-dark); font-size: var(--fs-xl); font-weight: var(--fw-bold);">Marketplace & Retail Services Completed</h2>
        <p style="color: var(--color-success-dark); font-size: var(--fs-sm); margin-top: 2px;">Your marketplace setup, catalog products, and retail coordinations are fully active.</p>
    </div>
@endif

@php
    $selectedMarketplaces = old('selected_marketplaces', $payload['selected_marketplaces'] ?? []);
    $sellingModels = old('selling_models', $payload['selling_models'] ?? []);
    $targetCountries = old('target_countries', $payload['target_countries'] ?? []);
    $goals = old('goals', $payload['goals'] ?? []);
    $accounts = old('accounts', $payload['accounts'] ?? []);
    $products = old('products', $payload['products'] ?? []);
    $listings = old('listings', $payload['listings'] ?? []);
    $optimizations = old('optimizations', $payload['optimizations'] ?? []);
    $pricings = old('pricings', $payload['pricings'] ?? []);
    $inventories = old('inventories', $payload['inventories'] ?? []);
    $orders = old('orders', $payload['orders'] ?? []);
    $campaigns = old('campaigns', $payload['campaigns'] ?? []);
    $retailers = old('retailers', $payload['retailers'] ?? []);

    $activeAccountsCount = collect($accounts)->where('account_status', 'Active')->count();
    $activeListingsCount = collect($listings)->where('listing_status', 'Active')->count();
    $retailProspectsCount = collect($retailers)->where('status', 'Prospect')->count();

    // Stepper mapping
    $stepTitles = [
        1 => 'Selection',
        2 => 'Account Setup',
        3 => 'Store Setup',
        4 => 'Verification',
        5 => 'Product Catalog',
        6 => 'Product Listing',
        7 => 'Optimization',
        8 => 'Pricing Setup',
        9 => 'Inventory Setup',
        10 => 'Launch Checklist',
        11 => 'Orders',
        12 => 'Advertising',
        13 => 'Physical Retail',
        14 => 'Coordination'
    ];
@endphp

<!-- SERVICE OVERVIEW PANEL -->
<div class="stats-panel-row">
    <div class="stat-card-mini">
        <span class="stat-card-title">Selected Markets</span>
        <span class="stat-card-value">{{ count($selectedMarketplaces) }}</span>
    </div>
    <div class="stat-card-mini">
        <span class="stat-card-title">Active Accounts</span>
        <span class="stat-card-value">{{ $activeAccountsCount }}</span>
    </div>
    <div class="stat-card-mini">
        <span class="stat-card-title">Products Catalog</span>
        <span class="stat-card-value">{{ count($products) }}</span>
    </div>
    <div class="stat-card-mini">
        <span class="stat-card-title">Active Listings</span>
        <span class="stat-card-value">{{ $activeListingsCount }}</span>
    </div>
    <div class="stat-card-mini">
        <span class="stat-card-title">Inventory Items</span>
        <span class="stat-card-value">{{ count($inventories) }}</span>
    </div>
    <div class="stat-card-mini">
        <span class="stat-card-title">Orders</span>
        <span class="stat-card-value">{{ count($orders) }}</span>
    </div>
    <div class="stat-card-mini">
        <span class="stat-card-title">Retail Prospects</span>
        <span class="stat-card-value">{{ $retailProspectsCount }}</span>
    </div>
</div>

<!-- Dynamic Stepper -->
<div style="position: relative; width: 100%; margin-bottom: var(--spacing-6);">
    <button type="button" class="stepper-scroll-btn scroll-left" onclick="scrollStepper(-240)" aria-label="Scroll Left">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
    </button>
    <button type="button" class="stepper-scroll-btn scroll-right" onclick="scrollStepper(240)" aria-label="Scroll Right">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
    </button>
    <div class="stepper-container" id="stepper-scroll-container">
        <ol class="stepper" style="min-width: 1960px; padding-bottom: var(--spacing-2);">
            @foreach($stepTitles as $stepNum => $title)
                <li class="step-item {{ $currentStep == $stepNum ? 'in-progress' : ($currentStep > $stepNum ? 'completed' : 'not-started') }}" onclick="jumpToStep({{ $stepNum }})">
                    <div class="step-circle">{{ str_pad($stepNum, 2, '0', STR_PAD_LEFT) }}</div>
                    <span class="step-title">{{ $title }}</span>
                </li>
            @endforeach
        </ol>
    </div>
</div>

<!-- Forms Container -->
<div class="card" style="padding: var(--spacing-8); margin-bottom: 80px;">

    <!-- ================== STEP 1: MARKETPLACE SELECTION ================== -->
    <div id="step-form-container-1" class="step-form-content {{ $currentStep == 1 ? 'active' : '' }}" style="display: {{ $currentStep == 1 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Marketplace Selection</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Select the marketplaces you plan to launch on and outline your primary target parameters.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST">
            @csrf
            <input type="hidden" name="step" value="1">
            <input type="hidden" name="action" id="step-1-action" value="save_continue">

            <div class="form-group">
                <label class="form-label">Marketplace Type (Select multiple) <span style="color: var(--color-danger);">*</span></label>
                <div class="selection-grid">
                    @foreach(['Amazon', 'Walmart', 'TikTok Shop', 'eBay', 'Shopify', 'Meta Shops', 'Google', 'Other'] as $market)
                        <div class="selection-card {{ in_array($market, $selectedMarketplaces) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'mk-{{ $market }}')">
                            <input type="checkbox" name="selected_marketplaces[]" id="mk-{{ $market }}" value="{{ $market }}" class="selection-checkbox" {{ in_array($market, $selectedMarketplaces) ? 'checked' : '' }}>
                            <span class="selection-card-title">{{ $market }}</span>
                            <span class="selection-card-desc">Sell products on {{ $market }}.</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Selling Model (Select multiple) <span style="color: var(--color-danger);">*</span></label>
                <div class="selection-grid">
                    @foreach(['Retail', 'Wholesale', 'Private Label', 'Dropshipping', 'Direct-to-Consumer', 'Hybrid'] as $model)
                        <div class="selection-card {{ in_array($model, $sellingModels) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'model-{{ $model }}')">
                            <input type="checkbox" name="selling_models[]" id="model-{{ $model }}" value="{{ $model }}" class="selection-checkbox" {{ in_array($model, $sellingModels) ? 'checked' : '' }}>
                            <span class="selection-card-title">{{ $model }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Primary Target Countries / Markets <span style="color: var(--color-danger);">*</span></label>
                    <div class="custom-multiselect-container" id="target_countries_container" style="position: relative;">
                        <div class="custom-multiselect-trigger" style="min-height: 38px; display: flex; flex-wrap: wrap; gap: 4px; padding: 6px 12px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); background-color: #ffffff; cursor: pointer; align-items: center;">
                            <span class="multiselect-placeholder" style="color: var(--color-text-muted); font-size: var(--fs-sm);">Select Countries</span>
                        </div>
                        <select name="target_countries[]" multiple class="hidden-select" style="display:none;" required>
                            @foreach(['US', 'CA', 'GB', 'DE', 'FR', 'IT', 'ES', 'AU', 'AE', 'SA', 'PK'] as $code)
                                <option value="{{ $code }}" {{ in_array($code, $targetCountries) ? 'selected' : '' }}>{{ $code }}</option>
                            @endforeach
                        </select>
                        <div class="custom-multiselect-dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background-color: #ffffff; border: 1px solid var(--color-border); border-radius: var(--radius-sm); box-shadow: var(--shadow-md); z-index: 100; max-height: 200px; overflow-y: auto; padding: var(--spacing-2);">
                            @foreach(['US', 'CA', 'GB', 'DE', 'FR', 'IT', 'ES', 'AU', 'AE', 'SA', 'PK'] as $code)
                                <div class="multiselect-option {{ in_array($code, $targetCountries) ? 'selected' : '' }}" data-value="{{ $code }}" style="display: flex; align-items: center; gap: 8px; padding: 6px 12px; font-size: var(--fs-xs); cursor: pointer; border-radius: var(--radius-sm);">
                                    <input type="checkbox" class="multiselect-checkbox" {{ in_array($code, $targetCountries) ? 'checked' : '' }}>
                                    <span>{{ $code }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Primary Marketplace <span style="color: var(--color-danger);">*</span></label>
                    <select name="primary_marketplace" class="form-control" style="height: 38px;" required>
                        <option value="">Select Primary</option>
                        @foreach(['Amazon', 'Walmart', 'TikTok Shop', 'eBay', 'Shopify', 'Meta Shops', 'Google', 'Other'] as $market)
                            <option value="{{ $market }}" {{ old('primary_marketplace', $payload['primary_marketplace'] ?? '') == $market ? 'selected' : '' }}>{{ $market }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Expected Launch Date <span style="color: var(--color-danger);">*</span></label>
                    <input type="date" name="expected_launch_date" class="form-control" style="height: 38px;" value="{{ old('expected_launch_date', $payload['expected_launch_date'] ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Marketplace Goals <span style="color: var(--color-danger);">*</span></label>
                    <div style="display: flex; flex-wrap: wrap; gap: var(--spacing-4); margin-top: 8px;">
                        @foreach(['Increase Sales', 'Brand Launch', 'Expand Market', 'Test Product', 'Wholesale', 'Other'] as $goal)
                            <label style="display: flex; align-items: center; gap: 6px; font-size: var(--fs-xs); font-weight: var(--fw-medium); color: var(--color-text-secondary); cursor: pointer;">
                                <input type="checkbox" name="goals[]" value="{{ $goal }}" {{ in_array($goal, $goals) ? 'checked' : '' }} style="accent-color: var(--color-primary);">
                                {{ $goal }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Additional Requirements</label>
                <textarea name="additional_requirements" class="form-control" rows="3" placeholder="Enter any extra details or operational guidelines...">{{ old('additional_requirements', $payload['additional_requirements'] ?? '') }}</textarea>
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

    <!-- ================== STEP 2: ACCOUNT SETUP ================== -->
    <div id="step-form-container-2" class="step-form-content {{ $currentStep == 2 ? 'active' : '' }}" style="display: {{ $currentStep == 2 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Account Setup</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage account credentials and status updates for your selected marketplaces.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-2-form">
            @csrf
            <input type="hidden" name="step" value="2">
            <input type="hidden" name="action" id="step-2-action" value="save_continue">

            <!-- Hidden Inputs Container for Dynamic Array -->
            <div id="accounts-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Accounts List</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('account')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Marketplace Account
                </button>
            </div>

            <!-- Dynamic Form for Adding Account -->
            <div id="add-account-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Account Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Marketplace Name</label>
                        <select id="acc_marketplace_name" class="form-control" style="height: 36px;">
                            @foreach($selectedMarketplaces as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                            @if(empty($selectedMarketplaces))
                                <option value="Amazon">Amazon</option>
                                <option value="Walmart">Walmart</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Account Status</label>
                        <select id="acc_account_status" class="form-control" style="height: 36px;">
                            @foreach(['Not Started', 'In Progress', 'Submitted', 'Under Review', 'Approved', 'Rejected', 'Active'] as $st)
                                <option value="{{ $st }}">{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Seller / Store Name</label>
                        <input type="text" id="acc_seller_name" class="form-control" placeholder="e.g. ApexBrands Store" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Account Email</label>
                        <input type="email" id="acc_account_email" class="form-control" placeholder="seller@company.com" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Account ID / Seller ID</label>
                        <input type="text" id="acc_account_id" class="form-control" placeholder="e.g. A29302193" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Store URL</label>
                        <input type="url" id="acc_store_url" class="form-control" placeholder="https://amazon.com/shops/..." style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Account Created Date</label>
                        <input type="date" id="acc_created_date" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Account Notes</label>
                        <input type="text" id="acc_notes" class="form-control" placeholder="Optional notes..." style="height: 36px;">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('account')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addListItem('account')">Save Account</button>
                </div>
            </div>

            <!-- Accounts Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="accounts-table">
                    <thead>
                        <tr>
                            <th>Marketplace</th>
                            <th>Seller Name</th>
                            <th>Email</th>
                            <th>Seller ID</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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

    <!-- ================== STEP 3: STORE SETUP ================== -->
    <div id="step-form-container-3" class="step-form-content {{ $currentStep == 3 ? 'active' : '' }}" style="display: {{ $currentStep == 3 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Store Setup</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Enter store profile information, assets, categories, and policies for your principal storefront.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST">
            @csrf
            <input type="hidden" name="step" value="3">
            <input type="hidden" name="action" id="step-3-action" value="save_continue">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Store Name <span style="color: var(--color-danger);">*</span></label>
                    <input type="text" name="store_name" class="form-control" style="height: 38px;" value="{{ old('store_name', $payload['store_name'] ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Store URL <span style="color: var(--color-danger);">*</span></label>
                    <input type="url" name="store_url" class="form-control" style="height: 38px;" value="{{ old('store_url', $payload['store_url'] ?? '') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Store Description <span style="color: var(--color-danger);">*</span></label>
                <textarea name="store_description" class="form-control" rows="3" required>{{ old('store_description', $payload['store_description'] ?? '') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Store Category <span style="color: var(--color-danger);">*</span></label>
                    <select name="store_category" class="form-control" style="height: 38px;" required>
                        <option value="">Select Category</option>
                        @foreach(['Electronics', 'Beauty & Personal Care', 'Home & Kitchen', 'Apparel & Fashion', 'Health & Household', 'Toys & Games', 'Office Products', 'Other'] as $cat)
                            <option value="{{ $cat }}" {{ old('store_category', $payload['store_category'] ?? '') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Store Contact Email <span style="color: var(--color-danger);">*</span></label>
                    <input type="email" name="store_contact_email" class="form-control" style="height: 38px;" value="{{ old('store_contact_email', $payload['store_contact_email'] ?? '') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Store Phone <span style="color: var(--color-danger);">*</span></label>
                    <input type="text" name="store_phone" class="form-control" style="height: 38px;" value="{{ old('store_phone', $payload['store_phone'] ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Store Setup Status <span style="color: var(--color-danger);">*</span></label>
                    <select name="store_setup_status" class="form-control" style="height: 38px;" required>
                        @foreach(['Not Started', 'In Progress', 'Completed'] as $st)
                            <option value="{{ $st }}" {{ old('store_setup_status', $payload['store_setup_status'] ?? '') == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Shipping Setup Required? <span style="color: var(--color-danger);">*</span></label>
                    <div class="segmented-control">
                        @php $shippingReq = old('shipping_setup_required', $payload['shipping_setup_required'] ?? 'yes'); @endphp
                        <input type="radio" name="shipping_setup_required" id="shipping_yes" value="yes" class="segmented-option" {{ $shippingReq == 'yes' ? 'checked' : '' }}>
                        <label for="shipping_yes" class="segmented-label">Yes</label>
                        <input type="radio" name="shipping_setup_required" id="shipping_no" value="no" class="segmented-option" {{ $shippingReq == 'no' ? 'checked' : '' }}>
                        <label for="shipping_no" class="segmented-label">No</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Return Policy Setup Required? <span style="color: var(--color-danger);">*</span></label>
                    <div class="segmented-control">
                        @php $returnReq = old('return_policy_setup_required', $payload['return_policy_setup_required'] ?? 'yes'); @endphp
                        <input type="radio" name="return_policy_setup_required" id="return_yes" value="yes" class="segmented-option" {{ $returnReq == 'yes' ? 'checked' : '' }}>
                        <label for="return_yes" class="segmented-label">Yes</label>
                        <input type="radio" name="return_policy_setup_required" id="return_no" value="no" class="segmented-option" {{ $returnReq == 'no' ? 'checked' : '' }}>
                        <label for="return_no" class="segmented-label">No</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Store Notes</label>
                <textarea name="store_notes" class="form-control" rows="3">{{ old('store_notes', $payload['store_notes'] ?? '') }}</textarea>
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

    <!-- ================== STEP 4: BUSINESS VERIFICATION ================== -->
    <div id="step-form-container-4" class="step-form-content {{ $currentStep == 4 ? 'active' : '' }}" style="display: {{ $currentStep == 4 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Business Verification</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Submit and manage registration and verification files required by your marketplaces.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-4-form">
            @csrf
            <input type="hidden" name="step" value="4">
            <input type="hidden" name="action" id="step-4-action" value="save_continue">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Marketplace <span style="color: var(--color-danger);">*</span></label>
                    <select name="verification_marketplace" class="form-control" style="height: 38px;" required>
                        <option value="">Select Marketplace</option>
                        @foreach($selectedMarketplaces as $m)
                            <option value="{{ $m }}" {{ old('verification_marketplace', $payload['verification_marketplace'] ?? '') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                        @if(empty($selectedMarketplaces))
                            <option value="Amazon" selected>Amazon</option>
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Verification Status <span style="color: var(--color-danger);">*</span></label>
                    <select name="verification_status" id="verification_status_select" class="form-control" style="height: 38px;" onchange="toggleRejectionReason(this.value)" required>
                        @foreach(['Not Started', 'Documents Required', 'Submitted', 'Under Review', 'Approved', 'Rejected'] as $st)
                            <option value="{{ $st }}" {{ old('verification_status', $payload['verification_status'] ?? '') == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Verification Submission Date <span style="color: var(--color-danger);">*</span></label>
                    <input type="date" name="verification_submission_date" class="form-control" style="height: 38px;" value="{{ old('verification_submission_date', $payload['verification_submission_date'] ?? '') }}" required>
                </div>
                <div class="form-group" id="rejection_reason_container" style="display: {{ old('verification_status', $payload['verification_status'] ?? '') == 'Rejected' ? 'block' : 'none' }};">
                    <label class="form-label">Rejection Reason</label>
                    <textarea name="rejection_reason" class="form-control" rows="2">{{ old('rejection_reason', $payload['rejection_reason'] ?? '') }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Verification Notes</label>
                <textarea name="verification_notes" class="form-control" rows="2">{{ old('verification_notes', $payload['verification_notes'] ?? '') }}</textarea>
            </div>

            <div class="section-separator"></div>

            <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-text-primary);">Required Documents</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: var(--spacing-6);">
                @include('admin.services.partials.doc_card', [
                    'title' => 'Business Utility Bill (Verification)',
                    'field' => 'verification_utility_bill',
                    'required' => true,
                    'step' => 4,
                    'serviceKey' => 'marketplace-retail',
                    'meta' => $payload['documents']['verification_utility_bill'] ?? null
                ])
                @include('admin.services.partials.doc_card', [
                    'title' => 'National ID / Passport (Verification)',
                    'field' => 'verification_passport',
                    'required' => true,
                    'step' => 4,
                    'serviceKey' => 'marketplace-retail',
                    'meta' => $payload['documents']['verification_passport'] ?? null
                ])
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

    <!-- ================== STEP 5: PRODUCT CATALOG ================== -->
    <div id="step-form-container-5" class="step-form-content {{ $currentStep == 5 ? 'active' : '' }}" style="display: {{ $currentStep == 5 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Product Catalog</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Register and manage products that will be listed on your selected marketplaces.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-5-form">
            @csrf
            <input type="hidden" name="step" value="5">
            <input type="hidden" name="action" id="step-5-action" value="save_continue">

            <div id="products-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Products Table</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('product')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Product
                </button>
            </div>

            <!-- Dynamic Form for Adding Product -->
            <div id="add-product-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Product Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Product Name</label>
                        <input type="text" id="prod_product_name" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">SKU</label>
                        <input type="text" id="prod_sku" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Product Category</label>
                        <select id="prod_product_category" class="form-control" style="height: 36px;">
                            <option value="Electronics">Electronics</option>
                            <option value="Home & Kitchen">Home & Kitchen</option>
                            <option value="Beauty">Beauty</option>
                            <option value="Apparel">Apparel</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Brand Name</label>
                        <input type="text" id="prod_brand_name" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Product Description</label>
                    <textarea id="prod_product_description" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Variants (Size / Color)</label>
                        <input type="text" id="prod_variants" class="form-control" placeholder="e.g. L, Red" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">UPC / GTIN</label>
                        <input type="text" id="prod_upc_gtin" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Product Weight (lbs)</label>
                        <input type="number" step="0.01" id="prod_product_weight" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Product Cost ($)</label>
                        <input type="number" step="0.01" id="prod_product_cost" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Target Selling Price ($)</label>
                        <input type="number" step="0.01" id="prod_target_selling_price" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Inventory Quantity</label>
                        <input type="number" id="prod_inventory_quantity" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Product Status</label>
                    <select id="prod_product_status" class="form-control" style="height: 36px;">
                        <option value="Draft">Draft</option>
                        <option value="Ready">Ready</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('product')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addListItem('product')">Save Product</button>
                </div>
            </div>

            <!-- Products Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="products-table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Cost</th>
                            <th>Price</th>
                            <th>Inventory</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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

    <!-- ================== STEP 6: PRODUCT LISTING ================== -->
    <div id="step-form-container-6" class="step-form-content {{ $currentStep == 6 ? 'active' : '' }}" style="display: {{ $currentStep == 6 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Product Listing</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Publish product listings tailored to your active marketplaces.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-6-form">
            @csrf
            <input type="hidden" name="step" value="6">
            <input type="hidden" name="action" id="step-6-action" value="save_continue">

            <div id="listings-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Listings Table</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('listing')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Listing
                </button>
            </div>

            <!-- Dynamic Form for Adding Listing -->
            <div id="add-listing-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Listing Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select Product</label>
                        <select id="list_product_sku" class="form-control" style="height: 36px;">
                            @foreach($products as $p)
                                <option value="{{ $p['sku'] }}">{{ $p['product_name'] }} ({{ $p['sku'] }})</option>
                            @endforeach
                            @if(empty($products))
                                <option value="">No products found</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Marketplace</label>
                        <select id="list_marketplace" class="form-control" style="height: 36px;">
                            @foreach($selectedMarketplaces as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                            @if(empty($selectedMarketplaces))
                                <option value="Amazon">Amazon</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Listing Title</label>
                        <input type="text" id="list_listing_title" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <input type="text" id="list_category" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Listing Description</label>
                    <textarea id="list_listing_description" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Listing SKU (Store SKU)</label>
                        <input type="text" id="list_sku" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Marketplace Product ID (e.g. ASIN)</label>
                        <input type="text" id="list_marketplace_product_id" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Listing Status</label>
                    <select id="list_listing_status" class="form-control" style="height: 36px;">
                        <option value="Draft">Draft</option>
                        <option value="Ready">Ready</option>
                        <option value="Submitted">Submitted</option>
                        <option value="Active">Active</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('listing')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addListItem('listing')">Save Listing</button>
                </div>
            </div>

            <!-- Listings Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="listings-table">
                    <thead>
                        <tr>
                            <th>Product SKU</th>
                            <th>Marketplace</th>
                            <th>Title</th>
                            <th>Store SKU</th>
                            <th>Product ID</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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

    <!-- ================== STEP 7: LISTING OPTIMIZATION ================== -->
    <div id="step-form-container-7" class="step-form-content {{ $currentStep == 7 ? 'active' : '' }}" style="display: {{ $currentStep == 7 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Listing Optimization</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Apply SEO keywords, optimized titles, and check score values for catalog listings.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-7-form">
            @csrf
            <input type="hidden" name="step" value="7">
            <input type="hidden" name="action" id="step-7-action" value="save_continue">

            <div id="optimizations-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Optimizations Table</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('optimization')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Optimization
                </button>
            </div>

            <!-- Dynamic Form for Adding Optimization -->
            <div id="add-optimization-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Optimization Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select Listing</label>
                        <select id="opt_listing_id" class="form-control" style="height: 36px;">
                            @foreach($listings as $l)
                                <option value="{{ $l['sku'] }}">{{ $l['listing_title'] }} ({{ $l['marketplace'] }})</option>
                            @endforeach
                            @if(empty($listings))
                                <option value="">No listings found</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Primary Keyword</label>
                        <input type="text" id="opt_primary_keyword" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Optimized Title</label>
                        <input type="text" id="opt_optimized_title" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Optimization Score (%)</label>
                        <input type="number" id="opt_optimization_score" class="form-control" style="height: 36px;" value="85">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Optimized Description</label>
                    <textarea id="opt_optimized_description" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Image Optimization Status</label>
                        <select id="opt_image_optimization_status" class="form-control" style="height: 36px;">
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keyword Optimization Status</label>
                        <select id="opt_keyword_optimization_status" class="form-control" style="height: 36px;">
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Optimization Notes</label>
                    <input type="text" id="opt_optimization_notes" class="form-control" placeholder="Optional comments..." style="height: 36px;">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('optimization')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addListItem('optimization')">Save Optimization</button>
                </div>
            </div>

            <!-- Optimizations Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="optimizations-table">
                    <thead>
                        <tr>
                            <th>Listing SKU</th>
                            <th>Primary Keyword</th>
                            <th>Optimized Title</th>
                            <th>Images</th>
                            <th>Keywords</th>
                            <th>Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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

    <!-- ================== STEP 8: PRICING SETUP ================== -->
    <div id="step-form-container-8" class="step-form-content {{ $currentStep == 8 ? 'active' : '' }}" style="display: {{ $currentStep == 8 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Pricing Setup</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage and scheduler pricing strategies, base values, minimums/maximums, and discounts.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-8-form">
            @csrf
            <input type="hidden" name="step" value="8">
            <input type="hidden" name="action" id="step-8-action" value="save_continue">

            <div id="pricings-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Pricings Table</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('pricing')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Pricing
                </button>
            </div>

            <!-- Dynamic Form for Adding Pricing -->
            <div id="add-pricing-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Pricing Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select Product</label>
                        <select id="pr_product_sku" class="form-control" style="height: 36px;">
                            @foreach($products as $p)
                                <option value="{{ $p['sku'] }}">{{ $p['product_name'] }} ({{ $p['sku'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Marketplace</label>
                        <select id="pr_marketplace" class="form-control" style="height: 36px;">
                            @foreach($selectedMarketplaces as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Base Cost Price ($)</label>
                        <input type="number" step="0.01" id="pr_base_price" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Marketplace Price ($)</label>
                        <input type="number" step="0.01" id="pr_marketplace_price" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Minimum Price ($)</label>
                        <input type="number" step="0.01" id="pr_minimum_price" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Maximum Price ($)</label>
                        <input type="number" step="0.01" id="pr_maximum_price" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Pricing Status</label>
                    <select id="pr_pricing_status" class="form-control" style="height: 36px;">
                        <option value="Draft">Draft</option>
                        <option value="Active">Active</option>
                        <option value="Scheduled">Scheduled</option>
                    </select>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('pricing')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addListItem('pricing')">Save Pricing</button>
                </div>
            </div>

            <!-- Pricings Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="pricings-table">
                    <thead>
                        <tr>
                            <th>Product SKU</th>
                            <th>Marketplace</th>
                            <th>Base Cost</th>
                            <th>Marketplace Price</th>
                            <th>Min Price</th>
                            <th>Max Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="form-navigation">
                <button type="button" class="btn btn-secondary" onclick="jumpToStep(7)">Back</button>
                <div>
                    <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-8-action').value='save_draft'">Save Draft</button>
                    <button type="submit" class="btn btn-primary">Save & Continue</button>
                </div>
            </div>
        </form>
    </div>

    <!-- ================== STEP 9: INVENTORY SETUP ================== -->
    <div id="step-form-container-9" class="step-form-content {{ $currentStep == 9 ? 'active' : '' }}" style="display: {{ $currentStep == 9 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Inventory Setup</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage warehouse quantities, low-stock reorder thresholds, and active auto-sync parameters.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-9-form">
            @csrf
            <input type="hidden" name="step" value="9">
            <input type="hidden" name="action" id="step-9-action" value="save_continue">

            <div id="inventories-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Inventories Table</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('inventory')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Inventory
                </button>
            </div>

            <!-- Dynamic Form for Adding Inventory -->
            <div id="add-inventory-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Inventory Record</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select Product</label>
                        <select id="inv_product_sku" class="form-control" style="height: 36px;" onchange="syncInvSku(this.value)">
                            <option value="">Select Product</option>
                            @foreach($products as $p)
                                <option value="{{ $p['sku'] }}">{{ $p['product_name'] }} ({{ $p['sku'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">SKU</label>
                        <input type="text" id="inv_sku" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Marketplace</label>
                        <select id="inv_marketplace" class="form-control" style="height: 36px;">
                            @foreach($selectedMarketplaces as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Available Quantity</label>
                        <input type="number" id="inv_available_quantity" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Reorder Level (Threshold)</label>
                        <input type="number" id="inv_reorder_level" class="form-control" style="height: 36px;" value="10">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Warehouse / Location</label>
                        <select id="inv_warehouse_location" class="form-control" style="height: 36px;">
                            <option value="US East (NY)">US East (NY)</option>
                            <option value="US West (LA)">US West (LA)</option>
                            <option value="Europe (DE)">Europe (DE)</option>
                            <option value="FBA Warehouse">FBA Warehouse</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Inventory Status</label>
                        <select id="inv_inventory_status" class="form-control" style="height: 36px;">
                            <option value="In Stock">In Stock</option>
                            <option value="Low Stock">Low Stock</option>
                            <option value="Out of Stock">Out of Stock</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Auto Inventory Sync?</label>
                        <div class="segmented-control">
                            <input type="radio" name="auto_sync_field" id="sync_yes" value="yes" class="segmented-option" checked>
                            <label for="sync_yes" class="segmented-label">Yes</label>
                            <input type="radio" name="auto_sync_field" id="sync_no" value="no" class="segmented-option">
                            <label for="sync_no" class="segmented-label">No</label>
                        </div>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('inventory')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addListItem('inventory')">Save Inventory</button>
                </div>
            </div>

            <!-- Inventories Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="inventories-table">
                    <thead>
                        <tr>
                            <th>Product SKU</th>
                            <th>Store SKU</th>
                            <th>Marketplace</th>
                            <th>Available Qty</th>
                            <th>Reorder Threshold</th>
                            <th>Warehouse</th>
                            <th>Auto Sync</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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

    <!-- ================== STEP 10: MARKETPLACE LAUNCH ================== -->
    <div id="step-form-container-10" class="step-form-content {{ $currentStep == 10 ? 'active' : '' }}" style="display: {{ $currentStep == 10 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Marketplace Launch Checklist</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Confirm listing checklist statuses and select launch readiness.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST">
            @csrf
            <input type="hidden" name="step" value="10">
            <input type="hidden" name="action" id="step-10-action" value="save_continue">

            <!-- Dynamic Readiness Progress Card -->
            @php
                $checklist = [
                    'Seller Account Active' => $activeAccountsCount > 0,
                    'Store Setup Completed' => !empty($payload['store_setup_status']) && $payload['store_setup_status'] === 'Completed',
                    'Business Verification Approved' => !empty($payload['verification_status']) && $payload['verification_status'] === 'Approved',
                    'Products Added' => count($products) > 0,
                    'Listings Created' => count($listings) > 0,
                    'Listings Optimized' => count($optimizations) > 0,
                    'Pricing Configured' => count($pricings) > 0,
                    'Inventory Configured' => count($inventories) > 0,
                    'Shipping Setup Completed' => !empty($payload['shipping_setup_required']),
                    'Return Policy Completed' => !empty($payload['return_policy_setup_required'])
                ];
                $checkedCount = collect($checklist)->filter()->count();
                $readinessPercentage = round(($checkedCount / 10) * 100);
            @endphp

            <div class="card" style="padding: var(--spacing-6); border-color: var(--color-primary); background-color: var(--color-primary-light); margin-bottom: var(--spacing-6);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-2);">
                    <h3 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); color: var(--color-primary-dark);">Readiness Percentage</h3>
                    <span style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-primary-dark);">{{ $readinessPercentage }}%</span>
                </div>
                <div class="progress-bar-outer" style="height: 10px; background-color: rgba(255, 255, 255, 0.4);">
                    <div class="progress-bar-inner" style="width: {{ $readinessPercentage }}%; background-color: var(--color-primary);"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Launch Checklist Checklist</label>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    @foreach($checklist as $item => $checked)
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="display: flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: var(--radius-full); background-color: {{ $checked ? 'var(--color-success)' : 'var(--color-border)' }}; color: #ffffff;">
                                @if($checked) ✓ @else ✕ @endif
                            </span>
                            <span style="font-size: var(--fs-xs); color: var(--color-text-secondary); font-weight: var(--fw-medium);">{{ $item }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-row" style="margin-top: var(--spacing-6);">
                <div class="form-group">
                    <label class="form-label">Launch Date <span style="color: var(--color-danger);">*</span></label>
                    <input type="date" name="launch_date" class="form-control" style="height: 38px;" value="{{ old('launch_date', $payload['launch_date'] ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Launch Status <span style="color: var(--color-danger);">*</span></label>
                    <select name="launch_status" class="form-control" style="height: 38px;" required>
                        @foreach(['Not Ready', 'Ready', 'Scheduled', 'Launched', 'Paused'] as $lst)
                            <option value="{{ $lst }}" {{ old('launch_status', $payload['launch_status'] ?? '') == $lst ? 'selected' : '' }}>{{ $lst }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Launch Notes</label>
                <textarea name="launch_notes" class="form-control" rows="3">{{ old('launch_notes', $payload['launch_notes'] ?? '') }}</textarea>
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

    <!-- ================== STEP 11: ORDER MANAGEMENT ================== -->
    <div id="step-form-container-11" class="step-form-content {{ $currentStep == 11 ? 'active' : '' }}" style="display: {{ $currentStep == 11 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Order Management</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Track marketplace sales, order details, delivery status, and carrier tracking info.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-11-form">
            @csrf
            <input type="hidden" name="step" value="11">
            <input type="hidden" name="action" id="step-11-action" value="save_continue">

            <div id="orders-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Orders Table</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('order')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Order
                </button>
            </div>

            <!-- Dynamic Form for Adding Order -->
            <div id="add-order-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Order Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Marketplace</label>
                        <select id="ord_marketplace" class="form-control" style="height: 36px;">
                            @foreach($selectedMarketplaces as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Order ID</label>
                        <input type="text" id="ord_order_id" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Customer Name</label>
                        <input type="text" id="ord_customer_name" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Order Date</label>
                        <input type="date" id="ord_order_date" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select Product</label>
                        <select id="ord_product_sku" class="form-control" style="height: 36px;">
                            @foreach($products as $p)
                                <option value="{{ $p['sku'] }}">{{ $p['product_name'] }} ({{ $p['sku'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quantity</label>
                        <input type="number" id="ord_quantity" class="form-control" style="height: 36px;" value="1">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Order Amount ($)</label>
                        <input type="number" step="0.01" id="ord_order_amount" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Order Status</label>
                        <select id="ord_order_status" class="form-control" style="height: 36px;">
                            <option value="New">New</option>
                            <option value="Processing">Processing</option>
                            <option value="Shipped">Shipped</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Cancelled">Cancelled</option>
                            <option value="Returned">Returned</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('order')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addListItem('order')">Save Order</button>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Marketplace</th>
                            <th>Customer Name</th>
                            <th>Date</th>
                            <th>Product SKU</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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

    <!-- ================== STEP 12: MARKETPLACE ADVERTISING ================== -->
    <div id="step-form-container-12" class="step-form-content {{ $currentStep == 12 ? 'active' : '' }}" style="display: {{ $currentStep == 12 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Marketplace Advertising</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage PPC campaigns, active budgets, target criteria, and optimization states.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-12-form">
            @csrf
            <input type="hidden" name="step" value="12">
            <input type="hidden" name="action" id="step-12-action" value="save_continue">

            <div id="campaigns-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Campaigns Table</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('campaign')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Campaign
                </button>
            </div>

            <!-- Dynamic Form for Adding Campaign -->
            <div id="add-campaign-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Campaign Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Advertising Platform</label>
                        <select id="camp_advertising_platform" class="form-control" style="height: 36px;">
                            <option value="Amazon Ads">Amazon Ads</option>
                            <option value="Walmart Ads">Walmart Ads</option>
                            <option value="TikTok Ads">TikTok Ads</option>
                            <option value="Meta Ads">Meta Ads</option>
                            <option value="Google Ads">Google Ads</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Campaign Name</label>
                        <input type="text" id="camp_campaign_name" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Marketplace</label>
                        <select id="camp_marketplace" class="form-control" style="height: 36px;">
                            @foreach($selectedMarketplaces as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Campaign Type</label>
                        <input type="text" id="camp_campaign_type" class="form-control" placeholder="e.g. Sponsored Products" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Daily Budget ($)</label>
                        <input type="number" step="0.01" id="camp_daily_budget" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Campaign Goal</label>
                        <select id="camp_campaign_goal" class="form-control" style="height: 36px;">
                            <option value="Sales">Sales</option>
                            <option value="Traffic">Traffic</option>
                            <option value="Product Launch">Product Launch</option>
                            <option value="Brand Awareness">Brand Awareness</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Campaign Status</label>
                    <select id="camp_campaign_status" class="form-control" style="height: 36px;">
                        <option value="Draft">Draft</option>
                        <option value="Active">Active</option>
                        <option value="Paused">Paused</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('campaign')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addListItem('campaign')">Save Campaign</button>
                </div>
            </div>

            <!-- Campaigns Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="campaigns-table">
                    <thead>
                        <tr>
                            <th>Campaign Name</th>
                            <th>Platform</th>
                            <th>Marketplace</th>
                            <th>Type</th>
                            <th>Daily Budget</th>
                            <th>Goal</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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

    <!-- ================== STEP 13: PHYSICAL RETAIL SETUP ================== -->
    <div id="step-form-container-13" class="step-form-content {{ $currentStep == 13 ? 'active' : '' }}" style="display: {{ $currentStep == 13 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Physical Retail Setup (Optional)</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Enable physical retail preparation for catalog products, pricing sheets, and retailer target requirements.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST">
            @csrf
            <input type="hidden" name="step" value="13">
            <input type="hidden" name="action" id="step-13-action" value="save_continue">

            <div class="form-group">
                <label class="form-label">Physical Retail Required? <span style="color: var(--color-danger);">*</span></label>
                <div class="segmented-control">
                    @php $retailReq = old('physical_retail_required', $payload['physical_retail_required'] ?? 'no'); @endphp
                    <input type="radio" name="physical_retail_required" id="retail_req_yes" value="yes" class="segmented-option" {{ $retailReq == 'yes' ? 'checked' : '' }} onchange="toggleRetailFields('yes')">
                    <label for="retail_req_yes" class="segmented-label">Yes</label>
                    <input type="radio" name="physical_retail_required" id="retail_req_no" value="no" class="segmented-option" {{ $retailReq == 'no' ? 'checked' : '' }} onchange="toggleRetailFields('no')">
                    <label for="retail_req_no" class="segmented-label">No</label>
                </div>
            </div>

            <!-- Conditional Retail Fields -->
            <div id="retail_fields_container" style="display: {{ $retailReq == 'yes' ? 'block' : 'none' }};">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Retail Product Category <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" name="retail_product_category" class="form-control" style="height: 38px;" value="{{ old('retail_product_category', $payload['retail_product_category'] ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Wholesale Price ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="wholesale_price" class="form-control" style="height: 38px;" value="{{ old('wholesale_price', $payload['wholesale_price'] ?? '') }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Suggested Retail Price ($) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" step="0.01" name="suggested_retail_price" class="form-control" style="height: 38px;" value="{{ old('suggested_retail_price', $payload['suggested_retail_price'] ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Minimum Order Quantity (MOQ) <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" name="min_order_quantity" class="form-control" style="height: 38px;" value="{{ old('min_order_quantity', $payload['min_order_quantity'] ?? '') }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Retail Packaging Required? <span style="color: var(--color-danger);">*</span></label>
                        <div class="segmented-control">
                            @php $packReq = old('retail_packaging_required', $payload['retail_packaging_required'] ?? 'no'); @endphp
                            <input type="radio" name="retail_packaging_required" id="pack_yes" value="yes" class="segmented-option" {{ $packReq == 'yes' ? 'checked' : '' }}>
                            <label for="pack_yes" class="segmented-label">Yes</label>
                            <input type="radio" name="retail_packaging_required" id="pack_no" value="no" class="segmented-option" {{ $packReq == 'no' ? 'checked' : '' }}>
                            <label for="pack_no" class="segmented-label">No</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Retail-Ready Packaging? <span style="color: var(--color-danger);">*</span></label>
                        <div class="segmented-control">
                            @php $readyPack = old('retail_ready_packaging', $payload['retail_ready_packaging'] ?? 'no'); @endphp
                            <input type="radio" name="retail_ready_packaging" id="ready_yes" value="yes" class="segmented-option" {{ $readyPack == 'yes' ? 'checked' : '' }}>
                            <label for="ready_yes" class="segmented-label">Yes</label>
                            <input type="radio" name="retail_ready_packaging" id="ready_no" value="no" class="segmented-option" {{ $readyPack == 'no' ? 'checked' : '' }}>
                            <label for="ready_no" class="segmented-label">No</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Retail Requirements</label>
                    <textarea name="retail_requirements" class="form-control" rows="3">{{ old('retail_requirements', $payload['retail_requirements'] ?? '') }}</textarea>
                </div>
            </div>

            <div class="form-navigation">
                <button type="button" class="btn btn-secondary" onclick="jumpToStep(12)">Back</button>
                <div>
                    <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-13-action').value='save_draft'">Save Draft</button>
                    <button type="submit" class="btn btn-primary">Save & Continue</button>
                </div>
            </div>
        </form>
    </div>

    <!-- ================== STEP 14: RETAILER / DISTRIBUTOR COORDINATION ================== -->
    <div id="step-form-container-14" class="step-form-content {{ $currentStep == 14 ? 'active' : '' }}" style="display: {{ $currentStep == 14 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Retailer / Distributor Coordination</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage connections with buyers, wholesalers, and physical distributors.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-14-form">
            @csrf
            <input type="hidden" name="step" value="14">
            <input type="hidden" name="action" id="step-14-action" value="save_continue">

            <div id="retailers-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Retailers List</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('retailer')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Retailer / Distributor
                </button>
            </div>

            <!-- Dynamic Form for Adding Retailer -->
            <div id="add-retailer-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Prospect Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Retailer / Distributor Name</label>
                        <input type="text" id="ret_retailer_name" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Type</label>
                        <select id="ret_type" class="form-control" style="height: 36px;">
                            <option value="Retailer">Retailer</option>
                            <option value="Distributor">Distributor</option>
                            <option value="Wholesaler">Wholesaler</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Contact Person</label>
                        <input type="text" id="ret_contact_person" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" id="ret_email" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" id="ret_phone" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select id="ret_status" class="form-control" style="height: 36px;">
                            <option value="Prospect">Prospect</option>
                            <option value="Contacted">Contacted</option>
                            <option value="Interested">Interested</option>
                            <option value="Negotiating">Negotiating</option>
                            <option value="Approved">Approved</option>
                            <option value="Active">Active</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('retailer')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addListItem('retailer')">Save Prospect</button>
                </div>
            </div>

            <!-- Retailers Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="retailers-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Contact Person</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="form-navigation">
                <button type="button" class="btn btn-secondary" onclick="jumpToStep(13)">Back</button>
                <div>
                    <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-14-action').value='save_draft'">Save Draft</button>
                    <button type="submit" class="btn btn-primary">Complete Marketplace & Retail Services</button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@section('dashboard_scripts')
<script>
    // State management for repeatable records
    const listStore = {
        account: @json($accounts),
        product: @json($products),
        listing: @json($listings),
        optimization: @json($optimizations),
        pricing: @json($pricings),
        inventory: @json($inventories),
        order: @json($orders),
        campaign: @json($campaigns),
        retailer: @json($retailers)
    };

    // Keep active step scroll positioning
    const stepperContainer = document.getElementById('stepper-scroll-container');
    function scrollStepper(amount) {
        if (stepperContainer) {
            stepperContainer.scrollBy({ left: amount, behavior: 'smooth' });
        }
    }

    // Toggle conditional retail fields
    function toggleRetailFields(isRequired) {
        const container = document.getElementById('retail_fields_container');
        if (container) {
            container.style.display = (isRequired === 'yes') ? 'block' : 'none';
        }
    }

    // Toggle Rejection Reason
    function toggleRejectionReason(status) {
        const container = document.getElementById('rejection_reason_container');
        if (container) {
            container.style.display = (status === 'Rejected') ? 'block' : 'none';
        }
    }

    // Auto sync product SKU to inventory SKU field
    function syncInvSku(sku) {
        const input = document.getElementById('inv_sku');
        if (input) input.value = sku;
    }

    // Show inline form for adding items
    function showAddForm(type) {
        const form = document.getElementById(`add-${type}-form`);
        if (form) form.style.display = 'block';
    }

    // Hide inline form
    function hideAddForm(type) {
        const form = document.getElementById(`add-${type}-form`);
        if (form) form.style.display = 'none';
    }

    // Add Item to local arrays and update tables
    function addListItem(type) {
        let item = {};
        
        if (type === 'account') {
            item = {
                marketplace_name: document.getElementById('acc_marketplace_name').value,
                account_status: document.getElementById('acc_account_status').value,
                seller_name: document.getElementById('acc_seller_name').value,
                account_email: document.getElementById('acc_account_email').value,
                account_id: document.getElementById('acc_account_id').value,
                store_url: document.getElementById('acc_store_url').value,
                created_date: document.getElementById('acc_created_date').value,
                notes: document.getElementById('acc_notes').value
            };
        } else if (type === 'product') {
            item = {
                product_name: document.getElementById('prod_product_name').value,
                sku: document.getElementById('prod_sku').value,
                product_category: document.getElementById('prod_product_category').value,
                brand_name: document.getElementById('prod_brand_name').value,
                product_description: document.getElementById('prod_product_description').value,
                variants: document.getElementById('prod_variants').value,
                upc_gtin: document.getElementById('prod_upc_gtin').value,
                product_weight: document.getElementById('prod_product_weight').value,
                product_cost: document.getElementById('prod_product_cost').value,
                target_selling_price: document.getElementById('prod_target_selling_price').value,
                inventory_quantity: document.getElementById('prod_inventory_quantity').value,
                product_status: document.getElementById('prod_product_status').value
            };
        } else if (type === 'listing') {
            item = {
                product_sku: document.getElementById('list_product_sku').value,
                marketplace: document.getElementById('list_marketplace').value,
                listing_title: document.getElementById('list_listing_title').value,
                listing_description: document.getElementById('list_listing_description').value,
                category: document.getElementById('list_category').value,
                sku: document.getElementById('list_sku').value,
                marketplace_product_id: document.getElementById('list_marketplace_product_id').value,
                listing_status: document.getElementById('list_listing_status').value
            };
        } else if (type === 'optimization') {
            item = {
                listing_id: document.getElementById('opt_listing_id').value,
                primary_keyword: document.getElementById('opt_primary_keyword').value,
                optimized_title: document.getElementById('opt_optimized_title').value,
                optimized_description: document.getElementById('opt_optimized_description').value,
                image_optimization_status: document.getElementById('opt_image_optimization_status').value,
                keyword_optimization_status: document.getElementById('opt_keyword_optimization_status').value,
                optimization_score: document.getElementById('opt_optimization_score').value,
                optimization_notes: document.getElementById('opt_optimization_notes').value
            };
        } else if (type === 'pricing') {
            item = {
                product_sku: document.getElementById('pr_product_sku').value,
                marketplace: document.getElementById('pr_marketplace').value,
                base_price: document.getElementById('pr_base_price').value,
                marketplace_price: document.getElementById('pr_marketplace_price').value,
                minimum_price: document.getElementById('pr_minimum_price').value,
                maximum_price: document.getElementById('pr_maximum_price').value,
                pricing_status: document.getElementById('pr_pricing_status').value
            };
        } else if (type === 'inventory') {
            item = {
                product_sku: document.getElementById('inv_product_sku').value,
                sku: document.getElementById('inv_sku').value,
                marketplace: document.getElementById('inv_marketplace').value,
                available_quantity: document.getElementById('inv_available_quantity').value,
                reorder_level: document.getElementById('inv_reorder_level').value,
                inventory_status: document.getElementById('inv_inventory_status').value,
                warehouse_location: document.getElementById('inv_warehouse_location').value,
                auto_inventory_sync: document.querySelector('input[name="auto_sync_field"]:checked').value
            };
        } else if (type === 'order') {
            item = {
                marketplace: document.getElementById('ord_marketplace').value,
                order_id: document.getElementById('ord_order_id').value,
                customer_name: document.getElementById('ord_customer_name').value,
                order_date: document.getElementById('ord_order_date').value,
                product_sku: document.getElementById('ord_product_sku').value,
                quantity: document.getElementById('ord_quantity').value,
                order_amount: document.getElementById('ord_order_amount').value,
                order_status: document.getElementById('ord_order_status').value
            };
        } else if (type === 'campaign') {
            item = {
                advertising_platform: document.getElementById('camp_advertising_platform').value,
                campaign_name: document.getElementById('camp_campaign_name').value,
                marketplace: document.getElementById('camp_marketplace').value,
                campaign_type: document.getElementById('camp_campaign_type').value,
                daily_budget: document.getElementById('camp_daily_budget').value,
                campaign_goal: document.getElementById('camp_campaign_goal').value,
                campaign_status: document.getElementById('camp_campaign_status').value
            };
        } else if (type === 'retailer') {
            item = {
                retailer_name: document.getElementById('ret_retailer_name').value,
                type: document.getElementById('ret_type').value,
                contact_person: document.getElementById('ret_contact_person').value,
                email: document.getElementById('ret_email').value,
                phone: document.getElementById('ret_phone').value,
                status: document.getElementById('ret_status').value
            };
        }

        // Validate basic requirement
        const firstVal = Object.values(item)[0];
        if (!firstVal) {
            alert('Please fill out all required details.');
            return;
        }

        // Push and re-render
        listStore[type].push(item);
        renderTable(type);
        hideAddForm(type);
    }

    // Remove Item from local array
    function removeListItem(type, idx) {
        listStore[type].splice(idx, 1);
        renderTable(type);
    }

    // Render tables and sync hidden input values
    function renderTable(type) {
        const tbody = document.querySelector(`#${type}s-table tbody`);
        if (!tbody) return;
        tbody.innerHTML = '';

        const list = listStore[type];
        
        list.forEach((item, idx) => {
            const tr = document.createElement('tr');
            
            if (type === 'account') {
                tr.innerHTML = `
                    <td><strong>${item.marketplace_name}</strong></td>
                    <td>${item.seller_name}</td>
                    <td>${item.account_email}</td>
                    <td><code>${item.account_id}</code></td>
                    <td><span class="badge badge-info">${item.account_status}</span></td>
                    <td>${item.created_date}</td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('account', ${idx})">Delete</button></td>
                `;
            } else if (type === 'product') {
                tr.innerHTML = `
                    <td><strong>${item.product_name}</strong></td>
                    <td><code>${item.sku}</code></td>
                    <td>${item.product_category}</td>
                    <td>$${parseFloat(item.product_cost).toFixed(2)}</td>
                    <td>$${parseFloat(item.target_selling_price).toFixed(2)}</td>
                    <td>${item.inventory_quantity}</td>
                    <td><span class="badge badge-success">${item.product_status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('product', ${idx})">Delete</button></td>
                `;
            } else if (type === 'listing') {
                tr.innerHTML = `
                    <td><code>${item.product_sku}</code></td>
                    <td>${item.marketplace}</td>
                    <td><strong>${item.listing_title}</strong></td>
                    <td><code>${item.sku}</code></td>
                    <td><code>${item.marketplace_product_id}</code></td>
                    <td><span class="badge badge-success">${item.listing_status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('listing', ${idx})">Delete</button></td>
                `;
            } else if (type === 'optimization') {
                tr.innerHTML = `
                    <td><code>${item.listing_id}</code></td>
                    <td><code>${item.primary_keyword}</code></td>
                    <td>${item.optimized_title}</td>
                    <td><span class="badge badge-info">${item.image_optimization_status}</span></td>
                    <td><span class="badge badge-info">${item.keyword_optimization_status}</span></td>
                    <td><strong>${item.optimization_score}%</strong></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('optimization', ${idx})">Delete</button></td>
                `;
            } else if (type === 'pricing') {
                tr.innerHTML = `
                    <td><code>${item.product_sku}</code></td>
                    <td>${item.marketplace}</td>
                    <td>$${parseFloat(item.base_price).toFixed(2)}</td>
                    <td>$${parseFloat(item.marketplace_price).toFixed(2)}</td>
                    <td>$${parseFloat(item.minimum_price).toFixed(2)}</td>
                    <td>$${parseFloat(item.maximum_price).toFixed(2)}</td>
                    <td><span class="badge badge-success">${item.pricing_status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('pricing', ${idx})">Delete</button></td>
                `;
            } else if (type === 'inventory') {
                tr.innerHTML = `
                    <td><code>${item.product_sku}</code></td>
                    <td><code>${item.sku}</code></td>
                    <td>${item.marketplace}</td>
                    <td><strong>${item.available_quantity}</strong></td>
                    <td>${item.reorder_level}</td>
                    <td>${item.warehouse_location}</td>
                    <td><span class="badge badge-info">${item.auto_inventory_sync}</span></td>
                    <td><span class="badge badge-success">${item.inventory_status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('inventory', ${idx})">Delete</button></td>
                `;
            } else if (type === 'order') {
                tr.innerHTML = `
                    <td><code>${item.order_id}</code></td>
                    <td>${item.marketplace}</td>
                    <td><strong>${item.customer_name}</strong></td>
                    <td>${item.order_date}</td>
                    <td><code>${item.product_sku}</code></td>
                    <td>${item.quantity}</td>
                    <td>$${parseFloat(item.order_amount).toFixed(2)}</td>
                    <td><span class="badge badge-warning">${item.order_status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('order', ${idx})">Delete</button></td>
                `;
            } else if (type === 'campaign') {
                tr.innerHTML = `
                    <td><strong>${item.campaign_name}</strong></td>
                    <td>${item.advertising_platform}</td>
                    <td>${item.marketplace}</td>
                    <td>${item.campaign_type}</td>
                    <td>$${parseFloat(item.daily_budget).toFixed(2)}</td>
                    <td>${item.campaign_goal}</td>
                    <td><span class="badge badge-success">${item.campaign_status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('campaign', ${idx})">Delete</button></td>
                `;
            } else if (type === 'retailer') {
                tr.innerHTML = `
                    <td><strong>${item.retailer_name}</strong></td>
                    <td>${item.type}</td>
                    <td>${item.contact_person}</td>
                    <td>${item.email}</td>
                    <td>${item.phone}</td>
                    <td><span class="badge badge-warning">${item.status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('retailer', ${idx})">Delete</button></td>
                `;
            }

            tbody.appendChild(tr);
        });

        // Update hidden inputs for array submission
        updateHiddenInputs(type === 'account' ? 'accounts' : 
                           type === 'product' ? 'products' : 
                           type === 'listing' ? 'listings' : 
                           type === 'optimization' ? 'optimizations' : 
                           type === 'pricing' ? 'pricings' : 
                           type === 'inventory' ? 'inventories' : 
                           type === 'order' ? 'orders' : 
                           type === 'campaign' ? 'campaigns' : 'retailers', list);
    }

    // Sync array helper to hidden input list
    function updateHiddenInputs(key, array) {
        const container = document.getElementById(key + '-hidden-inputs-container');
        if (!container) return;
        container.innerHTML = '';
        array.forEach((item, index) => {
            Object.keys(item).forEach(field => {
                const val = item[field];
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `${key}[${index}][${field}]`;
                input.value = val;
                container.appendChild(input);
            });
        });
    }

    // Toggle multi-select card checkbox
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

    // Jump between step container display
    function jumpToStep(stepNumber) {
        for (let i = 1; i <= 14; i++) {
            const form = document.getElementById('step-form-container-' + i);
            if (form) {
                form.style.display = (i === stepNumber) ? 'block' : 'none';
            }
            
            const items = document.querySelectorAll('.step-item');
            if (items[i-1]) {
                if (i === stepNumber) {
                    items[i-1].classList.add('in-progress');
                } else {
                    items[i-1].classList.remove('in-progress');
                }
            }
        }
    }

    // Initialize all tables on DOM load
    document.addEventListener('DOMContentLoaded', function() {
        // Init target countries multi-select
        initCustomMultiselect('target_countries_container');

        // Init tables
        renderTable('account');
        renderTable('product');
        renderTable('listing');
        renderTable('optimization');
        renderTable('pricing');
        renderTable('inventory');
        renderTable('order');
        renderTable('campaign');
        renderTable('retailer');
    });
</script>
@endsection
