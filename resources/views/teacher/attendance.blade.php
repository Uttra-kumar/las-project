@extends('layouts.app')

@section('title', 'Teacher Attendance')

@section('content')
<style>
    .attendance-container { animation: fadeIn 0.3s ease; }
    
    .attendance-header {
        background: linear-gradient(135deg, #1e3c72 0%, #0f2b4d 100%);
        color: white;
        padding: 10px 18px;
        border-radius: 10px;
        margin-bottom: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .attendance-header h2 { font-size: 1rem; margin: 0; display: flex; align-items: center; gap: 6px; }
    .session-badge {
        background: rgba(255,255,255,0.2);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
    }
    
    /* Stats Cards */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
        margin-bottom: 18px;
    }
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 10px 12px;
        text-align: center;
        border: 1px solid #e2e8f0;
        transition: 0.2s;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .stat-number { font-size: 1.5rem; font-weight: 800; }
    .stat-label { font-size: 0.6rem; color: #6b7280; text-transform: uppercase; }
    .stat-present .stat-number { color: #10b981; }
    .stat-absent .stat-number { color: #ef4444; }
    .stat-leave .stat-number { color: #f59e0b; }
    .stat-total .stat-number { color: #3b82f6; }
    
    /* Main Layout */
    .attendance-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 18px;
    }
    
    /* Calendar */
    .calendar-card {
        background: white;
        border-radius: 10px;
        padding: 12px;
        border: 1px solid #e2e8f0;
    }
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .calendar-header h3 { font-size: 0.85rem; margin: 0; color: #1e3c72; }
    .calendar-header button {
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
        color: #64748b;
        transition: 0.2s;
    }
    .calendar-header button:hover { background: #f1f5f9; }
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 2px;
        text-align: center;
    }
    .calendar-day {
        padding: 6px 0;
        font-size: 0.7rem;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.1s;
        position: relative;
        aspect-ratio: 1/1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .calendar-day:hover { background: #eef2fa; }
    .calendar-day.today {
        background: #1e3c72;
        color: white;
        font-weight: 700;
    }
    .calendar-day.selected {
        border: 2px solid #1e3c72;
        font-weight: 700;
    }
    .calendar-day.has-attendance {
        background: #5add1e;
    }
    .calendar-day .dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        margin-top: 2px;
    }
    .calendar-day .dot.present { background: #10b981; }
    .calendar-day .dot.absent { background: #ef4444; }
    .calendar-day .dot.leave { background: #f59e0b; }
    .calendar-weekday {
        font-size: 0.6rem;
        font-weight: 600;
        color: #6b7280;
        padding: 4px 0;
    }
    .calendar-legend {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 8px;
        font-size: 0.55rem;
        color: #6b7280;
    }
    .calendar-legend span {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .calendar-legend .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    
    /* Teacher List */
    .teacher-list-card {
        background: white;
        border-radius: 10px;
        padding: 12px;
        border: 1px solid #e2e8f0;
    }
    .teacher-list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 8px;
    }
    .teacher-list-header .date-display {
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e3c72;
    }
    .btn-save {
        background: #1e3c72;
        color: white;
        border: none;
        padding: 6px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-save:hover { background: #2b4c7c; }
    
    /* Teacher Table */
    .table-wrapper {
        overflow-x: auto;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.7rem;
    }
    .data-table th {
        text-align: left;
        padding: 8px 10px;
        background: #f8fafc;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
    }
    .data-table td {
        padding: 6px 10px;
        border-bottom: 1px solid #e2e8f0;
    }
    .data-table tr:hover { background: #f8fafc; }
    
    /* Status Buttons */
    .status-btns {
        display: flex;
        gap: 4px;
    }
    .status-btn {
        padding: 2px 10px;
        border: 2px solid #e2e8f0;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.6rem;
        font-weight: 600;
        transition: 0.2s;
        background: white;
    }
    .status-btn:hover { transform: scale(0.95); }
    .status-btn.present.active {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }
    .status-btn.absent.active {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }
    .status-btn.leave.active {
        background: #f59e0b;
        color: white;
        border-color: #f59e0b;
    }
    .status-btn.present { border-color: #10b981; color: #10b981; }
    .status-btn.absent { border-color: #ef4444; color: #ef4444; }
    .status-btn.leave { border-color: #f59e0b; color: #f59e0b; }
    
    /* Status Badge */
    .status-badge {
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.6rem;
        font-weight: 600;
        display: inline-block;
    }
    .status-badge.present { background: #dcfce7; color: #15803d; }
    .status-badge.absent { background: #fee2e2; color: #dc2626; }
    .status-badge.leave { background: #fef3c7; color: #b45309; }
    
    /* Responsive */
    @media (max-width: 820px) {
        .attendance-layout {
            grid-template-columns: 1fr;
        }
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 480px) {
        .stats-row {
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }
        .stat-card { padding: 6px 8px; }
        .stat-number { font-size: 1.2rem; }
        .status-btns { flex-wrap: wrap; }
        .status-btn { padding: 2px 6px; font-size: 0.55rem; }
        .data-table { font-size: 0.6rem; }
        .data-table th, .data-table td { padding: 4px 6px; }
    }
</style>

<div class="attendance-container">
    <!-- Header -->
    <div class="attendance-header">
        <h2><i class="fas fa-clipboard-check"></i> Teacher Attendance</h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i> {{ $currentSession->session_name ?? 'N/A' }}
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card stat-present">
            <div class="stat-number" id="presentCount">{{ $presentCount }}</div>
            <div class="stat-label"><i class="fas fa-check-circle"></i> Present</div>
        </div>
        <div class="stat-card stat-absent">
            <div class="stat-number" id="absentCount">{{ $absentCount }}</div>
            <div class="stat-label"><i class="fas fa-times-circle"></i> Absent</div>
        </div>
        <div class="stat-card stat-leave">
            <div class="stat-number" id="leaveCount">{{ $leaveCount }}</div>
            <div class="stat-label"><i class="fas fa-clock"></i> Leave</div>
        </div>
        <div class="stat-card stat-total">
            <div class="stat-number">{{ count($teachers) }}</div>
            <div class="stat-label"><i class="fas fa-users"></i> Total Teachers</div>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="attendance-layout">
        <!-- Calendar -->
        <div class="calendar-card">
            <div class="calendar-header">
                <h3><i class="fas fa-calendar-alt"></i> <span id="calendarMonthYear"></span></h3>
                <div>
                    <button id="prevMonth"><i class="fas fa-chevron-left"></i></button>
                    <button id="nextMonth"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="calendar-grid" id="calendarGrid">
                <!-- Weekdays -->
                <div class="calendar-weekday">S</div>
                <div class="calendar-weekday">M</div>
                <div class="calendar-weekday">T</div>
                <div class="calendar-weekday">W</div>
                <div class="calendar-weekday">T</div>
                <div class="calendar-weekday">F</div>
                <div class="calendar-weekday">S</div>
            </div>
            <div class="calendar-legend">
                <span><span class="dot present"></span> Present</span>
                <span><span class="dot absent"></span> Absent</span>
                <span><span class="dot leave"></span> Leave</span>
                <span><span class="dot" style="background:#1e3c72;"></span> Today</span>
            </div>
        </div>

        <!-- Teacher List -->
        <div class="teacher-list-card">
            <div class="teacher-list-header">
                <div class="date-display">
                    <i class="fas fa-calendar-day"></i> 
                    <span id="selectedDateDisplay">{{ date('d F Y', strtotime($selectedDate)) }}</span>
                </div>
                <div>
                    <button class="btn-save" onclick="saveAttendance()">
                        <i class="fas fa-save"></i> Save Attendance
                    </button>
                </div>
            </div>

            <div class="table-wrapper">
                <form id="attendanceForm">
                    <input type="hidden" name="date" value="{{ $selectedDate }}" id="selectedDateInput">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="30">#</th>
                                <th>Teacher ID</th>
                                <th>Teacher Name</th>
                                <th width="200">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendanceData as $index => $data)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="teacher-id-badge">{{ $data['teacher_id_number'] }}</span></td>
                                <td><strong>{{ $data['teacher_name'] }}</strong></td>
                                <td>
                                    <div class="status-btns">
                                        <button type="button" 
                                                class="status-btn present {{ $data['status'] == 'present' ? 'active' : '' }}"
                                                onclick="setStatus(this, 'present', {{ $data['teacher_id'] }})">
                                            <i class="fas fa-check"></i> Present
                                        </button>
                                        <button type="button" 
                                                class="status-btn absent {{ $data['status'] == 'absent' ? 'active' : '' }}"
                                                onclick="setStatus(this, 'absent', {{ $data['teacher_id'] }})">
                                            <i class="fas fa-times"></i> Absent
                                        </button>
                                        <button type="button" 
                                                class="status-btn leave {{ $data['status'] == 'leave' ? 'active' : '' }}"
                                                onclick="setStatus(this, 'leave', {{ $data['teacher_id'] }})">
                                            <i class="fas fa-clock"></i> Leave
                                        </button>
                                    </div>
                                    <input type="hidden" name="attendance[{{ $data['teacher_id'] }}]" 
                                           value="{{ $data['status'] }}" 
                                           id="status_{{ $data['teacher_id'] }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// ============================================================
// STATUS BUTTON HANDLING
// ============================================================
function setStatus(btn, status, teacherId) {
    const parent = btn.closest('.status-btns');
    const btns = parent.querySelectorAll('.status-btn');
    const hiddenInput = document.getElementById(`status_${teacherId}`);
    
    // Remove all active classes
    btns.forEach(b => b.classList.remove('active'));
    
    // Add active to clicked
    btn.classList.add('active');
    
    // Update hidden input
    hiddenInput.value = status;
    
    // Update stats
    updateStats();
}

function updateStats() {
    const forms = document.querySelectorAll('#attendanceForm');
    let present = 0, absent = 0, leave = 0;
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input[name^="attendance"]');
        inputs.forEach(input => {
            if (input.value === 'present') present++;
            else if (input.value === 'absent') absent++;
            else if (input.value === 'leave') leave++;
        });
    });
    
    document.getElementById('presentCount').textContent = present;
    document.getElementById('absentCount').textContent = absent;
    document.getElementById('leaveCount').textContent = leave;
}

// ============================================================
// SAVE ATTENDANCE
// ============================================================
function saveAttendance() {
    const form = document.getElementById('attendanceForm');
    const formData = new FormData(form);
    
    Swal.fire({
        title: 'Saving Attendance...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch('{{ route("teacher.attendance.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            });
            // Update stats
            document.getElementById('presentCount').textContent = data.present;
            document.getElementById('absentCount').textContent = data.absent;
            document.getElementById('leaveCount').textContent = data.leave;
            // Reload after 1.5s
            setTimeout(() => location.reload(), 1500);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Something went wrong! Please try again.'
        });
    });
}

// ============================================================
// CALENDAR - Select Date and Reload
// ============================================================
let currentMonth = {{ \Carbon\Carbon::parse($monthYear)->format('n') }};
let currentYear = {{ \Carbon\Carbon::parse($monthYear)->format('Y') }};
const calendarData = @json($calendarData);

function renderCalendar() {
    const grid = document.getElementById('calendarGrid');
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    document.getElementById('calendarMonthYear').textContent = `${monthNames[currentMonth - 1]} ${currentYear}`;
    
    // Clear existing days (keep weekdays)
    while (grid.children.length > 7) {
        grid.removeChild(grid.lastChild);
    }
    
    // Get first day of month
    const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
    const today = '{{ $selectedDate }}';
    
    // Empty cells
    for (let i = 0; i < firstDay; i++) {
        const div = document.createElement('div');
        div.className = 'calendar-day';
        div.style.visibility = 'hidden';
        grid.appendChild(div);
    }
    
    // Days
    for (let d = 1; d <= daysInMonth; d++) {
        const dateStr = `${currentYear}-${String(currentMonth).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const dayData = calendarData.find(item => item.date === dateStr);
        
        const div = document.createElement('div');
        div.className = 'calendar-day';
        div.textContent = d;
        
        if (dateStr === today) {
            div.classList.add('selected');
        }
        
        if (dayData && dayData.is_today) {
            div.classList.add('today');
        }
        
        if (dayData && dayData.has_attendance) {
            div.classList.add('has-attendance');
            const dotDiv = document.createElement('div');
            dotDiv.className = 'dot';
            if (dayData.present > 0) dotDiv.classList.add('present');
            if (dayData.absent > 0) dotDiv.classList.add('absent');
            if (dayData.leave > 0) dotDiv.classList.add('leave');
            div.appendChild(dotDiv);
        }
        
        div.addEventListener('click', function() {
            window.location.href = `{{ route('teacher.attendance') }}?date=${dateStr}`;
        });
        
        grid.appendChild(div);
    }
}

document.getElementById('prevMonth').addEventListener('click', function() {
    currentMonth--;
    if (currentMonth < 1) { currentMonth = 12; currentYear--; }
    window.location.href = `{{ route('teacher.attendance') }}?month_year=${currentYear}-${String(currentMonth).padStart(2,'0')}`;
});

document.getElementById('nextMonth').addEventListener('click', function() {
    currentMonth++;
    if (currentMonth > 12) { currentMonth = 1; currentYear++; }
    window.location.href = `{{ route('teacher.attendance') }}?month_year=${currentYear}-${String(currentMonth).padStart(2,'0')}`;
});

renderCalendar();
</script>
@endsection