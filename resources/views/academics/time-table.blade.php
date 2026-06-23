@extends('layouts.app')

@section('title', 'Time Table Scheduler')

@section('content')
<style>
    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }
    
    .tt-container {
        animation: fadeInUp 0.35s ease;
        max-width: 100%;
    }
    
    /* ===== HEADER ===== */
    .tt-header {
        background: linear-gradient(135deg, #1a365d 0%, #2d4a7a 50%, #1a365d 100%);
        color: white;
        padding: 12px 18px;
        border-radius: 10px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(26, 54, 93, 0.25);
    }
    .tt-header h2 {
        font-size: 0.95rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }
    .tt-header h2 i {
        color: #fbbf24;
    }
    .session-badge {
        background: rgba(255,255,255,0.12);
        padding: 3px 14px;
        border-radius: 16px;
        font-size: 0.6rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .session-badge i {
        color: #10b981;
    }
    
    /* ===== FILTER SECTION ===== */
    .filter-section {
        background: white;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .filter-row {
        display: flex;
        gap: 10px;
        align-items: flex-end;
        flex-wrap: wrap;
    }
    .filter-group {
        flex: 1;
        min-width: 120px;
    }
    .filter-group label {
        display: block;
        font-size: 0.6rem;
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 3px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .filter-group label i {
        color: #3b82f6;
        margin-right: 3px;
    }
    .filter-group select {
        width: 100%;
        padding: 5px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
        background: #fafbfc;
        transition: all 0.25s ease;
        height: 34px;
        font-family: inherit;
        font-weight: 500;
    }
    .filter-group select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        background: white;
    }
    
    .filter-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .btn-filter, .btn-save, .btn-print, .btn-copy {
        padding: 5px 14px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.65rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-weight: 600;
        color: white;
        height: 34px;
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    .btn-filter {
        background: linear-gradient(135deg, #1e3c72, #2d4a7a);
        box-shadow: 0 2px 6px rgba(30, 60, 114, 0.2);
    }
    .btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(30, 60, 114, 0.3);
    }
    .btn-save {
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);
    }
    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(16, 185, 129, 0.3);
    }
    .btn-print {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        box-shadow: 0 2px 6px rgba(245, 158, 11, 0.2);
    }
    .btn-print:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(245, 158, 11, 0.3);
    }
    .btn-copy {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        box-shadow: 0 2px 6px rgba(139, 92, 246, 0.2);
    }
    .btn-copy:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(139, 92, 246, 0.3);
    }
    .btn-filter i, .btn-save i, .btn-print i, .btn-copy i {
        font-size: 0.7rem;
    }
    
    /* ===== STREAM TABS ===== */
    .stream-tabs {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 12px;
        padding: 4px 0;
    }
    .stream-tab {
        padding: 4px 14px;
        border-radius: 6px;
        border: 1.5px solid #e2e8f0;
        background: white;
        cursor: pointer;
        font-size: 0.7rem;
        transition: all 0.25s ease;
        text-decoration: none;
        color: #1e293b;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        height: 30px;
    }
    .stream-tab:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
        transform: translateY(-1px);
    }
    .stream-tab.active {
        background: linear-gradient(135deg, #1e3c72, #2d4a7a);
        color: white;
        border-color: #1e3c72;
        box-shadow: 0 2px 8px rgba(30, 60, 114, 0.25);
    }
    .stream-tab i {
        font-size: 0.65rem;
    }
    
    /* ===== TABLE WRAPPER ===== */
    .timetable-wrapper {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding: 0 2px;
    }
    .timetable-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 500px;
        font-size: 0.75rem;
    }
    .timetable-table th {
        padding: 8px 12px;
        background: #f8fafc;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        color: #1e3c72;
        text-transform: uppercase;
        font-size: 0.6rem;
        letter-spacing: 0.3px;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .timetable-table td {
        padding: 6px 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .timetable-table tbody tr {
        transition: background 0.2s ease;
    }
    .timetable-table tbody tr:hover {
        background: #f8fafc;
    }
    
    /* ===== TIME CELL ===== */
    .time-cell {
        width: 100px;
        min-width: 80px;
    }
    .time-cell input {
        width: 140%;
        padding: 4px 8px;
        border: 1.5px solid #e2e8f0;
        border-radius: 4px;
        font-size: 0.7rem;
        font-family: inherit;
        font-weight: 600;
        background: #f8fafc;
        transition: all 0.25s ease;
        height: 30px;
        color: #1e293b;
    }
    .time-cell input:focus {
        border-color: #3b82f6;
        background: white;
        outline: none;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }
    .time-cell input[readonly] {
        background: #fef3c7;
        border-color: #f59e0b;
        color: #92400e;
        cursor: not-allowed;
    }
    
    /* ===== BREAK ROW ===== */
    .break-row {
        background: #fef3c7;
    }
    .break-row:hover {
        background: #fde68a !important;
    }
    .break-row td {
        color: #92400e;
        font-weight: 600;
    }
    .break-row .break-icon {
        color: #d97706;
        margin-left: 30px;
    }
    
    /* ===== SUBJECT SELECT ===== */
    .subject-select {
        width: 100%;
        max-width: 300px;
        padding: 4px 8px;
        border: 1.5px solid #e2e8f0;
        border-radius: 4px;
        font-size: 0.7rem;
        background: white;
        font-weight: 500;
        height: 30px;
        margin-left: 50px;
        transition: all 0.25s ease;
        font-family: inherit;
    }
    .subject-select:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }
    
    /* ===== ACTION BUTTONS ===== */
    .delete-row-btn {
        background: #fee2e2;
        color: #dc2626;
        border: none;
        padding: 2px 8px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.6rem;
        transition: all 0.2s ease;
        height: 26px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    .delete-row-btn:hover {
        background: #fecaca;
        transform: scale(1.05);
    }
    .delete-row-btn i {
        font-size: 0.55rem;
    }
    
    .add-time-btn {
        background: linear-gradient(135deg, #1e3c72, #2d4a7a);
        color: white;
        border: none;
        padding: 6px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        height: 34px;
        box-shadow: 0 2px 6px rgba(30, 60, 114, 0.2);
    }
    .add-time-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(30, 60, 114, 0.3);
    }
    .add-time-btn i {
        font-size: 0.7rem;
    }
    
    .table-footer {
        padding: 8px 12px;
        text-align: center;
        border-top: 1px solid #e2e8f0;
        background: #fafbfc;
    }
    
    /* ===== NO DATA ===== */
    .no-data {
        text-align: center;
        padding: 50px 20px;
        color: #94a3b8;
    }
    .no-data i {
        font-size: 2.5rem;
        margin-bottom: 12px;
        display: block;
        color: #cbd5e1;
    }
    .no-data p {
        font-size: 0.85rem;
        margin-bottom: 4px;
        color: #475569;
    }
    .no-data small {
        font-size: 0.7rem;
        color: #94a3b8;
    }
    
    /* ===== COPY SECTION ===== */
    .copy-section {
        display: flex;
        gap: 6px;
        align-items: center;
        flex-wrap: wrap;
    }
    .copy-section select {
        padding: 5px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.7rem;
        height: 34px;
        background: #fafbfc;
        font-family: inherit;
        font-weight: 500;
    }
    .copy-section select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }
    
    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .filter-row {
            gap: 8px;
        }
        .filter-group {
            min-width: 100px;
        }
        .timetable-table {
            font-size: 0.7rem;
            min-width: 450px;
        }
        .time-cell {
            width: 80px;
            min-width: 60px;
        }
        .subject-select {
            max-width: 200px;
        }
    }
    
    @media (max-width: 768px) {
        .tt-header {
            padding: 10px 14px;
            flex-direction: column;
            text-align: center;
        }
        .tt-header h2 {
            font-size: 0.85rem;
        }
        .session-badge {
            font-size: 0.55rem;
        }
        .filter-section {
            padding: 10px 12px;
        }
        .filter-row {
            flex-direction: column;
            gap: 6px;
        }
        .filter-group {
            min-width: 100%;
            width: 100%;
        }
        .filter-actions {
            width: 100%;
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }
        .filter-actions .btn-filter,
        .filter-actions .btn-save,
        .filter-actions .btn-print {
            flex: 1;
            justify-content: center;
            min-width: 0;
        }
        .copy-section {
            width: 100%;
            flex-wrap: wrap;
        }
        .copy-section select {
            flex: 1;
            min-width: 100px;
        }
        .copy-section .btn-copy {
            flex: 1;
            justify-content: center;
        }
        .stream-tabs {
            justify-content: center;
        }
        .stream-tab {
            font-size: 0.65rem;
            padding: 3px 10px;
            height: 28px;
        }
        .timetable-table {
            font-size: 0.65rem;
            min-width: 400px;
        }
        .timetable-table th,
        .timetable-table td {
            padding: 4px 8px;
        }
        .time-cell {
            width: 70px;
            min-width: 50px;
        }
        .time-cell input {
            font-size: 0.65rem;
            padding: 3px 6px;
            height: 28px;
        }
        .subject-select {
            font-size: 0.65rem;
            padding: 3px 6px;
            height: 28px;
            max-width: 160px;
        }
        .add-time-btn {
            font-size: 0.65rem;
            padding: 5px 16px;
            height: 30px;
        }
        .delete-row-btn {
            font-size: 0.55rem;
            padding: 2px 6px;
            height: 24px;
        }
        .no-data {
            padding: 30px 15px;
        }
        .no-data i {
            font-size: 2rem;
        }
        .no-data p {
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 480px) {
        .tt-header {
            padding: 8px 12px;
        }
        .tt-header h2 {
            font-size: 0.8rem;
        }
        .filter-section {
            padding: 8px 10px;
        }
        .filter-group label {
            font-size: 0.55rem;
        }
        .filter-group select {
            font-size: 0.7rem;
            padding: 4px 8px;
            height: 30px;
        }
        .btn-filter, .btn-save, .btn-print, .btn-copy {
            font-size: 0.6rem;
            padding: 4px 10px;
            height: 30px;
        }
        .stream-tab {
            font-size: 0.6rem;
            padding: 2px 8px;
            height: 24px;
        }
        .timetable-table {
            font-size: 0.6rem;
            min-width: 340px;
        }
        .timetable-table th,
        .timetable-table td {
            padding: 3px 6px;
        }
        .time-cell {
            width: 60px;
            min-width: 40px;
        }
        .time-cell input {
            font-size: 0.6rem;
            padding: 2px 4px;
            height: 24px;
        }
        .subject-select {
            font-size: 0.6rem;
            padding: 2px 4px;
            height: 24px;
            max-width: 120px;
        }
        .add-time-btn {
            font-size: 0.6rem;
            padding: 4px 12px;
            height: 28px;
        }
        .delete-row-btn {
            font-size: 0.5rem;
            padding: 1px 5px;
            height: 20px;
        }
        .delete-row-btn i {
            font-size: 0.45rem;
        }
        .filter-actions {
            flex-direction: column;
        }
        .filter-actions .btn-filter,
        .filter-actions .btn-save,
        .filter-actions .btn-print {
            width: 100%;
        }
        .copy-section {
            flex-direction: column;
        }
        .copy-section select {
            width: 100%;
        }
        .copy-section .btn-copy {
            width: 100%;
        }
    }
    
    /* ===== PRINT ===== */
    @media print {
        .sidebar, .top-header, .filter-section, .btn-save, .btn-print, .btn-filter, .btn-copy, .no-print,
        .menu-toggle, .user-dropdown, .session-badge, .delete-row-btn, .add-time-btn, .action-buttons,
        .filter-section, .stream-tabs, .copy-section, .table-footer {
            display: none !important;
        }
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .page-content {
            padding: 0 !important;
            margin: 0 !important;
        }
        .tt-container {
            padding: 0 !important;
            margin: 0 !important;
            animation: none !important;
        }
        .tt-header {
            padding: 6px 10px !important;
            margin-bottom: 6px !important;
            background: #1e3c72 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .tt-header h2 {
            font-size: 12px !important;
        }
        .timetable-wrapper {
            border: 1px solid #000 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        .timetable-table th {
            background: #f0f0f0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            border: 1px solid #000 !important;
            font-size: 8px !important;
            padding: 4px 6px !important;
        }
        .timetable-table td {
            border: 1px solid #000 !important;
            padding: 3px 6px !important;
            font-size: 7px !important;
        }
        .timetable-table {
            font-size: 7px !important;
            min-width: auto !important;
            width: 100% !important;
        }
        .break-row {
            background: #fef3c7 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .time-cell input {
            border: none !important;
            background: transparent !important;
            font-weight: 600 !important;
            padding: 0 !important;
            height: auto !important;
            font-size: 7px !important;
        }
        .subject-select {
            border: none !important;
            background: transparent !important;
            font-weight: 500 !important;
            padding: 0 !important;
            height: auto !important;
            font-size: 7px !important;
            -webkit-appearance: none !important;
            appearance: none !important;
            max-width: 100% !important;
        }
        .subject-select option {
            display: none !important;
        }
        .subject-select:after {
            content: attr(data-selected-text);
        }
        .timetable-table tr:hover {
            background: transparent !important;
        }
        .no-data {
            padding: 20px !important;
        }
        .no-data i {
            font-size: 1.5rem !important;
        }
        .no-data p {
            font-size: 10px !important;
        }
    }
</style>

<div class="tt-container">
    <!-- ===== HEADER ===== -->
    <div class="tt-header">
        <h2>
            <i class="fas fa-clock"></i>
            Time Table Scheduler
        </h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i>
            {{ $currentSession->session_name ?? 'No Active Session' }}
        </div>
    </div>
    
    <!-- ===== FILTER SECTION ===== -->
    <div class="filter-section">
        <div class="filter-row">
            <div class="filter-group">
                <label><i class="fas fa-chalkboard-user"></i> Class</label>
                <select id="class_id">
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                        {{ $class->class_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            @if($isStreamClass && $selectedClass)
            <div class="filter-group">
                <label><i class="fas fa-code-branch"></i> Stream</label>
                <select id="stream_id">
                    <option value="">-- Select Stream --</option>
                    @foreach($streams as $stream)
                    <option value="{{ $stream->id }}" {{ $selectedStream == $stream->id ? 'selected' : '' }}>
                        {{ $stream->stream_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif
            
            <div class="filter-group filter-actions">
                <button class="btn-filter" onclick="applyFilter()">
                    <i class="fas fa-search"></i> Apply
                </button>
                <button class="btn-save" onclick="saveTimeTable()">
                    <i class="fas fa-save"></i> Save
                </button>
                <button class="btn-print" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
            
            <div class="filter-group copy-section">
                <select id="copy_session">
                    <option value="">Copy from</option>
                    @foreach($previousSessions as $session)
                    <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
                    @endforeach
                </select>
                <button class="btn-copy" onclick="copyFromPrevious()">
                    <i class="fas fa-copy"></i> Copy
                </button>
            </div>
        </div>
    </div>
    
    <!-- ===== STREAM TABS ===== -->
    @if($isStreamClass && $selectedClass && $streams->count() > 0)
    <div class="stream-tabs">
        @foreach($streams as $stream)
        <a href="{{ route('time.table') }}?class_id={{ $selectedClass }}&stream_id={{ $stream->id }}" 
           class="stream-tab {{ $selectedStream == $stream->id ? 'active' : '' }}">
            <i class="fas fa-code-branch"></i> {{ $stream->stream_name }}
        </a>
        @endforeach
    </div>
    @endif
    
    <!-- ===== TIME TABLE ===== -->
    @if($selectedClass && (!$isStreamClass || ($isStreamClass && $selectedStream)))
    <div class="timetable-wrapper">
        <div class="table-scroll">
            <table class="timetable-table" id="timetableTable">
                <thead>
                    <tr>
                        <th class="time-cell">Time</th>
                        <th>
                            @if($isStreamClass && $selectedStream)
                                <i class="fas fa-book-open"></i>
                                {{ $streams->where('id', $selectedStream)->first()->stream_name ?? '' }}
                            @else
                                <i class="fas fa-chalkboard-teacher"></i>
                                Subject - Teacher
                            @endif
                        </th>
                        <th width="40"></th>
                    </tr>
                </thead>
                <tbody id="timetableBody">
                    @foreach($periods as $index => $period)
                    <tr class="{{ isset($period['is_break']) && $period['is_break'] ? 'break-row' : '' }}">
                        <td class="time-cell">
                            <input type="text" class="time-input" data-period="{{ $index }}" data-field="time" 
                                   value="{{ $period['time'] }}" 
                                   {{ (isset($period['is_break']) && $period['is_break']) ? 'readonly' : '' }}>
                        </td>
                        <td>
                            @if(isset($period['is_break']) && $period['is_break'])
                                <strong class="break-icon">
                                    <i class="fas fa-coffee"></i> {{ $period['break_name'] ?? 'Break' }}
                                </strong>
                            @else
                                <select class="subject-select" data-period="{{ $index }}" data-field="allocation_id">
                                    <option value="">-- Select --</option>
                                    @foreach($allocations as $allocation)
                                    <option value="{{ $allocation->id }}" 
                                        data-subject-id="{{ $allocation->subject_id }}"
                                        data-teacher-id="{{ $allocation->teacher_id }}"
                                        {{ ($period['allocation_id'] ?? '') == $allocation->id ? 'selected' : '' }}>
                                        {{ $allocation->subject->subject_name ?? 'N/A' }} - {{ $allocation->teacher->full_name ?? 'N/A' }}
                                    </option>
                                    @endforeach
                                </select>
                            @endif
                        </td>
                        <td>
                            @if(!isset($period['is_break']) || !$period['is_break'])
                            <button class="delete-row-btn" onclick="deleteRow(this)" title="Delete Period">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-footer">
            <button class="add-time-btn" onclick="addNewRow()">
                <i class="fas fa-plus"></i> Add Period
            </button>
        </div>
    </div>
    @elseif($isStreamClass && $selectedClass && !$selectedStream)
    <div class="timetable-wrapper">
        <div class="no-data">
            <i class="fas fa-code-branch"></i>
            <p>Please select a stream to view time table</p>
            <small>Click on a stream tab above</small>
        </div>
    </div>
    @elseif(!$selectedClass)
    <div class="timetable-wrapper">
        <div class="no-data">
            <i class="fas fa-chalkboard-user"></i>
            <p>Please select a class to view time table</p>
            <small>Use the filter above to select a class</small>
        </div>
    </div>
    @endif
</div>

<script>
    function applyFilter() {
        let classId = document.getElementById('class_id').value;
        let streamId = document.getElementById('stream_id')?.value || '';
        if(classId) {
            let url = '{{ route("time.table") }}?class_id=' + classId;
            if(streamId) url += '&stream_id=' + streamId;
            window.location.href = url;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please select a class',
                confirmButtonColor: '#1e3c72'
            });
        }
    }
    
    function saveTimeTable() {
        let classId = document.getElementById('class_id').value;
        let streamId = document.getElementById('stream_id')?.value || null;
        let periods = [];
        let rows = document.querySelectorAll('#timetableBody tr');
        
        if(!classId) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please select a class first',
                confirmButtonColor: '#1e3c72'
            });
            return;
        }
        
        rows.forEach((row, index) => {
            let isBreak = row.classList.contains('break-row');
            let timeInput = row.querySelector('.time-input');
            let time = timeInput ? timeInput.value : '';
            
            let allocationId = '';
            let isBreakVal = false;
            
            if(!isBreak) {
                let select = row.querySelector('.subject-select');
                if(select) {
                    allocationId = select.value;
                }
            }
            
            periods.push({
                time: time,
                allocation_id: allocationId,
                is_break: isBreak
            });
        });
        
        Swal.fire({
            title: 'Saving...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch('{{ route("time.table.save") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                class_id: classId,
                stream_id: streamId,
                periods: periods
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved! 🎉',
                    text: data.success,
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.error || 'Something went wrong',
                    confirmButtonColor: '#1e3c72'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Something went wrong!',
                confirmButtonColor: '#1e3c72'
            });
        });
    }
    
    function copyFromPrevious() {
        let classId = document.getElementById('class_id').value;
        let streamId = document.getElementById('stream_id')?.value || null;
        let fromSession = document.getElementById('copy_session').value;
        
        if(!classId) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please select a class first',
                confirmButtonColor: '#1e3c72'
            });
            return;
        }
        if(!fromSession) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please select a previous session to copy from',
                confirmButtonColor: '#1e3c72'
            });
            return;
        }
        
        Swal.fire({
            title: 'Copy Time Table?',
            text: 'This will overwrite current time table!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#8b5cf6',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Copy!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if(result.isConfirmed) {
                Swal.fire({
                    title: 'Copying...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                fetch('{{ route("time.table.copy") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        class_id: classId,
                        stream_id: streamId,
                        from_session: fromSession
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Copied! 🎉',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.error || 'Something went wrong',
                            confirmButtonColor: '#1e3c72'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong!',
                        confirmButtonColor: '#1e3c72'
                    });
                });
            }
        });
    }
    
    function addNewRow() {
        let tbody = document.getElementById('timetableBody');
        let newRow = tbody.insertRow();
        let lastIndex = tbody.rows.length - 1;
        
        let optionsHtml = '<option value="">-- Select --</option>';
        @foreach($allocations as $allocation)
        optionsHtml += `<option value="{{ $allocation->id }}" data-subject-id="{{ $allocation->subject_id }}" data-teacher-id="{{ $allocation->teacher_id }}">{{ $allocation->subject->subject_name }} - {{ $allocation->teacher->full_name }}</option>`;
        @endforeach
        
        newRow.innerHTML = `
            <td class="time-cell">
                <input type="text" class="time-input" data-period="${lastIndex}" data-field="time" value="00:00 - 00:45">
            </td>
            <td>
                <select class="subject-select" data-period="${lastIndex}" data-field="allocation_id">
                    ${optionsHtml}
                </select>
            </td>
            <td>
                <button class="delete-row-btn" onclick="deleteRow(this)"><i class="fas fa-times"></i></button>
            </td>
        `;
        
        // Update data-period attributes
        document.querySelectorAll('#timetableBody tr').forEach((row, idx) => {
            row.querySelectorAll('.time-input, .subject-select').forEach(input => {
                if(input) input.setAttribute('data-period', idx);
            });
        });
        
        // Scroll to new row
        newRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    function deleteRow(btn) {
        let row = btn.closest('tr');
        if(row && !row.classList.contains('break-row')) {
            // Animate deletion
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '0';
            row.style.transform = 'translateX(-20px)';
            
            setTimeout(() => {
                row.remove();
                // Update indices
                document.querySelectorAll('#timetableBody tr').forEach((row, idx) => {
                    row.querySelectorAll('.time-input, .subject-select').forEach(input => {
                        if(input) input.setAttribute('data-period', idx);
                    });
                });
            }, 300);
        }
    }
    
    // ===== ENTER KEY SUPPORT =====
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('class_id')?.addEventListener('keydown', function(e) {
            if(e.key === 'Enter') applyFilter();
        });
        document.getElementById('stream_id')?.addEventListener('keydown', function(e) {
            if(e.key === 'Enter') applyFilter();
        });
    });
</script>
@endsection