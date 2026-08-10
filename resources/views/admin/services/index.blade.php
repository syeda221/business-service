@extends('layouts.dashboard')

@section('title', 'Services Portal')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
@endsection

@section('content')
<!-- Breadcrumbs -->
<nav class="breadcrumbs">
    <a href="{{ route('admin.dashboard') }}">Console</a>
    <span>Services</span>
</nav>

<!-- Page Header -->
<div class="service-header">
    <div class="service-header-left">
        <h1 class="page-title" style="margin-bottom: var(--spacing-1);">Services</h1>
        <p class="page-subtitle">Manage your business services and their workflows.</p>
    </div>
</div>

<!-- Services Grid -->
<div class="services-overview-grid">
    @foreach($services as $service)
        <div class="service-card">
            <div class="service-card-top">
                <div class="service-icon-box">
                    @if($service['key'] === 'business-setup')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-.554-8.243-1.557m0 0A8.96 8.96 0 003 12c0 1.952.625 3.76 1.686 5.236m15.628 0c1.06-1.475 1.686-3.284 1.686-5.236 0-.857-.12-1.686-.343-2.467" />
                        </svg>
                    @elseif($service['key'] === 'branding-website')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l8.904-4.473L21 21l-1.187-5.096A9.005 9.005 0 0012 3a9.003 9.003 0 00-2.187 12.904z" />
                        </svg>
                    @elseif($service['key'] === 'product-hunting')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
                        </svg>
                    @elseif($service['key'] === 'marketplace-retail')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.015a2.993 2.993 0 002.25 1.015c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 003.75.614m-16.5 0L8.25 3h7.5l3.87 6.35" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25M3 14.25h15m0 0l-3-3m3 3l-3 3M3.375 6h17.25c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125H3.375C2.754 9.75 2.25 9.246 2.25 8.625v-1.5C2.25 6.504 2.754 6 3.375 6z" />
                        </svg>
                    @endif
                </div>

                <h2 class="service-title">{{ $service['title'] }}</h2>
                <p class="service-desc">{{ $service['desc'] }}</p>

                <!-- Detailed parameters for active modules -->
                @if($service['steps_count'] > 0)
                    <div class="service-meta-row">
                        <span class="service-meta-label">Total Steps</span>
                        <span class="service-meta-value">{{ $service['steps_count'] }} Steps</span>
                    </div>

                    <div class="service-meta-row">
                        <span class="service-meta-label">Status</span>
                        <span class="service-meta-value">
                            @if($service['status'] === 'completed')
                                <span class="badge badge-success">Completed</span>
                            @elseif($service['status'] === 'in_progress')
                                <span class="badge badge-warning">In Progress</span>
                            @else
                                <span class="badge badge-secondary" style="background-color: var(--color-bg-base); border: 1px solid var(--color-border); color: var(--color-text-secondary);">Not Started</span>
                            @endif
                        </span>
                    </div>

                    @if($service['status'] === 'in_progress' && !empty($service['stats']))
                        <div style="margin-top: var(--spacing-3);">
                            <div style="display: flex; justify-content: space-between; font-size: 10px; color: var(--color-text-secondary); margin-bottom: 2px;">
                                <span>Completion</span>
                                <span>{{ $service['stats']['percentage'] }}%</span>
                            </div>
                            <div class="progress-bar-outer" style="height: 6px;">
                                <div class="progress-bar-inner" style="width: {{ $service['stats']['percentage'] }}%;"></div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="service-meta-row">
                        <span class="service-meta-label">Module Type</span>
                        <span class="service-meta-value" style="color: var(--color-text-muted);">Overview level</span>
                    </div>
                @endif
            </div>

            <div class="service-card-bottom">
                <a href="{{ route('services.show', $service['key']) }}" class="btn btn-secondary" style="width: 100%;">
                    @if($service['steps_count'] > 0)
                        @if($service['status'] === 'completed')
                            Review Requirements
                        @elseif($service['status'] === 'in_progress')
                            Resume Setup
                        @else
                            Start Service Setup
                        @endif
                    @else
                        View Service Overview
                    @endif
                </a>
            </div>
        </div>
    @endforeach
</div>
@endsection
