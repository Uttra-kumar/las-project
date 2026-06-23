<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'School Management System')</title>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #f1f5f9;
            overflow-x: hidden;
        }
        
        .pagination {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 5px !important;
            margin-top: 20px !important;
            flex-wrap: wrap !important;
        }

        .pagination nav {
            display: inline-flex !important;
            gap: 5px !important;
            flex-wrap: wrap !important;
        }

        .pagination a,
        .pagination span {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 32px !important;
            height: 32px !important;
            padding: 0 8px !important;
            border-radius: 6px !important;
            font-size: 13px !important;
            text-decoration: none !important;
            transition: all 0.2s !important;
        }

        .pagination a {
            background: white !important;
            border: 1px solid #ddd !important;
            color: #333 !important;
        }

        .pagination a:hover {
            background: #1e3c72 !important;
            color: white !important;
            border-color: #1e3c72 !important;
        }

        .pagination span {
            background: #1e3c72 !important;
            color: white !important;
            border: 1px solid #1e3c72 !important;
        }

        .pagination .disabled span {
            background: #f5f5f5 !important;
            color: #999 !important;
            border-color: #ddd !important;
            cursor: not-allowed !important;
        }

        .pagination .hidden {
            display: none !important;
        }
        
        .app-container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            transition: all 0.3s;
            width: calc(100% - 250px);
            position: relative;
            z-index: 1;
            background: #f1f5f9;
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: 65px;
            width: calc(100% - 65px);
        }

        .page-content {
            padding: 20px;
            animation: fadeIn 0.3s ease;
            position: relative;
            z-index: 1;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="app-container">
        @include('layouts.sidebar')
        
        <div class="main-content" id="mainContent">
            @include('layouts.header')
            
            <div class="page-content">
                @yield('content')
            </div>
        </div>
    </div>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (window.innerWidth > 768) {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            } else {
                sidebar.classList.toggle('mobile-open');
            }
        }
        
        if (localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth > 768) {
            document.getElementById('sidebar')?.classList.add('collapsed');
            document.getElementById('mainContent')?.classList.add('expanded');
        }
        
        function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            const arrow = document.getElementById('dropdownArrow');
            if (menu && arrow) {
                menu.classList.toggle('show');
                arrow.classList.toggle('rotated');
            }
        }
        
        // ===== FIXED: Only one submenu open at a time =====
        let currentlyOpen = null;

        function toggleSubMenu(submenuId) {
            const submenu = document.getElementById(submenuId);
            const arrow = document.getElementById(submenuId.replace('Submenu', 'Arrow'));
            
            if (!submenu) return;
            
            // Check if this submenu is already open
            const isOpen = submenu.classList.contains('show');
            
            // CLOSE ALL SUBMENUS FIRST
            document.querySelectorAll('.sub-menu').forEach(menu => {
                menu.classList.remove('show');
            });
            
            // Remove all rotated arrows
            document.querySelectorAll('.nav-arrow').forEach(arrowEl => {
                arrowEl.classList.remove('rotated');
            });
            
            // If clicked submenu was NOT open, open it
            if (!isOpen) {
                submenu.classList.add('show');
                if (arrow) arrow.classList.add('rotated');
                currentlyOpen = submenuId;
            } else {
                currentlyOpen = null;
            }
            
            // Save to localStorage
            let openMenus = currentlyOpen ? [currentlyOpen] : [];
            localStorage.setItem('openMenus', JSON.stringify(openMenus));
        }

        // ===== Load saved submenu on page load =====
        function loadSavedSubmenus() {
            const openMenus = JSON.parse(localStorage.getItem('openMenus') || '[]');
            
            // Close all first
            document.querySelectorAll('.sub-menu').forEach(menu => {
                menu.classList.remove('show');
            });
            document.querySelectorAll('.nav-arrow').forEach(arrow => {
                arrow.classList.remove('rotated');
            });
            
            // Open only the saved one
            if (openMenus.length > 0) {
                const menuId = openMenus[0];
                const submenu = document.getElementById(menuId);
                const arrow = document.getElementById(menuId.replace('Submenu', 'Arrow'));
                if (submenu) {
                    submenu.classList.add('show');
                    if (arrow) arrow.classList.add('rotated');
                    currentlyOpen = menuId;
                }
            }
        }
        
        document.addEventListener('DOMContentLoaded', loadSavedSubmenus);
        
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userDropdown');
            const menu = document.getElementById('dropdownMenu');
            const arrow = document.getElementById('dropdownArrow');
            if (dropdown && !dropdown.contains(e.target) && menu && arrow) {
                menu.classList.remove('show');
                arrow.classList.remove('rotated');
            }
        });
        
        function confirmLogout() {
            Swal.fire({
                title: 'Logout?',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then(result => {
                if (result.isConfirmed) document.getElementById('logoutForm').submit();
            });
        }
        
        function showProfile() {
            Swal.fire({
                title: 'My Profile',
                html: `<div style="text-align:left">
                    <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    <p><strong>Mobile:</strong> {{ auth()->user()->mobile ?? 'Not provided' }}</p>
                    <p><strong>Role:</strong> {{ ucfirst(auth()->user()->role) }}</p>
                </div>`,
                icon: 'info',
                confirmButtonColor: '#667eea'
            });
        }
        
        function showSettings() {
            Swal.fire({ title: 'Settings', text: 'Coming soon!', icon: 'info', confirmButtonColor: '#667eea' });
        }
        
        function showComingSoon() {
            Swal.fire({ title: 'Coming Soon!', text: 'Under development', icon: 'info', confirmButtonColor: '#667eea', timer: 1500, showConfirmButton: false });
        }
        
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Success!', text: '{{ session('success') }}', confirmButtonColor: '#667eea', timer: 3000, showConfirmButton: false });
        @endif
        
    </script>
    
    @stack('scripts')
</body>
</html>