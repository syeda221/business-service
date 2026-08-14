@extends('layouts.dashboard')

@section('title', 'Business Setup & Compliance')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
@endsection

@section('content')
<!-- Breadcrumbs -->
<nav class="breadcrumbs">
    <a href="{{ route('admin.dashboard') }}">Console</a>
    <a href="{{ route('services.index') }}">Services</a>
    <span>Business Setup & Compliance</span>
</nav>

@php
    $docs = $payload['documents'] ?? [];
    $hasRequiredDocs = !empty($docs['articles_of_organization']) && !empty($docs['operating_agreement']) && !empty($docs['ein_letter']);
    
    $completedStepsCount = 0;
    $totalStepsCount = 7;
    
    $checklist = [
        'Business Information' => !empty($payload['business_name']),
        'LLC Formation' => !empty($payload['has_llc']),
        'EIN' => !empty($payload['has_ein']),
        'Business Documents' => $hasRequiredDocs,
        'Business Banking' => !empty($payload['has_bank']),
        'Tax & Compliance' => !empty($payload['tax_status']),
        'Business Structure' => !empty($payload['business_model']),
    ];

    foreach($checklist as $stepTitle => $isDone) {
        if ($isDone) $completedStepsCount++;
    }

    $percentage = round(($completedStepsCount / $totalStepsCount) * 100);
    
    // Count missing docs
    $missingDocs = 0;
    if (empty($docs['articles_of_organization'])) $missingDocs++;
    if (empty($docs['operating_agreement'])) $missingDocs++;
    if (empty($docs['ein_letter'])) $missingDocs++;
@endphp

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

<!-- Tabs Navigation -->
<div class="tabs-navigation" style="margin-bottom: var(--spacing-3);">
    <button class="tab-btn {{ $percentage < 100 ? 'active' : '' }}" id="tab-btn-wizard" onclick="switchMainTab('wizard')" style="{{ $percentage == 100 ? 'display: none;' : '' }}">Setup Stepper</button>
    <button class="tab-btn {{ $percentage == 100 ? 'active' : '' }}" id="tab-btn-overview" onclick="switchMainTab('overview')" style="{{ $percentage < 100 ? 'display: none;' : '' }}">Overview Dashboard</button>
</div>

<!-- STEPS WIZARD -->
<div id="tab-content-wizard" class="tab-content {{ $percentage < 100 ? 'active' : '' }}">

    <!-- Mobile Step Status Bar -->
    <div class="mobile-step-indicator" style="display: flex; align-items: center; justify-content: space-between; background-color: #ffffff; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 10px 14px; margin-bottom: var(--spacing-3); font-size: var(--fs-xs); box-shadow: var(--shadow-card);">
        <div style="display: flex; align-items: center; gap: 6px;">
            <span style="color: var(--color-text-muted);">Step {{ $currentStep }} of 7:</span>
            <strong style="color: var(--color-primary);" id="mobile-step-name">
                @if($currentStep == 1) Business Info
                @elseif($currentStep == 2) LLC Formation
                @elseif($currentStep == 3) EIN
                @elseif($currentStep == 4) Documents
                @elseif($currentStep == 5) Banking
                @elseif($currentStep == 6) Tax & Compliance
                @elseif($currentStep == 7) Business Structure
                @endif
            </strong>
        </div>
        <span class="badge badge-success">{{ $percentage }}% Done</span>
    </div>

    <!-- Stepper Navigation -->
    <div class="stepper-container" id="stepper-container-div" style="margin-bottom: var(--spacing-4); position: relative;">
        <button type="button" class="stepper-scroll-btn scroll-left" onclick="scrollStepper(-150)" id="stepper-left-btn" style="display: none;" aria-label="Scroll left">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </button>
        <button type="button" class="stepper-scroll-btn scroll-right" onclick="scrollStepper(150)" id="stepper-right-btn" style="display: none;" aria-label="Scroll right">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </button>
        <ol class="stepper" id="stepper-list">
            <!-- Step 1 -->
            <li class="step-item {{ $currentStep == 1 ? 'in-progress' : ($currentStep > 1 ? 'completed' : 'not-started') }}" id="step-nav-1" onclick="jumpToStep(1)">
                <div class="step-circle">01</div>
                <span class="step-title">Business Info</span>
                <span class="step-status">
                    @if($currentStep > 1) ✓ Completed @elseif($currentStep == 1) ● Active @else ○ Not Started @endif
                </span>
            </li>
            <!-- Step 2 -->
            <li class="step-item {{ $currentStep == 2 ? 'in-progress' : ($currentStep > 2 ? 'completed' : 'not-started') }}" id="step-nav-2" onclick="jumpToStep(2)">
                <div class="step-circle">02</div>
                <span class="step-title">LLC Formation</span>
                <span class="step-status">
                    @if($currentStep > 2) ✓ Completed @elseif($currentStep == 2) ● Active @else ○ Not Started @endif
                </span>
            </li>
            <!-- Step 3 -->
            <li class="step-item {{ $currentStep == 3 ? 'in-progress' : ($currentStep > 3 ? 'completed' : 'not-started') }}" id="step-nav-3" onclick="jumpToStep(3)">
                <div class="step-circle">03</div>
                <span class="step-title">EIN</span>
                <span class="step-status">
                    @if($currentStep > 3) ✓ Completed @elseif($currentStep == 3) ● Active @else ○ Not Started @endif
                </span>
            </li>
            <!-- Step 4 -->
            @php
                $step4Class = 'not-started';
                if ($currentStep == 4) {
                    $step4Class = $hasRequiredDocs ? 'in-progress' : 'action-required';
                } elseif ($currentStep > 4) {
                    $step4Class = 'completed';
                }
            @endphp
            <li class="step-item {{ $step4Class }}" id="step-nav-4" onclick="jumpToStep(4)">
                <div class="step-circle">04</div>
                <span class="step-title">Documents</span>
                <span class="step-status">
                    @if($currentStep > 4) ✓ Completed @elseif($currentStep == 4) {{ $hasRequiredDocs ? '● Active' : '⚠ Action Required' }} @else ○ Not Started @endif
                </span>
            </li>
            <!-- Step 5 -->
            <li class="step-item {{ $currentStep == 5 ? 'in-progress' : ($currentStep > 5 ? 'completed' : 'not-started') }}" id="step-nav-5" onclick="jumpToStep(5)">
                <div class="step-circle">05</div>
                <span class="step-title">Banking</span>
                <span class="step-status">
                    @if($currentStep > 5) ✓ Completed @elseif($currentStep == 5) ● Active @else ○ Not Started @endif
                </span>
            </li>
            <!-- Step 6 -->
            <li class="step-item {{ $currentStep == 6 ? 'in-progress' : ($currentStep > 6 ? 'completed' : 'not-started') }}" id="step-nav-6" onclick="jumpToStep(6)">
                <div class="step-circle">06</div>
                <span class="step-title">Tax</span>
                <span class="step-status">
                    @if($currentStep > 6) ✓ Completed @elseif($currentStep == 6) ● Active @else ○ Not Started @endif
                </span>
            </li>
            <!-- Step 7 -->
            <li class="step-item {{ $currentStep == 7 ? 'in-progress' : ($progress->status == 'completed' ? 'completed' : 'not-started') }}" id="step-nav-7" onclick="jumpToStep(7)">
                <div class="step-circle">07</div>
                <span class="step-title">Structure</span>
                <span class="step-status">
                    @if($progress->status == 'completed') ✓ Completed @elseif($currentStep == 7) ● Active @else ○ Not Started @endif
                </span>
            </li>
        </ol>
    </div>

    <!-- WIZARD STEP FORMS -->
    <div class="card" style="padding: var(--spacing-4) var(--spacing-4);">

        <!-- ================== STEP 1: BUSINESS INFO ================== -->
        <div id="step-form-container-1" class="step-form-content {{ $currentStep == 1 ? 'active' : '' }}" style="display: {{ $currentStep == 1 ? 'block' : 'none' }};">
            <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Business Information</h2>
            <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Enter the basic information required to set up the business.</p>

            <form action="{{ route('services.save_step', 'business-setup') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="1">
                <input type="hidden" name="action" id="step-1-action" value="save_continue">

                <div style="display: grid; grid-template-columns: repeat(1, minmax(0, 1fr)); gap: var(--spacing-5);">
                    <!-- 2-column layout on desktop -->
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label for="business_name" class="form-label">Business Name <span style="color: var(--color-danger);">*</span></label>
                            <input type="text" name="business_name" id="business_name" class="form-control" value="{{ old('business_name', $payload['business_name'] ?? '') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="business_type" class="form-label">Business Type <span style="color: var(--color-danger);">*</span></label>
                            <select name="business_type" id="business_type" class="form-control" required>
                                <option value="">Select Option</option>
                                <option value="eCommerce" {{ old('business_type', $payload['business_type'] ?? '') == 'eCommerce' ? 'selected' : '' }}>eCommerce</option>
                                <option value="LLC" {{ old('business_type', $payload['business_type'] ?? '') == 'LLC' ? 'selected' : '' }}>LLC</option>
                                <option value="Corporation" {{ old('business_type', $payload['business_type'] ?? '') == 'Corporation' ? 'selected' : '' }}>Corporation</option>
                                <option value="Sole Proprietorship" {{ old('business_type', $payload['business_type'] ?? '') == 'Sole Proprietorship' ? 'selected' : '' }}>Sole Proprietorship</option>
                                <option value="Partnership" {{ old('business_type', $payload['business_type'] ?? '') == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                <option value="Other" {{ old('business_type', $payload['business_type'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_activity" class="form-label">Business Activity / Description <span style="color: var(--color-danger);">*</span></label>
                        <textarea name="business_activity" id="business_activity" rows="4" class="form-control" style="height: auto;" required>{{ old('business_activity', $payload['business_activity'] ?? '') }}</textarea>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label for="website_url" class="form-label">Website URL <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                            <input type="url" name="website_url" id="website_url" class="form-control" placeholder="https://example.com" value="{{ old('website_url', $payload['website_url'] ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="business_email" class="form-label">Business Email <span style="color: var(--color-danger);">*</span></label>
                            <input type="email" name="business_email" id="business_email" class="form-control" value="{{ old('business_email', $payload['business_email'] ?? '') }}" required>
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label for="business_phone" class="form-label">Business Phone <span style="color: var(--color-danger);">*</span></label>
                            <input type="text" name="business_phone" id="business_phone" class="form-control" placeholder="+1 (555) 000-0000" value="{{ old('business_phone', $payload['business_phone'] ?? '') }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-navigation">
                    <div></div> <!-- spacing -->
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-1-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ================== STEP 2: LLC FORMATION ================== -->
        <div id="step-form-container-2" class="step-form-content {{ $currentStep == 2 ? 'active' : '' }}" style="display: {{ $currentStep == 2 ? 'block' : 'none' }};">
            <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">LLC Formation</h2>
            <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage the client's LLC formation status and requirements.</p>

            <form action="{{ route('services.save_step', 'business-setup') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="2">
                <input type="hidden" name="action" id="step-2-action" value="save_continue">

                <div class="form-group">
                    <label class="form-label">Have you already formed an LLC? <span style="color: var(--color-danger);">*</span></label>
                    <div class="segmented-control">
                        @php
                            $hasLlc = old('has_llc', $payload['has_llc'] ?? 'no');
                        @endphp
                        <input type="radio" name="has_llc" id="has_llc_yes" value="yes" class="segmented-option" {{ $hasLlc == 'yes' ? 'checked' : '' }} onchange="toggleLlcFields('yes')">
                        <label for="has_llc_yes" class="segmented-label">Yes</label>

                        <input type="radio" name="has_llc" id="has_llc_no" value="no" class="segmented-option" {{ $hasLlc == 'no' ? 'checked' : '' }} onchange="toggleLlcFields('no')">
                        <label for="has_llc_no" class="segmented-label">No</label>
                    </div>
                </div>

                <!-- Conditional Fields: Yes (Already formed) -->
                <div id="llc-yes-fields" class="form-grid-2" style="display: {{ $hasLlc == 'yes' ? 'grid' : 'none' }};">
                    <div class="form-group">
                        <label for="llc_name" class="form-label">LLC Name <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" name="llc_name" id="llc_name" class="form-control" value="{{ old('llc_name', $payload['llc_name'] ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="formation_date" class="form-label">Formation Date <span style="color: var(--color-danger);">*</span></label>
                        <input type="date" name="formation_date" id="formation_date" class="form-control" value="{{ old('formation_date', $payload['formation_date'] ?? '') }}">
                    </div>
                </div>

                <!-- Conditional Fields: No (Need formation) -->
                <div id="llc-no-fields" class="form-grid-2" style="display: {{ $hasLlc == 'no' ? 'grid' : 'none' }};">
                    <div class="form-group">
                        <label for="preferred_state" class="form-label">Preferred State <span style="color: var(--color-danger);">*</span></label>
                        <select name="preferred_state" id="preferred_state" class="form-control">
                            <option value="">Select State</option>
                            @foreach($states as $st)
                                <option value="{{ $st }}" {{ old('preferred_state', $payload['preferred_state'] ?? '') == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="proposed_llc_name" class="form-label">Proposed LLC Name <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" name="proposed_llc_name" id="proposed_llc_name" class="form-control" value="{{ old('proposed_llc_name', $payload['proposed_llc_name'] ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="llc_notes" class="form-label">LLC Notes <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="llc_notes" id="llc_notes" rows="3" class="form-control" style="height: auto;">{{ old('llc_notes', $payload['llc_notes'] ?? '') }}</textarea>
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

        <!-- ================== STEP 3: EIN ================== -->
        <div id="step-form-container-3" class="step-form-content {{ $currentStep == 3 ? 'active' : '' }}" style="display: {{ $currentStep == 3 ? 'block' : 'none' }};">
            <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">EIN</h2>
            <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage the client's Employer Identification Number requirements.</p>

            <form action="{{ route('services.save_step', 'business-setup') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="3">
                <input type="hidden" name="action" id="step-3-action" value="save_continue">

                <div class="form-group">
                    <label class="form-label">Do you already have an EIN? <span style="color: var(--color-danger);">*</span></label>
                    <div class="segmented-control">
                        @php
                            $hasEin = old('has_ein', $payload['has_ein'] ?? 'no');
                        @endphp
                        <input type="radio" name="has_ein" id="has_ein_yes" value="yes" class="segmented-option" {{ $hasEin == 'yes' ? 'checked' : '' }} onchange="toggleEinFields('yes')">
                        <label for="has_ein_yes" class="segmented-label">Yes</label>

                        <input type="radio" name="has_ein" id="has_ein_no" value="no" class="segmented-option" {{ $hasEin == 'no' ? 'checked' : '' }} onchange="toggleEinFields('no')">
                        <label for="has_ein_no" class="segmented-label">No</label>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group" id="ein-number-container" style="display: {{ $hasEin == 'yes' ? 'block' : 'none' }};">
                        <label for="ein_number" class="form-label">EIN Number <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" name="ein_number" id="ein_number" class="form-control" placeholder="12-3456789" value="{{ old('ein_number', $payload['ein_number'] ?? '') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="ein_status" class="form-label">EIN Application Status <span style="color: var(--color-danger);">*</span></label>
                        <select name="ein_status" id="ein_status" class="form-control" required>
                            <option value="Not Started" {{ old('ein_status', $payload['ein_status'] ?? '') == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                            <option value="Preparing" {{ old('ein_status', $payload['ein_status'] ?? '') == 'Preparing' ? 'selected' : '' }}>Preparing</option>
                            <option value="Submitted" {{ old('ein_status', $payload['ein_status'] ?? '') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="Processing" {{ old('ein_status', $payload['ein_status'] ?? '') == 'Processing' ? 'selected' : '' }}>Processing</option>
                            <option value="Approved" {{ old('ein_status', $payload['ein_status'] ?? '') == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ old('ein_status', $payload['ein_status'] ?? '') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ein_notes" class="form-label">EIN Notes <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="ein_notes" id="ein_notes" rows="3" class="form-control" style="height: auto;">{{ old('ein_notes', $payload['ein_notes'] ?? '') }}</textarea>
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

        <!-- ================== STEP 4: BUSINESS DOCUMENTS ================== -->
        <div id="step-form-container-4" class="step-form-content {{ $currentStep == 4 ? 'active' : '' }}" style="display: {{ $currentStep == 4 ? 'block' : 'none' }};">
            <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Business Documents</h2>
            <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Upload and manage the documents required for business setup.</p>

            <!-- Grid of Upload Cards -->
            <div class="document-upload-grid">
                
                <!-- Card 1: Articles of Organization (Required) -->
                @include('admin.services.partials.doc_card', [
                    'title' => 'Articles of Organization',
                    'field' => 'articles_of_organization',
                    'required' => true,
                    'meta' => $payload['documents']['articles_of_organization'] ?? null
                ])

                <!-- Card 2: Operating Agreement (Required) -->
                @include('admin.services.partials.doc_card', [
                    'title' => 'Operating Agreement',
                    'field' => 'operating_agreement',
                    'required' => true,
                    'meta' => $payload['documents']['operating_agreement'] ?? null
                ])

                <!-- Card 3: EIN Letter (Required) -->
                @include('admin.services.partials.doc_card', [
                    'title' => 'EIN Letter',
                    'field' => 'ein_letter',
                    'required' => true,
                    'meta' => $payload['documents']['ein_letter'] ?? null
                ])

                <!-- Card 4: Other Documents (Optional, Multi) -->
                <div class="document-upload-card" style="grid-column: span 1;">
                    <div class="document-card-header">
                        <span class="document-title">Other Documents</span>
                        <span class="badge" style="background-color: var(--color-bg-base); color: var(--color-text-secondary); border: 1px solid var(--color-border);">Optional</span>
                    </div>

                    <form action="{{ route('services.upload_document', 'business-setup') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="field_name" value="other_documents">
                        <input type="hidden" name="step" value="4">
                        
                        <div class="drag-drop-area" onclick="document.getElementById('file-upload-other_documents').click()">
                            <svg class="drag-drop-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <span class="drag-drop-text" style="font-weight: var(--fw-semibold); color: var(--color-primary);">Upload files</span>
                            <span class="drag-drop-text" style="margin-top: 2px;">PDF, PNG, JPG up to 10MB</span>
                            <input type="file" id="file-upload-other_documents" name="document" style="display:none;" onchange="this.form.submit()">
                        </div>
                    </form>

                    <!-- Multi Upload List -->
                    @php $otherDocs = $payload['documents']['other_documents'] ?? []; @endphp
                    @if(!empty($otherDocs))
                        <div style="display: flex; flex-direction: column; gap: var(--spacing-2); margin-top: var(--spacing-2);">
                            @foreach($otherDocs as $idx => $meta)
                                <div class="uploaded-file-meta">
                                    <div class="file-info">
                                        <a href="{{ $meta['url'] }}" target="_blank" class="file-name">{{ $meta['original_name'] }}</a>
                                        <span class="file-details">{{ $meta['size'] }} • {{ $meta['upload_date'] }}</span>
                                    </div>
                                    <div class="file-actions">
                                        <form action="{{ route('services.remove_document', 'business-setup') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="field_name" value="other_documents">
                                            <input type="hidden" name="index" value="{{ $idx }}">
                                            <button type="submit" class="btn btn-secondary" style="height: 28px; padding: 0 var(--spacing-2); font-size: 10px; color: var(--color-danger); border-color: rgba(239,68,68,0.2);">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            <!-- Stepper Actions Navigation Form -->
            <form action="{{ route('services.save_step', 'business-setup') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="4">
                <input type="hidden" name="action" id="step-4-action" value="save_continue">

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToStep(3)">Back</button>
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-4-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Save & Continue</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ================== STEP 5: BANKING ================== -->
        <div id="step-form-container-5" class="step-form-content {{ $currentStep == 5 ? 'active' : '' }}" style="display: {{ $currentStep == 5 ? 'block' : 'none' }};">
            <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Business Banking</h2>
            <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Configure the client's corporate banking details.</p>

            <form action="{{ route('services.save_step', 'business-setup') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="5">
                <input type="hidden" name="action" id="step-5-action" value="save_continue">

                <div class="form-group">
                    <label class="form-label">Do you have a US Business Bank Account? <span style="color: var(--color-danger);">*</span></label>
                    <div class="segmented-control">
                        @php $hasBank = old('has_bank', $payload['has_bank'] ?? 'no'); @endphp
                        <input type="radio" name="has_bank" id="has_bank_yes" value="yes" class="segmented-option" {{ $hasBank == 'yes' ? 'checked' : '' }}>
                        <label for="has_bank_yes" class="segmented-label">Yes</label>

                        <input type="radio" name="has_bank" id="has_bank_no" value="no" class="segmented-option" {{ $hasBank == 'no' ? 'checked' : '' }}>
                        <label for="has_bank_no" class="segmented-label">No</label>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="banking_type" class="form-label">Preferred Banking Type <span style="color: var(--color-danger);">*</span></label>
                        <select name="banking_type" id="banking_type" class="form-control" required>
                            <option value="Traditional Bank" {{ old('banking_type', $payload['banking_type'] ?? '') == 'Traditional Bank' ? 'selected' : '' }}>Traditional Bank</option>
                            <option value="Online Banking" {{ old('banking_type', $payload['banking_type'] ?? '') == 'Online Banking' ? 'selected' : '' }}>Online Banking</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="preferred_bank" class="form-label">Preferred Bank <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" name="preferred_bank" id="preferred_bank" class="form-control" placeholder="Mercury, Chase, Bank of America" value="{{ old('preferred_bank', $payload['preferred_bank'] ?? '') }}" required>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="bank_status" class="form-label">Bank Account Status <span style="color: var(--color-danger);">*</span></label>
                        <select name="bank_status" id="bank_status" class="form-control" required>
                            <option value="Not Started" {{ old('bank_status', $payload['bank_status'] ?? '') == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                            <option value="Preparing" {{ old('bank_status', $payload['bank_status'] ?? '') == 'Preparing' ? 'selected' : '' }}>Preparing</option>
                            <option value="Applied" {{ old('bank_status', $payload['bank_status'] ?? '') == 'Applied' ? 'selected' : '' }}>Applied</option>
                            <option value="Under Review" {{ old('bank_status', $payload['bank_status'] ?? '') == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                            <option value="Approved" {{ old('bank_status', $payload['bank_status'] ?? '') == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Active" {{ old('bank_status', $payload['bank_status'] ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Rejected" {{ old('bank_status', $payload['bank_status'] ?? '') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="banking_notes" class="form-label">Banking Notes <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="banking_notes" id="banking_notes" rows="3" class="form-control" style="height: auto;">{{ old('banking_notes', $payload['banking_notes'] ?? '') }}</textarea>
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

        <!-- ================== STEP 6: TAX & COMPLIANCE ================== -->
        <div id="step-form-container-6" class="step-form-content {{ $currentStep == 6 ? 'active' : '' }}" style="display: {{ $currentStep == 6 ? 'block' : 'none' }};">
            <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Tax & Compliance</h2>
            <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Manage ongoing compliance and tax requirements.</p>

            <form action="{{ route('services.save_step', 'business-setup') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="6">
                <input type="hidden" name="action" id="step-6-action" value="save_continue">

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="tax_status" class="form-label">Tax Filing Status <span style="color: var(--color-danger);">*</span></label>
                        <select name="tax_status" id="tax_status" class="form-control" required>
                            <option value="Not Started" {{ old('tax_status', $payload['tax_status'] ?? '') == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                            <option value="Documents Required" {{ old('tax_status', $payload['tax_status'] ?? '') == 'Documents Required' ? 'selected' : '' }}>Documents Required</option>
                            <option value="In Progress" {{ old('tax_status', $payload['tax_status'] ?? '') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Filed" {{ old('tax_status', $payload['tax_status'] ?? '') == 'Filed' ? 'selected' : '' }}>Filed</option>
                            <option value="Completed" {{ old('tax_status', $payload['tax_status'] ?? '') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tax Professional Required? <span style="color: var(--color-danger);">*</span></label>
                        <div class="segmented-control">
                            @php $taxProfessional = old('tax_professional', $payload['tax_professional'] ?? 'no'); @endphp
                            <input type="radio" name="tax_professional" id="tax_prof_yes" value="yes" class="segmented-option" {{ $taxProfessional == 'yes' ? 'checked' : '' }}>
                            <label for="tax_prof_yes" class="segmented-label">Yes</label>

                            <input type="radio" name="tax_professional" id="tax_prof_no" value="no" class="segmented-option" {{ $taxProfessional == 'no' ? 'checked' : '' }}>
                            <label for="tax_prof_no" class="segmented-label">No</label>
                        </div>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="annual_report_status" class="form-label">Annual Report Status <span style="color: var(--color-danger);">*</span></label>
                        <select name="annual_report_status" id="annual_report_status" class="form-control" required>
                            <option value="Not Due" {{ old('annual_report_status', $payload['annual_report_status'] ?? '') == 'Not Due' ? 'selected' : '' }}>Not Due</option>
                            <option value="Upcoming" {{ old('annual_report_status', $payload['annual_report_status'] ?? '') == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="Due" {{ old('annual_report_status', $payload['annual_report_status'] ?? '') == 'Due' ? 'selected' : '' }}>Due</option>
                            <option value="Filed" {{ old('annual_report_status', $payload['annual_report_status'] ?? '') == 'Filed' ? 'selected' : '' }}>Filed</option>
                            <option value="Completed" {{ old('annual_report_status', $payload['annual_report_status'] ?? '') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>

                <!-- Custom Documents multiupload for Tax -->
                <div class="form-group">
                    <label class="form-label">Tax Documents <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <!-- Display uploaded tax documents if any -->
                    <p style="font-size: var(--fs-xs); color: var(--color-text-muted); margin-bottom: var(--spacing-2);">Please manage tax-specific filings through the Document list or upload via operational files.</p>
                </div>

                <div class="form-group">
                    <label for="compliance_notes" class="form-label">Compliance Notes <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="compliance_notes" id="compliance_notes" rows="3" class="form-control" style="height: auto;">{{ old('compliance_notes', $payload['compliance_notes'] ?? '') }}</textarea>
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

        <!-- ================== STEP 7: BUSINESS STRUCTURE ================== -->
        <div id="step-form-container-7" class="step-form-content {{ $currentStep == 7 ? 'active' : '' }}" style="display: {{ $currentStep == 7 ? 'block' : 'none' }};">
            <h2 style="font-size: var(--fs-xl); margin-bottom: var(--spacing-1);">Business Structure</h2>
            <p style="margin-bottom: var(--spacing-6); color: var(--color-text-secondary);">Define the business operations structure and sales strategies.</p>

            <form action="{{ route('services.save_step', 'business-setup') }}" method="POST">
                @csrf
                <input type="hidden" name="step" value="7">
                <input type="hidden" name="action" id="step-7-action" value="save_continue">

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="business_model" class="form-label">Business Model <span style="color: var(--color-danger);">*</span></label>
                        <select name="business_model" id="business_model" class="form-control" required>
                            <option value="Private Label" {{ old('business_model', $payload['business_model'] ?? '') == 'Private Label' ? 'selected' : '' }}>Private Label</option>
                            <option value="Wholesale" {{ old('business_model', $payload['business_model'] ?? '') == 'Wholesale' ? 'selected' : '' }}>Wholesale</option>
                            <option value="Retail" {{ old('business_model', $payload['business_model'] ?? '') == 'Retail' ? 'selected' : '' }}>Retail</option>
                            <option value="Dropshipping" {{ old('business_model', $payload['business_model'] ?? '') == 'Dropshipping' ? 'selected' : '' }}>Dropshipping</option>
                            <option value="Online Store" {{ old('business_model', $payload['business_model'] ?? '') == 'Online Store' ? 'selected' : '' }}>Online Store</option>
                            <option value="Marketplace" {{ old('business_model', $payload['business_model'] ?? '') == 'Marketplace' ? 'selected' : '' }}>Marketplace</option>
                            <option value="Hybrid" {{ old('business_model', $payload['business_model'] ?? '') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                            <option value="Other" {{ old('business_model', $payload['business_model'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <!-- Checkbox cards for Sales Channels -->
                <div class="form-group">
                    <label class="form-label">Sales Channels <span style="color: var(--color-danger);">*</span></label>
                    <div class="selection-grid">
                        @php 
                            $channels = old('sales_channels', $payload['sales_channels'] ?? []); 
                        @endphp
                        
                        <div class="selection-card {{ in_array('Shopify', $channels) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'channel-shopify')">
                            <input type="checkbox" name="sales_channels[]" id="channel-shopify" value="Shopify" class="selection-checkbox" {{ in_array('Shopify', $channels) ? 'checked' : '' }}>
                            <div class="selection-card-details">
                                <span class="selection-card-title">Shopify</span>
                                <span class="selection-card-desc">eCommerce platform</span>
                            </div>
                        </div>

                        <div class="selection-card {{ in_array('Amazon', $channels) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'channel-amazon')">
                            <input type="checkbox" name="sales_channels[]" id="channel-amazon" value="Amazon" class="selection-checkbox" {{ in_array('Amazon', $channels) ? 'checked' : '' }}>
                            <div class="selection-card-details">
                                <span class="selection-card-title">Amazon</span>
                                <span class="selection-card-desc">Retail marketplace</span>
                            </div>
                        </div>

                        <div class="selection-card {{ in_array('eBay', $channels) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'channel-ebay')">
                            <input type="checkbox" name="sales_channels[]" id="channel-ebay" value="eBay" class="selection-checkbox" {{ in_array('eBay', $channels) ? 'checked' : '' }}>
                            <div class="selection-card-details">
                                <span class="selection-card-title">eBay</span>
                                <span class="selection-card-desc">Online auction portal</span>
                            </div>
                        </div>

                        <div class="selection-card {{ in_array('Walmart', $channels) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'channel-walmart')">
                            <input type="checkbox" name="sales_channels[]" id="channel-walmart" value="Walmart" class="selection-checkbox" {{ in_array('Walmart', $channels) ? 'checked' : '' }}>
                            <div class="selection-card-details">
                                <span class="selection-card-title">Walmart</span>
                                <span class="selection-card-desc">Retail chain platform</span>
                            </div>
                        </div>

                        <div class="selection-card {{ in_array('TikTok Shop', $channels) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'channel-tiktok')">
                            <input type="checkbox" name="sales_channels[]" id="channel-tiktok" value="TikTok Shop" class="selection-checkbox" {{ in_array('TikTok Shop', $channels) ? 'checked' : '' }}>
                            <div class="selection-card-details">
                                <span class="selection-card-title">TikTok Shop</span>
                                <span class="selection-card-desc">Social commerce</span>
                            </div>
                        </div>

                        <div class="selection-card {{ in_array('Own Website', $channels) ? 'selected' : '' }}" onclick="toggleCheckboxCard(this, 'channel-own')">
                            <input type="checkbox" name="sales_channels[]" id="channel-own" value="Own Website" class="selection-checkbox" {{ in_array('Own Website', $channels) ? 'checked' : '' }}>
                            <div class="selection-card-details">
                                <span class="selection-card-title">Own Website</span>
                                <span class="selection-card-desc">Custom domain setup</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Target US Market / States <span style="color: var(--color-danger);">*</span></label>
                    @php $targetStates = old('target_states', $payload['target_states'] ?? []); @endphp
                    <div class="custom-multiselect-container" id="target_states_container">
                        <div class="custom-multiselect-trigger">
                            <span class="multiselect-placeholder">Select target states...</span>
                        </div>
                        <div class="custom-multiselect-dropdown">
                            @foreach($states as $st)
                                <div class="multiselect-option {{ in_array($st, $targetStates) ? 'selected' : '' }}" data-value="{{ $st }}">
                                    <input type="checkbox" class="multiselect-checkbox" {{ in_array($st, $targetStates) ? 'checked' : '' }}>
                                    <span>{{ $st }}</span>
                                </div>
                            @endforeach
                        </div>
                        <select name="target_states[]" id="target_states" style="display: none;" multiple required>
                            @foreach($states as $st)
                                <option value="{{ $st }}" {{ in_array($st, $targetStates) ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="additional_requirements" class="form-label">Additional Requirements <span style="color: var(--color-text-muted); font-weight: normal;">(Optional)</span></label>
                    <textarea name="additional_requirements" id="additional_requirements" rows="4" class="form-control" style="height: auto;">{{ old('additional_requirements', $payload['additional_requirements'] ?? '') }}</textarea>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" onclick="jumpToStep(6)">Back</button>
                    <div>
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('step-7-action').value='save_draft'">Save Draft</button>
                        <button type="submit" class="btn btn-primary">Complete Business Setup</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- TAB 2: OVERVIEW DASHBOARD -->
<div id="tab-content-overview" class="tab-content {{ $percentage == 100 ? 'active' : '' }}">

    <div class="progress-banner">
        <div class="progress-banner-header">
            <div>
                <h3 style="font-size: var(--fs-lg); font-weight: var(--fw-bold); margin-bottom: 2px;">Business Setup Progress</h3>
                <p style="font-size: var(--fs-sm); color: var(--color-text-secondary);">Overall requirements completed</p>
            </div>
            <div class="progress-percentage">{{ $percentage }}%</div>
        </div>
        <div class="progress-bar-outer">
            <div class="progress-bar-inner" style="width: {{ $percentage }}%;"></div>
        </div>
    </div>

    <!-- Summary metrics row -->
    <div class="metrics-grid" style="margin-bottom: var(--spacing-6);">
        <div class="metric-card" style="padding: var(--spacing-4);">
            <span class="metric-title" style="font-size: 10px;">Completed Steps</span>
            <span class="metric-value" style="font-size: var(--fs-xl); margin-top: 4px; margin-bottom: 0; color: var(--color-success);">{{ $completedStepsCount }} / 7</span>
        </div>
        <div class="metric-card" style="padding: var(--spacing-4);">
            <span class="metric-title" style="font-size: 10px;">Remaining Steps</span>
            <span class="metric-value" style="font-size: var(--fs-xl); margin-top: 4px; margin-bottom: 0; color: var(--color-primary);">{{ $totalStepsCount - $completedStepsCount }} Steps</span>
        </div>
        <div class="metric-card" style="padding: var(--spacing-4);">
            <span class="metric-title" style="font-size: 10px;">Documents Missing</span>
            <span class="metric-value" style="font-size: var(--fs-xl); margin-top: 4px; margin-bottom: 0; color: {{ $missingDocs > 0 ? 'var(--color-danger)' : 'var(--color-success)' }};">
                {{ $missingDocs }} File{{ $missingDocs == 1 ? '' : 's' }}
            </span>
        </div>
        <div class="metric-card" style="padding: var(--spacing-4);">
            <span class="metric-title" style="font-size: 10px;">Actions Required</span>
            <span class="metric-value" style="font-size: var(--fs-xl); margin-top: 4px; margin-bottom: 0; color: {{ !$hasRequiredDocs ? 'var(--color-danger)' : 'var(--color-text-primary)' }};">
                {{ !$hasRequiredDocs ? 'Upload Docs' : 'None' }}
            </span>
        </div>
    </div>

    <div class="overview-grid">
        <div class="grid-col-span-2">
            @if($percentage == 100)
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
                                @php $stepNum = 1; @endphp
                                @foreach($checklist as $stepTitle => $isDone)
                                    <tr style="border-bottom: 1px solid var(--color-border-light);">
                                        <td style="padding: var(--spacing-3) var(--spacing-2); font-weight: var(--fw-medium); font-size: var(--fs-sm);">Step {{ $stepNum }}: {{ $stepTitle }}</td>
                                        <td style="padding: var(--spacing-3) var(--spacing-2);"><span class="badge badge-success">Completed</span></td>
                                        <td style="padding: var(--spacing-3) var(--spacing-2); text-align: right; white-space: nowrap;">
                                            <button type="button" class="btn btn-secondary" style="font-size: 11px; padding: 4px 8px; height: auto; margin-right: 4px;" onclick="openViewModal()">View</button>
                                            <button type="button" class="btn btn-primary" style="font-size: 11px; padding: 4px 8px; height: auto;" onclick="startEditMode({{ $stepNum }})">Edit</button>
                                        </td>
                                    </tr>
                                    @php $stepNum++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="checklist-card">
                    <h3 style="font-size: var(--fs-base); font-weight: var(--fw-bold); margin-bottom: var(--spacing-4);">Setup Checklist</h3>
                    
                    @php $stepNum = 1; @endphp
                    @foreach($checklist as $stepTitle => $isDone)
                        @php
                            // Determine step sub-state/color classes
                            $itemClass = 'not-started';
                            if ($isDone) {
                                $itemClass = 'completed';
                            } else {
                                if ($stepNum == 4 && !$hasRequiredDocs) {
                                    $itemClass = 'action-required';
                                } elseif ($currentStep == $stepNum) {
                                    $itemClass = 'in-progress';
                                }
                            }
                        @endphp
                        <div class="checklist-item {{ $itemClass }}" onclick="switchMainTab('wizard'); jumpToStep({{ $stepNum }})">
                            <div class="checklist-left">
                                <div class="checklist-marker">
                                    @if($itemClass == 'completed')
                                        ✓
                                    @elseif($itemClass == 'in-progress')
                                        ●
                                    @elseif($itemClass == 'action-required')
                                        ⚠
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
                                    <span class="badge badge-primary" style="background-color: var(--color-primary-light); color: var(--color-primary);">In Progress</span>
                                @elseif($itemClass == 'action-required')
                                    <span class="badge badge-danger">Action Required</span>
                                @else
                                    <span class="badge" style="background-color: var(--color-bg-base); color: var(--color-text-muted); border: 1px solid var(--color-border)">Not Started</span>
                                @endif
                            </div>
                        </div>
                        @php $stepNum++; @endphp
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <div class="card" style="padding: var(--spacing-5);">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-bold); margin-bottom: var(--spacing-3);">Client Documents</h3>
                <div style="display: flex; flex-direction: column; gap: var(--spacing-3);">
                    @foreach(['articles_of_organization' => 'Articles of Org', 'operating_agreement' => 'Operating Agreement', 'ein_letter' => 'EIN Letter'] as $key => $title)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: var(--spacing-2); border-bottom: 1px solid var(--color-border);">
                            <span style="font-size: var(--fs-xs); font-weight: var(--fw-medium);">{{ $title }}</span>
                            @if(isset($docs[$key]))
                                <span class="badge badge-success" style="font-size: 10px;">Uploaded</span>
                            @else
                                <span class="badge badge-danger" style="font-size: 10px;">Missing</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

<!-- VIEW DETAILS MODAL -->
<div id="view-details-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-content" style="background: var(--color-bg-base); width: 95%; max-width: 850px; max-height: 90vh; border-radius: var(--radius-xl); padding: 0; overflow: hidden; position: relative; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); display: flex; flex-direction: column;">
        
        <!-- Modal Header -->
        <div style="padding: var(--spacing-5) var(--spacing-6); border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; background: var(--color-bg-alt);">
            <div>
                <h2 style="font-size: var(--fs-lg); font-weight: var(--fw-bold); color: var(--color-text-primary); margin: 0;">Business Setup Summary</h2>
                <p style="font-size: var(--fs-sm); color: var(--color-text-secondary); margin: 4px 0 0 0;">Review all submitted details for this service.</p>
            </div>
            <button type="button" onclick="document.getElementById('view-details-modal').style.display='none'" style="background: var(--color-bg-base); border: 1px solid var(--color-border); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: var(--fs-lg); cursor: pointer; color: var(--color-text-secondary); transition: all 0.2s ease;">&times;</button>
        </div>

        <!-- Modal Body -->
        <div style="padding: var(--spacing-6); overflow-y: auto; background: var(--color-bg-base); display: flex; flex-direction: column; gap: var(--spacing-5);">
            
            <!-- Step 1 Info -->
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">1</div>
                    Business Information
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Business Name</div><div style="font-weight: var(--fw-medium);">{{ $payload['business_name'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Business Type</div><div style="font-weight: var(--fw-medium);">{{ $payload['business_type'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Business Email</div><div style="font-weight: var(--fw-medium);">{{ $payload['business_email'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Business Phone</div><div style="font-weight: var(--fw-medium);">{{ $payload['business_phone'] ?? 'N/A' }}</div></div>
                    <div style="grid-column: 1 / -1;"><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Business Activity</div><div style="font-weight: var(--fw-medium);">{{ $payload['business_activity'] ?? 'N/A' }}</div></div>
                </div>
            </div>

            <!-- Step 2 Info -->
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">2</div>
                    LLC Formation
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Has LLC?</div><div><span class="badge {{ ($payload['has_llc'] ?? '') === 'yes' ? 'badge-success' : 'badge-secondary' }}">{{ ucfirst($payload['has_llc'] ?? 'N/A') }}</span></div></div>
                    @if(($payload['has_llc'] ?? '') === 'yes')
                        <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">LLC Name</div><div style="font-weight: var(--fw-medium);">{{ $payload['llc_name'] ?? 'N/A' }}</div></div>
                        <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Formation Date</div><div style="font-weight: var(--fw-medium);">{{ $payload['formation_date'] ?? 'N/A' }}</div></div>
                    @else
                        <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Preferred State</div><div style="font-weight: var(--fw-medium);">{{ $payload['preferred_state'] ?? 'N/A' }}</div></div>
                        <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Proposed Name</div><div style="font-weight: var(--fw-medium);">{{ $payload['proposed_llc_name'] ?? 'N/A' }}</div></div>
                    @endif
                </div>
            </div>

            <!-- Step 3 Info -->
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">3</div>
                    EIN
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Has EIN?</div><div><span class="badge {{ ($payload['has_ein'] ?? '') === 'yes' ? 'badge-success' : 'badge-secondary' }}">{{ ucfirst($payload['has_ein'] ?? 'N/A') }}</span></div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">EIN Status</div><div><span class="badge" style="background: var(--color-bg-base); border: 1px solid var(--color-border);">{{ $payload['ein_status'] ?? 'N/A' }}</span></div></div>
                    @if(($payload['has_ein'] ?? '') === 'yes')
                        <div style="grid-column: 1 / -1;"><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">EIN Number</div><div style="font-weight: var(--fw-medium);">{{ $payload['ein_number'] ?? 'N/A' }}</div></div>
                    @endif
                </div>
            </div>

            <!-- Step 4 Info -->
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">4</div>
                    Business Documents
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #fff; border: 1px solid var(--color-border-light); border-radius: var(--radius-md);">
                        <span style="font-weight: var(--fw-medium); color: var(--color-text-secondary);">Articles of Organization</span>
                        <span class="badge {{ !empty($docs['articles_of_organization']) ? 'badge-success' : 'badge-danger' }}">{{ !empty($docs['articles_of_organization']) ? 'Uploaded' : 'Missing' }}</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #fff; border: 1px solid var(--color-border-light); border-radius: var(--radius-md);">
                        <span style="font-weight: var(--fw-medium); color: var(--color-text-secondary);">Operating Agreement</span>
                        <span class="badge {{ !empty($docs['operating_agreement']) ? 'badge-success' : 'badge-danger' }}">{{ !empty($docs['operating_agreement']) ? 'Uploaded' : 'Missing' }}</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #fff; border: 1px solid var(--color-border-light); border-radius: var(--radius-md);">
                        <span style="font-weight: var(--fw-medium); color: var(--color-text-secondary);">EIN Letter</span>
                        <span class="badge {{ !empty($docs['ein_letter']) ? 'badge-success' : 'badge-danger' }}">{{ !empty($docs['ein_letter']) ? 'Uploaded' : 'Missing' }}</span>
                    </div>
                </div>
            </div>

            <!-- Step 5 Info -->
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">5</div>
                    Business Banking
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Has US Bank Account?</div><div><span class="badge {{ ($payload['has_bank'] ?? '') === 'yes' ? 'badge-success' : 'badge-secondary' }}">{{ ucfirst($payload['has_bank'] ?? 'N/A') }}</span></div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Banking Type</div><div style="font-weight: var(--fw-medium);">{{ $payload['banking_type'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Preferred Bank</div><div style="font-weight: var(--fw-medium);">{{ $payload['preferred_bank'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Bank Status</div><div><span class="badge" style="background: var(--color-bg-base); border: 1px solid var(--color-border);">{{ $payload['bank_status'] ?? 'N/A' }}</span></div></div>
                </div>
            </div>

            <!-- Step 6 Info -->
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">6</div>
                    Tax & Compliance
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Tax Filing Status</div><div><span class="badge" style="background: var(--color-bg-base); border: 1px solid var(--color-border);">{{ $payload['tax_status'] ?? 'N/A' }}</span></div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Tax Professional Required?</div><div><span class="badge {{ ($payload['tax_professional'] ?? '') === 'yes' ? 'badge-primary' : 'badge-secondary' }}">{{ ucfirst($payload['tax_professional'] ?? 'N/A') }}</span></div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Annual Report Status</div><div><span class="badge" style="background: var(--color-bg-base); border: 1px solid var(--color-border);">{{ $payload['annual_report_status'] ?? 'N/A' }}</span></div></div>
                </div>
            </div>

            <!-- Step 7 Info -->
            <div style="border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--spacing-4); background: #fafafa;">
                <h3 style="font-size: var(--fs-base); font-weight: var(--fw-semibold); margin-bottom: var(--spacing-4); color: var(--color-text-primary); display: flex; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">7</div>
                    Business Structure
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-4); font-size: var(--fs-sm);">
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Business Model</div><div style="font-weight: var(--fw-medium);">{{ $payload['business_model'] ?? 'N/A' }}</div></div>
                    <div><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Sales Channels</div><div style="font-weight: var(--fw-medium);">{{ isset($payload['sales_channels']) && is_array($payload['sales_channels']) ? implode(', ', $payload['sales_channels']) : 'N/A' }}</div></div>
                    <div style="grid-column: 1 / -1;"><div style="color: var(--color-text-secondary); font-size: 12px; margin-bottom: 2px;">Target States</div><div style="font-weight: var(--fw-medium); line-height: 1.4;">{{ isset($payload['target_states']) && is_array($payload['target_states']) ? implode(', ', $payload['target_states']) : 'N/A' }}</div></div>
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

@section('scripts')
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
        for (let i = 1; i <= 7; i++) {
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

    // Tab switching (Wizard Stepper vs Overview Dashboard)
    function switchMainTab(tab) {
        const wizardBtn = document.getElementById('tab-btn-wizard');
        const overviewBtn = document.getElementById('tab-btn-overview');
        const wizardContent = document.getElementById('tab-content-wizard');
        const overviewContent = document.getElementById('tab-content-overview');

        if (tab === 'wizard') {
            wizardBtn.classList.add('active');
            overviewBtn.classList.remove('active');
            wizardContent.classList.add('active');
            overviewContent.classList.remove('active');
        } else {
            wizardBtn.classList.remove('active');
            overviewBtn.classList.add('active');
            wizardContent.classList.remove('active');
            overviewContent.classList.add('active');
        }
    }

    const stepNames = {
        1: 'Business Info',
        2: 'LLC Formation',
        3: 'EIN',
        4: 'Documents',
        5: 'Banking',
        6: 'Tax & Compliance',
        7: 'Business Structure'
    };

    // Step switching within Wizard
    function jumpToStep(stepNumber) {
        if (isEditMode && stepNumber !== editModeStep) return;
        
        // Toggle step forms visibility
        for (let i = 1; i <= 7; i++) {
            const form = document.getElementById('step-form-container-' + i);
            if (form) {
                form.style.display = (i === stepNumber) ? 'block' : 'none';
            }
            
            // Sync step class highlights
            const item = document.getElementById('step-nav-' + i);
            if (item) {
                if (i === stepNumber) {
                    item.classList.add('in-progress');
                    // Smoothly scroll active step into view
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

        if (typeof updateStepperScrollButtons === 'function') {
            setTimeout(updateStepperScrollButtons, 300);
        }
    }

    // Conditional Fields toggle: LLC Yes/No
    function toggleLlcFields(hasLlc) {
        const yesFields = document.getElementById('llc-yes-fields');
        const noFields = document.getElementById('llc-no-fields');
        
        const llcName = document.getElementById('llc_name');
        const formationDate = document.getElementById('formation_date');
        const prefState = document.getElementById('preferred_state');
        const proposedName = document.getElementById('proposed_llc_name');

        if (hasLlc === 'yes') {
            yesFields.style.display = 'grid';
            noFields.style.display = 'none';
            if (llcName) llcName.required = true;
            if (formationDate) formationDate.required = true;
            if (prefState) prefState.required = false;
            if (proposedName) proposedName.required = false;
        } else {
            yesFields.style.display = 'none';
            noFields.style.display = 'grid';
            if (llcName) llcName.required = false;
            if (formationDate) formationDate.required = false;
            if (prefState) prefState.required = true;
            if (proposedName) proposedName.required = true;
        }
    }

    // Conditional Fields toggle: EIN Yes/No
    function toggleEinFields(hasEin) {
        const numContainer = document.getElementById('ein-number-container');
        const einNum = document.getElementById('ein_number');
        
        if (hasEin === 'yes') {
            numContainer.style.display = 'block';
            if (einNum) einNum.required = true;
        } else {
            numContainer.style.display = 'none';
            if (einNum) einNum.required = false;
        }
    }

    // Toggle Selectable Checkbox Cards
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

    // Initialize conditional fields based on default/previous database choices
    // Scroll stepper horizontally
    function scrollStepper(amount) {
        const container = document.getElementById('stepper-container-div');
        if (container) {
            container.scrollBy({ left: amount, behavior: 'smooth' });
        }
    }

    function updateStepperScrollButtons() {
        const container = document.getElementById('stepper-container-div');
        const leftBtn = document.getElementById('stepper-left-btn');
        const rightBtn = document.getElementById('stepper-right-btn');
        
        if (!container || !leftBtn || !rightBtn) return;

        if (container.scrollWidth > container.clientWidth) {
            leftBtn.style.display = container.scrollLeft > 0 ? 'flex' : 'none';
            rightBtn.style.display = Math.ceil(container.scrollLeft + container.clientWidth) < container.scrollWidth ? 'flex' : 'none';
        } else {
            leftBtn.style.display = 'none';
            rightBtn.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const selectedLlcRadio = document.querySelector('input[name="has_llc"]:checked');
        if (selectedLlcRadio) {
            toggleLlcFields(selectedLlcRadio.value);
        }

        const selectedEinRadio = document.querySelector('input[name="has_ein"]:checked');
        if (selectedEinRadio) {
            toggleEinFields(selectedEinRadio.value);
        }

        // Initialize custom multi-select
        initCustomMultiselect('target_states_container');

        // Initialize scroll buttons
        const container = document.getElementById('stepper-container-div');
        if (container) {
            container.addEventListener('scroll', updateStepperScrollButtons);
            window.addEventListener('resize', updateStepperScrollButtons);
            setTimeout(updateStepperScrollButtons, 100);
        }
    });
</script>
@endsection
