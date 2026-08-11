@extends('layouts.admin')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('layout_content')
<div class="dashboard-layout">

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar-menu">
        <div class="sidebar-brand">
            <div class="sidebar-logo-icon">S</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-logo-text" style="color: #ffffff; text-decoration: none;">
                SaaS<span style="color: var(--color-primary);">Admin</span>
            </a>
        </div>

        <nav class="sidebar-navigation">
            <div class="sidebar-navigation-label">Overview</div>
            
            <!-- Dashboard Link -->
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Expandable Services Menu -->
            <div class="sidebar-dropdown-container {{ request()->is('services*') ? 'open' : '' }}" id="services-dropdown">
                <a href="javascript:void(0)" class="sidebar-link sidebar-dropdown-header {{ request()->is('services*') ? 'active' : '' }}" id="services-menu-header">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    <span>Services</span>
                    <span class="sidebar-dropdown-chevron-btn" id="services-chevron-btn" aria-label="Toggle Submenu">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="chevron-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </span>
                </a>
                
                <div class="sidebar-submenu" id="services-submenu">
                    <a href="{{ url('/services/business-setup') }}" class="sidebar-submenu-link {{ request()->is('services/business-setup*') ? 'active' : '' }}" title="Business Setup & Compliance">
                        Business Setup & Compliance
                    </a>
                    <a href="{{ url('/services/branding-website') }}" class="sidebar-submenu-link {{ request()->is('services/branding-website*') ? 'active' : '' }}" title="Branding & Website Development">
                        Branding & Website Development
                    </a>
                    <a href="{{ url('/services/product-hunting') }}" class="sidebar-submenu-link {{ request()->is('services/product-hunting*') ? 'active' : '' }}" title="Product Hunting & Sourcing">
                        Product Hunting & Sourcing
                    </a>
                    <a href="{{ url('/services/marketplace-retail') }}" class="sidebar-submenu-link {{ request()->is('services/marketplace-retail*') ? 'active' : '' }}" title="Marketplace & Retail Services">
                        Marketplace & Retail Services
                    </a>
                    <a href="{{ url('/services/fulfillment-logistics') }}" class="sidebar-submenu-link {{ request()->is('services/fulfillment-logistics*') ? 'active' : '' }}" title="Fulfillment & Logistics">
                        Fulfillment & Logistics
                    </a>
                </div>
            </div>

            <!-- Placeholders links for other sidebar items -->
            <div class="sidebar-navigation-label">SaaS Operations</div>
            
            <a href="#" class="sidebar-link">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 13.5V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 9.75V10.5" />
                </svg>
                <span>Projects</span>
            </a>

            <a href="#" class="sidebar-link">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 12.481A2.25 2.25 0 017.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-.375" />
                </svg>
                <span>Tasks</span>
            </a>

            <a href="#" class="sidebar-link">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span>Documents</span>
            </a>

            <a href="#" class="sidebar-link">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v5.625c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 013 18.75v-5.625zM16.5 13.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 12 21 12.504 21 13.125v5.625c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125v-5.625zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v10.125c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625z" />
                </svg>
                <span>Reports</span>
            </a>

            <a href="#" class="sidebar-link">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.991l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Settings</span>
            </a>
        </nav>

        <!-- Sidebar User Footer -->
        <div class="sidebar-footer">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: var(--spacing-3);">
                    <div class="avatar" style="border: none; background-color: var(--color-primary); color: #ffffff;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="sidebar-user-details" style="display: flex; flex-direction: column;">
                        <span style="font-weight: var(--fw-semibold); color: #ffffff; white-space: nowrap; max-width: 130px; overflow: hidden; text-overflow: ellipsis; font-size: var(--fs-xs);">
                            {{ Auth::user()->name }}
                        </span>
                        <span style="font-size: 10px; color: rgba(255,255,255,0.4);">Admin Portal</span>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper" id="main-content-layout">
        
        <!-- Top navbar -->
        <header class="top-navbar">
            <div class="navbar-left">
                <button class="sidebar-toggle" id="sidebar-toggle-btn" aria-label="Toggle menu">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="user-role" style="background-color: var(--color-primary-light); color: var(--color-primary); padding: 4px 10px; border-radius: var(--radius-sm); font-weight: var(--fw-semibold); font-size: var(--fs-xs);">
                    Production Environment
                </div>
                
                <!-- Company Selector Dropdown -->
                <div class="company-selector" style="position: relative; display: inline-block; margin-left: 10px;">
                    <button class="btn" id="company-dropdown-btn" style="display: flex; align-items: center; gap: 8px; font-size: var(--fs-xs); font-weight: var(--fw-semibold); height: 28px; padding: 0 12px; border-radius: var(--radius-sm); border: 1px solid var(--color-border); background-color: #ffffff; color: var(--color-text-primary); cursor: pointer; transition: all var(--transition-fast);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px; color: var(--color-primary);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v3m0 0h4.5" />
                        </svg>
                        <span>{{ $activeCompany->name ?? 'Select Company' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 10px; height: 10px; opacity: 0.5;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <!-- Dropdown List -->
                    <div id="company-dropdown-menu" style="display: none; position: absolute; top: calc(100% + 6px); left: 0; min-width: 220px; background-color: #ffffff; border: 1px solid var(--color-border); border-radius: var(--radius-md); box-shadow: var(--shadow-lg); z-index: 1000; padding: 6px;">
                        <div style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); padding: 6px 12px; font-weight: var(--fw-bold);">Select Business</div>
                        <div class="company-list" style="max-height: 200px; overflow-y: auto; display: flex; flex-direction: column; gap: 2px;">
                            @foreach($companies as $company)
                                <form action="{{ route('companies.switch') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <input type="hidden" name="company_id" value="{{ $company->id }}">
                                    <button type="submit" style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; font-size: var(--fs-xs); font-weight: var(--fw-medium); border: none; border-radius: var(--radius-sm); background: none; text-align: left; color: {{ $activeCompany->id == $company->id ? 'var(--color-primary)' : 'var(--color-text-primary)' }}; cursor: pointer; transition: all var(--transition-fast);">
                                        <span>{{ $company->name }}</span>
                                        @if($activeCompany->id == $company->id)
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                            @endforeach
                        </div>
                        <div style="height: 1px; background-color: var(--color-border); margin: 6px 0;"></div>
                        <a href="javascript:void(0)" id="add-company-btn" style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; font-size: var(--fs-xs); font-weight: var(--fw-semibold); color: var(--color-primary); text-decoration: none; border-radius: var(--radius-sm); cursor: pointer; transition: all var(--transition-fast);">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Create New Business</span>
                        </a>
                    </div>
                </div>

                <!-- Create Company Modal -->
                <div id="create-company-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center;">
                    <div style="background-color: #ffffff; padding: 24px; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); width: 100%; max-width: 400px; position: relative;">
                        <h3 style="font-size: var(--fs-md); font-weight: var(--fw-bold); color: var(--color-text-primary); margin-bottom: var(--spacing-4);">Create New Business Profile</h3>
                        <form action="{{ route('companies.store') }}" method="POST">
                            @csrf
                            <div style="margin-bottom: var(--spacing-4);">
                                <label style="display: block; font-size: var(--fs-xs); font-weight: var(--fw-semibold); color: var(--color-text-secondary); margin-bottom: var(--spacing-2);">Business Name</label>
                                <input type="text" name="name" required placeholder="e.g. Acme Corporation" style="width: 100%; height: 36px; padding: 0 var(--spacing-3); border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: var(--fs-sm); outline: none;" />
                            </div>
                            <div style="display: flex; justify-content: flex-end; gap: var(--spacing-3);">
                                <button type="button" id="close-modal-btn" style="height: 36px; padding: 0 16px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: var(--fs-xs); font-weight: var(--fw-semibold); background-color: #ffffff; cursor: pointer;">Cancel</button>
                                <button type="submit" style="height: 36px; padding: 0 16px; background-color: var(--color-primary); color: #ffffff; border: none; border-radius: var(--radius-sm); font-size: var(--fs-xs); font-weight: var(--fw-semibold); cursor: pointer;">Create Business</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="navbar-right">
                <div class="user-profile">
                    <div class="avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">Administrator</span>
                    </div>
                </div>
                
                <div style="width: 1px; height: 24px; background-color: var(--color-border);"></div>
                
                <!-- Logout Action -->
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout" title="Sign Out">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    </button>
                </form>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <main class="content-body">
            @yield('content')
        </main>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Services Submenu Dropdown
        const servicesDropdown = document.getElementById('services-dropdown');
        const chevronBtn = document.getElementById('services-chevron-btn');
        const servicesHeader = document.getElementById('services-menu-header');

        if (servicesDropdown && servicesHeader) {
            servicesHeader.addEventListener('click', function(e) {
                e.preventDefault();
                servicesDropdown.classList.toggle('open');
            });
        }

        // Company Selector Dropdown Toggle
        const companyDropdownBtn = document.getElementById('company-dropdown-btn');
        const companyDropdownMenu = document.getElementById('company-dropdown-menu');
        
        if (companyDropdownBtn && companyDropdownMenu) {
            companyDropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                companyDropdownMenu.style.display = companyDropdownMenu.style.display === 'none' ? 'block' : 'none';
            });
            
            document.addEventListener('click', function(event) {
                if (!companyDropdownBtn.contains(event.target) && !companyDropdownMenu.contains(event.target)) {
                    companyDropdownMenu.style.display = 'none';
                }
            });
        }

        // Create Company Modal Toggle
        const addCompanyBtn = document.getElementById('add-company-btn');
        const createCompanyModal = document.getElementById('create-company-modal');
        const closeModalBtn = document.getElementById('close-modal-btn');
        
        if (addCompanyBtn && createCompanyModal && closeModalBtn) {
            addCompanyBtn.addEventListener('click', function(e) {
                e.preventDefault();
                companyDropdownMenu.style.display = 'none';
                createCompanyModal.style.display = 'flex';
            });
            
            closeModalBtn.addEventListener('click', function() {
                createCompanyModal.style.display = 'none';
            });
            
            createCompanyModal.addEventListener('click', function(event) {
                if (event.target === createCompanyModal) {
                    createCompanyModal.style.display = 'none';
                }
            });
        }
    });

    // Custom SaaS Dropdown Multi-Select UI Component
    function initCustomMultiselect(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const trigger = container.querySelector('.custom-multiselect-trigger');
        const options = container.querySelectorAll('.multiselect-option');
        const hiddenSelect = container.querySelector('select');
        
        // Open/Close dropdown
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            document.querySelectorAll('.custom-multiselect-container').forEach(c => {
                if (c !== container) c.classList.remove('open');
            });
            container.classList.toggle('open');
        });

        // Click Option
        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                const checkbox = option.querySelector('.multiselect-checkbox');
                const val = option.getAttribute('data-value');
                
                const isSelected = option.classList.toggle('selected');
                if (checkbox) checkbox.checked = isSelected;
                
                const selectOption = hiddenSelect.querySelector(`option[value="${val}"]`);
                if (selectOption) selectOption.selected = isSelected;
                
                hiddenSelect.dispatchEvent(new Event('change'));
                updateTags();
            });

            // Prevent double-clicking via direct checkbox clicks
            const checkbox = option.querySelector('.multiselect-checkbox');
            if (checkbox) {
                checkbox.addEventListener('click', function(e) {
                    e.stopPropagation();
                    option.click();
                });
            }
        });

        // Synchronize and render visual tag capsules
        function updateTags() {
            const tags = trigger.querySelectorAll('.multiselect-tag');
            tags.forEach(t => t.remove());

            const placeholder = trigger.querySelector('.multiselect-placeholder');
            const selectedOptions = Array.from(hiddenSelect.selectedOptions);

            if (selectedOptions.length === 0) {
                placeholder.style.display = 'block';
            } else {
                placeholder.style.display = 'none';
                selectedOptions.forEach(opt => {
                    const val = opt.value;
                    const text = opt.text;

                    const tag = document.createElement('span');
                    tag.className = 'multiselect-tag';
                    tag.innerHTML = `${text}<span class="multiselect-tag-remove" data-value="${val}">&times;</span>`;
                    
                    // Click cross icon to unselect tag
                    tag.querySelector('.multiselect-tag-remove').addEventListener('click', function(e) {
                        e.stopPropagation();
                        const optToRemove = hiddenSelect.querySelector(`option[value="${val}"]`);
                        if (optToRemove) optToRemove.selected = false;
                        
                        const optionDiv = container.querySelector(`.multiselect-option[data-value="${val}"]`);
                        if (optionDiv) {
                            optionDiv.classList.remove('selected');
                            const chk = optionDiv.querySelector('.multiselect-checkbox');
                            if (chk) chk.checked = false;
                        }
                        hiddenSelect.dispatchEvent(new Event('change'));
                        updateTags();
                    });

                    trigger.appendChild(tag);
                });
            }
        }

        // Run initially to restore state from previous database saves
        updateTags();
    }

    // Close custom dropdowns when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.custom-multiselect-container').forEach(c => {
            c.classList.remove('open');
        });
    });
</script>
@yield('dashboard_scripts')
@endsection
