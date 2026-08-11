<div class="document-upload-card">
    <div class="document-card-header">
        <span class="document-title">{{ $title }} @if($required)<span style="color: var(--color-danger);">*</span>@endif</span>
        <span class="badge {{ $required ? 'badge-danger' : 'badge-warning' }}" style="font-size: 10px;">
            {{ $required ? 'Required' : 'Optional' }}
        </span>
    </div>

    @if(empty($meta))
        <!-- Upload Form -->
        <form action="{{ route('services.upload_document', $serviceKey ?? 'business-setup') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="field_name" value="{{ $field }}">
            <input type="hidden" name="step" value="{{ $step ?? 4 }}">
            
            <div class="drag-drop-area" onclick="document.getElementById('file-upload-{{ $field }}').click()">
                <svg class="drag-drop-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <span class="drag-drop-text" style="font-weight: var(--fw-semibold); color: var(--color-primary);">Upload file</span>
                <span class="drag-drop-text" style="margin-top: 2px;">PDF, PNG, JPG up to 10MB</span>
                <input type="file" id="file-upload-{{ $field }}" name="document" style="display:none;" onchange="this.form.submit()">
            </div>
        </form>
        
        <div class="uploaded-file-meta" style="border-style: dashed; background-color: transparent; border-color: var(--color-border);">
            <div class="file-info">
                <span class="file-name" style="color: var(--color-text-muted); font-weight: normal;">No file uploaded</span>
                <span class="file-details">Status: Missing</span>
            </div>
        </div>
    @else
        <!-- View / Delete Info -->
        <div class="drag-drop-area" style="border-style: solid; background-color: var(--color-success-light); border-color: var(--color-success); cursor: default;">
            <svg class="drag-drop-icon" style="color: var(--color-success);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="drag-drop-text" style="font-weight: var(--fw-semibold); color: var(--color-success-dark);">File Uploaded Successfully</span>
        </div>

        <div class="uploaded-file-meta">
            <div class="file-info">
                <a href="{{ $meta['url'] }}" target="_blank" class="file-name" title="{{ $meta['original_name'] }}">
                    {{ $meta['original_name'] }}
                </a>
                <span class="file-details">
                    {{ $meta['size'] }} • {{ $meta['upload_date'] }} • 
                    <span style="font-weight: var(--fw-semibold); color: var(--color-success-dark);">{{ $meta['status'] }}</span>
                </span>
            </div>
            <div class="file-actions">
                <form action="{{ route('services.remove_document', $serviceKey ?? 'business-setup') }}" method="POST">
                    @csrf
                    <input type="hidden" name="field_name" value="{{ $field }}">
                    <button type="submit" class="btn btn-secondary" style="height: 28px; padding: 0 var(--spacing-2); font-size: 10px; color: var(--color-danger); border-color: rgba(239,68,68,0.2);">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
