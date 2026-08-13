@extends('layouts.dashboard')

@section('title', 'Warehousing, Fulfillment & Logistics')

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
        .flow-pipeline-badge {
            background-color: var(--color-primary-light);
            color: var(--color-primary-dark);
            border: 1px solid rgba(0, 102, 204, 0.15);
            padding: 4px 8px;
            border-radius: var(--radius-sm);
            font-size: 10px;
            font-weight: var(--fw-semibold);
        }
        .tracking-timeline {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-4);
            padding: var(--spacing-4);
            background-color: var(--color-bg-base);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
        }
        .timeline-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
        }
        .timeline-dot {
            width: 12px;
            height: 12px;
            border-radius: var(--radius-full);
            background-color: var(--color-border);
            border: 2px solid #ffffff;
            box-shadow: 0 0 0 1px var(--color-border);
        }
        .timeline-dot.active {
            background-color: var(--color-primary);
            box-shadow: 0 0 0 2px var(--color-primary-light);
        }
        .timeline-label {
            font-size: var(--fs-xs);
            font-weight: var(--fw-medium);
            color: var(--color-text-secondary);
        }
        .timeline-label.active {
            color: var(--color-primary-dark);
            font-weight: var(--fw-bold);
        }
    </style>
@endsection

@section('content')
<!-- Breadcrumbs -->
<nav class="breadcrumbs" style="margin-bottom: var(--spacing-2); margin-top: 0;">
    <a href="{{ route('admin.dashboard') }}">Console</a>
    <a href="{{ route('services.index') }}">Services</a>
    <span>Fulfillment & Logistics</span>
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
        <h2 style="color: var(--color-success-dark); font-size: var(--fs-xl); font-weight: var(--fw-bold);">Logistics Operations Completed</h2>
        <p style="color: var(--color-success-dark); font-size: var(--fs-sm); margin-top: 2px;">Your fulfillment warehouse networks, active inventory stocks, and carrier channels are completely verified.</p>
    </div>
@endif

@php
    // Read list values from payload
    $serviceTypes = old('service_types', $payload['service_types'] ?? []);
    $productCategories = old('product_categories', $payload['product_categories'] ?? []);
    $shipments = old('shipments', $payload['shipments'] ?? []);
    $receivings = old('receivings', $payload['receivings'] ?? []);
    $verifications = old('verifications', $payload['verifications'] ?? []);
    $inspections = old('inspections', $payload['inspections'] ?? []);
    $storageRecords = old('storage_records', $payload['storage_records'] ?? []);
    $inventories = old('inventories', $payload['inventories'] ?? []);
    $orders = old('orders', $payload['orders'] ?? []);
    $picks = old('picks', $payload['picks'] ?? []);
    $labels = old('labels', $payload['labels'] ?? []);
    $carriers = old('carriers', $payload['carriers'] ?? []);
    $trackings = old('trackings', $payload['trackings'] ?? []);
    $deliveries = old('deliveries', $payload['deliveries'] ?? []);
    $returns = old('returns', $payload['returns'] ?? []);
    $inventoryUpdates = old('inventory_updates', $payload['inventory_updates'] ?? []);

    // Overview counters
    $warehouseCount = collect($inventories)->pluck('warehouse')->unique()->filter()->count();
    $incomingShipmentsCount = collect($shipments)->whereIn('status', ['Preparing', 'Booked', 'In Transit'])->count();
    $inventoryItemsCount = count($inventories);
    $pendingOrdersCount = collect($orders)->whereIn('status', ['New', 'Processing', 'Ready to Pick'])->count();
    $ordersInFulfillmentCount = collect($orders)->whereIn('status', ['Picked', 'Packed', 'Shipped'])->count();
    $shipmentsCount = count($shipments);
    $deliveriesCount = collect($deliveries)->where('status', 'Delivered')->count();
    $returnsCount = count($returns);

    $stepTitles = [
        1 => 'Requirements',
        2 => 'Planning',
        3 => 'Coordination',
        4 => 'Receiving',
        5 => 'Verification',
        6 => 'Inspection',
        7 => 'Storage',
        8 => 'Management',
        9 => 'Order Process',
        10 => 'Pick & Pack',
        11 => 'Labeling',
        12 => 'Carrier',
        13 => 'Tracking',
        14 => 'Delivery',
        15 => 'Returns',
        16 => 'Stock Update'
    ];
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
        <div class="stats-panel-row" style="margin-top: var(--spacing-3);">
            <div class="stat-card-mini">
                <span class="stat-card-title">Warehouses</span>
                <span class="stat-card-value">{{ $warehouseCount }}</span>
            </div>
            <div class="stat-card-mini">
                <span class="stat-card-title">Incoming Shipments</span>
                <span class="stat-card-value">{{ $incomingShipmentsCount }}</span>
            </div>
            <div class="stat-card-mini">
                <span class="stat-card-title">Inventory SKU Items</span>
                <span class="stat-card-value">{{ $inventoryItemsCount }}</span>
            </div>
            <div class="stat-card-mini">
                <span class="stat-card-title">Pending Orders</span>
                <span class="stat-card-value">{{ $pendingOrdersCount }}</span>
            </div>
            <div class="stat-card-mini">
                <span class="stat-card-title">Orders In-Fulfillment</span>
                <span class="stat-card-value">{{ $ordersInFulfillmentCount }}</span>
            </div>
            <div class="stat-card-mini">
                <span class="stat-card-title">Deliveries Sent</span>
                <span class="stat-card-value">{{ $deliveriesCount }}</span>
            </div>
            <div class="stat-card-mini">
                <span class="stat-card-title">Returns Handled</span>
                <span class="stat-card-value">{{ $returnsCount }}</span>
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
                <h2 style="font-size: var(--fs-lg); font-weight: var(--fw-bold); color: var(--color-text-primary); margin: 0;">Fulfillment & Logistics Summary</h2>
                <p style="font-size: var(--fs-sm); color: var(--color-text-secondary); margin: 4px 0 0 0;">Review all submitted details for this service.</p>
            </div>
            <button type="button" onclick="document.getElementById('view-details-modal').style.display='none'" style="background: var(--color-bg-base); border: 1px solid var(--color-border); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: var(--fs-lg); cursor: pointer; color: var(--color-text-secondary); transition: all 0.2s ease;">&times;</button>
        </div>

        <!-- Modal Body -->
        <div style="padding: var(--spacing-6); overflow-y: auto; background: var(--color-bg-base); display: flex; flex-direction: column; gap: var(--spacing-5);">
            
            <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--spacing-5);">
                <!-- Stat Card 1 -->
                <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                    <div style="width: 48px; height: 48px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <div style="font-size: var(--fs-2xl); font-weight: var(--fw-bold); color: var(--color-text-primary);">{{ $inventoryItemsCount }}</div>
                    <div style="font-size: var(--fs-sm); color: var(--color-text-secondary); font-weight: var(--fw-medium); margin-top: 4px;">Inventory SKUs Tracked</div>
                </div>

                <!-- Stat Card 2 -->
                <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                    <div style="width: 48px; height: 48px; background: var(--color-success-light); color: var(--color-success); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                    </div>
                    <div style="font-size: var(--fs-2xl); font-weight: var(--fw-bold); color: var(--color-text-primary);">{{ $deliveriesCount }}</div>
                    <div style="font-size: var(--fs-sm); color: var(--color-text-secondary); font-weight: var(--fw-medium); margin-top: 4px;">Deliveries Completed</div>
                </div>
            </div>

            <!-- Operations Overview -->
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                        </svg>
                    </div>
                    Operations Overview
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Service Types</div>
                        <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                            @foreach($serviceTypes as $type)
                                <span class="badge" style="background: var(--color-bg-base); border: 1px solid var(--color-border); font-size: 10px;">{{ $type }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Active Warehouses</div><div style="font-weight: var(--fw-medium);">{{ $warehouseCount }} Locations</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Pending Orders</div><div style="font-weight: var(--fw-medium);">{{ $pendingOrdersCount }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Returns Handled</div><div style="font-weight: var(--fw-medium);">{{ $returnsCount }}</div></div>
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
        <ol class="stepper" style="min-width: 2240px; padding-bottom: var(--spacing-2);">
            @foreach($stepTitles as $stepNum => $title)
                <li class="step-item {{ $currentStep == $stepNum ? 'in-progress' : ($currentStep > $stepNum ? 'completed' : 'not-started') }}" onclick="jumpToStep({{ $stepNum }})">
                    <div class="step-circle">{{ str_pad($stepNum, 2, '0', STR_PAD_LEFT) }}</div>
                    <span class="step-title">{{ $title }}</span>
                </li>
            @endforeach
        </ol>
    </div>
</div>

<!-- Wizard Container -->
<div class="card" style="padding: var(--spacing-5) var(--spacing-6); margin-bottom: 80px;">

    <!-- Operations Flow Pipeline -->
    <div class="card" style="padding: var(--spacing-4); margin-bottom: var(--spacing-6); background-color: var(--color-bg-base); border: 1px solid var(--color-border);">
        <h3 style="font-size: 10px; text-transform: uppercase; color: var(--color-text-secondary); margin-bottom: var(--spacing-3); font-weight: var(--fw-semibold); letter-spacing: 0.05em;">Fulfillment Operations Pipeline</h3>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
            <span class="flow-pipeline-badge">Supplier</span>
            <span style="color: var(--color-border);">→</span>
            <span class="flow-pipeline-badge">Shipment</span>
            <span style="color: var(--color-border);">→</span>
            <span class="flow-pipeline-badge">Receiving</span>
            <span style="color: var(--color-border);">→</span>
            <span class="flow-pipeline-badge">Verification</span>
            <span style="color: var(--color-border);">→</span>
            <span class="flow-pipeline-badge">Inspection</span>
            <span style="color: var(--color-border);">→</span>
            <span class="flow-pipeline-badge">Storage</span>
            <span style="color: var(--color-border);">→</span>
            <span class="flow-pipeline-badge">Inventory</span>
            <span style="color: var(--color-border);">→</span>
            <span class="flow-pipeline-badge">Order</span>
            <span style="color: var(--color-border);">→</span>
            <span class="flow-pipeline-badge">Pick & Pack</span>
            <span style="color: var(--color-border);">→</span>
            <span class="flow-pipeline-badge">Label</span>
            <span style="color: var(--color-border);">→</span>
            <span class="flow-pipeline-badge">Carrier</span>
            <span style="color: var(--color-border);">→</span>
            <span class="flow-pipeline-badge">Tracking</span>
            <span style="color: var(--color-border);">→</span>
            <span class="flow-pipeline-badge">Delivery</span>
            <span style="color: var(--color-border);">→</span>
            <span class="flow-pipeline-badge">Return</span>
            <span style="color: var(--color-border);">→</span>
            <span class="flow-pipeline-badge">Stock Update</span>
        </div>
    </div>

    <!-- ================== STEP 1: LOGISTICS REQUIREMENTS ================== -->
    <div id="step-form-container-1" class="step-form-content {{ $currentStep == 1 ? 'active' : '' }}" style="display: {{ $currentStep == 1 ? 'block' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Logistics Requirements</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Define your monthly fulfillment metrics, warehouse networks, and product category specifics.</p>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST">
            @csrf
            <input type="hidden" name="step" value="1">
            <input type="hidden" name="action" id="step-1-action" value="save_continue">

            <div class="form-group">
                <label class="form-label">Service Type (Select multiple) <span style="color: var(--color-danger);">*</span></label>
                <div class="selection-grid">
                    @foreach(['Warehousing', 'Fulfillment', 'Shipping', 'Returns', 'Inventory Management', 'Repackaging', 'Labeling'] as $srv)
                        <div class="selection-card {{ in_array($srv, $serviceTypes) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'st-{{ \Str::slug($srv) }}')">
                            <input type="checkbox" name="service_types[]" id="st-{{ \Str::slug($srv) }}" value="{{ $srv }}" class="selection-checkbox" {{ in_array($srv, $serviceTypes) ? 'checked' : '' }}>
                            <span class="selection-card-title">{{ $srv }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Warehouse Storage Required? <span style="color: var(--color-danger);">*</span></label>
                    <div class="segmented-control">
                        @php $whReq = old('warehouse_required', $payload['warehouse_required'] ?? 'yes'); @endphp
                        <input type="radio" name="warehouse_required" id="wh_yes" value="yes" class="segmented-option" {{ $whReq == 'yes' ? 'checked' : '' }}>
                        <label for="wh_yes" class="segmented-label">Yes</label>
                        <input type="radio" name="warehouse_required" id="wh_no" value="no" class="segmented-option" {{ $whReq == 'no' ? 'checked' : '' }}>
                        <label for="wh_no" class="segmented-label">No</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Preferred Warehouse Location <span style="color: var(--color-danger);">*</span></label>
                    <input type="text" name="preferred_location" class="form-control" placeholder="e.g. Los Angeles, CA" style="height: 38px;" value="{{ old('preferred_location', $payload['preferred_location'] ?? '') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Target Destination Country <span style="color: var(--color-danger);">*</span></label>
                    <select name="target_country" class="form-control" style="height: 38px;" required>
                        <option value="">Select Country</option>
                        @foreach(['US', 'CA', 'GB', 'DE', 'FR', 'AU', 'AE', 'PK'] as $c)
                            <option value="{{ $c }}" {{ old('target_country', $payload['target_country'] ?? '') == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Expected Monthly Orders <span style="color: var(--color-danger);">*</span></label>
                    <input type="number" name="expected_monthly_orders" class="form-control" style="height: 38px;" value="{{ old('expected_monthly_orders', $payload['expected_monthly_orders'] ?? '') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Expected Monthly Units <span style="color: var(--color-danger);">*</span></label>
                    <input type="number" name="expected_monthly_units" class="form-control" style="height: 38px;" value="{{ old('expected_monthly_units', $payload['expected_monthly_units'] ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Product Categories (Select multiple) <span style="color: var(--color-danger);">*</span></label>
                    <div style="display: flex; flex-wrap: wrap; gap: var(--spacing-4); margin-top: 8px;">
                        @foreach(['Electronics', 'Apparel', 'Beauty', 'Kitchen', 'Home', 'Fragile'] as $cat)
                            <label style="font-size: var(--fs-xs); display: flex; align-items: center; gap: 6px; cursor: pointer;">
                                <input type="checkbox" name="product_categories[]" value="{{ $cat }}" {{ in_array($cat, $productCategories) ? 'checked' : '' }} style="accent-color: var(--color-primary);">
                                {{ $cat }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Special Handling Required? <span style="color: var(--color-danger);">*</span></label>
                <div class="segmented-control" style="width: fit-content;">
                    @php $specialHand = old('special_handling', $payload['special_handling'] ?? 'no'); @endphp
                    <input type="radio" name="special_handling" id="sh_yes" value="yes" class="segmented-option" {{ $specialHand == 'yes' ? 'checked' : '' }} onchange="toggleSpecialLogistics(this.value)">
                    <label for="sh_yes" class="segmented-label">Yes</label>
                    <input type="radio" name="special_handling" id="sh_no" value="no" class="segmented-option" {{ $specialHand == 'no' ? 'checked' : '' }} onchange="toggleSpecialLogistics(this.value)">
                    <label for="sh_no" class="segmented-label">No</label>
                </div>
            </div>

            <!-- Conditional Handling Requirements -->
            <div id="sh_fields_container" style="display: {{ $specialHand == 'yes' ? 'block' : 'none' }}; margin-top: var(--spacing-4);">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Temperature Controlled Storage?</label>
                        <select name="temp_controlled" class="form-control" style="height: 38px;">
                            <option value="no" {{ old('temp_controlled', $payload['temp_controlled'] ?? '') == 'no' ? 'selected' : '' }}>No</option>
                            <option value="yes" {{ old('temp_controlled', $payload['temp_controlled'] ?? '') == 'yes' ? 'selected' : '' }}>Yes (Ambient Control)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fragile Products Handling?</label>
                        <select name="fragile_products" class="form-control" style="height: 38px;">
                            <option value="no" {{ old('fragile_products', $payload['fragile_products'] ?? '') == 'no' ? 'selected' : '' }}>No</option>
                            <option value="yes" {{ old('fragile_products', $payload['fragile_products'] ?? '') == 'yes' ? 'selected' : '' }}>Yes (Bubble Wrap Required)</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Handling Requirements Instructions</label>
                    <textarea name="handling_requirements" class="form-control" rows="2" placeholder="e.g. Keep away from direct sunlight, pack with ice packs...">{{ old('handling_requirements', $payload['handling_requirements'] ?? '') }}</textarea>
                </div>
            </div>

            <div class="form-group" style="margin-top: var(--spacing-4);">
                <label class="form-label">Additional Logistics Requirements</label>
                <textarea name="additional_requirements" class="form-control" rows="3">{{ old('additional_requirements', $payload['additional_requirements'] ?? '') }}</textarea>
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

    <!-- ================== STEP 2: SUPPLIER → WAREHOUSE PLANNING ================== -->
    <div id="step-form-container-2" class="step-form-content {{ $currentStep == 2 ? 'active' : '' }}" style="display: {{ $currentStep == 2 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Supplier → Warehouse Planning</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Register dispatch dates, destination warehouse networks, expected SKU counts, and carton numbers.</p>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="step" value="2">
            <input type="hidden" name="action" id="step-2-action" value="save_continue">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Supplier <span style="color: var(--color-danger);">*</span></label>
                    <input type="text" name="planning_supplier" class="form-control" style="height: 38px;" value="{{ old('planning_supplier', $payload['planning_supplier'] ?? '') }}" placeholder="e.g. Apex Trading" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Supplier Contact Info <span style="color: var(--color-danger);">*</span></label>
                    <input type="text" name="planning_contact" class="form-control" style="height: 38px;" value="{{ old('planning_contact', $payload['planning_contact'] ?? '') }}" placeholder="Email / Phone" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Supplier Country <span style="color: var(--color-danger);">*</span></label>
                    <select name="planning_country" class="form-control" style="height: 38px;" required>
                        <option value="">Select Country</option>
                        @foreach(['US', 'CA', 'GB', 'DE', 'FR', 'CN', 'PK'] as $c)
                            <option value="{{ $c }}" {{ old('planning_country', $payload['planning_country'] ?? '') == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Destination Warehouse <span style="color: var(--color-danger);">*</span></label>
                    <input type="text" name="planning_warehouse" class="form-control" style="height: 38px;" value="{{ old('planning_warehouse', $payload['planning_warehouse'] ?? '') }}" placeholder="e.g. LA Warehouse" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Product / SKUs planned (Select multiple) <span style="color: var(--color-danger);">*</span></label>
                <div style="display: flex; flex-wrap: wrap; gap: var(--spacing-4); margin-top: 8px;">
                    @php $planningSkus = old('planning_skus', $payload['planning_skus'] ?? []); @endphp
                    @foreach(['SKU-A', 'SKU-B', 'SKU-C', 'SKU-D'] as $sku)
                        <label style="font-size: var(--fs-xs); display: flex; align-items: center; gap: 6px; cursor: pointer;">
                            <input type="checkbox" name="planning_skus[]" value="{{ $sku }}" {{ in_array($sku, $planningSkus) ? 'checked' : '' }} style="accent-color: var(--color-primary);">
                            {{ $sku }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Expected Total Quantity <span style="color: var(--color-danger);">*</span></label>
                    <input type="number" name="planning_expected_qty" class="form-control" style="height: 38px;" value="{{ old('planning_expected_qty', $payload['planning_expected_qty'] ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Expected Cartons Count <span style="color: var(--color-danger);">*</span></label>
                    <input type="number" name="planning_cartons" class="form-control" style="height: 38px;" value="{{ old('planning_cartons', $payload['planning_cartons'] ?? '') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Estimated Shipment Date <span style="color: var(--color-danger);">*</span></label>
                    <input type="date" name="planning_ship_date" class="form-control" style="height: 38px;" value="{{ old('planning_ship_date', $payload['planning_ship_date'] ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Expected Warehouse Arrival <span style="color: var(--color-danger);">*</span></label>
                    <input type="date" name="planning_arrival_date" class="form-control" style="height: 38px;" value="{{ old('planning_arrival_date', $payload['planning_arrival_date'] ?? '') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Shipping Method <span style="color: var(--color-danger);">*</span></label>
                    <select name="planning_ship_method" class="form-control" style="height: 38px;" required>
                        @foreach(['Air', 'Sea', 'Ground', 'Express', 'Other'] as $m)
                            <option value="{{ $m }}" {{ old('planning_ship_method', $payload['planning_ship_method'] ?? '') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Shipment Documents (Multiple upload)</label>
                    <input type="file" name="planning_documents[]" multiple class="form-control">
                    @if(!empty($payload['planning_documents']))
                        <div style="margin-top: 8px;">
                            <strong style="font-size: var(--fs-xs); color: var(--color-text-secondary);">Uploaded Documents:</strong>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 4px;">
                                @foreach($payload['planning_documents'] as $doc)
                                    <a href="{{ $doc['url'] }}" target="_blank" class="flow-pipeline-badge" style="background-color: var(--color-bg-base); border-color: var(--color-border); color: var(--color-text-primary);">
                                        📎 {{ $doc['name'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Special Instructions</label>
                <textarea name="planning_instructions" class="form-control" rows="3">{{ old('planning_instructions', $payload['planning_instructions'] ?? '') }}</textarea>
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

    <!-- ================== STEP 3: SHIPMENT COORDINATION ================== -->
    <div id="step-form-container-3" class="step-form-content {{ $currentStep == 3 ? 'active' : '' }}" style="display: {{ $currentStep == 3 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Shipment Coordination</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Create shipment records, tracking references, carrier configurations, and custom documentation.</p>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST" id="step-3-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="step" value="3">
            <input type="hidden" name="action" id="step-3-action" value="save_continue">

            <div id="shipments-hidden-inputs-container"></div>
            <div id="shipments-files-container" style="display: none;"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Shipments Records</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('shipment')">
                    + Add Shipment
                </button>
            </div>

            <!-- Dynamic Form to Add Shipment -->
            <div id="add-shipment-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Shipment Record</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Shipment Reference</label>
                        <input type="text" id="ship_reference" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Supplier</label>
                        <input type="text" id="ship_supplier" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Warehouse</label>
                        <input type="text" id="ship_warehouse" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Products SKU (Comma separated)</label>
                        <input type="text" id="ship_products" class="form-control" style="height: 36px;" placeholder="SKU-A, SKU-B">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Total Quantity</label>
                        <input type="number" id="ship_quantity" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Cartons</label>
                        <input type="number" id="ship_cartons" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Shipping Method</label>
                        <select id="ship_method" class="form-control" style="height: 36px;">
                            <option value="Air">Air</option>
                            <option value="Sea">Sea</option>
                            <option value="Ground">Ground</option>
                            <option value="Express">Express</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Carrier</label>
                        <input type="text" id="ship_carrier" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tracking Number</label>
                        <input type="text" id="ship_tracking_number" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tracking URL</label>
                        <input type="url" id="ship_tracking_url" class="form-control" placeholder="https://..." style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Shipment Date</label>
                        <input type="date" id="ship_ship_date" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Expected Arrival Date</label>
                        <input type="date" id="ship_arrival_date" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select id="ship_status" class="form-control" style="height: 36px;">
                            @foreach(['Preparing', 'Booked', 'In Transit', 'Arrived', 'Delayed', 'Cancelled'] as $st)
                                <option value="{{ $st }}">{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Shipping Documents (Multiple upload)</label>
                        <input type="file" id="ship_documents" multiple class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Shipment Notes</label>
                    <textarea id="ship_notes" class="form-control" rows="2"></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('shipment')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addShipmentRecord()">Save Shipment</button>
                </div>
            </div>

            <!-- Shipments Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="shipments-table">
                    <thead>
                        <tr>
                            <th>Shipment Ref</th>
                            <th>Supplier</th>
                            <th>Warehouse</th>
                            <th>SKUs</th>
                            <th>Qty / Cartons</th>
                            <th>Dates</th>
                            <th>Status</th>
                            <th>Files</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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

    <!-- ================== STEP 4: WAREHOUSE RECEIVING ================== -->
    <div id="step-form-container-4" class="step-form-content {{ $currentStep == 4 ? 'active' : '' }}" style="display: {{ $currentStep == 4 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Warehouse Receiving</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Record warehouse receiving details, actual received carton quantities, and photographic proofs.</p>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST" id="step-4-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="step" value="4">
            <input type="hidden" name="action" id="step-4-action" value="save_continue">

            <div id="receivings-hidden-inputs-container"></div>
            <div id="receivings-files-container" style="display: none;"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Receiving Log</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('receiving')">
                    + Add Receiving Entry
                </button>
            </div>

            <!-- Dynamic Add Form for Receiving -->
            <div id="add-receiving-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Receiving Entry</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Shipment Reference</label>
                        <select id="rec_shipment_ref" class="form-control" style="height: 36px;" onchange="syncReceivingExpectedQty(this.value)">
                            <option value="">Select Shipment</option>
                            @foreach($shipments as $sh)
                                <option value="{{ $sh['reference'] }}" data-qty="{{ $sh['quantity'] }}" data-cartons="{{ $sh['cartons'] }}" data-wh="{{ $sh['warehouse'] }}">{{ $sh['reference'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Warehouse</label>
                        <input type="text" id="rec_warehouse" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Receiving Date</label>
                        <input type="date" id="rec_receive_date" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Received By</label>
                        <input type="text" id="rec_received_by" class="form-control" placeholder="e.g. John Doe" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Expected Quantity</label>
                        <input type="number" id="rec_expected_qty" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);" oninput="calcReceivingDiff()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Received Quantity</label>
                        <input type="number" id="rec_received_qty" class="form-control" style="height: 36px;" oninput="calcReceivingDiff()">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Expected Cartons</label>
                        <input type="number" id="rec_cartons_expected" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Received Cartons</label>
                        <input type="number" id="rec_cartons_received" class="form-control" style="height: 36px;">
                    </div>
                </div>

                <!-- Conditional Difference Display -->
                <div class="form-row" id="rec_diff_section" style="display: none;">
                    <div class="form-group">
                        <label class="form-label" style="color: var(--color-danger);">Quantity Difference</label>
                        <input type="text" id="rec_diff_qty" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base); font-weight: var(--fw-bold); color: var(--color-danger);">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="color: var(--color-danger);">Variance Type</label>
                        <input type="text" id="rec_diff_type" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base); font-weight: var(--fw-bold); color: var(--color-danger);">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Receiving Documents</label>
                        <input type="file" id="rec_documents" multiple class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Receiving Photos (Multiple upload)</label>
                        <input type="file" id="rec_photos" multiple class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Receiving Status</label>
                        <select id="rec_status" class="form-control" style="height: 36px;">
                            @foreach(['Pending', 'Receiving', 'Partially Received', 'Fully Received', 'Rejected'] as $st)
                                <option value="{{ $st }}">{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Receiving Notes</label>
                        <input type="text" id="rec_notes" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('receiving')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addReceivingRecord()">Save Entry</button>
                </div>
            </div>

            <!-- Receivings Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="receivings-table">
                    <thead>
                        <tr>
                            <th>Shipment Ref</th>
                            <th>Warehouse</th>
                            <th>Received By</th>
                            <th>Date</th>
                            <th>Expected / Received Qty</th>
                            <th>Expected / Received Cartons</th>
                            <th>Difference</th>
                            <th>Status</th>
                            <th>Files</th>
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

    <!-- ================== STEP 5: QUANTITY VERIFICATION ================== -->
    <div id="step-form-container-5" class="step-form-content {{ $currentStep == 5 ? 'active' : '' }}" style="display: {{ $currentStep == 5 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Quantity Verification</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Verify received SKU quantities against expected dispatch lists.</p>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST" id="step-5-form">
            @csrf
            <input type="hidden" name="step" value="5">
            <input type="hidden" name="action" id="step-5-action" value="save_continue">

            <div id="verifications-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Verification Logs</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('verification')">
                    + Add Verification
                </button>
            </div>

            <!-- Add Verification form -->
            <div id="add-verification-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Verification Entry</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Shipment Reference</label>
                        <select id="ver_shipment_ref" class="form-control" style="height: 36px;" onchange="syncVerificationDetails(this.value)">
                            <option value="">Select Shipment</option>
                            @foreach($receivings as $rc)
                                <option value="{{ $rc['shipment_ref'] }}" data-expected="{{ $rc['expected_qty'] }}" data-received="{{ $rc['received_qty'] }}">{{ $rc['shipment_ref'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Product SKU</label>
                        <select id="ver_sku" class="form-control" style="height: 36px;">
                            @foreach(['SKU-A', 'SKU-B', 'SKU-C', 'SKU-D'] as $sku)
                                <option value="{{ $sku }}">{{ $sku }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Expected Quantity</label>
                        <input type="number" id="ver_expected_qty" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);" oninput="calcVerificationDiff()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Received Quantity</label>
                        <input type="number" id="ver_received_qty" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);" oninput="calcVerificationDiff()">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Verified Quantity</label>
                        <input type="number" id="ver_verified_qty" class="form-control" style="height: 36px;" oninput="calcVerificationDiff()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Difference</label>
                        <input type="text" id="ver_difference" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base); font-weight: var(--fw-bold);">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Verification Status</label>
                        <select id="ver_status" class="form-control" style="height: 36px;">
                            @foreach(['Pending', 'Verified', 'Short', 'Excess', 'Disputed'] as $st)
                                <option value="{{ $st }}">{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Verified By</label>
                        <input type="text" id="ver_verified_by" class="form-control" placeholder="e.g. Inspector Mark" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Verification Date</label>
                        <input type="date" id="ver_verify_date" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Verification Notes</label>
                        <input type="text" id="ver_notes" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('verification')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addVerificationRecord()">Save Entry</button>
                </div>
            </div>

            <!-- Verifications Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="verifications-table">
                    <thead>
                        <tr>
                            <th>Shipment Ref</th>
                            <th>Product SKU</th>
                            <th>Expected Qty</th>
                            <th>Received Qty</th>
                            <th>Verified Qty</th>
                            <th>Difference</th>
                            <th>Status</th>
                            <th>Verified By</th>
                            <th>Date</th>
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

    <!-- ================== STEP 6: QUALITY INSPECTION ================== -->
    <div id="step-form-container-6" class="step-form-content {{ $currentStep == 6 ? 'active' : '' }}" style="display: {{ $currentStep == 6 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Quality Inspection</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Inspect products, log defect quantities, check quality checklists, and calculate scores.</p>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST" id="step-6-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="step" value="6">
            <input type="hidden" name="action" id="step-6-action" value="save_continue">

            <div id="inspections-hidden-inputs-container"></div>
            <div id="inspections-files-container" style="display: none;"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Inspections Log</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('inspection')">
                    + Add Quality Entry
                </button>
            </div>

            <!-- Dynamic Form for Quality Entry -->
            <div id="add-inspection-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Quality Entry</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Shipment Reference</label>
                        <select id="ins_shipment_ref" class="form-control" style="height: 36px;">
                            @foreach($verifications as $v)
                                <option value="{{ $v['shipment_ref'] }}">{{ $v['shipment_ref'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Product SKU</label>
                        <select id="ins_sku" class="form-control" style="height: 36px;">
                            @foreach(['SKU-A', 'SKU-B', 'SKU-C', 'SKU-D'] as $sku)
                                <option value="{{ $sku }}">{{ $sku }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Inspection Date</label>
                        <input type="date" id="ins_inspect_date" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Inspector Name</label>
                        <input type="text" id="ins_inspector" class="form-control" placeholder="e.g. QA Manager" style="height: 36px;">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Quality Checklist (Tick approved parameters)</label>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--spacing-2); margin-top: 4px;">
                        @foreach(['Product Condition', 'Packaging', 'Labeling', 'Quantity', 'Product Size', 'Product Color', 'Product Functionality', 'Branding', 'Overall Quality'] as $chk)
                            <label style="font-size: var(--fs-xs); display: flex; align-items: center; gap: 6px; cursor: pointer;">
                                <input type="checkbox" name="ins_checklist" value="{{ $chk }}" checked style="accent-color: var(--color-primary);" onchange="calcQualityScore()">
                                {{ $chk }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Defects Found?</label>
                        <div class="segmented-control" style="width: fit-content;">
                            <input type="radio" name="ins_defects_radio" id="defects_yes" value="yes" class="segmented-option" onchange="toggleInspectionDefects(this.value)">
                            <label for="defects_yes" class="segmented-label">Yes</label>
                            <input type="radio" name="ins_defects_radio" id="defects_no" value="no" class="segmented-option" checked onchange="toggleInspectionDefects(this.value)">
                            <label for="defects_no" class="segmented-label">No</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quality Score (%)</label>
                        <input type="number" id="ins_score" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base); font-weight: var(--fw-bold);" value="100">
                    </div>
                </div>

                <!-- Conditional Defect Fields -->
                <div id="ins_defect_fields" style="display: none; margin-top: var(--spacing-3);">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Defect Quantity</label>
                            <input type="number" id="ins_defect_qty" class="form-control" style="height: 36px;" value="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Defect Details</label>
                            <input type="text" id="ins_defect_details" class="form-control" placeholder="e.g. Scratched casing" style="height: 36px;">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Inspection Photos</label>
                        <input type="file" id="ins_photos" multiple class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Inspection Report</label>
                        <input type="file" id="ins_report" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Final Decision</label>
                        <select id="ins_decision" class="form-control" style="height: 36px;">
                            @foreach(['Approved', 'Approved with Changes', 'Re-inspection', 'Rejected'] as $dec)
                                <option value="{{ $dec }}">{{ $dec }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Inspection Status</label>
                        <select id="ins_status" class="form-control" style="height: 36px;">
                            @foreach(['Pending', 'In Progress', 'Passed', 'Failed', 'Needs Recheck'] as $lst)
                                <option value="{{ $lst }}">{{ $lst }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('inspection')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addInspectionRecord()">Save Entry</button>
                </div>
            </div>

            <!-- Inspections Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="inspections-table">
                    <thead>
                        <tr>
                            <th>Shipment Ref</th>
                            <th>Product SKU</th>
                            <th>Date</th>
                            <th>Inspector</th>
                            <th>Defects Found</th>
                            <th>Score</th>
                            <th>Decision</th>
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

    <!-- ================== STEP 7: INVENTORY STORAGE ================== -->
    <div id="step-form-container-7" class="step-form-content {{ $currentStep == 7 ? 'active' : '' }}" style="display: {{ $currentStep == 7 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Inventory Storage</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Assign approved inventory to warehouse locations, bins, or storage shelves.</p>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST" id="step-7-form">
            @csrf
            <input type="hidden" name="step" value="7">
            <input type="hidden" name="action" id="step-7-action" value="save_continue">

            <div id="storage_records-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Storage Locations</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('storage')">
                    + Assign Location
                </button>
            </div>

            <!-- Dynamic Form for Storage Location -->
            <div id="add-storage-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Location Assignment</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Warehouse</label>
                        <select id="st_warehouse" class="form-control" style="height: 36px;">
                            <option value="LA Warehouse">LA Warehouse</option>
                            <option value="NY Warehouse">NY Warehouse</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Product SKU</label>
                        <select id="st_sku" class="form-control" style="height: 36px;">
                            @foreach(['SKU-A', 'SKU-B', 'SKU-C', 'SKU-D'] as $sku)
                                <option value="{{ $sku }}">{{ $sku }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Quantity</label>
                        <input type="number" id="st_quantity" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Storage Location Zone</label>
                        <select id="st_location_code" class="form-control" style="height: 36px;">
                            <option value="Zone A (High-Rack)">Zone A (High-Rack)</option>
                            <option value="Zone B (Bulk)">Zone B (Bulk)</option>
                            <option value="Zone C (Bin shelf)">Zone C (Bin shelf)</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Bin / Shelf / Location Code</label>
                        <input type="text" id="st_shelf_bin" class="form-control" placeholder="e.g. Row 5, Bin 12" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Storage Date</label>
                        <input type="date" id="st_storage_date" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Inventory Status</label>
                        <select id="st_status" class="form-control" style="height: 36px;">
                            @foreach(['Available', 'Reserved', 'Damaged', 'Quarantine'] as $ist)
                                <option value="{{ $ist }}">{{ $ist }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Storage Notes</label>
                        <input type="text" id="st_notes" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('storage')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addStorageRecord()">Save Record</button>
                </div>
            </div>

            <!-- Storage Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="storages-table">
                    <thead>
                        <tr>
                            <th>Warehouse</th>
                            <th>Product SKU</th>
                            <th>Quantity</th>
                            <th>Storage Zone</th>
                            <th>Bin / Shelf</th>
                            <th>Storage Date</th>
                            <th>Status</th>
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

    <!-- ================== STEP 8: SKU & INVENTORY MANAGEMENT ================== -->
    <div id="step-form-container-8" class="step-form-content {{ $currentStep == 8 ? 'active' : '' }}" style="display: {{ $currentStep == 8 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">SKU & Inventory Management</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Create warehouse stock listings, reorder thresholds, and active storage parameters.</p>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST" id="step-8-form">
            @csrf
            <input type="hidden" name="step" value="8">
            <input type="hidden" name="action" id="step-8-action" value="save_continue">

            <div id="inventories-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Inventory Stocks Table</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('inventory')">
                    + Add Inventory SKU
                </button>
            </div>

            <!-- Dynamic Form for Inventory SKU -->
            <div id="add-inventory-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Inventory SKU</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">SKU Code</label>
                        <input type="text" id="inv_sku" class="form-control" placeholder="e.g. SKU-A" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Product Name</label>
                        <input type="text" id="inv_product_name" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Warehouse</label>
                        <select id="inv_warehouse" class="form-control" style="height: 36px;">
                            <option value="LA Warehouse">LA Warehouse</option>
                            <option value="NY Warehouse">NY Warehouse</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Storage Location Zone</label>
                        <select id="inv_location_code" class="form-control" style="height: 36px;">
                            <option value="Zone A (High-Rack)">Zone A (High-Rack)</option>
                            <option value="Zone B (Bulk)">Zone B (Bulk)</option>
                            <option value="Zone C (Bin shelf)">Zone C (Bin shelf)</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Available Qty</label>
                        <input type="number" id="inv_available" class="form-control" style="height: 36px;" value="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reserved Qty</label>
                        <input type="number" id="inv_reserved" class="form-control" style="height: 36px;" value="0">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Damaged Qty</label>
                        <input type="number" id="inv_damaged" class="form-control" style="height: 36px;" value="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reorder Level Threshold</label>
                        <input type="number" id="inv_reorder_level" class="form-control" style="height: 36px;" value="10">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Reorder Target Quantity</label>
                        <input type="number" id="inv_reorder_qty" class="form-control" style="height: 36px;" value="50">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Inventory Notes</label>
                        <input type="text" id="inv_notes" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('inventory')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addInventoryRecord()">Save Stock</button>
                </div>
            </div>

            <!-- SKU Stock Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="inventories-table">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>Warehouse</th>
                            <th>Storage Zone</th>
                            <th>Available</th>
                            <th>Reserved</th>
                            <th>Damaged</th>
                            <th>Total Stock</th>
                            <th>Reorder Threshold</th>
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

    <!-- ================== STEP 9: ORDER PROCESSING ================== -->
    <div id="step-form-container-9" class="step-form-content {{ $currentStep == 9 ? 'active' : '' }}" style="display: {{ $currentStep == 9 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Order Processing</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage incoming orders, prioritize urgency, and verify fulfillment stock channels.</p>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST" id="step-9-form">
            @csrf
            <input type="hidden" name="step" value="9">
            <input type="hidden" name="action" id="step-9-action" value="save_continue">

            <div id="orders-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Order Entries</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('order')">
                    + Add Order
                </button>
            </div>

            <!-- Dynamic Form for Order -->
            <div id="add-order-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Order Record</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Order ID</label>
                        <input type="text" id="ord_order_id" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Order Source</label>
                        <select id="ord_source" class="form-control" style="height: 36px;">
                            @foreach(['Shopify', 'Amazon', 'Walmart', 'eBay', 'TikTok Shop', 'Own Website', 'Manual', 'Other'] as $src)
                                <option value="{{ $src }}">{{ $src }}</option>
                            @endforeach
                        </select>
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
                        <label class="form-label">Warehouse</label>
                        <select id="ord_warehouse" class="form-control" style="height: 36px;">
                            <option value="LA Warehouse">LA Warehouse</option>
                            <option value="NY Warehouse">NY Warehouse</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Order Amount ($)</label>
                        <input type="number" step="0.01" id="ord_amount" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Order Status</label>
                        <select id="ord_status" class="form-control" style="height: 36px;">
                            @foreach(['New', 'Processing', 'Ready to Pick', 'Picked', 'Packed', 'Shipped', 'Delivered', 'Cancelled', 'Returned'] as $st)
                                <option value="{{ $st }}">{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Priority</label>
                        <select id="ord_priority" class="form-control" style="height: 36px;">
                            <option value="Normal">Normal</option>
                            <option value="High">High</option>
                            <option value="Urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Order Product & Quantity (Comma separated SKU:Qty)</label>
                    <input type="text" id="ord_products" class="form-control" placeholder="e.g. SKU-A:2, SKU-B:1" style="height: 36px;">
                </div>
                <div class="form-group">
                    <label class="form-label">Order Notes</label>
                    <input type="text" id="ord_notes" class="form-control" style="height: 36px;">
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
                            <th>Source</th>
                            <th>Customer Name</th>
                            <th>Date</th>
                            <th>Fulfillment Warehouse</th>
                            <th>Order Products</th>
                            <th>Amount</th>
                            <th>Priority</th>
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

    <!-- ================== STEP 10: PICK & PACK ================== -->
    <div id="step-form-container-10" class="step-form-content {{ $currentStep == 10 ? 'active' : '' }}" style="display: {{ $currentStep == 10 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Pick & Pack</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage picked quantities, verify storage shelf sources, package weights, and photo records.</p>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST" id="step-10-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="step" value="10">
            <input type="hidden" name="action" id="step-10-action" value="save_continue">

            <div id="picks-hidden-inputs-container"></div>
            <div id="picks-files-container" style="display: none;"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Picking Checklist Logs</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('pick')">
                    + Add Pick List Entry
                </button>
            </div>

            <!-- Dynamic Form for Pick entry -->
            <div id="add-pick-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Picking Entry</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select Order</label>
                        <select id="pick_order_id" class="form-control" style="height: 36px;" onchange="syncPickOrderWarehouse(this.value)">
                            <option value="">Select Order</option>
                            @foreach($orders as $o)
                                <option value="{{ $o['order_id'] }}" data-wh="{{ $o['warehouse'] }}">{{ $o['order_id'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Warehouse</label>
                        <input type="text" id="pick_warehouse" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Product SKU</label>
                        <select id="pick_sku" class="form-control" style="height: 36px;" onchange="syncPickStorageLocation(this.value)">
                            <option value="">Select SKU</option>
                            @foreach($inventories as $i)
                                <option value="{{ $i['sku'] }}" data-loc="{{ $i['location_code'] }}">{{ $i['sku'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Storage Location Zone</label>
                        <input type="text" id="pick_location_code" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Required Quantity</label>
                        <input type="number" id="pick_required_qty" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Picked Quantity</label>
                        <input type="number" id="pick_picked_qty" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Picking Status</label>
                        <select id="pick_pick_status" class="form-control" style="height: 36px;">
                            <option value="Pending">Pending</option>
                            <option value="Picking">Picking</option>
                            <option value="Picked">Picked</option>
                            <option value="Short Pick">Short Pick</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Packed By</label>
                        <input type="text" id="pick_packed_by" class="form-control" placeholder="e.g. Packer 1" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Packing Date</label>
                        <input type="date" id="pick_pack_date" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Package Count</label>
                        <input type="number" id="pick_pkg_count" class="form-control" style="height: 36px;" value="1">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Package Weight (lbs)</label>
                        <input type="number" step="0.01" id="pick_pkg_weight" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Package Dimensions (L x W x H)</label>
                        <input type="text" id="pick_pkg_dims" class="form-control" placeholder="e.g. 10x10x10" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Packaging Type</label>
                        <select id="pick_pkg_type" class="form-control" style="height: 36px;">
                            <option value="Standard">Standard Box</option>
                            <option value="Premium">Premium Brand Box</option>
                            <option value="Fragile">Fragile Wrap</option>
                            <option value="Custom">Custom Sleeve</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Packing Photos (Optional)</label>
                        <input type="file" id="pick_photos" multiple class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Packing Notes</label>
                    <input type="text" id="pick_notes" class="form-control" style="height: 36px;">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('pick')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addPickRecord()">Save Packing</button>
                </div>
            </div>

            <!-- Pick List Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="picks-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Warehouse</th>
                            <th>SKU</th>
                            <th>Required Qty</th>
                            <th>Picked Qty</th>
                            <th>Storage Zone</th>
                            <th>Packer</th>
                            <th>Weight / Dims</th>
                            <th>Files</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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

    <!-- ================== STEP 11: SHIPPING LABEL ================== -->
    <div id="step-form-container-11" class="step-form-content {{ $currentStep == 11 ? 'active' : '' }}" style="display: {{ $currentStep == 11 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Shipping Label</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage recipient delivery addresses, zip codes, shipping services, and upload generated labels.</p>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST" id="step-11-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="step" value="11">
            <input type="hidden" name="action" id="step-11-action" value="save_continue">

            <div id="labels-hidden-inputs-container"></div>
            <div id="labels-files-container" style="display: none;"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Shipping Labels Table</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('label')">
                    + Add Label Entry
                </button>
            </div>

            <!-- Dynamic Form for Shipping Label -->
            <div id="add-label-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Label Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select Order</label>
                        <select id="lbl_order_id" class="form-control" style="height: 36px;" onchange="syncLabelOrderDetails(this.value)">
                            <option value="">Select Order</option>
                            @foreach($picks as $pk)
                                <option value="{{ $pk['order_id'] }}" data-weight="{{ $pk['pkg_weight'] }}" data-dims="{{ $pk['pkg_dims'] }}">{{ $pk['order_id'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Recipient Name</label>
                        <input type="text" id="lbl_recipient_name" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Recipient Address</label>
                    <textarea id="lbl_recipient_address" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" id="lbl_city" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">State</label>
                        <input type="text" id="lbl_state" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">ZIP Code</label>
                        <input type="text" id="lbl_zip_code" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Country</label>
                        <select id="lbl_country" class="form-control" style="height: 36px;">
                            @foreach(['US', 'CA', 'GB', 'DE', 'FR', 'AU', 'AE'] as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Package Weight (lbs)</label>
                        <input type="number" step="0.01" id="lbl_pkg_weight" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Package Dimensions</label>
                        <input type="text" id="lbl_pkg_dims" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Shipping Service Tier</label>
                        <select id="lbl_shipping_service" class="form-control" style="height: 36px;">
                            <option value="Ground Standard">Ground Standard</option>
                            <option value="2-Day Express">2-Day Express</option>
                            <option value="Next-Day Air">Next-Day Air</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Label Status</label>
                        <select id="lbl_label_status" class="form-control" style="height: 36px;">
                            <option value="Not Generated">Not Generated</option>
                            <option value="Generated">Generated</option>
                            <option value="Printed">Printed</option>
                            <option value="Attached">Attached</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tracking Number</label>
                        <input type="text" id="lbl_tracking_number" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tracking URL</label>
                        <input type="url" id="lbl_tracking_url" class="form-control" placeholder="https://..." style="height: 36px;">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Shipping Label File PDF / Image</label>
                    <input type="file" id="lbl_label_file" class="form-control">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('label')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addLabelRecord()">Save Label</button>
                </div>
            </div>

            <!-- Labels Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="labels-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Recipient</th>
                            <th>City / ZIP</th>
                            <th>Weight / Dims</th>
                            <th>Service</th>
                            <th>Tracking ID</th>
                            <th>Label Status</th>
                            <th>File</th>
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

    <!-- ================== STEP 12: CARRIER ASSIGNMENT ================== -->
    <div id="step-form-container-12" class="step-form-content {{ $currentStep == 12 ? 'active' : '' }}" style="display: {{ $currentStep == 12 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Carrier Assignment</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Assign shipping couriers/carriers, log pickup/delivery targets, and track shipping charges.</p>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST" id="step-12-form">
            @csrf
            <input type="hidden" name="step" value="12">
            <input type="hidden" name="action" id="step-12-action" value="save_continue">

            <div id="carriers-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Carrier Assignments List</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('carrier')">
                    + Assign Carrier
                </button>
            </div>

            <!-- Dynamic Form for Carrier -->
            <div id="add-carrier-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Carrier Entry</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select Order</label>
                        <select id="car_order_id" class="form-control" style="height: 36px;" onchange="syncCarrierTrackingNo(this.value)">
                            <option value="">Select Order</option>
                            @foreach($labels as $lb)
                                <option value="{{ $lb['order_id'] }}" data-tracking="{{ $lb['tracking_number'] }}">{{ $lb['order_id'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Carrier / Courier Name</label>
                        <select id="car_carrier_name" class="form-control" style="height: 36px;">
                            <option value="FedEx">FedEx</option>
                            <option value="UPS">UPS</option>
                            <option value="USPS">USPS</option>
                            <option value="DHL">DHL</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Service Type</label>
                        <select id="car_service_type" class="form-control" style="height: 36px;">
                            <option value="Ground Standard">Ground Standard</option>
                            <option value="2-Day Saver">2-Day Saver</option>
                            <option value="Overnight Express">Overnight Express</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tracking Number</label>
                        <input type="text" id="car_tracking_number" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Shipping Cost ($)</label>
                        <input type="number" step="0.01" id="car_shipping_cost" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pickup Date</label>
                        <input type="date" id="car_pickup_date" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Estimated Delivery Date</label>
                        <input type="date" id="car_est_delivery_date" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Carrier Status</label>
                        <select id="car_status" class="form-control" style="height: 36px;">
                            @foreach(['Assigned', 'Picked Up', 'In Transit', 'Delivered', 'Delayed', 'Failed'] as $cst)
                                <option value="{{ $cst }}">{{ $cst }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Carrier Notes</label>
                    <input type="text" id="car_notes" class="form-control" style="height: 36px;">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('carrier')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addCarrierRecord()">Save Assignment</button>
                </div>
            </div>

            <!-- Carrier Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="carriers-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Carrier</th>
                            <th>Service Type</th>
                            <th>Tracking ID</th>
                            <th>Cost</th>
                            <th>Pickup Date</th>
                            <th>Est Delivery Date</th>
                            <th>Carrier Status</th>
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

    <!-- ================== STEP 13: SHIPMENT TRACKING ================== -->
    <div id="step-form-container-13" class="step-form-content {{ $currentStep == 13 ? 'active' : '' }}" style="display: {{ $currentStep == 13 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Shipment Tracking</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage current shipping locations, transit milestones, and tracking logs.</p>

        <!-- Clean Interactive Shipment Timeline Graphic -->
        <div class="card" style="padding: var(--spacing-6); margin-bottom: var(--spacing-6); border-color: var(--color-border); background-color: #ffffff;">
            <h3 style="font-size: var(--fs-xs); text-transform: uppercase; color: var(--color-text-secondary); margin-bottom: var(--spacing-4); font-weight: var(--fw-semibold);">Interactive Shipment Milestone Tracker</h3>
            <div class="tracking-timeline">
                <div class="timeline-item">
                    <div class="timeline-dot active" id="dot-processed"></div>
                    <span class="timeline-label active" id="label-processed">Order Processed</span>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot" id="dot-picked"></div>
                    <span class="timeline-label" id="label-picked">Picked & Packed</span>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot" id="dot-shipped"></div>
                    <span class="timeline-label" id="label-shipped">Shipped</span>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot" id="dot-transit"></div>
                    <span class="timeline-label" id="label-transit">In Transit</span>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot" id="dot-outfordel"></div>
                    <span class="timeline-label" id="label-outfordel">Out for Delivery</span>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot" id="dot-delivered"></div>
                    <span class="timeline-label" id="label-delivered">Delivered</span>
                </div>
            </div>
        </div>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST" id="step-13-form">
            @csrf
            <input type="hidden" name="step" value="13">
            <input type="hidden" name="action" id="step-13-action" value="save_continue">

            <div id="trackings-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Tracking Entries</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('tracking')">
                    + Add Tracking Status
                </button>
            </div>

            <!-- Dynamic Form for Tracking -->
            <div id="add-tracking-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Tracking Update</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select Order</label>
                        <select id="tr_order_id" class="form-control" style="height: 36px;" onchange="syncTrackingDetails(this.value)">
                            <option value="">Select Order</option>
                            @foreach($carriers as $cr)
                                <option value="{{ $cr['order_id'] }}" data-tracking="{{ $cr['tracking_number'] }}" data-carrier="{{ $cr['carrier_name'] }}" data-est="{{ $cr['est_delivery_date'] }}">{{ $cr['order_id'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tracking Number</label>
                        <input type="text" id="tr_tracking_number" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Carrier</label>
                        <input type="text" id="tr_carrier" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tracking URL</label>
                        <input type="url" id="tr_tracking_url" class="form-control" placeholder="https://..." style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Current Status</label>
                        <select id="tr_status" class="form-control" style="height: 36px;" onchange="updateInteractiveTimeline(this.value)">
                            @foreach(['Label Created', 'Picked Up', 'In Transit', 'Out for Delivery', 'Delivered', 'Delayed', 'Exception'] as $ts)
                                <option value="{{ $ts }}">{{ $ts }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Update Timestamp</label>
                        <input type="datetime-local" id="tr_last_update" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Estimated Delivery Date</label>
                        <input type="date" id="tr_est_delivery" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tracking Notes</label>
                        <input type="text" id="tr_notes" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('tracking')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addTrackingRecord()">Save Tracking</button>
                </div>
            </div>

            <!-- Trackings Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="trackings-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Tracking Number</th>
                            <th>Carrier</th>
                            <th>Status</th>
                            <th>Last Update</th>
                            <th>Est Delivery</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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

    <!-- ================== STEP 14: CUSTOMER DELIVERY ================== -->
    <div id="step-form-container-14" class="step-form-content {{ $currentStep == 14 ? 'active' : '' }}" style="display: {{ $currentStep == 14 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Customer Delivery</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Verify final package delivery dates, recipient name, delivery failure reason codes, and upload proofs.</p>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST" id="step-14-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="step" value="14">
            <input type="hidden" name="action" id="step-14-action" value="save_continue">

            <div id="deliveries-hidden-inputs-container"></div>
            <div id="deliveries-files-container" style="display: none;"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Deliveries Table</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('delivery')">
                    + Add Delivery Entry
                </button>
            </div>

            <!-- Dynamic Form for Delivery -->
            <div id="add-delivery-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Delivery Entry</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select Order</label>
                        <select id="del_order_id" class="form-control" style="height: 36px;" onchange="syncDeliveryCustomer(this.value)">
                            <option value="">Select Order</option>
                            @foreach($trackings as $tr)
                                <option value="{{ $tr['order_id'] }}">{{ $tr['order_id'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Customer Name</label>
                        <input type="text" id="del_customer_name" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Delivery Date</label>
                        <input type="date" id="del_delivery_date" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Delivery Status</label>
                        <select id="del_status" class="form-control" style="height: 36px;" onchange="toggleDeliveryFailure(this.value)">
                            <option value="Delivered">Delivered</option>
                            <option value="Out for Delivery">Out for Delivery</option>
                            <option value="Failed">Failed</option>
                            <option value="Rescheduled">Rescheduled</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Received By</label>
                        <input type="text" id="del_received_by" class="form-control" placeholder="e.g. Self / Front Door" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Delivery Proof (Photo Upload)</label>
                        <input type="file" id="del_proof" class="form-control">
                    </div>
                </div>

                <!-- Conditional Failure Reason -->
                <div class="form-row" id="del_failure_section" style="display: none;">
                    <div class="form-group">
                        <label class="form-label">Failure Reason</label>
                        <select id="del_failure_reason" class="form-control" style="height: 36px;">
                            <option value="Wrong Address">Wrong Address</option>
                            <option value="Customer Unavailable">Customer Unavailable</option>
                            <option value="Carrier Issue">Carrier Issue</option>
                            <option value="Damaged Package">Damaged Package</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Additional Comments</label>
                        <input type="text" id="del_failure_notes" class="form-control" placeholder="Optional comments..." style="height: 36px;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Delivery Notes</label>
                    <textarea id="del_notes" class="form-control" rows="2"></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('delivery')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addDeliveryRecord()">Save Entry</button>
                </div>
            </div>

            <!-- Deliveries Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="deliveries-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Delivery Date</th>
                            <th>Status</th>
                            <th>Received By</th>
                            <th>Variance Reason</th>
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
                    <button type="submit" class="btn btn-primary">Save & Continue</button>
                </div>
            </div>
        </form>
    </div>

    <!-- ================== STEP 15: RETURNS HANDLING ================== -->
    <div id="step-form-container-15" class="step-form-content {{ $currentStep == 15 ? 'active' : '' }}" style="display: {{ $currentStep == 15 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Returns Handling</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage customer return requests, return reasons, tracking numbers, and inspections.</p>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST" id="step-15-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="step" value="15">
            <input type="hidden" name="action" id="step-15-action" value="save_continue">

            <div id="returns-hidden-inputs-container"></div>
            <div id="returns-files-container" style="display: none;"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Return Logs</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('return')">
                    + Add Return Entry
                </button>
            </div>

            <!-- Dynamic Form for Return -->
            <div id="add-return-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Return Record</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select Order</label>
                        <select id="ret_order_id" class="form-control" style="height: 36px;" onchange="syncReturnCustomer(this.value)">
                            <option value="">Select Order</option>
                            @foreach($deliveries as $dl)
                                <option value="{{ $dl['order_id'] }}">{{ $dl['order_id'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Customer Name</label>
                        <input type="text" id="ret_customer_name" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Request Date</label>
                        <input type="date" id="ret_request_date" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Product SKU</label>
                        <select id="ret_sku" class="form-control" style="height: 36px;">
                            @foreach(['SKU-A', 'SKU-B', 'SKU-C', 'SKU-D'] as $sku)
                                <option value="{{ $sku }}">{{ $sku }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Return Quantity</label>
                        <input type="number" id="ret_quantity" class="form-control" style="height: 36px;" value="1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Return Reason</label>
                        <select id="ret_reason" class="form-control" style="height: 36px;">
                            <option value="Damaged">Damaged</option>
                            <option value="Wrong Product">Wrong Product</option>
                            <option value="Defective">Defective</option>
                            <option value="Customer Changed Mind">Customer Changed Mind</option>
                            <option value="Incorrect Size">Incorrect Size</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Return Tracking Number</label>
                        <input type="text" id="ret_tracking_number" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Return Carrier</label>
                        <input type="text" id="ret_carrier" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Received Date</label>
                        <input type="date" id="ret_received_date" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Inspection Result</label>
                        <select id="ret_inspection_result" class="form-control" style="height: 36px;">
                            <option value="Good">Good</option>
                            <option value="Damaged">Damaged</option>
                            <option value="Defective">Defective</option>
                            <option value="Resellable">Resellable</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Return Documents / Photos</label>
                        <input type="file" id="ret_photos" multiple class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Return Status</label>
                        <select id="ret_status" class="form-control" style="height: 36px;">
                            @foreach(['Requested', 'Approved', 'Return In Transit', 'Received', 'Inspected', 'Refunded', 'Rejected', 'Completed'] as $rst)
                                <option value="{{ $rst }}">{{ $rst }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Return Notes</label>
                    <input type="text" id="ret_notes" class="form-control" style="height: 36px;">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('return')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addReturnRecord()">Save Return</button>
                </div>
            </div>

            <!-- Returns Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="returns-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Request Date</th>
                            <th>SKU / Qty</th>
                            <th>Reason</th>
                            <th>Carrier Info</th>
                            <th>Inspection</th>
                            <th>Files</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="form-navigation">
                <button type="button" class="btn btn-secondary" onclick="jumpToStep(14)">Back</button>
                <div>
                    <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-15-action').value='save_draft'">Save Draft</button>
                    <button type="submit" class="btn btn-primary">Save & Continue</button>
                </div>
            </div>
        </form>
    </div>

    <!-- ================== STEP 16: INVENTORY UPDATE ================== -->
    <div id="step-form-container-16" class="step-form-content {{ $currentStep == 16 ? 'active' : '' }}" style="display: {{ $currentStep == 16 ? 'none' : 'none' }};">
        <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Inventory Update</h2>
        <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Track stock transaction logs, adjusts available items, and synchronizes warehouse levels.</p>

        <form action="{{ route('services.save_step', 'fulfillment-logistics') }}" method="POST" id="step-16-form">
            @csrf
            <input type="hidden" name="step" value="16">
            <input type="hidden" name="action" id="step-16-action" value="save_continue">

            <div id="inventory_updates-hidden-inputs-container"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
                <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary);">Inventory Transaction Updates</h3>
                <button type="button" class="btn btn-primary" style="height: 30px; font-size: var(--fs-xs); display: flex; align-items: center; gap: 4px;" onclick="showAddForm('inventory_update')">
                    + Add Transaction
                </button>
            </div>

            <!-- Dynamic Form for Inventory Update -->
            <div id="add-inventory_update-form" class="inline-form-card" style="display: none;">
                <h4 style="font-size: var(--fs-sm); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4); color: var(--color-primary);">New Stock Transaction</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select SKU</label>
                        <select id="upd_sku" class="form-control" style="height: 36px;" onchange="syncUpdateStockQty(this.value)">
                            <option value="">Select SKU</option>
                            @foreach($inventories as $i)
                                <option value="{{ $i['sku'] }}" data-wh="{{ $i['warehouse'] }}" data-avail="{{ $i['available'] }}">{{ $i['sku'] }} ({{ $i['warehouse'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Warehouse</label>
                        <input type="text" id="upd_warehouse" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Transaction Type</label>
                        <select id="upd_transaction_type" class="form-control" style="height: 36px;" onchange="calculateUpdateNewQty()">
                            <option value="Received">Received (+)</option>
                            <option value="Sold">Sold (-)</option>
                            <option value="Returned">Returned (+)</option>
                            <option value="Damaged">Damaged (-)</option>
                            <option value="Adjusted">Adjusted (+/-)</option>
                            <option value="Transferred">Transferred (+/-)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quantity</label>
                        <input type="number" id="upd_quantity" class="form-control" style="height: 36px;" value="1" oninput="calculateUpdateNewQty()">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Previous Quantity</label>
                        <input type="number" id="upd_prev_quantity" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Quantity (Calculated)</label>
                        <input type="number" id="upd_new_quantity" class="form-control" readonly style="height: 36px; background-color: var(--color-bg-base); font-weight: var(--fw-bold); color: var(--color-primary-dark);">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Reference Type</label>
                        <select id="upd_ref_type" class="form-control" style="height: 36px;">
                            <option value="Shipment">Shipment</option>
                            <option value="Order">Order</option>
                            <option value="Return">Return</option>
                            <option value="Manual Adjustment">Manual Adjustment</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reference ID</label>
                        <input type="text" id="upd_ref_id" class="form-control" placeholder="e.g. SHIP-9021" style="height: 36px;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Updated By</label>
                        <input type="text" id="upd_updated_by" class="form-control" placeholder="e.g. Warehouse Lead" style="height: 36px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Updated Date & Time</label>
                        <input type="datetime-local" id="upd_updated_date" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Adjustment Reason</label>
                    <textarea id="upd_reason" class="form-control" rows="2"></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3); margin-top: var(--spacing-2);">
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm('inventory_update')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addInventoryUpdateRecord()">Save Transaction</button>
                </div>
            </div>

            <!-- Transaction Logs Table -->
            <div class="table-responsive" style="margin-bottom: var(--spacing-6);">
                <table class="table" id="inventory_updates-table">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Warehouse</th>
                            <th>Transaction Type</th>
                            <th>Quantity</th>
                            <th>Prev Qty</th>
                            <th>New Qty</th>
                            <th>Ref Code</th>
                            <th>Updated By</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="form-navigation">
                <button type="button" class="btn btn-secondary" onclick="jumpToStep(15)">Back</button>
                <div>
                    <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-16-action').value='save_draft'">Save Draft</button>
                    <button type="submit" class="btn btn-primary">Complete Warehousing, Fulfillment & Logistics</button>
                </div>
            </div>
        </form>
    </div>

</div>

</div>{{-- /tab-content-wizard --}}
@endsection

@section('dashboard_scripts')
<script>
    // State arrays from PHP
    const listStore = {
        shipment: @json($shipments),
        receiving: @json($receivings),
        verification: @json($verifications),
        inspection: @json($inspections),
        storage: @json($storageRecords),
        inventory: @json($inventories),
        order: @json($orders),
        pick: @json($picks),
        label: @json($labels),
        carrier: @json($carriers),
        tracking: @json($trackings),
        delivery: @json($deliveries),
        return: @json($returns),
        inventory_update: @json($inventoryUpdates)
    };

    // Scroll stepper timeline
    const stepperContainer = document.getElementById('stepper-scroll-container');
    function scrollStepper(amount) {
        if (stepperContainer) {
            stepperContainer.scrollBy({ left: amount, behavior: 'smooth' });
        }
    }

    // Toggle special logistics storage requirements
    function toggleSpecialLogistics(value) {
        const container = document.getElementById('sh_fields_container');
        if (container) {
            container.style.display = (value === 'yes') ? 'block' : 'none';
        }
    }

    // Show/Hide forms
    function showAddForm(type) {
        const form = document.getElementById(`add-${type}-form`);
        if (form) form.style.display = 'block';
    }
    function hideAddForm(type) {
        const form = document.getElementById(`add-${type}-form`);
        if (form) form.style.display = 'none';
    }

    // Dynamic Multi File upload cloner
    function moveAndStoreFiles(type, inputId, index, isMultiple = true) {
        const originalInput = document.getElementById(inputId);
        if (!originalInput || originalInput.files.length === 0) return;

        const filesContainer = document.getElementById(`${type}s-files-container`);
        if (!filesContainer) return;

        let itemWrapper = document.getElementById(`${type}-file-item-${index}`);
        if (!itemWrapper) {
            itemWrapper = document.createElement('div');
            itemWrapper.id = `${type}-file-item-${index}`;
            filesContainer.appendChild(itemWrapper);
        }

        const nameAttr = isMultiple ? `${type}s[${index}][${inputId.split('_')[1]}][]` : `${type}s[${index}][${inputId.split('_')[1]}]`;
        originalInput.name = nameAttr;
        originalInput.id = `${inputId}_stored_${index}`;
        itemWrapper.appendChild(originalInput);

        const newInput = document.createElement('input');
        newInput.type = 'file';
        newInput.id = inputId;
        if (isMultiple) newInput.multiple = true;
        newInput.className = 'form-control';
        originalInput.parentNode.replaceChild(newInput, originalInput);
    }

    // Add entries
    function addShipmentRecord() {
        const index = listStore.shipment.length;
        const item = {
            reference: document.getElementById('ship_reference').value,
            supplier: document.getElementById('ship_supplier').value,
            warehouse: document.getElementById('ship_warehouse').value,
            products: document.getElementById('ship_products').value.split(',').map(s => s.trim()),
            quantity: document.getElementById('ship_quantity').value,
            cartons: document.getElementById('ship_cartons').value,
            method: document.getElementById('ship_method').value,
            carrier: document.getElementById('ship_carrier').value,
            tracking_number: document.getElementById('ship_tracking_number').value,
            tracking_url: document.getElementById('ship_tracking_url').value,
            ship_date: document.getElementById('ship_ship_date').value,
            arrival_date: document.getElementById('ship_arrival_date').value,
            status: document.getElementById('ship_status').value,
            notes: document.getElementById('ship_notes').value
        };

        if (!item.reference || !item.quantity) {
            alert('Shipment Reference and Total Quantity are required.');
            return;
        }

        moveAndStoreFiles('shipment', 'ship_documents', index, true);

        listStore.shipment.push(item);
        renderTable('shipment');
        hideAddForm('shipment');
    }

    function syncReceivingExpectedQty(ref) {
        const select = document.getElementById('rec_shipment_ref');
        const qty = select.options[select.selectedIndex].getAttribute('data-qty') || 0;
        const cartons = select.options[select.selectedIndex].getAttribute('data-cartons') || 0;
        const wh = select.options[select.selectedIndex].getAttribute('data-wh') || '';

        document.getElementById('rec_expected_qty').value = qty;
        document.getElementById('rec_cartons_expected').value = cartons;
        document.getElementById('rec_warehouse').value = wh;
        calcReceivingDiff();
    }

    function calcReceivingDiff() {
        const expected = parseInt(document.getElementById('rec_expected_qty').value) || 0;
        const received = parseInt(document.getElementById('rec_received_qty').value) || 0;
        const diff = received - expected;

        const section = document.getElementById('rec_diff_section');
        if (diff !== 0) {
            section.style.display = 'grid';
            document.getElementById('rec_diff_qty').value = Math.abs(diff);
            document.getElementById('rec_diff_type').value = diff < 0 ? 'Shortage' : 'Excess';
        } else {
            section.style.display = 'none';
            document.getElementById('rec_diff_qty').value = 0;
            document.getElementById('rec_diff_type').value = 'Correct';
        }
    }

    function addReceivingRecord() {
        const index = listStore.receiving.length;
        const item = {
            shipment_ref: document.getElementById('rec_shipment_ref').value,
            warehouse: document.getElementById('rec_warehouse').value,
            receive_date: document.getElementById('rec_receive_date').value,
            received_by: document.getElementById('rec_received_by').value,
            expected_qty: document.getElementById('rec_expected_qty').value,
            received_qty: document.getElementById('rec_received_qty').value,
            cartons_expected: document.getElementById('rec_cartons_expected').value,
            cartons_received: document.getElementById('rec_cartons_received').value,
            status: document.getElementById('rec_status').value,
            notes: document.getElementById('rec_notes').value,
            diff_qty: parseInt(document.getElementById('rec_diff_qty').value) || 0,
            diff_type: document.getElementById('rec_diff_type').value
        };

        if (!item.shipment_ref || !item.received_qty) {
            alert('Shipment Reference and Received Quantity are required.');
            return;
        }

        moveAndStoreFiles('receiving', 'rec_documents', index, true);
        moveAndStoreFiles('receiving', 'rec_photos', index, true);

        listStore.receiving.push(item);
        renderTable('receiving');
        hideAddForm('receiving');
    }

    function syncVerificationDetails(ref) {
        const select = document.getElementById('ver_shipment_ref');
        const expected = select.options[select.selectedIndex].getAttribute('data-expected') || 0;
        const received = select.options[select.selectedIndex].getAttribute('data-received') || 0;

        document.getElementById('ver_expected_qty').value = expected;
        document.getElementById('ver_received_qty').value = received;
        document.getElementById('ver_verified_qty').value = received;
        calcVerificationDiff();
    }

    function calcVerificationDiff() {
        const verified = parseInt(document.getElementById('ver_verified_qty').value) || 0;
        const expected = parseInt(document.getElementById('ver_expected_qty').value) || 0;
        const diff = verified - expected;

        document.getElementById('ver_difference').value = diff;
        const status = document.getElementById('ver_status');
        if (diff < 0) {
            status.value = 'Short';
        } else if (diff > 0) {
            status.value = 'Excess';
        } else {
            status.value = 'Verified';
        }
    }

    function addVerificationRecord() {
        const item = {
            shipment_ref: document.getElementById('ver_shipment_ref').value,
            sku: document.getElementById('ver_sku').value,
            expected_qty: document.getElementById('ver_expected_qty').value,
            received_qty: document.getElementById('ver_received_qty').value,
            verified_qty: document.getElementById('ver_verified_qty').value,
            difference: document.getElementById('ver_difference').value,
            status: document.getElementById('ver_status').value,
            verified_by: document.getElementById('ver_verified_by').value,
            verify_date: document.getElementById('ver_verify_date').value,
            notes: document.getElementById('ver_notes').value
        };

        if (!item.shipment_ref || !item.verified_qty) {
            alert('Shipment Reference and Verified Quantity are required.');
            return;
        }

        listStore.verification.push(item);
        renderTable('verification');
        hideAddForm('verification');
    }

    function toggleInspectionDefects(value) {
        const div = document.getElementById('ins_defect_fields');
        if (div) {
            div.style.display = (value === 'yes') ? 'block' : 'none';
        }
    }

    function calcQualityScore() {
        const checkedCount = document.querySelectorAll('input[name="ins_checklist"]:checked').length;
        const total = 9;
        const score = Math.round((checkedCount / total) * 100);
        document.getElementById('ins_score').value = score;
    }

    function addInspectionRecord() {
        const index = listStore.inspection.length;
        const checklist = [];
        document.querySelectorAll('input[name="ins_checklist"]:checked').forEach(c => checklist.push(c.value));

        const item = {
            shipment_ref: document.getElementById('ins_shipment_ref').value,
            sku: document.getElementById('ins_sku').value,
            inspect_date: document.getElementById('ins_inspect_date').value,
            inspector: document.getElementById('ins_inspector').value,
            status: document.getElementById('ins_status').value,
            decision: document.getElementById('ins_decision').value,
            score: document.getElementById('ins_score').value,
            defect_qty: document.getElementById('ins_defect_qty').value,
            defects_found: document.querySelector('input[name="ins_defects_radio"]:checked').value,
            defect_details: document.getElementById('ins_defect_details').value,
            checklist: checklist
        };

        if (!item.shipment_ref || !item.inspector) {
            alert('Shipment and Inspector are required.');
            return;
        }

        moveAndStoreFiles('inspection', 'ins_photos', index, true);
        moveAndStoreFiles('inspection', 'ins_report', index, false);

        listStore.inspection.push(item);
        renderTable('inspection');
        hideAddForm('inspection');
    }

    function addStorageRecord() {
        const item = {
            warehouse: document.getElementById('st_warehouse').value,
            sku: document.getElementById('st_sku').value,
            quantity: document.getElementById('st_quantity').value,
            location_code: document.getElementById('st_location_code').value,
            shelf_bin: document.getElementById('st_shelf_bin').value,
            storage_date: document.getElementById('st_storage_date').value,
            status: document.getElementById('st_status').value,
            notes: document.getElementById('st_notes').value
        };

        if (!item.sku || !item.quantity) {
            alert('SKU and Quantity are required.');
            return;
        }

        listStore.storage.push(item);
        renderTable('storage');
        hideAddForm('storage');
    }

    function addInventoryRecord() {
        const item = {
            sku: document.getElementById('inv_sku').value,
            product_name: document.getElementById('inv_product_name').value,
            warehouse: document.getElementById('inv_warehouse').value,
            location_code: document.getElementById('inv_location_code').value,
            available: document.getElementById('inv_available').value,
            reserved: document.getElementById('inv_reserved').value,
            damaged: document.getElementById('inv_damaged').value,
            total: parseInt(document.getElementById('inv_available').value) + parseInt(document.getElementById('inv_reserved').value) + parseInt(document.getElementById('inv_damaged').value),
            reorder_level: document.getElementById('inv_reorder_level').value,
            reorder_qty: document.getElementById('inv_reorder_qty').value,
            status: 'In Stock',
            notes: document.getElementById('inv_notes').value
        };

        if (!item.sku || !item.product_name) {
            alert('SKU and Product Name are required.');
            return;
        }

        const exists = listStore.inventory.some(i => i.sku === item.sku && i.warehouse === item.warehouse);
        if (exists) {
            alert('Inventory SKU record already exists for this Warehouse.');
            return;
        }

        listStore.inventory.push(item);
        renderTable('inventory');
        hideAddForm('inventory');
    }

    function addOrderRecord() {
        const item = {
            order_id: document.getElementById('ord_order_id').value,
            source: document.getElementById('ord_source').value,
            customer_name: document.getElementById('ord_customer_name').value,
            order_date: document.getElementById('ord_order_date').value,
            warehouse: document.getElementById('ord_warehouse').value,
            amount: document.getElementById('ord_amount').value,
            status: document.getElementById('ord_status').value,
            priority: document.getElementById('ord_priority').value,
            products: document.getElementById('ord_products').value.split(',').map(s => s.trim()),
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

    function syncPickOrderWarehouse(orderId) {
        const select = document.getElementById('pick_order_id');
        const wh = select.options[select.selectedIndex].getAttribute('data-wh') || '';
        document.getElementById('pick_warehouse').value = wh;
    }

    function syncPickStorageLocation(sku) {
        const select = document.getElementById('pick_sku');
        const loc = select.options[select.selectedIndex].getAttribute('data-loc') || '';
        document.getElementById('pick_location_code').value = loc;
    }

    function addPickRecord() {
        const index = listStore.pick.length;
        const item = {
            order_id: document.getElementById('pick_order_id').value,
            warehouse: document.getElementById('pick_warehouse').value,
            sku: document.getElementById('pick_sku').value,
            required_qty: document.getElementById('pick_required_qty').value,
            picked_qty: document.getElementById('pick_picked_qty').value,
            location_code: document.getElementById('pick_location_code').value,
            pick_status: document.getElementById('pick_pick_status').value,
            packed_by: document.getElementById('pick_packed_by').value,
            pack_date: document.getElementById('pick_pack_date').value,
            pkg_count: document.getElementById('pick_pkg_count').value,
            pkg_weight: document.getElementById('pick_pkg_weight').value,
            pkg_dims: document.getElementById('pick_pkg_dims').value,
            pkg_type: document.getElementById('pick_pkg_type').value,
            notes: document.getElementById('pick_notes').value
        };

        if (!item.order_id || !item.sku) {
            alert('Order ID and SKU are required.');
            return;
        }

        moveAndStoreFiles('pick', 'pick_photos', index, true);

        listStore.pick.push(item);
        renderTable('pick');
        hideAddForm('pick');
    }

    function syncLabelOrderDetails(orderId) {
        const select = document.getElementById('lbl_order_id');
        const weight = select.options[select.selectedIndex].getAttribute('data-weight') || 0;
        const dims = select.options[select.selectedIndex].getAttribute('data-dims') || '';

        document.getElementById('lbl_pkg_weight').value = weight;
        document.getElementById('lbl_pkg_dims').value = dims;
    }

    function addLabelRecord() {
        const index = listStore.label.length;
        const item = {
            order_id: document.getElementById('lbl_order_id').value,
            recipient_name: document.getElementById('lbl_recipient_name').value,
            recipient_address: document.getElementById('lbl_recipient_address').value,
            city: document.getElementById('lbl_city').value,
            state: document.getElementById('lbl_state').value,
            zip_code: document.getElementById('lbl_zip_code').value,
            country: document.getElementById('lbl_country').value,
            pkg_weight: document.getElementById('lbl_pkg_weight').value,
            pkg_dims: document.getElementById('lbl_pkg_dims').value,
            shipping_service: document.getElementById('lbl_shipping_service').value,
            label_status: document.getElementById('lbl_label_status').value,
            tracking_number: document.getElementById('lbl_tracking_number').value,
            tracking_url: document.getElementById('lbl_tracking_url').value
        };

        if (!item.order_id || !item.tracking_number) {
            alert('Order ID and Tracking Number are required.');
            return;
        }

        moveAndStoreFiles('label', 'lbl_label_file', index, false);

        listStore.label.push(item);
        renderTable('label');
        hideAddForm('label');
    }

    function syncCarrierTrackingNo(orderId) {
        const select = document.getElementById('car_order_id');
        const tracking = select.options[select.selectedIndex].getAttribute('data-tracking') || '';
        document.getElementById('car_tracking_number').value = tracking;
    }

    function addCarrierRecord() {
        const item = {
            order_id: document.getElementById('car_order_id').value,
            carrier_name: document.getElementById('car_carrier_name').value,
            service_type: document.getElementById('car_service_type').value,
            tracking_number: document.getElementById('car_tracking_number').value,
            shipping_cost: document.getElementById('car_shipping_cost').value,
            pickup_date: document.getElementById('car_pickup_date').value,
            est_delivery_date: document.getElementById('car_est_delivery_date').value,
            status: document.getElementById('car_status').value,
            notes: document.getElementById('car_notes').value
        };

        if (!item.order_id || !item.shipping_cost) {
            alert('Order ID and Shipping Cost are required.');
            return;
        }

        listStore.carrier.push(item);
        renderTable('carrier');
        hideAddForm('carrier');
    }

    function syncTrackingDetails(orderId) {
        const select = document.getElementById('tr_order_id');
        const tracking = select.options[select.selectedIndex].getAttribute('data-tracking') || '';
        const carrier = select.options[select.selectedIndex].getAttribute('data-carrier') || '';
        const est = select.options[select.selectedIndex].getAttribute('data-est') || '';

        document.getElementById('tr_tracking_number').value = tracking;
        document.getElementById('tr_carrier').value = carrier;
        document.getElementById('tr_est_delivery').value = est;
    }

    function updateInteractiveTimeline(status) {
        // Reset all timeline dots
        document.querySelectorAll('.timeline-dot').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.timeline-label').forEach(el => el.classList.remove('active'));

        const milestones = {
            'Label Created': ['processed'],
            'Picked Up': ['processed', 'picked'],
            'In Transit': ['processed', 'picked', 'shipped', 'transit'],
            'Out for Delivery': ['processed', 'picked', 'shipped', 'transit', 'outfordel'],
            'Delivered': ['processed', 'picked', 'shipped', 'transit', 'outfordel', 'delivered']
        };

        const activeKeys = milestones[status] || ['processed'];
        activeKeys.forEach(key => {
            const dot = document.getElementById('dot-' + key);
            const lbl = document.getElementById('label-' + key);
            if (dot) dot.classList.add('active');
            if (lbl) lbl.classList.add('active');
        });
    }

    function addTrackingRecord() {
        const item = {
            order_id: document.getElementById('tr_order_id').value,
            tracking_number: document.getElementById('tr_tracking_number').value,
            carrier: document.getElementById('tr_carrier').value,
            tracking_url: document.getElementById('tr_tracking_url').value,
            status: document.getElementById('tr_status').value,
            last_update: document.getElementById('tr_last_update').value,
            est_delivery: document.getElementById('tr_est_delivery').value,
            notes: document.getElementById('tr_notes').value
        };

        if (!item.order_id || !item.last_update) {
            alert('Order ID and Last Update Timestamp are required.');
            return;
        }

        listStore.tracking.push(item);
        renderTable('tracking');
        hideAddForm('tracking');
    }

    function syncDeliveryCustomer(orderId) {
        // Fetch order details
        const order = listStore.order.find(o => o.order_id === orderId);
        if (order) {
            document.getElementById('del_customer_name').value = order.customer_name;
        }
    }

    function toggleDeliveryFailure(value) {
        const div = document.getElementById('del_failure_section');
        if (div) {
            div.style.display = (value === 'Failed') ? 'block' : 'none';
        }
    }

    function addDeliveryRecord() {
        const index = listStore.delivery.length;
        const item = {
            order_id: document.getElementById('del_order_id').value,
            customer_name: document.getElementById('del_customer_name').value,
            delivery_date: document.getElementById('del_delivery_date').value,
            status: document.getElementById('del_status').value,
            received_by: document.getElementById('del_received_by').value,
            notes: document.getElementById('del_notes').value,
            failure_reason: document.getElementById('del_status').value === 'Failed' ? document.getElementById('del_failure_reason').value : '',
            failure_notes: document.getElementById('del_status').value === 'Failed' ? document.getElementById('del_failure_notes').value : ''
        };

        if (!item.order_id || !item.delivery_date) {
            alert('Order ID and Delivery Date are required.');
            return;
        }

        moveAndStoreFiles('delivery', 'del_proof', index, false);

        listStore.delivery.push(item);
        renderTable('delivery');
        hideAddForm('delivery');
    }

    function syncReturnCustomer(orderId) {
        const order = listStore.order.find(o => o.order_id === orderId);
        if (order) {
            document.getElementById('ret_customer_name').value = order.customer_name;
        }
    }

    function addReturnRecord() {
        const index = listStore.return.length;
        const item = {
            order_id: document.getElementById('ret_order_id').value,
            customer_name: document.getElementById('ret_customer_name').value,
            request_date: document.getElementById('ret_request_date').value,
            sku: document.getElementById('ret_sku').value,
            quantity: document.getElementById('ret_quantity').value,
            reason: document.getElementById('ret_reason').value,
            status: document.getElementById('ret_status').value,
            tracking_number: document.getElementById('ret_tracking_number').value,
            carrier: document.getElementById('ret_carrier').value,
            received_date: document.getElementById('ret_received_date').value,
            inspection_result: document.getElementById('ret_inspection_result').value,
            notes: document.getElementById('ret_notes').value
        };

        if (!item.order_id || !item.sku) {
            alert('Order ID and SKU are required.');
            return;
        }

        moveAndStoreFiles('return', 'ret_photos', index, true);

        listStore.return.push(item);
        renderTable('return');
        hideAddForm('return');
    }

    function syncUpdateStockQty(sku) {
        const select = document.getElementById('upd_sku');
        const wh = select.options[select.selectedIndex].getAttribute('data-wh') || '';
        const avail = select.options[select.selectedIndex].getAttribute('data-avail') || 0;

        document.getElementById('upd_warehouse').value = wh;
        document.getElementById('upd_prev_quantity').value = avail;
        calculateUpdateNewQty();
    }

    function calculateUpdateNewQty() {
        const prev = parseInt(document.getElementById('upd_prev_quantity').value) || 0;
        const qty = parseInt(document.getElementById('upd_quantity').value) || 0;
        const type = document.getElementById('upd_transaction_type').value;

        let newQty = prev;
        if (type === 'Received' || type === 'Returned') {
            newQty = prev + qty;
        } else {
            newQty = Math.max(prev - qty, 0);
        }

        document.getElementById('upd_new_quantity').value = newQty;
    }

    function addInventoryUpdateRecord() {
        const item = {
            sku: document.getElementById('upd_sku').value,
            warehouse: document.getElementById('upd_warehouse').value,
            transaction_type: document.getElementById('upd_transaction_type').value,
            quantity: document.getElementById('upd_quantity').value,
            prev_quantity: document.getElementById('upd_prev_quantity').value,
            new_quantity: document.getElementById('upd_new_quantity').value,
            ref_type: document.getElementById('upd_ref_type').value,
            ref_id: document.getElementById('upd_ref_id').value,
            reason: document.getElementById('upd_reason').value,
            updated_by: document.getElementById('upd_updated_by').value,
            updated_date: document.getElementById('upd_updated_date').value
        };

        if (!item.sku || !item.quantity) {
            alert('SKU and Transaction Quantity are required.');
            return;
        }

        listStore.inventory_update.push(item);
        renderTable('inventory_update');
        hideAddForm('inventory_update');
    }

    // Remove log entry helper
    function removeListItem(type, idx) {
        listStore[type].splice(idx, 1);
        renderTable(type);

        const wrapper = document.getElementById(`${type}-file-item-${idx}`);
        if (wrapper) wrapper.remove();
    }

    // Dynamic list store Table renderer
    function renderTable(type) {
        const tbody = document.querySelector(`#${type}s-table tbody`);
        if (!tbody) return;
        tbody.innerHTML = '';

        const list = listStore[type];

        list.forEach((item, idx) => {
            const tr = document.createElement('tr');

            if (type === 'shipment') {
                tr.innerHTML = `
                    <td><strong>${item.reference}</strong></td>
                    <td>${item.supplier}</td>
                    <td>${item.warehouse}</td>
                    <td><code>${item.products ? item.products.join(', ') : '-'}</code></td>
                    <td>Qty: ${item.quantity} • Crt: ${item.cartons}</td>
                    <td>Depart: ${item.ship_date} • Arrival: ${item.arrival_date}</td>
                    <td><span class="badge badge-info">${item.status}</span></td>
                    <td><span style="font-size: 10px;">Docs: ${item.documents ? item.documents.length : 0}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('shipment', ${idx})">Delete</button></td>
                `;
            } else if (type === 'receiving') {
                tr.innerHTML = `
                    <td><code>${item.shipment_ref}</code></td>
                    <td>${item.warehouse}</td>
                    <td><strong>${item.received_by}</strong></td>
                    <td>${item.receive_date}</td>
                    <td>Expected: ${item.expected_qty} • Received: ${item.received_qty}</td>
                    <td>Expected: ${item.cartons_expected} • Received: ${item.cartons_received}</td>
                    <td><strong style="color: ${item.diff_qty !== 0 ? 'var(--color-danger)' : 'var(--color-success)'};">${item.diff_qty} (${item.diff_type})</strong></td>
                    <td><span class="badge badge-info">${item.status}</span></td>
                    <td><span style="font-size: 10px;">Docs: ${item.documents ? item.documents.length : 0} • Pht: ${item.photos ? item.photos.length : 0}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('receiving', ${idx})">Delete</button></td>
                `;
            } else if (type === 'verification') {
                tr.innerHTML = `
                    <td><code>${item.shipment_ref}</code></td>
                    <td><code>${item.sku}</code></td>
                    <td>${item.expected_qty}</td>
                    <td>${item.received_qty}</td>
                    <td><strong>${item.verified_qty}</strong></td>
                    <td><span style="font-weight: bold; color: ${item.difference !== 0 ? 'var(--color-danger)' : 'var(--color-success)'};">${item.difference}</span></td>
                    <td><span class="badge badge-info">${item.status}</span></td>
                    <td>${item.verified_by}</td>
                    <td>${item.verify_date}</td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('verification', ${idx})">Delete</button></td>
                `;
            } else if (type === 'inspection') {
                tr.innerHTML = `
                    <td><code>${item.shipment_ref}</code></td>
                    <td><code>${item.sku}</code></td>
                    <td>${item.inspect_date}</td>
                    <td><strong>${item.inspector}</strong></td>
                    <td>${item.defects_found === 'yes' ? 'Qty: ' + item.defect_qty : 'None'}</td>
                    <td><strong>${item.score}%</strong></td>
                    <td><span class="badge badge-warning">${item.decision}</span></td>
                    <td><span style="font-size: 10px;">Photos: ${item.photos ? item.photos.length : 0} • Rep: ${item.report ? 'Yes' : 'No'}</span></td>
                    <td><span class="badge badge-success">${item.status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('inspection', ${idx})">Delete</button></td>
                `;
            } else if (type === 'storage') {
                tr.innerHTML = `
                    <td>${item.warehouse}</td>
                    <td><code>${item.sku}</code></td>
                    <td><strong>${item.quantity}</strong></td>
                    <td>${item.location_code}</td>
                    <td><code>${item.shelf_bin}</code></td>
                    <td>${item.storage_date}</td>
                    <td><span class="badge badge-success">${item.status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('storage', ${idx})">Delete</button></td>
                `;
            } else if (type === 'inventory') {
                tr.innerHTML = `
                    <td><code>${item.sku}</code></td>
                    <td><strong>${item.product_name}</strong></td>
                    <td>${item.warehouse}</td>
                    <td><code>${item.location_code}</code></td>
                    <td><strong>${item.available}</strong></td>
                    <td>${item.reserved}</td>
                    <td>${item.damaged}</td>
                    <td>${item.total}</td>
                    <td>${item.reorder_level}</td>
                    <td><span class="badge badge-success">${item.available <= 0 ? 'Out of Stock' : (item.available <= item.reorder_level ? 'Low Stock' : 'In Stock')}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('inventory', ${idx})">Delete</button></td>
                `;
            } else if (type === 'order') {
                tr.innerHTML = `
                    <td><code>${item.order_id}</code></td>
                    <td>${item.source}</td>
                    <td><strong>${item.customer_name}</strong></td>
                    <td>${item.order_date}</td>
                    <td>${item.warehouse}</td>
                    <td><code>${item.products ? item.products.join(', ') : '-'}</code></td>
                    <td>$${parseFloat(item.amount).toFixed(2)}</td>
                    <td><span class="badge badge-warning">${item.priority}</span></td>
                    <td><span class="badge badge-info">${item.status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('order', ${idx})">Delete</button></td>
                `;
            } else if (type === 'pick') {
                tr.innerHTML = `
                    <td><code>${item.order_id}</code></td>
                    <td>${item.warehouse}</td>
                    <td><code>${item.sku}</code></td>
                    <td>${item.required_qty}</td>
                    <td><strong>${item.picked_qty}</strong></td>
                    <td><code>${item.location_code}</code></td>
                    <td>${item.packed_by}</td>
                    <td>${item.pkg_weight} lbs • ${item.pkg_dims}</td>
                    <td><span style="font-size: 10px;">Photos: ${item.photos ? item.photos.length : 0}</span></td>
                    <td><span class="badge badge-info">${item.pick_status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('pick', ${idx})">Delete</button></td>
                `;
            } else if (type === 'label') {
                tr.innerHTML = `
                    <td><code>${item.order_id}</code></td>
                    <td><strong>${item.recipient_name}</strong></td>
                    <td>${item.city} (${item.zip_code})</td>
                    <td>${item.pkg_weight} lbs • ${item.pkg_dims}</td>
                    <td>${item.shipping_service}</td>
                    <td><code>${item.tracking_number}</code></td>
                    <td><span class="badge badge-success">${item.label_status}</span></td>
                    <td><span style="font-size: 10px;">${item.label_file ? 'Uploaded Label' : 'No file'}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('label', ${idx})">Delete</button></td>
                `;
            } else if (type === 'carrier') {
                tr.innerHTML = `
                    <td><code>${item.order_id}</code></td>
                    <td><strong>${item.carrier_name}</strong></td>
                    <td>${item.service_type}</td>
                    <td><code>${item.tracking_number}</code></td>
                    <td>$${parseFloat(item.shipping_cost).toFixed(2)}</td>
                    <td>${item.pickup_date}</td>
                    <td>${item.est_delivery_date}</td>
                    <td><span class="badge badge-info">${item.status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('carrier', ${idx})">Delete</button></td>
                `;
            } else if (type === 'tracking') {
                tr.innerHTML = `
                    <td><code>${item.order_id}</code></td>
                    <td><code>${item.tracking_number}</code></td>
                    <td>${item.carrier}</td>
                    <td><span class="badge badge-info">${item.status}</span></td>
                    <td>${item.last_update.replace('T', ' ')}</td>
                    <td>${item.est_delivery}</td>
                    <td>${item.notes || '-'}</td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('tracking', ${idx})">Delete</button></td>
                `;
            } else if (type === 'delivery') {
                tr.innerHTML = `
                    <td><code>${item.order_id}</code></td>
                    <td><strong>${item.customer_name}</strong></td>
                    <td>${item.delivery_date}</td>
                    <td><span class="badge badge-success">${item.status}</span></td>
                    <td>${item.received_by || '-'}</td>
                    <td>${item.status === 'Failed' ? item.failure_reason : '-'}</td>
                    <td><span style="font-size: 10px;">${item.proof ? 'Proof Uploaded' : 'No proof'}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('delivery', ${idx})">Delete</button></td>
                `;
            } else if (type === 'return') {
                tr.innerHTML = `
                    <td><code>${item.order_id}</code></td>
                    <td>${item.customer_name}</td>
                    <td>${item.request_date}</td>
                    <td><code>${item.sku}</code> (Qty: ${item.quantity})</td>
                    <td>${item.reason}</td>
                    <td>${item.tracking_number} (${item.carrier})</td>
                    <td>Result: ${item.inspection_result || 'Pending'}</td>
                    <td><span style="font-size: 10px;">Photos: ${item.photos ? item.photos.length : 0}</span></td>
                    <td><span class="badge badge-warning">${item.status}</span></td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('return', ${idx})">Delete</button></td>
                `;
            } else if (type === 'inventory_update') {
                tr.innerHTML = `
                    <td><code>${item.sku}</code></td>
                    <td>${item.warehouse}</td>
                    <td><span class="badge badge-info">${item.transaction_type}</span></td>
                    <td><strong>${item.quantity}</strong></td>
                    <td>${item.prev_quantity}</td>
                    <td><strong>${item.new_quantity}</strong></td>
                    <td><code>${item.ref_type}: ${item.ref_id}</code></td>
                    <td>${item.updated_by}</td>
                    <td>${item.updated_date.replace('T', ' ')}</td>
                    <td><button type="button" class="btn btn-secondary" style="height: 24px; padding: 0 8px; font-size: 10px; color: var(--color-danger);" onclick="removeListItem('inventory_update', ${idx})">Delete</button></td>
                `;
            }

            tbody.appendChild(tr);
        });

        // Sync hidden inputs
        updateHiddenInputs(type === 'inventory_update' ? 'inventory_updates' :
                           type === 'verification' ? 'verifications' :
                           type === 'inspection' ? 'inspections' :
                           type === 'storage' ? 'storage_records' :
                           type === 'inventory' ? 'inventories' :
                           type === 'order' ? 'orders' :
                           type === 'pick' ? 'picks' :
                           type === 'label' ? 'labels' :
                           type === 'carrier' ? 'carriers' :
                           type === 'tracking' ? 'trackings' :
                           type === 'delivery' ? 'deliveries' :
                           type === 'return' ? 'returns' : type + 's', list);
    }

    // Sync input helpers
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

    // Multi-select cards
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
        for (let i = 1; i <= 16; i++) {
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

    // Stepper jump to specific step
    function jumpToStep(stepNumber) {
        if (isEditMode && stepNumber !== editModeStep) return;

        for (let i = 1; i <= 16; i++) {
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

    // Boot and render tables
    document.addEventListener('DOMContentLoaded', function() {
        initCustomMultiselect('target_countries_container');

        // Render all tables
        renderTable('shipment');
        renderTable('receiving');
        renderTable('verification');
        renderTable('inspection');
        renderTable('storage');
        renderTable('inventory');
        renderTable('order');
        renderTable('pick');
        renderTable('label');
        renderTable('carrier');
        renderTable('tracking');
        renderTable('delivery');
        renderTable('return');
        renderTable('inventory_update');
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
