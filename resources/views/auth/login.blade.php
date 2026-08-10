@extends('layouts.admin')

@section('title', 'Admin Sign In')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('layout_content')
<div class="auth-layout">
    <div class="auth-container">
        
        <!-- Logo -->
        <div class="auth-logo">
            <div class="auth-logo-icon">S</div>
            <div class="auth-logo-text">SaaS<span style="color: var(--color-primary);">Admin</span></div>
        </div>

        <!-- Login Card -->
        <div class="auth-card">
            <div class="auth-header">
                <h1>Sign in to portal</h1>
                <p>Enter admin credentials below to access dashboard</p>
            </div>

            <!-- Errors Alerts -->
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf

                <!-- Email Input -->
                <div class="form-group">
                    <label for="email" class="form-label">Email address</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="form-control" 
                        placeholder="admin@admin.com" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email" 
                        autofocus
                    >
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="form-group" style="margin-bottom: var(--spacing-6);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-2);">
                        <label for="password" class="form-label" style="margin-bottom: 0;">Password</label>
                    </div>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-control" 
                        placeholder="••••••••" 
                        required 
                        autocomplete="current-password"
                    >
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="form-group form-check" style="margin-bottom: var(--spacing-6);">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                    <label for="remember" class="form-label" style="margin-bottom: 0; cursor: pointer; color: var(--color-text-secondary); user-select: none;">
                        Remember this device
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Continue to Dashboard
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            &copy; 2026 SaaS Portal. Secured with AES-256.
        </div>

    </div>
</div>
@endsection
