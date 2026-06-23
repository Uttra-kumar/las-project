<!-- Super Compact Sidebar Component -->
<style>
    /* ===== SIDEBAR ===== */
    .sidebar {
        width: 250px;
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        color: white;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        z-index: 1000;
        overflow-y: auto;
        box-shadow: 4px 0 15px rgba(0,0,0,0.2);
        transform: translateX(0);
    }

    .sidebar::-webkit-scrollbar {
        width: 4px;
    }
    
    .sidebar::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.05);
    }
    
    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.2);
        border-radius: 4px;
    }

    /* ===== SIDEBAR COLLAPSED ===== */
    .sidebar.collapsed {
        width: 65px;
    }

    .sidebar.collapsed .sidebar-header,
    .sidebar.collapsed .nav-item span,
    .sidebar.collapsed .nav-label,
    .sidebar.collapsed .sub-menu {
        display: none;
    }

    .sidebar.collapsed .nav-item {
        justify-content: center;
        padding: 10px;
    }

    .sidebar.collapsed .nav-item i {
        margin: 0;
        font-size: 15px;
    }

    .sidebar.collapsed .sidebar-header {
        padding: 15px 0;
    }
    
    .sidebar.collapsed .user-avatar-large {
        width: 40px;
        height: 40px;
    }
    
    .sidebar.collapsed .user-avatar-large i {
        font-size: 1.2rem;
    }
    
    .sidebar.collapsed .user-name,
    .sidebar.collapsed .online-status {
        display: none;
    }

    /* ===== SIDEBAR HEADER ===== */
    .sidebar-header {
        padding: 20px 12px;
        text-align: center;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        margin-bottom: 16px;
    }

    .user-avatar-large {
        width: 50px;
        height: 50px;
        margin: 0 auto 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid rgba(255,255,255,0.2);
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        overflow: hidden;
    }

    .user-avatar-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-avatar-large i {
        font-size: 2rem;
        color: white;
    }

    .sidebar-user-name {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: white;
    }

    .online-status {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        margin-top: 5px;
    }

    .green-dot {
        width: 8px;
        height: 8px;
        background: #22c55e;
        border-radius: 50%;
        display: inline-block;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
        100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }

    .online-text {
        font-size: 0.65rem;
        color: rgba(255,255,255,0.7);
    }

    /* ===== NAVIGATION ===== */
    .nav-menu {
        padding: 0 10px;
        padding-bottom: 20px;
    }

    .nav-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255,255,255,0.4);
        padding: 8px 12px;
        margin-top: 8px;
        font-weight: 600;
    }

    .nav-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        margin: 3px 0;
        border-radius: 8px;
        color: rgba(255,255,255,0.75);
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
        min-height: 40px;
    }

    .nav-item:hover {
        background: rgba(255,255,255,0.08);
        color: white;
        transform: translateX(3px);
    }

    .nav-item.active {
        background: linear-gradient(95deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .nav-item-left {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .nav-item-left i {
        width: 20px;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .nav-item-left span {
        font-size: 0.8rem;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .nav-arrow {
        font-size: 0.7rem;
        transition: transform 0.3s ease;
        color: rgba(255,255,255,0.5);
        flex-shrink: 0;
    }

    .nav-arrow.rotated {
        transform: rotate(180deg);
    }

    /* ===== SUB MENU ===== */
    .sub-menu {
        margin-left: 30px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.6s ease-in-out;
    }

    .sub-menu.show {
        max-height: 500px;
    }

    .sub-menu-item {
        display: flex;
        align-items: center;
        padding: 6px 12px;
        margin: 2px 0;
        border-radius: 6px;
        color: rgba(255,255,255,0.6);
        text-decoration: none;
        font-size: 0.72rem;
        transition: all 0.2s;
        gap: 8px;
    }

    .sub-menu-item:hover {
        background: rgba(255,255,255,0.06);
        color: white;
        padding-left: 16px;
    }

    .sub-menu-item i {
        width: 18px;
        font-size: 0.7rem;
        color: rgba(255,255,255,0.5);
    }

    .sub-menu-item.active {
        background: rgba(102, 126, 234, 0.2);
        color: white;
    }

    .sub-menu-item.active i {
        color: #667eea;
    }

    .nav-divider {
        height: 1px;
        background: rgba(255,255,255,0.06);
        margin: 10px 12px;
    }

    /* ===== MOBILE OVERLAY ===== */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 999;
        backdrop-filter: blur(4px);
        transition: all 0.3s ease;
    }

    .sidebar-overlay.active {
        display: block;
    }

    /* ===== MOBILE HAMBURGER ===== */
    .hamburger-btn {
        display: none;
        position: fixed;
        top: 12px;
        left: 12px;
        z-index: 1001;
        background: linear-gradient(135deg, #0f172a, #1e1b4b);
        border: none;
        color: white;
        padding: 10px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1.2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .hamburger-btn:hover {
        background: linear-gradient(135deg, #1e293b, #2d2a5e);
        transform: scale(1.05);
    }

    .hamburger-btn .close-icon {
        display: none;
    }

    .hamburger-btn.active .hamburger-icon {
        display: none;
    }

    .hamburger-btn.active .close-icon {
        display: inline-block;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .hamburger-btn {
            display: block;
        }

        .sidebar {
            transform: translateX(-100%);
            width: 280px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 25px rgba(0,0,0,0.3);
        }

        .sidebar.mobile-open {
            transform: translateX(0);
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Push content when sidebar is open on mobile */
        .main-content {
            transition: margin-left 0.3s ease;
        }
    }

    @media (max-width: 480px) {
        .sidebar {
            width: 85vw;
            max-width: 300px;
        }

        .hamburger-btn {
            top: 10px;
            left: 10px;
            padding: 8px 10px;
            font-size: 1rem;
        }

        .sidebar-header {
            padding: 16px 12px;
        }

        .user-avatar-large {
            width: 45px;
            height: 45px;
        }

        .user-avatar-large i {
            font-size: 1.5rem;
        }

        .user-name {
            font-size: 0.8rem;
        }

        .nav-item {
            padding: 6px 10px;
            min-height: 36px;
        }

        .nav-item-left span {
            font-size: 0.75rem;
        }

        .sub-menu-item {
            font-size: 0.68rem;
            padding: 5px 10px;
        }

        .sub-menu {
            margin-left: 24px;
        }
    }

    @media (max-width: 380px) {
        .sidebar {
            width: 90vw;
        }

        .hamburger-btn {
            top: 8px;
            left: 8px;
            padding: 6px 8px;
            font-size: 0.9rem;
        }

        .user-avatar-large {
            width: 38px;
            height: 38px;
        }

        .user-avatar-large i {
            font-size: 1.2rem;
        }

        .nav-item {
            padding: 5px 8px;
            min-height: 32px;
        }

        .nav-item-left i {
            width: 16px;
            font-size: 0.85rem;
        }
    }

    /* ===== UTILITY ===== */
    .text-ellipsis {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<!-- ===== MOBILE HAMBURGER BUTTON ===== -->
<button class="hamburger-btn no-print" id="hamburgerBtn" onclick="toggleMobileSidebar()">
    <span class="hamburger-icon"><i class="fas fa-bars"></i></span>
    <span class="close-icon"><i class="fas fa-times"></i></span>
</button>

<!-- ===== SIDEBAR OVERLAY ===== -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

<!-- ===== SIDEBAR ===== -->
<aside class="sidebar" id="sidebar">
    <!-- User Profile Section -->
    <div class="sidebar-header">
        <div class="user-avatar-large">
            @if(auth()->user()->image)
                <img src="{{ asset('storage/' . auth()->user()->image) }}" 
                     alt="{{ auth()->user()->name }}">
            @else
                <i class="fas fa-user-circle"></i>
            @endif
        </div>
        <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
        <div class="online-status">
            <span class="green-dot"></span>
            <span class="online-text">Online</span>
        </div>
    </div>
    
    <div class="nav-menu">
        <!-- MAIN MENU -->
        <a href="{{ route('dashboard') }}" class="nav-item @yield('dashboard-active')">
            <div class="nav-item-left">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </div>
        </a>

        <div class="nav-divider"></div>
        
        <!-- STUDENTS -->
        <div class="nav-item" onclick="toggleSubMenu('studentSubmenu', this)">
            <div class="nav-item-left">
                <i class="fas fa-user-graduate"></i>
                <span>Students</span>
            </div>
            <i class="fas fa-chevron-down nav-arrow" id="studentArrow"></i>
        </div>
        <div class="sub-menu" id="studentSubmenu">
            <a href="{{ route('student.register') }}" class="sub-menu-item">
                <i class="fas fa-plus-circle"></i>
                <span>Registration</span>
            </a>
            <a href="{{ route('student.list') }}" class="sub-menu-item">
                <i class="fas fa-list"></i>
                <span>Student List</span>
            </a>
        </div>
        
        <!-- TEACHERS -->
        <div class="nav-item" onclick="toggleSubMenu('teacherSubmenu', this)">
            <div class="nav-item-left">
                <i class="fas fa-chalkboard-user"></i>
                <span>Teachers</span>
            </div>
            <i class="fas fa-chevron-down nav-arrow" id="teacherArrow"></i>
        </div>
        <div class="sub-menu" id="teacherSubmenu">
            <a href="{{ route('teacher.create') }}" class="sub-menu-item">
                <i class="fas fa-user-plus"></i>
                <span>Add Teacher</span>
            </a>
            <a href="{{ route('teacher.list') }}" class="sub-menu-item">
                <i class="fas fa-users"></i>
                <span>Teacher List</span>
            </a>
            <a href="{{ route('teacher.attendance') }}" class="sub-menu-item">
                <i class="fas fa-calendar-check"></i>
                <span>Attendance</span>
            </a>
            <!-- <a href="#" class="sub-menu-item" onclick="showComingSoon()">
                <i class="fas fa-money-bill"></i>
                <span>Salary</span>
            </a> -->
            <a href="{{ route('teacher.attendance.report') }}" class="sub-menu-item">
                <i class="fas fa-file-alt"></i>
                <span>Attendance Report</span>
            </a>
        </div>
        
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'accountant')
        <div class="nav-item" onclick="toggleSubMenu('classSubmenu', this)">
            <div class="nav-item-left">
                <i class="fas fa-book"></i>
                <span>Classes</span>
            </div>
            <i class="fas fa-chevron-down nav-arrow" id="classArrow"></i>
        </div>
        <div class="sub-menu" id="classSubmenu">
            <a href="{{ route('academics.index') }}" class="sub-menu-item">
                <i class="fas fa-plus"></i>
                <span>Add Class</span>
            </a>
            <a href="{{ route('subjects.index') }}" class="sub-menu-item">
                <i class="fas fa-book-open"></i>
                <span>Subjects</span>
            </a>
            <a href="{{ route('class.subject') }}" class="sub-menu-item">
                <i class="fas fa-link"></i>
                <span>Class Subject</span>
            </a>
            <a href="{{ route('teacher.subject.allocation') }}" class="sub-menu-item">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Teacher Subject</span>
            </a>
            <a href="{{ route('streams.manage') }}" class="sub-menu-item">
                <i class="fas fa-link"></i>
                <span>Stream Manage</span>
            </a>
            <a href="{{ route('stream.allocation') }}" class="sub-menu-item">
                <i class="fas fa-user-plus"></i>
                <span>Stream Allocation</span>
            </a>
            <a href="{{ route('time.table') }}" class="sub-menu-item">
                <i class="fas fa-clock"></i>
                <span>Time Table</span>
            </a>
        </div>
        
        <!-- PROMOTION -->
        <div class="nav-item" onclick="toggleSubMenu('promotionSubmenu', this)">
            <div class="nav-item-left">
                <i class="fas fa-arrow-up"></i>
                <span>Promotion</span>
            </div>
            <i class="fas fa-chevron-down nav-arrow" id="promotionArrow"></i>
        </div>
        <div class="sub-menu" id="promotionSubmenu">
            <a href="{{ route('promotion.index') }}" class="sub-menu-item">
                <i class="fas fa-user-plus"></i>
                <span>Promote Students</span>
            </a>
            <a href="{{ route('promotion.history') }}" class="sub-menu-item">
                <i class="fas fa-history"></i>
                <span>Promotion History</span>
            </a>
        </div>
        
        <!-- FEES -->
        <div class="nav-item" onclick="toggleSubMenu('feesSubmenu', this)">
            <div class="nav-item-left">
                <i class="fas fa-rupee-sign"></i>
                <span>Fees</span>
            </div>
            <i class="fas fa-chevron-down nav-arrow" id="feesArrow"></i>
        </div>
        <div class="sub-menu" id="feesSubmenu">
            <a href="{{ route('fees.manager') }}" class="sub-menu-item">
                <i class="fas fa-plus-circle"></i>
                <span>Fee Assign</span>
            </a>
            <a href="{{ route('fees.student.list') }}" class="sub-menu-item">
                <i class="fas fa-receipt"></i>
                <span>Collect Fees</span>
            </a>
            <a href="{{ route('fees.master') }}" class="sub-menu-item">
                <i class="fas fa-history"></i>
                <span>Fees Master</span>
            </a>
            <a href="{{ route('fees.index') }}" class="sub-menu-item">
                <i class="fas fa-file-invoice"></i>
                <span>Fees Type</span>
            </a>
        </div>
        
        <!-- ATTENDANCE -->
       <!--  <div class="nav-item" onclick="toggleSubMenu('attendanceSubmenu', this)">
            <div class="nav-item-left">
                <i class="fas fa-calendar-alt"></i>
                <span>Attendance</span>
            </div>
            <i class="fas fa-chevron-down nav-arrow" id="attendanceArrow"></i>
        </div>
        <div class="sub-menu" id="attendanceSubmenu">
            <a href="#" class="sub-menu-item" onclick="showComingSoon()">
                <i class="fas fa-check-circle"></i>
                <span>Mark Attendance</span>
            </a>
            <a href="#" class="sub-menu-item" onclick="showComingSoon()">
                <i class="fas fa-eye"></i>
                <span>View</span>
            </a>
            <a href="#" class="sub-menu-item" onclick="showComingSoon()">
                <i class="fas fa-chart-pie"></i>
                <span>Report</span>
            </a>
        </div> -->
        @endif
        
        <!-- EXAMS -->
        <div class="nav-item" onclick="toggleSubMenu('examSubmenu', this)">
            <div class="nav-item-left">
                <i class="fas fa-chart-line"></i>
                <span>Exams</span>
            </div>
            <i class="fas fa-chevron-down nav-arrow" id="examArrow"></i>
        </div>
        <div class="sub-menu" id="examSubmenu">
            <a href="{{ route('exam.schedule') }}" class="sub-menu-item">
                <i class="fas fa-plus"></i>
                <span>Create Exam</span>
            </a>
            <a href="{{ route('marks.marks.entry') }}" class="sub-menu-item">
                <i class="fas fa-edit"></i>
                <span>Enter Marks</span>
            </a>
            <a href="{{ route('marks.report') }}" class="sub-menu-item">
                <i class="fas fa-chart-bar"></i>
                <span>Results</span>
            </a>
            <!-- <a href="#" class="sub-menu-item" onclick="showComingSoon()">
                <i class="fas fa-trophy"></i>
                <span>Rankings</span>
            </a> -->
        </div>
        
        <!-- REPORTS -->
        <div class="nav-item" onclick="toggleSubMenu('reportsSubmenu', this)">
            <div class="nav-item-left">
                <i class="fas fa-chart-line"></i>
                <span>Reports</span>
            </div>
            <i class="fas fa-chevron-down nav-arrow" id="reportsArrow"></i>
        </div>
        <div class="sub-menu" id="reportsSubmenu">
            <a href="{{ route('reports.daily') }}" class="sub-menu-item">
                <i class="fas fa-calendar-day"></i>
                <span>Daily Collection</span>
            </a>
            <a href="{{ route('reports.transactions') }}" class="sub-menu-item">
                <i class="fas fa-rupee-sign"></i>
                <span>Fees Collection</span>
            </a>
            <a href="{{ route('reports.due') }}" class="sub-menu-item">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Due Report</span>
            </a>
            <a href="{{ route('reports.student') }}" class="sub-menu-item">
                <i class="fas fa-users"></i>
                <span>Student Report</span>
            </a>
            <a href="{{ route('reports.teacher') }}" class="sub-menu-item">
                <i class="fas fa-user-graduate"></i>
                <span>Teacher Report</span>
            </a>
        </div>


        <!-- MANAGEMENT -->
            <div class="nav-item" onclick="toggleSubMenu('managementSubmenu')">
                <div class="nav-item-left">
                    <i class="fas fa-users-cog"></i>
                    <span>Management</span>
                </div>
                <i class="fas fa-chevron-down nav-arrow" id="managementArrow"></i>
            </div>
            <div class="sub-menu" id="managementSubmenu">
                <a href="{{ route('management.index') }}" class="sub-menu-item">
                    <i class="fas fa-user-tie"></i>
                    <span>Other Staff</span>
                </a>
                <a href="{{ route('management.vehicle.index') }}" class="sub-menu-item">
                    <i class="fas fa-truck"></i>
                    <span>Vehicle Registration</span>
                </a>
            </div>

            <div class="nav-item" onclick="toggleSubMenu('querySubmenu')">
                <div class="nav-item-left">
                    <i class="fas fa-question-circle"></i>
                    <span>Queries</span>
                </div>
                <i class="fas fa-chevron-down nav-arrow" id="queryArrow"></i>
            </div>
            <div class="sub-menu" id="querySubmenu">
                <a href="{{ route('control-panel.query') }}" class="sub-menu-item">
                    <i class="fas fa-list"></i>
                    <span>All Queries</span>
                </a>
            </div>
        <div class="nav-divider"></div>
        
        <!-- CONTROL PANEL (Admin Only) -->
        @if(Auth::user()->role == 'admin')


        <div class="nav-item" onclick="toggleSubMenu('financeSubmenu')">
            <div class="nav-item-left">
                <i class="fas fa-money-bill-wave"></i>
                <span>Finance</span>
            </div>
            <i class="fas fa-chevron-down nav-arrow" id="financeArrow"></i>
        </div>
        <div class="sub-menu" id="financeSubmenu">
            <a href="{{ route('finance.salary.index') }}" class="sub-menu-item">
                <i class="fas fa-wallet"></i>
                <span>Teacher Salary</span>
            </a>
        
            <a href="{{ route('finance.staff-salary.index') }}" class="sub-menu-item">
                <i class="fas fa-wallet"></i>
                <span>Staff Salary</span>
            </a>
         <!-- <a href="{{ route('finance.ledger.creation') }}" class="sub-menu-item">
        <i class="fas fa-book"></i>
        <span>Ledger Creation</span>
        </a> -->
    </div>  
    
        <a href="{{ route('control-panel') }}" class="nav-item">
            <div class="nav-item-left">
                <i class="fas fa-laptop-code"></i>
                <span>Control Panel</span>
            </div>
        </a>
        @endif
    </div>
</aside>

<script>
// ===== MOBILE SIDEBAR FUNCTIONS =====
function toggleMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const hamburger = document.getElementById('hamburgerBtn');
    
    sidebar.classList.toggle('mobile-open');
    overlay.classList.toggle('active');
    hamburger.classList.toggle('active');
    
    // Prevent body scroll when sidebar is open
    document.body.style.overflow = sidebar.classList.contains('mobile-open') ? 'hidden' : '';
}

function closeMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const hamburger = document.getElementById('hamburgerBtn');
    
    sidebar.classList.remove('mobile-open');
    overlay.classList.remove('active');
    hamburger.classList.remove('active');
    document.body.style.overflow = '';
}

// ===== SUBMENU FUNCTIONS =====
let currentOpenMenu = null;

function toggleSubMenu(submenuId, element) {
    const currentSubmenu = document.getElementById(submenuId);
    const currentArrow = document.getElementById(
        submenuId.replace('Submenu', 'Arrow')
    );
    
    // Check if clicked menu is already open
    const isCurrentlyOpen = currentSubmenu.classList.contains('show');
    
    // CLOSE ALL SUBMENUS
    document.querySelectorAll('.sub-menu').forEach(menu => {
        menu.classList.remove('show');
    });
    
    document.querySelectorAll('.nav-arrow').forEach(arrow => {
        arrow.classList.remove('rotated');
    });
    
    // If the clicked menu was NOT open, open it
    if (!isCurrentlyOpen) {
        currentSubmenu.classList.add('show');
        if (currentArrow) {
            currentArrow.classList.add('rotated');
        }
        currentOpenMenu = submenuId;
    } else {
        currentOpenMenu = null;
    }
}

function showComingSoon() {
    Swal.fire({
        title: 'Coming Soon!',
        text: 'This feature is under development.',
        icon: 'info',
        confirmButtonColor: '#667eea',
        timer: 1500,
        showConfirmButton: false,
        background: '#fff'
    });
}

// ===== AUTO-OPEN ACTIVE SUBMENU =====
document.addEventListener('DOMContentLoaded', function() {
    // Find active submenu item and open its parent
    const activeSubItem = document.querySelector('.sub-menu-item.active');
    if (activeSubItem) {
        const parentSubmenu = activeSubItem.closest('.sub-menu');
        if (parentSubmenu) {
            const parentId = parentSubmenu.id;
            const parentArrow = document.getElementById(parentId.replace('Submenu', 'Arrow'));
            parentSubmenu.classList.add('show');
            if (parentArrow) {
                parentArrow.classList.add('rotated');
            }
        }
    }
});

// ===== CLOSE SIDEBAR ON ESC KEY =====
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMobileSidebar();
    }
});

// ===== CLOSE SIDEBAR ON RESIZE TO DESKTOP =====
window.addEventListener('resize', function() {
    if (window.innerWidth > 992) {
        closeMobileSidebar();
    }
});

// ===== SWIPE TO CLOSE (Touch) =====
let touchStartX = 0;
let touchEndX = 0;

document.addEventListener('touchstart', function(e) {
    touchStartX = e.changedTouches[0].screenX;
});

document.addEventListener('touchend', function(e) {
    touchEndX = e.changedTouches[0].screenX;
    const sidebar = document.getElementById('sidebar');
    
    // If sidebar is open and user swipes left
    if (sidebar.classList.contains('mobile-open') && (touchStartX - touchEndX) > 50) {
        closeMobileSidebar();
    }
});
</script>