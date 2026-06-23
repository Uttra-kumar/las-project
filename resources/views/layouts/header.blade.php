<style>
    .top-header {
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 2px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 100;
        width: 100%;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .menu-toggle {
        background: none;
        border: none;
        font-size: 1.3rem;
        cursor: pointer;
        color: #333;
        padding: 8px;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .menu-toggle:hover {
        background: #f0f0f0;
    }

    /* School Name Section */
    .school-info {
        display: flex;
        align-items: center;
        gap: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 5px 15px;
        border-radius: 30px;
        color: white;
    }

    .school-icon {
        font-size: 1rem;
    }

    .school-name {
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Session Dropdown */
    .session-dropdown {
        position: relative;
    }

    .session-selector {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
        padding: 6px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
    }

    .session-selector:hover {
        background: #e2e8f0;
    }

    .session-icon {
        color: #667eea;
        font-size: 0.9rem;
    }

    .session-name {
        font-size: 0.85rem;
        font-weight: 500;
        color: #1e293b;
    }

    .session-arrow {
        color: #64748b;
        font-size: 0.7rem;
        transition: transform 0.2s;
    }

    .session-arrow.rotated {
        transform: rotate(180deg);
    }

    .session-menu {
        position: absolute;
        top: 100%;
        left: 0;
        margin-top: 8px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        min-width: 200px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s;
        z-index: 1000;
        border: 1px solid #e2e8f0;
    }

    .session-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .session-item {
        padding: 10px 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.85rem;
        color: #1e293b;
    }

    .session-item:hover {
        background: #f8fafc;
    }

    .session-item.active {
        background: #e0e7ff;
        color: #4338ca;
    }

    .session-item.active i {
        color: #4338ca;
    }

    .session-item i {
        width: 18px;
        font-size: 0.8rem;
        color: #64748b;
    }

    .session-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 5px 0;
    }

    /* User Dropdown */
    .user-dropdown {
        position: relative;
        cursor: pointer;
    }

    .user-info-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 6px 12px;
        border-radius: 30px;
        background: #f8fafc;
        transition: all 0.3s;
    }

    .user-info-header:hover {
        background: #e2e8f0;
    }

    .user-avatar {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1rem;
    }

    .user-name {
        font-weight: 500;
        color: black;
        font-size: 0.85rem;
    }

    .dropdown-arrow {
        color: #666;
        font-size: 0.7rem;
        transition: transform 0.3s;
    }

    .dropdown-arrow.rotated {
        transform: rotate(180deg);
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 10px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        min-width: 180px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s;
        z-index: 1000;
    }

    .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item {
        padding: 10px 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #333;
        text-decoration: none;
        transition: all 0.3s;
        border: none;
        background: none;
        width: 100%;
        cursor: pointer;
        font-size: 0.85rem;
    }

    .dropdown-item:hover {
        background: #f8fafc;
    }

    .dropdown-item i {
        width: 18px;
        color: #667eea;
    }

    .dropdown-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 5px 0;
    }

    @media (max-width: 768px) {
        .top-header {
            padding: 8px 15px;
        }
        .user-name {
            display: none;
        }
        .session-name {
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .school-name {
            display: none;
        }
        .school-info {
            padding: 5px 10px;
        }
    }
</style>

<header class="top-header">
    <div class="header-left">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- School Name Section -->
      
        
        <!-- Session Dropdown -->
        <div class="session-dropdown" id="sessionDropdown">
            <div class="session-selector" onclick="toggleSessionMenu()">
                <i class="fas fa-calendar-alt session-icon"></i>
                <span class="session-name" id="currentSessionName">
                    {{ \App\Helpers\SessionHelper::getCurrentSessionName() }}
                </span>
                <i class="fas fa-chevron-down session-arrow" id="sessionArrow"></i>
            </div>
            
            <div class="session-menu" id="sessionMenu">
                <div style="padding: 8px 12px; font-size: 0.7rem; color: #64748b; border-bottom: 1px solid #e2e8f0;">
                    <i class="fas fa-calendar-alt"></i> Select Active Session
                </div>
                @php
                    use App\Helpers\SessionHelper;
                    $activeSessions = SessionHelper::getActiveSessions();
                    $currentSessionId = SessionHelper::getCurrentSessionId();
                @endphp
                @forelse($activeSessions as $session)
                <div class="session-item {{ $currentSessionId == $session->session_id ? 'active' : '' }}" 
                     onclick="changeSession('{{ $session->session_id }}', '{{ $session->session_name }}')">
                    <i class="fas {{ $currentSessionId == $session->session_id ? 'fa-check-circle' : 'fa-circle' }}"></i>
                    <span>{{ $session->session_name }}</span>
                    @if($currentSessionId == $session->session_id)
                        <span style="margin-left: auto; font-size: 0.6rem; color: #16a34a;">
                            <i class="fas fa-check"></i> Selected
                        </span>
                    @endif
                </div>
                @empty
                <div class="session-item" style="opacity: 0.6;">
                    <i class="fas fa-info-circle"></i>
                    <span>No active sessions</span>
                </div>
                @endforelse
                <div class="session-divider"></div>
                <div class="session-item" onclick="window.location.href='{{ route('sessions.index') }}'">
                    <i class="fas fa-cog"></i>
                    <span>Manage Sessions</span>
                </div>
            </div>
        </div>
    </div>
      <div class="school-info">
            <i class="fas fa-school school-icon"></i>
            <span class="school-name">
                {{ \App\Models\SchoolSetting::first()->school_name ?? 'School Management' }}
            </span>
        </div>
    <div class="user-dropdown" id="userDropdown" onclick="toggleUserDropdown()">
        <div class="user-info-header">
            <div class="user-avatar">
                @if(auth()->user()->image)
                    <img src="{{ asset('storage/' . auth()->user()->image) }}" 
                         alt="{{ auth()->user()->name }}" 
                         style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                @else
                    {{ substr(auth()->user()->name, 0, 1) }}
                @endif
            </div>
            <span class="user-name">{{ auth()->user()->name }}</span>
            <i class="fas fa-chevron-down dropdown-arrow" id="userArrow"></i>
        </div>
        
        <div class="dropdown-menu" id="userMenu">
            <button class="dropdown-item" onclick="showProfile()">
                <i class="fas fa-user-circle"></i> Profile
            </button>
            <button class="dropdown-item" onclick="profile()">
                <i class="fas fa-cog"></i> Settings
            </button>
            <div class="dropdown-divider"></div>
            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <button type="button" class="dropdown-item" onclick="confirmLogout()">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
</header>

<script>
    // Session Dropdown Functions
    function toggleSessionMenu() {
        const menu = document.getElementById('sessionMenu');
        const arrow = document.getElementById('sessionArrow');
        if (menu && arrow) {
            menu.classList.toggle('show');
            arrow.classList.toggle('rotated');
        }
    }
    
    function changeSession(sessionId, sessionName) {
        // Show loading
        Swal.fire({
            title: 'Changing Session...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        fetch('/change-session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ session_id: sessionId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update current session name in header
                document.getElementById('currentSessionName').innerText = sessionName;
                
                // Close dropdown
                document.getElementById('sessionMenu').classList.remove('show');
                document.getElementById('sessionArrow').classList.remove('rotated');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Session Changed!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                
                // Reload page after 1.5 seconds
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Something went wrong! Please try again.'
            });
        });
    }
    
    // Close session menu when clicking outside
    document.addEventListener('click', function(event) {
        const sessionDropdown = document.getElementById('sessionDropdown');
        const sessionMenu = document.getElementById('sessionMenu');
        const sessionArrow = document.getElementById('sessionArrow');
        
        if (sessionDropdown && !sessionDropdown.contains(event.target)) {
            if (sessionMenu) sessionMenu.classList.remove('show');
            if (sessionArrow) sessionArrow.classList.remove('rotated');
        }
    });
    
    // User Dropdown Functions
    function toggleUserDropdown() {
        const menu = document.getElementById('userMenu');
        const arrow = document.getElementById('userArrow');
        if (menu && arrow) {
            menu.classList.toggle('show');
            arrow.classList.toggle('rotated');
        }
    }
    
    // Close user menu when clicking outside
    document.addEventListener('click', function(event) {
        const userDropdown = document.getElementById('userDropdown');
        const userMenu = document.getElementById('userMenu');
        const userArrow = document.getElementById('userArrow');
        
        if (userDropdown && !userDropdown.contains(event.target)) {
            if (userMenu) userMenu.classList.remove('show');
            if (userArrow) userArrow.classList.remove('rotated');
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
    
   function profile() {
    window.location.href = "{{ route('profile.show') }}";
}
</script>