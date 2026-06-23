@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .dashboard {
        max-width: 1400px;
        margin: 0 auto;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Header */
    .dashboard-header {
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .welcome h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.5rem;
        background: linear-gradient(120deg, #1e3c72, #2a5298);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin: 0;
    }
    .welcome p {
        color: #5a6e7c;
        font-size: 0.8rem;
        margin: 0;
    }
    .date-time {
        background: white;
        padding: 0.4rem 1rem;
        border-radius: 2rem;
        font-weight: 500;
        font-size: 0.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }

    /* Stats Cards Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1rem 1.2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid rgba(255,255,255,0.5);
        cursor: default;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }
    .stat-info h3 {
        font-size: 0.6rem;
        text-transform: uppercase;
        color: #6b7280;
        letter-spacing: 0.5px;
        margin: 0 0 4px 0;
    }
    .stat-info .value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1f3a4b;
        margin: 0;
    }
    .stat-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border-radius: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .stat-card.today .stat-icon { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-card.month .stat-icon { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .stat-card.class .stat-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .stat-card.student .stat-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }

    /* Two Column Layout */
    .two-columns {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1.2rem;
        margin-bottom: 1.5rem;
    }

    /* Class-wise Student Card */
    .class-card {
        background: white;
        border-radius: 1rem;
        padding: 1rem 1.2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }
    .section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 1rem;
        font-weight: 700;
        font-size: 0.95rem;
        color: #1e3c72;
        border-left: 4px solid #f59e0b;
        padding-left: 12px;
    }
    .section-title i {
        color: #f59e0b;
    }
    .class-grid {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
        max-height: 350px;
        overflow-y: auto;
    }
    .class-grid::-webkit-scrollbar {
        width: 4px;
    }
    .class-grid::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .class-grid::-webkit-scrollbar-thumb {
        background: #c1c7cd;
        border-radius: 10px;
    }
    .class-item {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        background: #f8fafd;
        padding: 0.5rem 0.8rem;
        border-radius: 0.8rem;
        transition: 0.2s;
    }
    .class-item:hover {
        background: #eef2f8;
    }
    .class-name {
        width: 70px;
        font-weight: 700;
        color: #1e4663;
        font-size: 0.75rem;
        flex-shrink: 0;
    }
    .class-name i {
        color: #f59e0b;
        font-size: 0.6rem;
        margin-right: 4px;
    }
    .progress-bar-container {
        flex: 1;
        height: 8px;
        background: #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #2c7da0, #1e4a76);
        border-radius: 20px;
        width: 0%;
        transition: width 0.5s;
    }
    .student-count {
        font-weight: 700;
        color: #2c7da0;
        min-width: 40px;
        text-align: right;
        font-size: 0.75rem;
        flex-shrink: 0;
    }

    /* Faculty & Quick Links */
    .faculty-card {
        background: white;
        border-radius: 1rem;
        padding: 1rem 1.2rem;
        margin-top: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }
    .faculty-stats {
        display: flex;
        justify-content: space-around;
        margin-top: 0.5rem;
    }
    .faculty-item {
        text-align: center;
        flex: 1;
    }
    .faculty-number {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e3c72;
    }
    .faculty-item div:last-child {
        font-size: 0.6rem;
        color: #6b7280;
    }

    /* Calendar */
    .calendar-card {
        background: white;
        border-radius: 1rem;
        padding: 1rem 1.2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.8rem;
    }
    .calendar-header h3 {
        font-size: 0.85rem;
        margin: 0;
        color: #1e3c72;
    }
    .calendar-header button {
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
        color: #64748b;
        transition: 0.2s;
    }
    .calendar-header button:hover {
        background: #f1f5f9;
    }
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.2rem;
        text-align: center;
    }
    .calendar-weekday {
        font-size: 0.6rem;
        font-weight: 600;
        color: #6b7280;
        padding: 0.3rem 0;
    }
    .calendar-day {
        padding: 0.3rem 0;
        font-size: 0.7rem;
        border-radius: 50%;
        cursor: pointer;
        transition: 0.1s;
        aspect-ratio: 1/1;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .calendar-day:hover {
        background: #eef2fa;
    }
    .calendar-day.today {
        background: #2c7da0;
        color: white;
        font-weight: 700;
    }
    .calendar-day.event {
        color: #f59e0b;
        font-weight: 700;
        position: relative;
    }
    .calendar-day.event::after {
        content: '';
        position: absolute;
        bottom: 2px;
        width: 4px;
        height: 4px;
        background: #f59e0b;
        border-radius: 50%;
    }
    .calendar-day.weekend {
        color: #94a3b8;
    }
    .calendar-legend {
        margin-top: 0.6rem;
        font-size: 0.6rem;
        text-align: center;
        color: #8ba3bc;
        display: flex;
        justify-content: center;
        gap: 12px;
    }
    .calendar-legend span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .calendar-legend i {
        font-size: 0.4rem;
    }
    .legend-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    .legend-dot.orange { background: #f59e0b; }
    .legend-dot.blue { background: #3b82f6; }

    /* Quick Links */
    .quick-links {
        background: white;
        border-radius: 1rem;
        padding: 1rem 1.2rem;
        margin-top: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }
    .links-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.6rem;
        margin-top: 0.8rem;
    }
    .quick-link {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0.6rem 0.3rem;
        border-radius: 0.8rem;
        text-decoration: none;
        transition: 0.2s;
        text-align: center;
        border: none;
        cursor: pointer;
        font-family: inherit;
    }
    .quick-link i {
        font-size: 1.2rem;
        margin-bottom: 0.3rem;
    }
    .quick-link span {
        font-size: 0.6rem;
        font-weight: 600;
    }
    .quick-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .link-student { background: #dbeafe; color: #1e40af; }
    .link-admission { background: #dcfce7; color: #15803d; }
    .link-fees { background: #fed7aa; color: #9a3412; }
    .link-reporting { background: #e0e7ff; color: #4338ca; }
    .link-attendance { background: #fce7f3; color: #9d174d; }
    /*.link-exam { background: #fff3e3; color: #b45309; }*/

    .bottom-section {
        margin-top: 1rem;
    }
    .bottom-info {
        background: white;
        border-radius: 1rem;
        padding: 0.6rem 1rem;
        text-align: center;
        font-size: 0.7rem;
        color: #6c8dae;
        border: 1px solid #e2e8f0;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .two-columns {
            grid-template-columns: 1fr 280px;
        }
    }

    @media (max-width: 820px) {
        .two-columns {
            grid-template-columns: 1fr;
        }
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        body { padding: 0.5rem; }
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .welcome h1 { font-size: 1.2rem; }
        .stats-row {
            grid-template-columns: 1fr 1fr;
            gap: 0.6rem;
        }
        .stat-card {
            padding: 0.8rem;
        }
        .stat-info .value {
            font-size: 1.2rem;
        }
        .stat-icon {
            width: 38px;
            height: 38px;
            font-size: 1rem;
        }
        .stat-info h3 {
            font-size: 0.5rem;
        }
        .links-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .class-name {
            width: 55px;
            font-size: 0.65rem;
        }
        .student-count {
            font-size: 0.65rem;
            min-width: 30px;
        }
        .calendar-day {
            font-size: 0.6rem;
        }
    }

    @media (max-width: 380px) {
        .stats-row {
            grid-template-columns: 1fr 1fr;
            gap: 0.4rem;
        }
        .stat-card {
            padding: 0.6rem;
            border-radius: 0.8rem;
        }
        .stat-info .value {
            font-size: 1rem;
        }
        .stat-icon {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
            border-radius: 0.6rem;
        }
        .links-grid {
            grid-template-columns: 1fr 1fr;
            gap: 0.4rem;
        }
        .quick-link {
            padding: 0.4rem 0.2rem;
        }
        .quick-link i {
            font-size: 1rem;
        }
        .quick-link span {
            font-size: 0.55rem;
        }
    }

    /* Print */
    @media print {
        .stat-card { box-shadow: none; border: 1px solid #ddd; }
        .quick-link { box-shadow: none; border: 1px solid #ddd; }
        .date-time { box-shadow: none; border: 1px solid #ddd; }
    }
</style>

<div class="dashboard">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="welcome">
            <h1><i class="fas fa-chalkboard-user"></i> Welcome back, {{ Auth::user()->name }}</h1>
            <p>Here's what's happening with your school today.</p>
        </div>
        <div class="date-time" id="liveDateTime"></div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stat-card today">
            <div class="stat-info">
                <h3><i class="fas fa-coins"></i> Today's Collection</h3>
                <div class="value" id="todayCollection">₹0</div>
            </div>
            <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
        </div>
        <div class="stat-card month">
            <div class="stat-info">
                <h3><i class="fas fa-chart-line"></i> This Month</h3>
                <div class="value" id="monthCollection">₹0</div>
            </div>
            <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
        </div>
        <div class="stat-card class">
            <div class="stat-info">
                <h3><i class="fas fa-layer-group"></i> Total Classes</h3>
                <div class="value" id="totalClasses">0</div>
            </div>
            <div class="stat-icon"><i class="fas fa-school"></i></div>
        </div>
        <div class="stat-card student">
            <div class="stat-info">
                <h3><i class="fas fa-users"></i> Total Students</h3>
                <div class="value" id="totalStudents">0</div>
            </div>
            <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
        </div>
    </div>

    <!-- Two Columns -->
    <div class="two-columns">
        <!-- LEFT -->
        <div>
            <!-- Class-wise Student Distribution -->
            <div class="class-card">
                <div class="section-title">
                    <i class="fas fa-chart-simple"></i> Class-wise Student Strength
                    <i class="fas fa-arrow-trend-up" style="color:#f59e0b; font-size:0.8rem; margin-left:auto;"></i>
                </div>
                <div class="class-grid" id="classGrid">
                    <!-- dynamic -->
                </div>
            </div>

            <!-- Faculty Info -->
            <div class="faculty-card">
                <div class="section-title">
                    <i class="fas fa-chalkboard-user"></i> Faculty & Staff
                </div>
                <div class="faculty-stats">
                    <div class="faculty-item">
                        <div class="faculty-number" id="totalTeachers">0</div>
                        <div>Teachers</div>
                    </div>
                    <div class="faculty-item">
                        <div class="faculty-number" id="totalStaff">0</div>
                        <div>Non-Teaching</div>
                    </div>
                    <div class="faculty-item">
                        <div class="faculty-number" id="totalFaculty">0</div>
                        <div>Total Faculty</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT -->
        <div>
            <!-- Calendar -->
            <div class="calendar-card">
                <div class="calendar-header">
                    <h3><i class="fas fa-calendar-alt"></i> <span id="calendarMonthYear"></span></h3>
                    <div>
                        <button id="prevMonth"><i class="fas fa-chevron-left"></i></button>
                        <button id="nextMonth"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
                <div class="calendar-grid" id="calendarGrid"></div>
                
            </div>

            <!-- Quick Links -->
            <div class="quick-links">
                <div class="section-title" style="margin-bottom:0.5rem; border-left-color:#8b5cf6;">
                    <i class="fas fa-link"></i> Quick Actions
                </div>
                <div class="links-grid">
                    <a href="{{ route('student.list') }}" class="quick-link link-student"><i class="fas fa-user-graduate"></i><span>Students</span></a>
                    <a href="{{ route('student.register') }}" class="quick-link link-admission"><i class="fas fa-user-plus"></i><span>Admission</span></a>
                    <a href="{{ route('fees.student.list') }}" class="quick-link link-fees"><i class="fas fa-rupee-sign"></i><span>Fees</span></a>
                    <a href="{{ route('reports.daily') }}" class="quick-link link-reporting"><i class="fas fa-chart-pie"></i><span>Reporting</span></a>
                    <a href="#" class="quick-link link-attendance" onclick="showComingSoon()"><i class="fas fa-calendar-check"></i><span>Attendance</span></a>
                    <a href="#" class="quick-link link-exam" onclick="showComingSoon()"><i class="fas fa-file-alt"></i><span>Exam</span></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom -->
    <div class="bottom-section">
        <div class="bottom-info">
            <i class="fas fa-chart-line"></i> Last updated: <span id="lastUpdated"></span> | 📊 Dashboard reflects real-time data
        </div>
    </div>
</div>

<script>
// ============================================================
// DATA FROM BACKEND (Passed via Blade)
// ============================================================
const dashboardData = @json($dashboardData);

// ============================================================
// UPDATE STATS
// ============================================================
document.getElementById('todayCollection').innerHTML = '₹' + Number(dashboardData.today_collection).toLocaleString('en-IN');
document.getElementById('monthCollection').innerHTML = '₹' + Number(dashboardData.month_collection).toLocaleString('en-IN');
document.getElementById('totalClasses').innerText = dashboardData.total_classes || 0;
document.getElementById('totalStudents').innerText = dashboardData.total_students || 0;

// Faculty
const teachers = dashboardData.total_teachers || 0;
const nonTeaching = dashboardData.total_staff || 0;
document.getElementById('totalTeachers').innerText = teachers;
document.getElementById('totalStaff').innerText = nonTeaching;
document.getElementById('totalFaculty').innerText = teachers + nonTeaching;

// ============================================================
// CLASS-WISE STUDENT GRID
// ============================================================
const classData = @json($classData);
const maxStudents = classData.length > 0 ? Math.max(...classData.map(c => c.students)) : 1;

function renderClassGrid() {
    const container = document.getElementById('classGrid');
    container.innerHTML = '';
    
    if (classData.length === 0) {
        container.innerHTML = `
            <div style="text-align:center; padding:20px; color:#94a3b8; font-size:0.8rem;">
                <i class="fas fa-info-circle"></i> No classes found
            </div>
        `;
        return;
    }
    
    classData.forEach(cls => {
        const percentage = maxStudents > 0 ? (cls.students / maxStudents) * 100 : 0;
        const row = document.createElement('div');
        row.className = 'class-item';
        row.innerHTML = `
            <div class="class-name"><i class="fas fa-arrow-right"></i> ${cls.class_name}</div>
            <div class="progress-bar-container"><div class="progress-fill" style="width: ${percentage}%;"></div></div>
            <div class="student-count">${cls.students} <i class="fas fa-user-graduate" style="font-size:0.6rem;"></i></div>
        `;
        container.appendChild(row);
    });
}
renderClassGrid();

// ============================================================
// CALENDAR
// ============================================================
let currentDate = new Date();

function renderCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth();
    
    const firstDay = new Date(year, month, 1);
    const startWeekday = firstDay.getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    document.getElementById('calendarMonthYear').innerText = `${monthNames[month]} ${year}`;
    
    const grid = document.getElementById('calendarGrid');
    grid.innerHTML = '';
    
    const weekdays = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
    weekdays.forEach(day => {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'calendar-weekday';
        dayDiv.innerText = day;
        grid.appendChild(dayDiv);
    });
    
    for (let i = 0; i < startWeekday; i++) {
        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'calendar-day';
        emptyDiv.innerText = '';
        grid.appendChild(emptyDiv);
    }
    
    const today = new Date();
    const isCurrentMonth = (today.getMonth() === month && today.getFullYear() === year);
    const events = dashboardData.calendar_events || {};
    
    for (let d = 1; d <= daysInMonth; d++) {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'calendar-day';
        dayDiv.innerText = d;
        
        const dayOfWeek = new Date(year, month, d).getDay();
        if (dayOfWeek === 0 || dayOfWeek === 6) {
            dayDiv.classList.add('weekend');
        }
        
        if (isCurrentMonth && today.getDate() === d) {
            dayDiv.classList.add('today');
        }
        
        // Check events
        const dateKey = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        if (events[dateKey]) {
            dayDiv.classList.add('event');
            dayDiv.title = events[dateKey];
        }
        
        grid.appendChild(dayDiv);
    }
}

document.getElementById('prevMonth').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
});

document.getElementById('nextMonth').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
});

renderCalendar(currentDate);

// ============================================================
// LIVE DATE & TIME
// ============================================================
function updateDateTime() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    document.getElementById('liveDateTime').innerHTML = now.toLocaleDateString('en-IN', options);
    document.getElementById('lastUpdated').innerHTML = now.toLocaleString('en-IN', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
}
setInterval(updateDateTime, 1000);
updateDateTime();

// ============================================================
// COMING SOON
// ============================================================
function showComingSoon() {
    Swal.fire({
        title: 'Coming Soon!',
        text: 'This feature is under development.',
        icon: 'info',
        confirmButtonColor: '#1e3c72',
        timer: 1500,
        showConfirmButton: false
    });
}
</script>
@endsection