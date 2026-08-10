@extends('layouts.dashboard')

@section('title', $title)

@section('content')
<!-- Breadcrumbs -->
<nav class="breadcrumbs">
    <a href="{{ route('admin.dashboard') }}">Console</a>
    <a href="{{ route('services.index') }}">Services</a>
    <span>{{ $title }}</span>
</nav>

<!-- Page Header -->
<div class="service-header" style="margin-bottom: var(--spacing-6);">
    <div class="service-header-left">
        <h1 class="page-title" style="margin-bottom: var(--spacing-1);">{{ $title }}</h1>
        <p class="page-subtitle">{{ $desc }}</p>
    </div>
</div>

<!-- Placeholder Info Card -->
<div class="card" style="text-align: center; padding: var(--spacing-12) var(--spacing-8);">
    <div style="width: 64px; height: 64px; border-radius: var(--radius-full); background-color: var(--color-primary-light); color: var(--color-primary); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-5);">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 32px; height: 32px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
        </svg>
    </div>

    <h2 style="font-size: var(--fs-lg); font-weight: var(--fw-bold); color: var(--color-text-primary); margin-bottom: var(--spacing-2);">Module Under Construction</h2>
    <p style="max-width: 500px; margin: 0 auto var(--spacing-6); color: var(--color-text-secondary); font-size: var(--fs-sm);">
        Detailed operational steps, forms, and validation checklists for <strong>{{ $title }}</strong> are currently under design and will be implemented in subsequent phases.
    </p>

    <div style="display: inline-flex; gap: var(--spacing-3); justify-content: center;">
        <a href="{{ route('services.index') }}" class="btn btn-secondary">
            Back to Services
        </a>
        <button class="btn btn-primary" onclick="alert('Notification alert set. You will be notified when this service is available.')">
            Notify Me When Ready
        </button>
    </div>
</div>
@endsection
