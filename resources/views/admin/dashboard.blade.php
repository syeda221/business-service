@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Breadcrumbs -->
<nav class="breadcrumbs">
    <a href="{{ route('admin.dashboard') }}">Console</a>
    <span>Overview</span>
</nav>

<!-- Page Header -->
<div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: var(--spacing-4);">
    <div>
        <h1 class="page-title">Welcome back, {{ Auth::user()->name }}</h1>
        <p class="page-subtitle">Here is what is happening across your SaaS platform today.</p>
    </div>
    <div>
        <a href="{{ route('services.index') }}" class="btn btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Manage Services
        </a>
    </div>
</div>

<!-- KPI Metrics Grid -->
<section class="metrics-grid">
    @foreach($stats as $stat)
        <div class="metric-card">
            <div class="metric-top">
                <span class="metric-title">{{ $stat['title'] }}</span>
                <div class="metric-icon-wrapper">
                    @if($stat['icon'] == 'currency-dollar')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.22.058a7.08 7.08 0 005.155-1.009l.328-.223m-5.483-3.09l.08-.02a7.197 7.197 0 003.078-1.907m-2.77 1.907c0 1.258.497 2.467 1.385 3.375L12 18m0-12v12m0-12l-.385.289c-.888.908-1.385 2.117-1.385 3.375m5.155-1.009a7.08 7.08 0 01-5.155 1.01m5.155-1.01l-.22-.058a7.08 7.08 0 00-5.155 1.009" />
                        </svg>
                    @elseif($stat['icon'] == 'users')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94-3.198.001-.031c0-2.25-1.78-4.08-4.043-4.14M18 12.932V13c0 .88-.36 1.72-1 2.34l-2.68 2.68a2 2 0 01-2.83 0l-2.68-2.68a3.3 3.3 0 01-1-2.34v-.068m10.14-1.92a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                        </svg>
                    @elseif($stat['icon'] == 'trending-up')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.5 4.5 8.25-8.25M21 12V6h-6" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-5.25L9 10.5l-4.75-5.25M2 10.5h6" />
                        </svg>
                    @endif
                </div>
            </div>
            <div class="metric-value">{{ $stat['value'] }}</div>
            <div class="metric-footer">
                <span class="metric-change {{ $stat['trend'] }}">
                    {{ $stat['change'] }}
                </span>
                <span class="metric-desc">{{ $stat['description'] }}</span>
            </div>
        </div>
    @endforeach
</section>

<!-- Dashboard Content Grid -->
<div class="dashboard-grid">
    
    <!-- Recent Sales Table widget (Takes 2 cols) -->
    <div class="grid-col-span-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Transactions</h3>
            </div>
            
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ref ID</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $tx)
                            <tr>
                                <td style="font-weight: var(--fw-semibold); color: var(--color-text-primary);">{{ $tx['id'] }}</td>
                                <td>
                                    <div style="display: flex; flex-direction: column;">
                                        <span style="font-weight: var(--fw-medium); color: var(--color-text-primary);">{{ $tx['customer'] }}</span>
                                        <span style="font-size: var(--fs-xs); color: var(--color-text-muted);">{{ $tx['email'] }}</span>
                                    </div>
                                </td>
                                <td>{{ $tx['product'] }}</td>
                                <td style="font-weight: var(--fw-semibold); color: var(--color-text-primary);">{{ $tx['amount'] }}</td>
                                <td>
                                    @if($tx['status'] == 'Succeeded')
                                        <span class="badge badge-success">{{ $tx['status'] }}</span>
                                    @elseif($tx['status'] == 'Pending')
                                        <span class="badge badge-warning">{{ $tx['status'] }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ $tx['status'] }}</span>
                                    @endif
                                </td>
                                <td>{{ $tx['date'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px 16px; color: var(--color-text-muted);">
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 32px; height: 32px; opacity: 0.35;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                                        </svg>
                                        <span style="font-size: var(--fs-sm); font-weight: var(--fw-medium);">No recent transactions found</span>
                                        <span style="font-size: var(--fs-xs); opacity: 0.75;">When orders are processed in Marketplace & Retail services, they will appear here.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Products list widget (Takes 1 col) -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Top Performing Products</h3>
            </div>
            
            <div class="product-list">
                @forelse($topProducts as $product)
                    <div class="product-item">
                        <div class="product-info">
                            <span class="product-name">{{ $product['name'] }}</span>
                            <span class="product-sales">{{ $product['sales'] }} sales</span>
                        </div>
                        <span class="product-revenue">{{ $product['revenue'] }}</span>
                    </div>
                @empty
                    <div style="text-align: center; padding: 36px 16px; color: var(--color-text-muted); display: flex; flex-direction: column; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px; opacity: 0.35;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        <span style="font-size: var(--fs-xs); font-weight: var(--fw-medium);">No products added yet</span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
