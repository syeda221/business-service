<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SaaS Admin') - Enterprise Portal</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Global CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>
    @yield('layout_content')

    <!-- Global JS Actions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Toggle
            const toggleBtn = document.getElementById('sidebar-toggle-btn');
            const sidebar = document.getElementById('sidebar-menu');
            const mainContent = document.getElementById('main-content-layout');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            function toggleSidebarMobile() {
                if (sidebar) {
                    const isOpen = sidebar.classList.toggle('open');
                    if (backdrop) {
                        if (isOpen) {
                            backdrop.classList.add('active');
                        } else {
                            backdrop.classList.remove('active');
                        }
                    }
                }
            }

            function closeSidebarMobile() {
                if (sidebar && sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                }
                if (backdrop) {
                    backdrop.classList.remove('active');
                }
            }

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (window.innerWidth > 1024) {
                        sidebar.classList.toggle('collapsed');
                        if (mainContent) {
                            mainContent.classList.toggle('collapsed');
                        }
                    } else {
                        toggleSidebarMobile();
                    }
                });
                
                if (backdrop) {
                    backdrop.addEventListener('click', function() {
                        closeSidebarMobile();
                    });
                }

                // Close sidebar if user clicks outside of it on mobile
                document.addEventListener('click', function(event) {
                    if (window.innerWidth <= 1024) {
                        const isClickInsideSidebar = sidebar.contains(event.target);
                        const isClickInsideToggle = toggleBtn.contains(event.target);
                        
                        if (!isClickInsideSidebar && !isClickInsideToggle && sidebar.classList.contains('open')) {
                            closeSidebarMobile();
                        }
                    }
                });
            }

            // Auto-scroll active step in steppers into view smoothly on mobile/desktop
            const activeStep = document.querySelector('.step-item.in-progress');
            const stepperContainer = document.querySelector('.stepper-container');
            if (activeStep && stepperContainer) {
                setTimeout(() => {
                    const containerRect = stepperContainer.getBoundingClientRect();
                    const stepRect = activeStep.getBoundingClientRect();
                    if (stepRect.left < containerRect.left || stepRect.right > containerRect.right) {
                        activeStep.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                    }
                }, 100);
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
