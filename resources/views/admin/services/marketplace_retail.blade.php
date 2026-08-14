@extends('layouts.dashboard')

@section('title', 'Marketplace & Retail Services')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
    <style>
        .form-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--spacing-4);
            margin-bottom: var(--spacing-4);
        }
        @media (min-width: 640px) {
            .form-row {
                grid-template-columns: repeat(2, 1fr);
            }
            .form-row-full {
                grid-column: span 2;
            }
        }
        .selection-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: var(--spacing-3);
            margin-bottom: var(--spacing-6);
        }
        @media (min-width: 640px) {
            .selection-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: var(--spacing-4);
            }
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
            padding: var(--spacing-5);
            margin-bottom: var(--spacing-6);
        }
        .summary-badge-list {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-2);
            margin-top: var(--spacing-2);
        }
        .badge-market {
            background-color: var(--color-primary-light);
            color: var(--color-primary-dark);
            border: 1px solid rgba(0, 102, 204, 0.15);
            padding: 4px 8px;
            border-radius: var(--radius-sm);
            font-size: var(--fs-xs);
            font-weight: var(--fw-semibold);
        }
        .custom-file-upload {
            border: 1px dashed var(--color-border);
            border-radius: var(--radius-sm);
            padding: var(--spacing-4);
            text-align: center;
            background-color: #ffffff;
            cursor: pointer;
            transition: all var(--transition-fast);
        }
        .custom-file-upload:hover {
            border-color: var(--color-primary);
            background-color: var(--color-primary-light);
        }
    </style>
@endsection

@section('content')
<!-- Breadcrumbs -->
<nav class="breadcrumbs" style="margin-bottom: var(--spacing-2); margin-top: 0;">
    <a href="{{ route('admin.dashboard') }}">Console</a>
    <a href="{{ route('services.index') }}">Services</a>
    <span>Marketplace & Retail Services</span>
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

    $totalStepsCount = 14;
    $completedStepsCount = 0;
    $stepChecklist = [
        1 => !empty($selectedMarketplaces),
        2 => $activeAccountsCount > 0 || count($accounts) > 0,
        3 => !empty($payload['store_name']),
        4 => !empty($payload['verification_status']),
        5 => count($products) > 0,
        6 => count($listings) > 0,
        7 => count($optimizations) > 0,
        8 => count($pricings) > 0,
        9 => count($inventories) > 0,
        10 => !empty($payload['launch_status']),
        11 => count($orders) > 0,
        12 => count($campaigns) > 0,
        13 => !empty($payload['physical_retail_required']),
        14 => count($retailers) > 0 || ($status === 'completed'),
    ];
    foreach($stepChecklist as $isDone) {
        if ($isDone) $completedStepsCount++;
    }
    $percentage = round(($completedStepsCount / $totalStepsCount) * 100);
@endphp

<!-- Tabs Navigation -->
<div class="tabs-navigation" style="margin-bottom: 0;">
    <button class="tab-btn {{ $status !== 'completed' ? 'active' : '' }}" id="tab-btn-wizard" onclick="switchMainTab('wizard')" style="{{ $status === 'completed' ? 'display: none;' : '' }}">Setup Stepper</button>
    <button class="tab-btn {{ $status === 'completed' ? 'active' : '' }}" id="tab-btn-overview" onclick="switchMainTab('overview')" style="{{ $status !== 'completed' ? 'display: none;' : '' }}">Overview Dashboard</button>
</div>

<!-- TAB: OVERVIEW DASHBOARD -->
<div id="tab-content-overview" class="tab-content" style="{{ $status === 'completed' ? 'display:block;' : 'display:none;' }}">
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
        <!-- SERVICE OVERVIEW PANEL -->
        <div class="progress-banner" style="margin-top: var(--spacing-3);">
            <div class="progress-banner-header">
                <div>
                    <h3 style="font-size: var(--fs-lg); font-weight: var(--fw-bold); margin-bottom: 2px;">Marketplace & Retail Progress</h3>
                    <p style="font-size: var(--fs-sm); color: var(--color-text-secondary);">Setup, listings, inventory & store status</p>
                </div>
                <div class="progress-percentage">{{ $percentage }}%</div>
            </div>
            <div class="progress-bar-outer">
                <div class="progress-bar-inner" style="width: {{ $percentage }}%;"></div>
            </div>
        </div>

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
        </div>{{-- /stats-panel-row --}}
    @endif
</div>{{-- /tab-content-overview --}}

<!-- VIEW DETAILS MODAL -->
<div id="view-details-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-content" style="background: var(--color-bg-base); width: 95%; max-width: 850px; max-height: 90vh; border-radius: var(--radius-xl); padding: 0; overflow: hidden; position: relative; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); display: flex; flex-direction: column;">
        
        <!-- Modal Header -->
        <div style="padding: var(--spacing-5) var(--spacing-6); border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; background: var(--color-bg-alt);">
            <div>
                <h2 style="font-size: var(--fs-lg); font-weight: var(--fw-bold); color: var(--color-text-primary); margin: 0;">Marketplace & Retail Summary</h2>
                <p style="font-size: var(--fs-sm); color: var(--color-text-secondary); margin: 4px 0 0 0;">Review all submitted details for this service.</p>
            </div>
            <button type="button" onclick="document.getElementById('view-details-modal').style.display='none'" style="background: var(--color-bg-base); border: 1px solid var(--color-border); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: var(--fs-lg); cursor: pointer; color: var(--color-text-secondary); transition: all 0.2s ease;">&times;</button>
        </div>

        <!-- Modal Body -->
        <div style="padding: var(--spacing-6); overflow-y: auto; background: var(--color-bg-base); display: flex; flex-direction: column; gap: var(--spacing-5);">
            
            <div class="form-grid-2">
                <!-- Stat Card 1 -->
                <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                    <div style="width: 48px; height: 48px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div style="font-size: var(--fs-2xl); font-weight: var(--fw-bold); color: var(--color-text-primary);">{{ count($selectedMarketplaces) }}</div>
                    <div style="font-size: var(--fs-sm); color: var(--color-text-secondary); font-weight: var(--fw-medium); margin-top: 4px;">Marketplaces Selected</div>
                </div>

                <!-- Stat Card 2 -->
                <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                    <div style="width: 48px; height: 48px; background: var(--color-success-light); color: var(--color-success); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                    <div style="font-size: var(--fs-2xl); font-weight: var(--fw-bold); color: var(--color-text-primary);">{{ $activeListingsCount }}</div>
                    <div style="font-size: var(--fs-sm); color: var(--color-text-secondary); font-weight: var(--fw-medium); margin-top: 4px;">Active Listings</div>
                </div>
            </div>

            <!-- Retail Overview -->
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">13</div>
                    Retail Coordination
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Total Prospects</div><div style="font-weight: var(--fw-medium);">{{ count($retailers) }} Stores</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Target Countries</div><div style="font-weight: var(--fw-medium);">{{ count($targetCountries) }} Locations</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Selling Models</div>
                        <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                            @foreach($sellingModels as $model)
                                <span class="badge" style="background: var(--color-bg-base); border: 1px solid var(--color-border); font-size: 10px;">{{ $model }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
        <!-- Modal Footer -->
        <div style="padding: var(--spacing-4) var(--spacing-6); border-top: 1px solid var(--color-border); background: var(--color-bg-base); display: flex; justify-content: flex-end;">
            <button class="btn btn-secondary" onclick="document.getElementById('view-details-modal').style.display='none'">Close</button>
        </div>
    </div>
</div>

<!-- TAB: SETUP WIZARD -->
<div id="tab-content-wizard" class="tab-content {{ $status !== 'completed' ? 'active' : '' }}" style="{{ $status === 'completed' ? 'display:none;' : 'display:block;' }}">

<!-- Mobile Step Status Bar -->
<div class="mobile-step-indicator" style="display: flex; align-items: center; justify-content: space-between; background-color: #ffffff; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 10px 14px; margin-bottom: var(--spacing-3); font-size: var(--fs-xs); box-shadow: var(--shadow-card);">
    <div style="display: flex; align-items: center; gap: 6px;">
        <span style="color: var(--color-text-muted);">Step {{ $currentStep }} of 14:</span>
        <strong style="color: var(--color-primary);" id="mobile-step-name">
            {{ $stepTitles[$currentStep] ?? 'Selection' }}
        </strong>
    </div>
    <span class="badge badge-success">{{ $percentage }}% Done</span>
</div>

<!-- Dynamic Stepper -->
<div style="position: relative; width: 100%; margin-top: var(--spacing-2); margin-bottom: var(--spacing-3);">
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
<div class="card" style="padding: var(--spacing-5) var(--spacing-6); margin-bottom: 80px;">

    <!-- ================== STEP 1: MARKETPLACE SELECTION ================== -->
    <div id="step-form-container-1" class="step-form-content {{ $currentStep == 1 ? 'active' : '' }}" style="display: {{ $currentStep == 1 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Marketplace Selection</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Select the marketplaces you plan to launch on and outline your primary target parameters.</p>

        <!-- Summary Section showing Selected Marketplaces -->
        @if(!empty($selectedMarketplaces))
            <div class="card" style="padding: var(--spacing-4); margin-bottom: var(--spacing-6); border-left: 4px solid var(--color-primary); background-color: var(--color-bg-base);">
                <strong style="font-size: 10px; text-transform: uppercase; color: var(--color-text-secondary); display: block; margin-bottom: var(--spacing-2); letter-spacing: 0.05em;">Selected Marketplaces Summary</strong>
                <div class="summary-badge-list">
                    @foreach($selectedMarketplaces as $market)
                        <span class="badge-market">✓ {{ $market }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST">
            @csrf
            <input type="hidden" name="step" value="1">
            <input type="hidden" name="action" id="step-1-action" value="save_continue">

            <div class="form-group">
                <label class="form-label">Marketplace Type (Select multiple) <span style="color: var(--color-danger);">*</span></label>
                <div class="selection-grid">
                    @foreach(['Amazon', 'Walmart', 'TikTok Shop', 'eBay', 'Shopify', 'Meta Shops', 'Google', 'Other'] as $market)
                        <div class="selection-card {{ in_array($market, $selectedMarketplaces) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'mk-{{ \Str::slug($market) }}')">
                            <input type="checkbox" name="selected_marketplaces[]" id="mk-{{ \Str::slug($market) }}" value="{{ $market }}" class="selection-checkbox" {{ in_array($market, $selectedMarketplaces) ? 'checked' : '' }}>
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
                        <div class="selection-card {{ in_array($model, $sellingModels) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'model-{{ \Str::slug($model) }}')">
                            <input type="checkbox" name="selling_models[]" id="model-{{ \Str::slug($model) }}" value="{{ $model }}" class="selection-checkbox" {{ in_array($model, $sellingModels) ? 'checked' : '' }}>
                            <span class="selection-card-title">{{ $model }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Target Countries / Markets <span style="color: var(--color-danger);">*</span></label>
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
    <div id="step-form-container-2" class="step-form-content {{ $currentStep == 2 ? 'active' : '' }}" style="display: {{ $currentStep == 2 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Account Setup</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage account credentials and status updates for your selected marketplaces.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-2-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="step" value="2">
            <input type="hidden" name="action" id="step-2-action" value="save_continue">

            <!-- Hidden Inputs Container for Dynamic Array -->
            <div id="accounts-hidden-inputs-container"></div>
            <div id="accounts-files-container" style="display: none;"></div>

            @if(empty($selectedMarketplaces))
                <div class="alert alert-warning" style="margin-bottom: var(--spacing-6);">
                    Please select at least one marketplace in Step 1 first.
                </div>
            @else
                @foreach($selectedMarketplaces as $market)
                    @php $slug = \Str::slug($market); @endphp
                    <div class="card" style="padding: var(--spacing-6); margin-bottom: var(--spacing-6); border-color: var(--color-border); background-color: #ffffff;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4); border-bottom: 1px solid var(--color-border); padding-bottom: var(--spacing-2);">
                            <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-primary);">
                                {{ $market }} Accounts
                            </h3>
                            <button type="button" class="btn btn-primary" style="height: 28px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('account_{{ $slug }}')">
                                + Add {{ $market }} Account
                            </button>
                        </div>

                        <!-- Dynamic Add Account Form for this specific Marketplace -->
                        <div id="add-account_{{ $slug }}-form" class="inline-form-card" style="display: none;">
                            <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary-dark);">New {{ $market }} Account</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Seller / Store Name</label>
                                    <input type="text" id="acc_seller_name_{{ $slug }}" class="form-control" placeholder="e.g. Apex Store" style="height: 36px;">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Account Status</label>
                                    <select id="acc_account_status_{{ $slug }}" class="form-control" style="height: 36px;">
                                        @foreach(['Not Started', 'In Progress', 'Submitted', 'Under Review', 'Approved', 'Rejected', 'Active'] as $st)
                                            <option value="{{ $st }}">{{ $st }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Account Email</label>
                                    <input type="email" id="acc_account_email_{{ $slug }}" class="form-control" placeholder="seller@company.com" style="height: 36px;">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Account ID / Seller ID</label>
                                    <input type="text" id="acc_account_id_{{ $slug }}" class="form-control" placeholder="Seller ID" style="height: 36px;">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Store URL</label>
                                    <input type="url" id="acc_store_url_{{ $slug }}" class="form-control" placeholder="https://..." style="height: 36px;">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Account Created Date</label>
                                    <input type="date" id="acc_created_date_{{ $slug }}" class="form-control" style="height: 36px;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Account Documents (Multiple upload)</label>
                                <input type="file" id="acc_documents_{{ $slug }}" multiple class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Account Notes</label>
                                <textarea id="acc_notes_{{ $slug }}" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                            </div>
                            <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                                <button type="button" class="btn btn-secondary" onclick="hideAddForm('account_{{ $slug }}')">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="addMarketplaceAccount('{{ $market }}', '{{ $slug }}')">Save Account</button>
                            </div>
                        </div>

                        <!-- Accounts list for this marketplace -->
                        <div class="table-responsive">
                            <table class="table" id="accounts-{{ $slug }}-table">
                                <thead>
                                    <tr>
                                        <th>Seller Name</th>
                                        <th>Email</th>
                                        <th>Account ID</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Documents</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populated via renderTable -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @endif

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
    <div id="step-form-container-3" class="step-form-content {{ $currentStep == 3 ? 'active' : '' }}" style="display: {{ $currentStep == 3 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Store Setup</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Enter store profile information, assets, categories, and policies for your principal storefront.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" enctype="multipart/form-data">
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

            <!-- Uploads -->
            <div class="form-row" style="margin-top: var(--spacing-4);">
                <div class="form-group">
                    <label class="form-label">Store Logo</label>
                    <input type="file" name="store_logo" class="form-control">
                    @if(!empty($payload['store_logo']['url']))
                        <div style="margin-top: 6px; font-size: var(--fs-xs);">
                            Uploaded: <a href="{{ $payload['store_logo']['url'] }}" target="_blank" style="color: var(--color-primary); font-weight: var(--fw-semibold);">{{ $payload['store_logo']['name'] }}</a>
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label class="form-label">Store Banner</label>
                    <input type="file" name="store_banner" class="form-control">
                    @if(!empty($payload['store_banner']['url']))
                        <div style="margin-top: 6px; font-size: var(--fs-xs);">
                            Uploaded: <a href="{{ $payload['store_banner']['url'] }}" target="_blank" style="color: var(--color-primary); font-weight: var(--fw-semibold);">{{ $payload['store_banner']['name'] }}</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group" style="margin-top: var(--spacing-4);">
                <label class="form-label">Brand Assets (Multiple file upload)</label>
                <input type="file" name="brand_assets[]" multiple class="form-control">
                @if(!empty($payload['brand_assets']))
                    <div style="margin-top: 8px;">
                        <strong style="font-size: var(--fs-xs); color: var(--color-text-secondary);">Uploaded Assets:</strong>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 4px;">
                            @foreach($payload['brand_assets'] as $asset)
                                <a href="{{ $asset['url'] }}" target="_blank" class="badge-market" style="background-color: var(--color-bg-base); border-color: var(--color-border); color: var(--color-text-primary);">
                                    📎 {{ $asset['name'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="form-group" style="margin-top: var(--spacing-4);">
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
    <div id="step-form-container-4" class="step-form-content {{ $currentStep == 4 ? 'active' : '' }}" style="display: {{ $currentStep == 4 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Business Verification</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Submit and manage registration and verification files required by your marketplaces.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-4-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="step" value="4">
            <input type="hidden" name="action" id="step-4-action" value="save_continue">

            <div id="verifications-hidden-inputs-container"></div>
            <div id="verifications-files-container" style="display: none;"></div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Marketplace <span style="color: var(--color-danger);">*</span></label>
                    <select name="verification_marketplace" class="form-control" style="height: 38px;" required>
                        <option value="">Select Marketplace</option>
                        @foreach($selectedMarketplaces as $m)
                            <option value="{{ $m }}" {{ old('verification_marketplace', $payload['verification_marketplace'] ?? '') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
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

            <!-- Dynamic Verification Documents List -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Verification Documents Checklist</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('verification')">
                    + Add Document Requirement
                </button>
            </div>

            <!-- Add Verification Document Form -->
            <div id="add-verification-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Document Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Document Name</label>
                        <input type="text" id="vd_name" class="form-control" placeholder="e.g. Utility Bill" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Document Type</label>
                        <select id="vd_type" class="form-control" style="height: 36px;">
                            <option value="Utility Bill">Utility Bill</option>
                            <option value="Passport / ID">Passport / ID</option>
                            <option value="Tax Registration Certificate">Tax Registration Certificate</option>
                            <option value="Certificate of Incorporation">Certificate of Incorporation</option>
                            <option value="Bank Statement">Bank Statement</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Document File Upload</label>
                        <input type="file" id="vd_file" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Document Status</label>
                        <select id="vd_status" class="form-control" style="height: 36px;">
                            @foreach(['Missing', 'Uploaded', 'Submitted', 'Approved', 'Rejected'] as $ds)
                                <option value="{{ $ds }}">{{ $ds }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('verification')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addVerificationDoc()">Save Document</button>
                </div>
            </div>

            <!-- Documents Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="verifications-table">
                    <thead>
                        <tr>
                            <th>Document Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Uploaded File</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
    <div id="step-form-container-5" class="step-form-content {{ $currentStep == 5 ? 'active' : '' }}" style="display: {{ $currentStep == 5 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Product Catalog</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Register and manage products that will be listed on your selected marketplaces.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-5-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="step" value="5">
            <input type="hidden" name="action" id="step-5-action" value="save_continue">

            <div id="products-hidden-inputs-container"></div>
            <div id="products-files-container" style="display: none;"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Products Table</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('product')">
                    + Add Product
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
                        <input type="text" id="prod_variants" class="form-control" placeholder="e.g. Large, Red" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">UPC / GTIN</label>
                        <input type="text" id="prod_upc_gtin" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Dimensions (L x W x H inches)</label>
                        <div style="display: flex; gap: 4px;">
                            <input type="number" step="0.1" id="prod_dim_l" class="form-control" placeholder="L" style="height: 36px; text-align: center;">
                            <input type="number" step="0.1" id="prod_dim_w" class="form-control" placeholder="W" style="height: 36px; text-align: center;">
                            <input type="number" step="0.1" id="prod_dim_h" class="form-control" placeholder="H" style="height: 36px; text-align: center;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Product Weight (lbs)</label>
                        <input type="number" step="0.01" id="prod_product_weight" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Product Cost ($)</label>
                        <input type="number" step="0.01" id="prod_product_cost" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Target Selling Price ($)</label>
                        <input type="number" step="0.01" id="prod_target_selling_price" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Inventory Quantity</label>
                        <input type="number" id="prod_inventory_quantity" class="form-control" style="height: 36px;">
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
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Product Images (Multiple upload)</label>
                        <input type="file" id="prod_images" multiple class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Product Video (Optional upload)</label>
                        <input type="file" id="prod_video" class="form-control">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('product')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addProductToCatalog()">Save Product</button>
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
                            <th>Price</th>
                            <th>Inventory</th>
                            <th>Files</th>
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
    <div id="step-form-container-6" class="step-form-content {{ $currentStep == 6 ? 'active' : '' }}" style="display: {{ $currentStep == 6 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Product Listing</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Publish product listings tailored to your active marketplaces.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-6-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="step" value="6">
            <input type="hidden" name="action" id="step-6-action" value="save_continue">

            <div id="listings-hidden-inputs-container"></div>
            <div id="listings-files-container" style="display: none;"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Listings Table</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('listing')">
                    + Add Listing
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
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Marketplace</label>
                        <select id="list_marketplace" class="form-control" style="height: 36px;">
                            @foreach($selectedMarketplaces as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
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
                    <label class="form-label">Listing Description (Rich specifications)</label>
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
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Listing Images (Multiple upload)</label>
                        <input type="file" id="list_images" multiple class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bullet Points (Separate with commas)</label>
                        <input type="text" id="list_bullet_points" class="form-control" placeholder="Point 1, Point 2, Point 3" style="height: 36px;">
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
                    <button type="button" class="btn btn-primary" onclick="addListingRecord()">Save Listing</button>
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
                            <th>Files</th>
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
    <div id="step-form-container-7" class="step-form-content {{ $currentStep == 7 ? 'active' : '' }}" style="display: {{ $currentStep == 7 ? 'none' : 'none' }};">
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
                    + Add Optimization
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
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Primary Keyword</label>
                        <input type="text" id="opt_primary_keyword" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">SEO Keywords / Tags (Comma separated)</label>
                        <input type="text" id="opt_seo_keywords" class="form-control" placeholder="keyword1, keyword2" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Secondary Keywords (Comma separated)</label>
                        <input type="text" id="opt_secondary_keywords" class="form-control" style="height: 36px;">
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
                    <button type="button" class="btn btn-primary" onclick="addOptimizationRecord()">Save Optimization</button>
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
    <div id="step-form-container-8" class="step-form-content {{ $currentStep == 8 ? 'active' : '' }}" style="display: {{ $currentStep == 8 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Pricing Setup</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage and scheduler pricing strategies, base values, minimums/maximums, and discounts.</p>

        <!-- Dynamic Pricing Summary Card -->
        <div class="card" style="padding: var(--spacing-4); margin-bottom: var(--spacing-6); background-color: var(--color-bg-base); border: 1px solid var(--color-border); border-left: 4px solid var(--color-primary);">
            <h3 style="font-size: var(--fs-xs); text-transform: uppercase; color: var(--color-text-secondary); margin-bottom: var(--spacing-2); font-weight: var(--fw-semibold);">Interactive Pricing Calculator</h3>
            <div class="form-row" style="margin-bottom: 0;">
                <div style="font-size: var(--fs-xs);">Base Cost: <strong id="calc_base_cost">$0.00</strong></div>
                <div style="font-size: var(--fs-xs);">Marketplace Price: <strong id="calc_market_price">$0.00</strong></div>
                <div style="font-size: var(--fs-xs);">Discount: <strong id="calc_discount">$0.00</strong></div>
                <div style="font-size: var(--fs-xs);">Final Price: <strong id="calc_final_price" style="color: var(--color-primary-dark); font-weight: var(--fw-bold);">$0.00</strong></div>
            </div>
        </div>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-8-form">
            @csrf
            <input type="hidden" name="step" value="8">
            <input type="hidden" name="action" id="step-8-action" value="save_continue">

            <div id="pricings-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Pricings Table</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('pricing')">
                    + Add Pricing
                </button>
            </div>

            <!-- Dynamic Form for Adding Pricing -->
            <div id="add-pricing-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Pricing Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select Product</label>
                        <select id="pr_product_sku" class="form-control" style="height: 36px;" onchange="syncPricingCost(this.value)">
                            <option value="">Select Product</option>
                            @foreach($products as $p)
                                <option value="{{ $p['sku'] }}" data-cost="{{ $p['product_cost'] }}">{{ $p['product_name'] }} ({{ $p['sku'] }})</option>
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
                        <input type="number" step="0.01" id="pr_base_price" class="form-control" style="height: 36px;" oninput="runPricingCalc()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Marketplace Price ($)</label>
                        <input type="number" step="0.01" id="pr_marketplace_price" class="form-control" style="height: 36px;" oninput="runPricingCalc()">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Discount Type</label>
                        <select id="pr_discount_type" class="form-control" style="height: 36px;" onchange="runPricingCalc()">
                            <option value="Percentage">Percentage (%)</option>
                            <option value="Fixed Amount">Fixed Amount ($)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Discount Value</label>
                        <input type="number" step="0.01" id="pr_discount_value" class="form-control" style="height: 36px;" value="0" oninput="runPricingCalc()">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Sale / Final Price ($)</label>
                        <input type="number" step="0.01" id="pr_sale_price" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Minimum Price ($)</label>
                        <input type="number" step="0.01" id="pr_minimum_price" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Maximum Price ($)</label>
                        <input type="number" step="0.01" id="pr_maximum_price" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pricing Status</label>
                        <select id="pr_pricing_status" class="form-control" style="height: 36px;">
                            <option value="Draft">Draft</option>
                            <option value="Active">Active</option>
                            <option value="Scheduled">Scheduled</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Discount Start Date</label>
                        <input type="date" id="pr_start_date" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Discount End Date</label>
                        <input type="date" id="pr_end_date" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('pricing')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addPricingRecord()">Save Pricing</button>
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
                            <th>Sale Price</th>
                            <th>Min/Max</th>
                            <th>Discount</th>
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
    <div id="step-form-container-9" class="step-form-content {{ $currentStep == 9 ? 'active' : '' }}" style="display: {{ $currentStep == 9 ? 'none' : 'none' }};">
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
                    + Add Inventory
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
                        <label class="form-label">Reserved Quantity</label>
                        <input type="number" id="inv_reserved_quantity" class="form-control" style="height: 36px;" value="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reorder Level (Threshold)</label>
                        <input type="number" id="inv_reorder_level" class="form-control" style="height: 36px;" value="10">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Warehouse / Location</label>
                        <select id="inv_warehouse_location" class="form-control" style="height: 36px;">
                            <option value="US East (NY)">US East (NY)</option>
                            <option value="US West (LA)">US West (LA)</option>
                            <option value="Europe (DE)">Europe (DE)</option>
                            <option value="FBA Warehouse">FBA Warehouse</option>
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
                        <label class="form-label">Inventory Notes</label>
                        <input type="text" id="inv_notes" class="form-control" placeholder="Optional comments..." style="height: 36px;">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('inventory')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addInventoryRecord()">Save Inventory</button>
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
                            <th>Reserved Qty</th>
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
    <div id="step-form-container-10" class="step-form-content {{ $currentStep == 10 ? 'active' : '' }}" style="display: {{ $currentStep == 10 ? 'none' : 'none' }};">
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
                <div class="form-grid-2" style="margin-top: var(--spacing-2);">
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
    <div id="step-form-container-11" class="step-form-content {{ $currentStep == 11 ? 'active' : '' }}" style="display: {{ $currentStep == 11 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Order Management</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Track marketplace sales, order details, delivery status, and carrier tracking info.</p>

        <!-- Filters Section -->
        <div class="card" style="padding: var(--spacing-4); margin-bottom: var(--spacing-6); background-color: var(--color-bg-base); border: 1px solid var(--color-border);">
            <h3 style="font-size: var(--fs-xs); text-transform: uppercase; color: var(--color-text-secondary); margin-bottom: var(--spacing-3); font-weight: var(--fw-semibold);">Filter Orders</h3>
            <div class="form-grid-4">
                <div>
                    <label class="form-label" style="font-size: 10px;">Marketplace</label>
                    <select id="filter_ord_marketplace" class="form-control" style="height: 32px;" onchange="runOrderFilters()">
                        <option value="">All Marketplaces</option>
                        @foreach($selectedMarketplaces as $m)
                            <option value="{{ $m }}">{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label" style="font-size: 10px;">Status</label>
                    <select id="filter_ord_status" class="form-control" style="height: 32px;" onchange="runOrderFilters()">
                        <option value="">All Statuses</option>
                        <option value="New">New</option>
                        <option value="Processing">Processing</option>
                        <option value="Shipped">Shipped</option>
                        <option value="Delivered">Delivered</option>
                    </select>
                </div>
                <div>
                    <label class="form-label" style="font-size: 10px;">Product SKU</label>
                    <select id="filter_ord_product" class="form-control" style="height: 32px;" onchange="runOrderFilters()">
                        <option value="">All Products</option>
                        @foreach($products as $p)
                            <option value="{{ $p['sku'] }}">{{ $p['sku'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label" style="font-size: 10px;">Order Date</label>
                    <input type="date" id="filter_ord_date" class="form-control" style="height: 32px;" oninput="runOrderFilters()">
                </div>
            </div>
        </div>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-11-form">
            @csrf
            <input type="hidden" name="step" value="11">
            <input type="hidden" name="action" id="step-11-action" value="save_continue">

            <div id="orders-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Orders Table</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('order')">
                    + Add Order
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
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tracking Number</label>
                        <input type="text" id="ord_tracking_number" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Carrier</label>
                        <input type="text" id="ord_carrier" class="form-control" placeholder="e.g. FedEx" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tracking URL</label>
                        <input type="url" id="ord_tracking_url" class="form-control" placeholder="https://..." style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Order Notes</label>
                        <input type="text" id="ord_notes" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('order')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addOrderRecord()">Save Order</button>
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
                            <th>Tracking</th>
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
    <div id="step-form-container-12" class="step-form-content {{ $currentStep == 12 ? 'active' : '' }}" style="display: {{ $currentStep == 12 ? 'none' : 'none' }};">
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
                    + Add Campaign
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
                        <label class="form-label">Monthly Budget ($)</label>
                        <input type="number" step="0.01" id="camp_monthly_budget" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="date" id="camp_start_date" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Date</label>
                        <input type="date" id="camp_end_date" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Campaign Goal</label>
                        <select id="camp_campaign_goal" class="form-control" style="height: 36px;">
                            <option value="Sales">Sales</option>
                            <option value="Traffic">Traffic</option>
                            <option value="Product Launch">Product Launch</option>
                            <option value="Brand Awareness">Brand Awareness</option>
                        </select>
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
                </div>
                <div class="form-group">
                    <label class="form-label">Target Products (Select multiple)</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 4px;">
                        @foreach($products as $p)
                            <label style="font-size: var(--fs-xs); display: flex; align-items: center; gap: 6px; cursor: pointer;">
                                <input type="checkbox" name="camp_target_products_check" value="{{ $p['sku'] }}" style="accent-color: var(--color-primary);">
                                {{ $p['product_name'] }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Campaign Notes</label>
                    <textarea id="camp_notes" class="form-control" rows="2"></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('campaign')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addCampaignRecord()">Save Campaign</button>
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
                            <th>Daily Budget</th>
                            <th>Goal</th>
                            <th>Target SKU</th>
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
    <div id="step-form-container-13" class="step-form-content {{ $currentStep == 13 ? 'active' : '' }}" style="display: {{ $currentStep == 13 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Physical Retail Setup (Optional)</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Enable physical retail preparation for catalog products, pricing sheets, and retailer target requirements.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" enctype="multipart/form-data">
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

                <div class="form-row" style="margin-top: var(--spacing-4);">
                    <div class="form-group">
                        <label class="form-label">Product Catalog File Upload</label>
                        <input type="file" name="retail_catalog" class="form-control">
                        @if(!empty($payload['retail_catalog']['url']))
                            <div style="margin-top: 6px; font-size: var(--fs-xs);">
                                Uploaded: <a href="{{ $payload['retail_catalog']['url'] }}" target="_blank" style="color: var(--color-primary); font-weight: var(--fw-semibold);">{{ $payload['retail_catalog']['name'] }}</a>
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Wholesale Price List File</label>
                        <input type="file" name="retail_price_list" class="form-control">
                        @if(!empty($payload['retail_price_list']['url']))
                            <div style="margin-top: 6px; font-size: var(--fs-xs);">
                                Uploaded: <a href="{{ $payload['retail_price_list']['url'] }}" target="_blank" style="color: var(--color-primary); font-weight: var(--fw-semibold);">{{ $payload['retail_price_list']['name'] }}</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-row" style="margin-top: var(--spacing-4);">
                    <div class="form-group">
                        <label class="form-label">Target Retailers (Check multiple)</label>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 4px;">
                            @foreach(['Walmart', 'Target', 'Costco', 'Best Buy', 'Local Boutiques'] as $tr)
                                <label style="font-size: var(--fs-xs); display: flex; align-items: center; gap: 6px; cursor: pointer;">
                                    <input type="checkbox" name="target_retailers[]" value="{{ $tr }}" {{ in_array($tr, old('target_retailers', $payload['target_retailers'] ?? [])) ? 'checked' : '' }} style="accent-color: var(--color-primary);">
                                    {{ $tr }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Target Locations (Check multiple)</label>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 4px;">
                            @foreach(['New York', 'California', 'Texas', 'Florida', 'Midwest'] as $loc)
                                <label style="font-size: var(--fs-xs); display: flex; align-items: center; gap: 6px; cursor: pointer;">
                                    <input type="checkbox" name="target_locations[]" value="{{ $loc }}" {{ in_array($loc, old('target_locations', $payload['target_locations'] ?? [])) ? 'checked' : '' }} style="accent-color: var(--color-primary);">
                                    {{ $loc }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: var(--spacing-4);">
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
    <div id="step-form-container-14" class="step-form-content {{ $currentStep == 14 ? 'active' : '' }}" style="display: {{ $currentStep == 14 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Retailer / Distributor Coordination</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage connections with buyers, wholesalers, and physical distributors.</p>

        <form action="{{ route('services.save_step', 'marketplace-retail') }}" method="POST" id="step-14-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="step" value="14">
            <input type="hidden" name="action" id="step-14-action" value="save_continue">

            <div id="retailers-hidden-inputs-container"></div>
            <div id="retailers-files-container" style="display: none;"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Retailers List</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('retailer')">
                    + Add Retailer / Distributor
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
                        <label class="form-label">Website</label>
                        <input type="url" id="ret_website" class="form-control" placeholder="https://..." style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Location / Address</label>
                        <input type="text" id="ret_location" class="form-control" placeholder="e.g. New York, USA" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">MOQ Required</label>
                        <input type="number" id="ret_moq" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Target Wholesale Price ($)</label>
                        <input type="number" step="0.01" id="ret_wholesale_price" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Contact Date</label>
                        <input type="date" id="ret_contact_date" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Documents / Agreements Upload</label>
                        <input type="file" id="ret_agreements" multiple class="form-control">
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
                <div class="form-group">
                    <label class="form-label">Products Interested In (Select multiple)</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 4px;">
                        @foreach($products as $p)
                            <label style="font-size: var(--fs-xs); display: flex; align-items: center; gap: 6px; cursor: pointer;">
                                <input type="checkbox" name="ret_products_check" value="{{ $p['sku'] }}" style="accent-color: var(--color-primary);">
                                {{ $p['product_name'] }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea id="ret_notes" class="form-control" rows="2"></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('retailer')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addRetailerRecord()">Save Prospect</button>
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
                            <th>Email/Phone</th>
                            <th>Location</th>
                            <th>MOQ/Price</th>
                            <th>Status</th>
                            <th>Files</th>
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

</div>{{-- /tab-content-wizard --}}
@endsection

@section('dashboard_scripts')
<script>
    // Local data arrays from payload
    const listStore = {
        account: @json($accounts),
        product: @json($products),
        listing: @json($listings),
        optimization: @json($optimizations),
        pricing: @json($pricings),
        inventory: @json($inventories),
        order: @json($orders),
        campaign: @json($campaigns),
        retailer: @json($retailers),
        verification: @json($payload['verification_documents'] ?? [])
    };

    // Tracking uploaded dynamic inputs to append before form submit
    const fileInputsStore = {
        account: {},
        product: {},
        listing: {},
        retailer: {},
        verification: {}
    };

    // Scroll horizontal stepper
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

    // Show/Hide inline forms
    function showAddForm(type) {
        const form = document.getElementById(`add-${type}-form`);
        if (form) form.style.display = 'block';
    }
    function hideAddForm(type) {
        const form = document.getElementById(`add-${type}-form`);
        if (form) form.style.display = 'none';
    }

    // Dynamic dynamic multi-file input cloning helpers
    function moveAndStoreFiles(type, inputId, index, isMultiple = true) {
        const originalInput = document.getElementById(inputId);
        if (!originalInput || originalInput.files.length === 0) return;

        // Create container wrapper for this item index
        const filesContainer = document.getElementById(`${type}s-files-container`);
        if (!filesContainer) return;

        let itemWrapper = document.getElementById(`${type}-file-item-${index}`);
        if (!itemWrapper) {
            itemWrapper = document.createElement('div');
            itemWrapper.id = `${type}-file-item-${index}`;
            filesContainer.appendChild(itemWrapper);
        }

        // Clone/Move original input element
        const nameAttr = isMultiple ? `${type}s[${index}][${inputId.split('_')[1]}][]` : `${type}s[${index}][${inputId.split('_')[1]}]`;
        originalInput.name = nameAttr;
        originalInput.id = `${inputId}_stored_${index}`;
        itemWrapper.appendChild(originalInput);

        // Replace original input with a new blank file input
        const newInput = document.createElement('input');
        newInput.type = 'file';
        newInput.id = inputId;
        if (isMultiple) newInput.multiple = true;
        newInput.className = 'form-control';
        originalInput.parentNode.replaceChild(newInput, originalInput);
    }

    // Add items
    function addMarketplaceAccount(marketName, slug) {
        const index = listStore.account.length;
        const item = {
            marketplace_name: marketName,
            account_status: document.getElementById(`acc_account_status_${slug}`).value,
            seller_name: document.getElementById(`acc_seller_name_${slug}`).value,
            account_email: document.getElementById(`acc_account_email_${slug}`).value,
            account_id: document.getElementById(`acc_account_id_${slug}`).value,
            store_url: document.getElementById(`acc_store_url_${slug}`).value,
            created_date: document.getElementById(`acc_created_date_${slug}`).value,
            notes: document.getElementById(`acc_notes_${slug}`).value
        };

        if (!item.seller_name || !item.account_email) {
            alert('Seller Name and Account Email are required.');
            return;
        }

        // Store selected files
        moveAndStoreFiles('account', `acc_documents_${slug}`, index, true);

        listStore.account.push(item);
        renderTable('account');
        hideAddForm(`account_${slug}`);
    }

    function addVerificationDoc() {
        const index = listStore.verification.length;
        const item = {
            name: document.getElementById('vd_name').value,
            type: document.getElementById('vd_type').value,
            status: document.getElementById('vd_status').value
        };

        if (!item.name) {
            alert('Document Name is required.');
            return;
        }

        moveAndStoreFiles('verification', 'vd_file', index, false);

        listStore.verification.push(item);
        renderTable('verification');
        hideAddForm('verification');
    }

    function addProductToCatalog() {
        const index = listStore.product.length;
        const item = {
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
            product_status: document.getElementById('prod_product_status').value,
            dimensions_length: document.getElementById('prod_dim_l').value,
            dimensions_width: document.getElementById('prod_dim_w').value,
            dimensions_height: document.getElementById('prod_dim_h').value
        };

        if (!item.product_name || !item.sku) {
            alert('Product Name and SKU are required.');
            return;
        }

        moveAndStoreFiles('product', 'prod_images', index, true);
        moveAndStoreFiles('product', 'prod_video', index, false);

        listStore.product.push(item);
        renderTable('product');
        hideAddForm('product');
    }

    function addListingRecord() {
        const index = listStore.listing.length;
        const item = {
            product_sku: document.getElementById('list_product_sku').value,
            marketplace: document.getElementById('list_marketplace').value,
            listing_title: document.getElementById('list_listing_title').value,
            listing_description: document.getElementById('list_listing_description').value,
            category: document.getElementById('list_category').value,
            sku: document.getElementById('list_sku').value,
            marketplace_product_id: document.getElementById('list_marketplace_product_id').value,
            listing_status: document.getElementById('list_listing_status').value,
            bullet_points: document.getElementById('list_bullet_points').value.split(',').map(s => s.trim())
        };

        if (!item.listing_title || !item.sku) {
            alert('Listing Title and Store SKU are required.');
            return;
        }

        moveAndStoreFiles('listing', 'list_images', index, true);

        listStore.listing.push(item);
        renderTable('listing');
        hideAddForm('listing');
    }

    function addOptimizationRecord() {
        const item = {
            listing_id: document.getElementById('opt_listing_id').value,
            primary_keyword: document.getElementById('opt_primary_keyword').value,
            seo_keywords: document.getElementById('opt_seo_keywords').value,
            secondary_keywords: document.getElementById('opt_secondary_keywords').value,
            optimized_title: document.getElementById('opt_optimized_title').value,
            optimized_description: document.getElementById('opt_optimized_description').value,
            image_optimization_status: document.getElementById('opt_image_optimization_status').value,
            keyword_optimization_status: document.getElementById('opt_keyword_optimization_status').value,
            optimization_score: document.getElementById('opt_optimization_score').value,
            optimization_notes: document.getElementById('opt_optimization_notes').value
        };

        if (!item.primary_keyword || !item.optimized_title) {
            alert('Primary Keyword and Optimized Title are required.');
            return;
        }

        listStore.optimization.push(item);
        renderTable('optimization');
        hideAddForm('optimization');
    }

    function syncPricingCost(sku) {
        const select = document.getElementById('pr_product_sku');
        const cost = select.options[select.selectedIndex].getAttribute('data-cost') || 0;
        document.getElementById('pr_base_price').value = cost;
        runPricingCalc();
    }

    function runPricingCalc() {
        const base = parseFloat(document.getElementById('pr_base_price').value) || 0;
        const market = parseFloat(document.getElementById('pr_marketplace_price').value) || 0;
        const discType = document.getElementById('pr_discount_type').value;
        const discVal = parseFloat(document.getElementById('pr_discount_value').value) || 0;

        let discount = 0;
        if (discType === 'Percentage') {
            discount = market * (discVal / 100);
        } else {
            discount = discVal;
        }

        const sale = Math.max(market - discount, 0);

        // Display
        document.getElementById('pr_sale_price').value = sale.toFixed(2);
        document.getElementById('calc_base_cost').innerText = '$' + base.toFixed(2);
        document.getElementById('calc_market_price').innerText = '$' + market.toFixed(2);
        document.getElementById('calc_discount').innerText = '$' + discount.toFixed(2);
        document.getElementById('calc_final_price').innerText = '$' + sale.toFixed(2);
    }

    function addPricingRecord() {
        const item = {
            product_sku: document.getElementById('pr_product_sku').value,
            marketplace: document.getElementById('pr_marketplace').value,
            base_price: document.getElementById('pr_base_price').value,
            marketplace_price: document.getElementById('pr_marketplace_price').value,
            sale_price: document.getElementById('pr_sale_price').value,
            minimum_price: document.getElementById('pr_minimum_price').value,
            maximum_price: document.getElementById('pr_maximum_price').value,
            discount_type: document.getElementById('pr_discount_type').value,
            discount_value: document.getElementById('pr_discount_value').value,
            start_date: document.getElementById('pr_start_date').value,
            end_date: document.getElementById('pr_end_date').value,
            pricing_status: document.getElementById('pr_pricing_status').value
        };

        if (!item.product_sku || !item.marketplace_price) {
            alert('Product SKU and Marketplace Price are required.');
            return;
        }

        listStore.pricing.push(item);
        renderTable('pricing');
        hideAddForm('pricing');
    }

    function addInventoryRecord() {
        const item = {
            product_sku: document.getElementById('inv_product_sku').value,
            sku: document.getElementById('inv_sku').value,
            marketplace: document.getElementById('inv_marketplace').value,
            available_quantity: document.getElementById('inv_available_quantity').value,
            reserved_quantity: document.getElementById('inv_reserved_quantity').value,
            reorder_level: document.getElementById('inv_reorder_level').value,
            inventory_status: document.getElementById('inv_inventory_status').value,
            warehouse_location: document.getElementById('inv_warehouse_location').value,
            auto_inventory_sync: document.querySelector('input[name="auto_sync_field"]:checked').value,
            notes: document.getElementById('inv_notes').value
        };

        if (!item.product_sku || !item.available_quantity) {
            alert('Product and Available Quantity are required.');
            return;
        }

        // Prevent duplicate records for same company + product + marketplace
        const exists = listStore.inventory.some(i => i.product_sku === item.product_sku && i.marketplace === item.marketplace);
        if (exists) {
            alert('Inventory record already exists for this Product + Marketplace combination.');
            return;
        }

        listStore.inventory.push(item);
        renderTable('inventory');
        hideAddForm('inventory');
    }

    function addOrderRecord() {
        const item = {
            marketplace: document.getElementById('ord_marketplace').value,
            order_id: document.getElementById('ord_order_id').value,
            customer_name: document.getElementById('ord_customer_name').value,
            order_date: document.getElementById('ord_order_date').value,
            product_sku: document.getElementById('ord_product_sku').value,
            quantity: document.getElementById('ord_quantity').value,
            order_amount: document.getElementById('ord_order_amount').value,
            order_status: document.getElementById('ord_order_status').value,
            tracking_number: document.getElementById('ord_tracking_number').value,
            carrier: document.getElementById('ord_carrier').value,
            tracking_url: document.getElementById('ord_tracking_url').value,
            notes: document.getElementById('ord_notes').value
        };

        if (!item.order_id || !item.customer_name) {
            alert('Order ID and Customer Name are required.');
            return;
        }

        listStore.order.push(item);
        renderTable('order');
        hideAddForm('order');
    }

    function addCampaignRecord() {
        // Collect targeted products checked checkboxes
        const targets = [];
        document.querySelectorAll('input[name="camp_target_products_check"]:checked').forEach(c => {
            targets.push(c.value);
        });

        const item = {
            advertising_platform: document.getElementById('camp_advertising_platform').value,
            campaign_name: document.getElementById('camp_campaign_name').value,
            marketplace: document.getElementById('camp_marketplace').value,
            campaign_type: document.getElementById('camp_campaign_type').value,
            daily_budget: document.getElementById('camp_daily_budget').value,
            monthly_budget: document.getElementById('camp_monthly_budget').value,
            start_date: document.getElementById('camp_start_date').value,
            end_date: document.getElementById('camp_end_date').value,
            campaign_goal: document.getElementById('camp_campaign_goal').value,
            campaign_status: document.getElementById('camp_campaign_status').value,
            target_products: targets,
            notes: document.getElementById('camp_notes').value
        };

        if (!item.campaign_name || !item.daily_budget) {
            alert('Campaign Name and Daily Budget are required.');
            return;
        }

        listStore.campaign.push(item);
        renderTable('campaign');
        hideAddForm('campaign');
    }

    function addRetailerRecord() {
        const index = listStore.retailer.length;
        const targets = [];
        document.querySelectorAll('input[name="ret_products_check"]:checked').forEach(c => {
            targets.push(c.value);
        });

        const item = {
            retailer_name: document.getElementById('ret_retailer_name').value,
            type: document.getElementById('ret_type').value,
            contact_person: document.getElementById('ret_contact_person').value,
            email: document.getElementById('ret_email').value,
            phone: document.getElementById('ret_phone').value,
            status: document.getElementById('ret_status').value,
            website: document.getElementById('ret_website').value,
            location: document.getElementById('ret_location').value,
            moq: document.getElementById('ret_moq').value,
            wholesale_price: document.getElementById('ret_wholesale_price').value,
            contact_date: document.getElementById('ret_contact_date').value,
            products_interested: targets,
            notes: document.getElementById('ret_notes').value
        };

        if (!item.retailer_name || !item.contact_person) {
            alert('Retailer Name and Contact Person are required.');
            return;
        }

        moveAndStoreFiles('retailer', 'ret_agreements', index, true);

        listStore.retailer.push(item);
        renderTable('retailer');
        hideAddForm('retailer');
    }

    // Remove item helper
    function removeListItem(type, idx) {
        listStore[type].splice(idx, 1);
        renderTable(type);

        // Delete associated stored files if any
        const wrapper = document.getElementById(`${type}-file-item-${idx}`);
        if (wrapper) wrapper.remove();
    }

    // Table render engine
    function renderTable(type) {
        const tbody = document.querySelector(`#${type}s-table tbody`);
        
        // Custom check for step 2 marketplaces split tables
        if (type === 'account') {
            @if(!empty($selectedMarketplaces))
                @foreach($selectedMarketplaces as $market)
                    @php $slug = \Str::slug($market); @endphp
                    const tbody_{{ $slug }} = document.querySelector(`#accounts-{{ $slug }}-table tbody`);
                    if (tbody_{{ $slug }}) {
                        tbody_{{ $slug }}.innerHTML = '';
                        listStore.account.filter(a => a.marketplace_name === '{{ $market }}').forEach((item, idx) => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td><strong>${item.seller_name}</strong></td>
                                <td>${item.account_email}</td>
                                <td><code>${item.account_id}</code></td>
                                <td><span class="badge badge-info">${item.account_status}</span></td>
                                <td>${item.created_date}</td>
                                <td><span style="font-size: 10px;">${item.documents ? item.documents.length + ' file(s)' : '0 files'}</span></td>
                                <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('account', ${idx})">Delete</button></td>
                            `;
                            tbody_{{ $slug }}.appendChild(tr);
                        });
                    }
                @endforeach
            @endif
            updateHiddenInputs('accounts', listStore.account);
            return;
        }

        if (!tbody) return;
        tbody.innerHTML = '';
        const list = listStore[type];

        list.forEach((item, idx) => {
            const tr = document.createElement('tr');

            if (type === 'verification') {
                tr.innerHTML = `
                    <td><strong>${item.name}</strong></td>
                    <td>${item.type}</td>
                    <td><span class="badge badge-info">${item.status}</span></td>
                    <td><span style="font-size: 10px;">${item.file ? 'Uploaded File' : 'No file'}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('verification', ${idx})">Delete</button></td>
                `;
            } else if (type === 'product') {
                tr.innerHTML = `
                    <td><strong>${item.product_name}</strong></td>
                    <td><code>${item.sku}</code></td>
                    <td>${item.product_category}</td>
                    <td>$${parseFloat(item.target_selling_price).toFixed(2)}</td>
                    <td>${item.inventory_quantity}</td>
                    <td><span style="font-size: 10px;">Images: ${item.images ? item.images.length : 0} • Video: ${item.video ? 'Yes' : 'No'}</span></td>
                    <td><span class="badge badge-success">${item.product_status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('product', ${idx})">Delete</button></td>
                `;
            } else if (type === 'listing') {
                tr.innerHTML = `
                    <td><code>${item.product_sku}</code></td>
                    <td><strong>${item.marketplace}</strong></td>
                    <td>${item.listing_title}</td>
                    <td><code>${item.sku}</code></td>
                    <td><code>${item.marketplace_product_id}</code></td>
                    <td><span style="font-size: 10px;">Images: ${item.images ? item.images.length : 0}</span></td>
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
                    <td><strong>${item.marketplace}</strong></td>
                    <td>$${parseFloat(item.base_price).toFixed(2)}</td>
                    <td>$${parseFloat(item.marketplace_price).toFixed(2)}</td>
                    <td><strong>$${parseFloat(item.sale_price).toFixed(2)}</strong></td>
                    <td>Min: $${item.minimum_price} • Max: $${item.maximum_price}</td>
                    <td>${item.discount_value} (${item.discount_type})</td>
                    <td><span class="badge badge-success">${item.pricing_status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('pricing', ${idx})">Delete</button></td>
                `;
            } else if (type === 'inventory') {
                tr.innerHTML = `
                    <td><code>${item.product_sku}</code></td>
                    <td><code>${item.sku}</code></td>
                    <td><strong>${item.marketplace}</strong></td>
                    <td><strong>${item.available_quantity}</strong></td>
                    <td>${item.reserved_quantity}</td>
                    <td>${item.reorder_level}</td>
                    <td>${item.warehouse_location}</td>
                    <td><span class="badge badge-info">${item.auto_inventory_sync}</span></td>
                    <td><span class="badge badge-success">${item.inventory_status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('inventory', ${idx})">Delete</button></td>
                `;
            } else if (type === 'order') {
                tr.innerHTML = `
                    <td><code>${item.order_id}</code></td>
                    <td><strong>${item.marketplace}</strong></td>
                    <td>${item.customer_name}</td>
                    <td>${item.order_date}</td>
                    <td><code>${item.product_sku}</code></td>
                    <td>${item.quantity}</td>
                    <td><strong>$${parseFloat(item.order_amount).toFixed(2)}</strong></td>
                    <td><span class="badge badge-warning">${item.order_status}</span></td>
                    <td><code>${item.tracking_number || '-'}</code> (${item.carrier || '-'})</td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('order', ${idx})">Delete</button></td>
                `;
            } else if (type === 'campaign') {
                tr.innerHTML = `
                    <td><strong>${item.campaign_name}</strong></td>
                    <td>${item.advertising_platform}</td>
                    <td>${item.marketplace}</td>
                    <td>$${parseFloat(item.daily_budget).toFixed(2)}</td>
                    <td>${item.campaign_goal}</td>
                    <td><code>${item.target_products ? item.target_products.join(', ') : '-'}</code></td>
                    <td><span class="badge badge-success">${item.campaign_status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('campaign', ${idx})">Delete</button></td>
                `;
            } else if (type === 'retailer') {
                tr.innerHTML = `
                    <td><strong>${item.retailer_name}</strong></td>
                    <td>${item.type}</td>
                    <td>${item.contact_person}</td>
                    <td>${item.email} • ${item.phone}</td>
                    <td>${item.location}</td>
                    <td>MOQ: ${item.moq} • Wholesale: $${item.wholesale_price}</td>
                    <td><span class="badge badge-warning">${item.status}</span></td>
                    <td><span style="font-size: 10px;">Files: ${item.agreements ? item.agreements.length : 0}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('retailer', ${idx})">Delete</button></td>
                `;
            }

            tbody.appendChild(tr);
        });

        // Sync inputs
        updateHiddenInputs(type === 'verification' ? 'verification_documents' : 
                           type === 'account' ? 'accounts' : 
                           type === 'product' ? 'products' : 
                           type === 'listing' ? 'listings' : 
                           type === 'optimization' ? 'optimizations' : 
                           type === 'pricing' ? 'pricings' : 
                           type === 'inventory' ? 'inventories' : 
                           type === 'order' ? 'orders' : 
                           type === 'campaign' ? 'campaigns' : 'retailers', list);
    }

    // Run Order filters dynamically in the frontend!
    function runOrderFilters() {
        const m = document.getElementById('filter_ord_marketplace').value.toLowerCase();
        const s = document.getElementById('filter_ord_status').value.toLowerCase();
        const p = document.getElementById('filter_ord_product').value.toLowerCase();
        const d = document.getElementById('filter_ord_date').value;

        const rows = document.querySelectorAll('#orders-table tbody tr');
        rows.forEach(tr => {
            const cells = tr.getElementsByTagName('td');
            if (cells.length < 8) return;

            const orderMarket = cells[1].innerText.toLowerCase();
            const orderDate = cells[3].innerText;
            const orderProduct = cells[4].innerText.toLowerCase();
            const orderStatus = cells[7].innerText.toLowerCase();

            const matchMarket = !m || orderMarket.includes(m);
            const matchStatus = !s || orderStatus.includes(s);
            const matchProduct = !p || orderProduct.includes(p);
            const matchDate = !d || orderDate.includes(d);

            if (matchMarket && matchStatus && matchProduct && matchDate) {
                tr.style.display = '';
            } else {
                tr.style.display = 'none';
            }
        });
    }

    // Sync arrays to Laravel form elements
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
                input.value = Array.isArray(val) ? JSON.stringify(val) : val;
                container.appendChild(input);
            });
        });
    }

    // Toggle multi select cards
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
        for (let i = 1; i <= 14; i++) {
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

    const stepNames = {
        1: 'Selection',
        2: 'Account Setup',
        3: 'Store Setup',
        4: 'Verification',
        5: 'Product Catalog',
        6: 'Product Listing',
        7: 'Optimization',
        8: 'Pricing Setup',
        9: 'Inventory Setup',
        10: 'Launch Checklist',
        11: 'Orders',
        12: 'Advertising',
        13: 'Physical Retail',
        14: 'Coordination'
    };

    // Stepper wizard navigation Jumps
    function jumpToStep(stepNumber) {
        if (isEditMode && stepNumber !== editModeStep) return;

        for (let i = 1; i <= 14; i++) {
            const form = document.getElementById('step-form-container-' + i);
            if (form) {
                form.style.display = (i === stepNumber) ? 'block' : 'none';
            }

            const items = document.querySelectorAll('.stepper .step-item');
            if (items[i-1]) {
                if (i === stepNumber) {
                    items[i-1].classList.add('in-progress');
                    items[i-1].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                } else {
                    items[i-1].classList.remove('in-progress');
                }
            }
        }

        // Update mobile step indicator
        const mobileIndicator = document.getElementById('mobile-step-name');
        if (mobileIndicator && stepNames[stepNumber]) {
            mobileIndicator.innerText = stepNames[stepNumber];
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        initCustomMultiselect('target_countries_container');

        // Init tables
        renderTable('account');
        renderTable('verification');
        renderTable('product');
        renderTable('listing');
        renderTable('optimization');
        renderTable('pricing');
        renderTable('inventory');
        renderTable('order');
        renderTable('campaign');
        renderTable('retailer');
    });

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
