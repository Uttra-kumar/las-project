@extends('layouts.app')

@section('title', 'Teacher Subject Allocation')

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
    
    .tsa-container {
        animation: fadeInUp 0.35s ease;
        max-width: 100%;
    }

    /* ===== HEADER ===== */
    .tsa-header {
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
    .tsa-header h2 {
        font-size: 0.95rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }
    .tsa-header h2 i {
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
    }

    .btn-filter, .btn-print {
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
    .btn-print {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        box-shadow: 0 2px 6px rgba(245, 158, 11, 0.2);
    }
    .btn-print:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(245, 158, 11, 0.3);
    }
    .btn-filter i, .btn-print i {
        font-size: 0.7rem;
    }

    /* ===== ALLOCATION SECTION ===== */
    .allocation-section {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .allocation-header {
        padding: 10px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .allocation-header h3 {
        font-size: 0.75rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
        color: #1e3c72;
        font-weight: 700;
    }
    .allocation-header h3 i {
        color: #3b82f6;
    }
    .allocation-header .badge-count {
        background: #1e3c72;
        color: white;
        padding: 1px 10px;
        border-radius: 12px;
        font-size: 0.55rem;
        font-weight: 600;
    }

    .header-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        align-items: center;
    }

    .btn-add, .btn-copy {
        border: none;
        color: white;
        padding: 4px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.65rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
        height: 30px;
        transition: all 0.3s ease;
    }
    .btn-add {
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);
    }
    .btn-add:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(16, 185, 129, 0.3);
    }
    .btn-copy {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        box-shadow: 0 2px 6px rgba(139, 92, 246, 0.2);
    }
    .btn-copy:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(139, 92, 246, 0.3);
    }
    .btn-add i, .btn-copy i {
        font-size: 0.6rem;
    }

    .copy-section {
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .copy-section select {
        padding: 4px 8px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.65rem;
        height: 30px;
        background: #fafbfc;
        font-family: inherit;
        font-weight: 500;
    }
    .copy-section select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }

    /* ===== TABLE ===== */
    .table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding: 0 2px;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.7rem;
        min-width: 450px;
    }
    .data-table th {
        text-align: left;
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
    .data-table td {
        padding: 6px 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .data-table tbody tr {
        transition: background 0.2s ease;
    }
    .data-table tbody tr:hover {
        background: #f8fafc;
    }

    /* ===== ACTION BUTTONS ===== */
    .action-btns {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    .btn-edit, .btn-delete {
        padding: 2px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.6rem;
        font-weight: 600;
        transition: all 0.2s ease;
        height: 24px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    .btn-edit {
        background: #dbeafe;
        color: #2563eb;
    }
    .btn-edit:hover {
        background: #bfdbfe;
        transform: scale(1.05);
    }
    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }
    .btn-delete:hover {
        background: #fecaca;
        transform: scale(1.05);
    }
    .btn-edit i, .btn-delete i {
        font-size: 0.5rem;
    }

    /* ===== SUBJECT BADGE ===== */
    .subject-badge {
        background: #dbeafe;
        color: #1e40af;
        padding: 2px 10px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.6rem;
        display: inline-block;
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

    /* ===== MODAL ===== */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(4px);
        animation: fadeInUp 0.3s ease;
    }
    .modal.show {
        display: flex;
    }
    .modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 420px;
        padding: 0;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: slideDown 0.3s ease;
    }
    .modal-header {
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
        border-radius: 12px 12px 0 0;
    }
    .modal-header h3 {
        font-size: 0.85rem;
        margin: 0;
        color: #1e3c72;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .modal-header h3 i {
        color: #3b82f6;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: #94a3b8;
        transition: all 0.2s;
        padding: 0 4px;
    }
    .modal-close:hover {
        color: #dc2626;
        transform: rotate(90deg);
    }
    .modal-body {
        padding: 16px;
    }
    .form-group {
        margin-bottom: 12px;
    }
    .form-group label {
        display: block;
        font-size: 0.65rem;
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .form-group label .req {
        color: #dc2626;
        font-weight: 700;
    }
    .form-group label i {
        color: #3b82f6;
        margin-right: 3px;
    }
    .form-group select {
        width: 100%;
        padding: 6px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.75rem;
        height: 34px;
        background: #fafbfc;
        font-family: inherit;
        font-weight: 500;
        transition: all 0.25s ease;
    }
    .form-group select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        background: white;
    }
    .modal-footer {
        padding: 12px 16px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        background: #f8fafc;
        border-radius: 0 0 12px 12px;
    }
    .btn-save, .btn-cancel {
        padding: 6px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        font-weight: 600;
        transition: all 0.3s ease;
        height: 34px;
    }
    .btn-save {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);
    }
    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(16, 185, 129, 0.3);
    }
    .btn-cancel {
        background: #e2e8f0;
        color: #475569;
    }
    .btn-cancel:hover {
        background: #cbd5e1;
        transform: translateY(-1px);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .filter-row {
            gap: 8px;
        }
        .filter-group {
            min-width: 100px;
        }
        .data-table {
            font-size: 0.65rem;
            min-width: 400px;
        }
        .data-table th,
        .data-table td {
            padding: 6px 10px;
        }
    }

    @media (max-width: 768px) {
        .tsa-header {
            padding: 10px 14px;
            flex-direction: column;
            text-align: center;
        }
        .tsa-header h2 {
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
        }
        .filter-actions .btn-filter,
        .filter-actions .btn-print {
            flex: 1;
            justify-content: center;
        }
        .allocation-header {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
        }
        .header-actions {
            width: 100%;
            flex-direction: column;
        }
        .header-actions .copy-section {
            width: 100%;
        }
        .copy-section select {
            flex: 1;
        }
        .btn-add {
            width: 100%;
            justify-content: center;
        }
        .data-table {
            font-size: 0.6rem;
            min-width: 350px;
        }
        .data-table th,
        .data-table td {
            padding: 4px 8px;
        }
        .action-btns {
            flex-direction: column;
            gap: 3px;
        }
        .btn-edit, .btn-delete {
            font-size: 0.55rem;
            padding: 2px 8px;
            height: 22px;
            justify-content: center;
        }
        .modal-content {
            max-width: 95%;
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
        .tsa-header {
            padding: 8px 12px;
        }
        .tsa-header h2 {
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
        .btn-filter, .btn-print {
            font-size: 0.6rem;
            padding: 4px 10px;
            height: 30px;
        }
        .allocation-header h3 {
            font-size: 0.7rem;
        }
        .btn-add, .btn-copy {
            font-size: 0.6rem;
            padding: 3px 10px;
            height: 28px;
        }
        .copy-section select {
            font-size: 0.6rem;
            height: 28px;
            padding: 3px 6px;
        }
        .data-table {
            font-size: 0.55rem;
            min-width: 300px;
        }
        .data-table th,
        .data-table td {
            padding: 3px 6px;
        }
        .data-table th {
            font-size: 0.5rem;
        }
        .subject-badge {
            font-size: 0.5rem;
            padding: 1px 6px;
        }
        .filter-actions {
            flex-direction: column;
        }
        .filter-actions .btn-filter,
        .filter-actions .btn-print {
            width: 100%;
        }
        .modal-content {
            max-width: 98%;
        }
        .modal-header h3 {
            font-size: 0.75rem;
        }
        .form-group label {
            font-size: 0.55rem;
        }
        .form-group select {
            font-size: 0.7rem;
            padding: 4px 8px;
            height: 30px;
        }
        .btn-save, .btn-cancel {
            font-size: 0.65rem;
            padding: 4px 14px;
            height: 30px;
        }
        .no-data i {
            font-size: 1.5rem;
        }
        .no-data p {
            font-size: 0.65rem;
        }
    }

    /* ===== PRINT ===== */
    @media print {
        .sidebar, .top-header, .filter-section, .btn-print, .btn-add, .btn-copy,
        .action-btns, .no-print, .allocation-header .btn-add, .allocation-header .btn-copy,
        .copy-section, .header-actions, .allocation-header .badge-count {
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
        .tsa-container {
            padding: 0 !important;
            margin: 0 !important;
            animation: none !important;
        }
        .tsa-header {
            background: #1e3c72 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            padding: 8px 12px !important;
        }
        .tsa-header h2 {
            font-size: 12px !important;
        }
        .allocation-section {
            border: 1px solid #000 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        .allocation-header {
            background: #f0f0f0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            border-bottom: 1px solid #000 !important;
        }
        .allocation-header h3 {
            font-size: 10px !important;
        }
        .data-table th {
            background: #f0f0f0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            border: 1px solid #000 !important;
            font-size: 8px !important;
            padding: 4px 6px !important;
        }
        .data-table td {
            border: 1px solid #000 !important;
            padding: 3px 6px !important;
            font-size: 7px !important;
        }
        .data-table {
            font-size: 7px !important;
            min-width: auto !important;
            width: 100% !important;
        }
        .data-table tbody tr:hover {
            background: transparent !important;
        }
        .subject-badge {
            background: #dbeafe !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-size: 6px !important;
            padding: 1px 6px !important;
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
        .modal {
            display: none !important;
        }
    }
</style>

<div class="tsa-container">
    <!-- ===== HEADER ===== -->
    <div class="tsa-header">
        <h2>
            <i class="fas fa-chalkboard-teacher"></i>
            Teacher Subject Allocation
        </h2>
        <div class="session-badge">
            <i class="fas fa-calendar-alt"></i>
            Session: <strong>{{ $currentSession->session_name ?? 'No Active Session' }}</strong>
        </div>
    </div>

    <!-- ===== FILTER SECTION ===== -->
    <div class="filter-section">
        <div class="filter-row">
            <div class="filter-group">
                <label><i class="fas fa-chalkboard-user"></i> Select Class</label>
                <select id="class_id" onchange="onClassChange()">
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
                <label><i class="fas fa-code-branch"></i> Select Stream</label>
                <select id="stream_id" onchange="onStreamChange()">
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
                <button class="btn-print" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>

    <!-- ===== ALLOCATIONS ===== -->
    @if($selectedClass)
    <div class="allocation-section">
        <div class="allocation-header">
            <h3>
                <i class="fas fa-list"></i>
                Subject Allocations
                <span class="badge-count">{{ $allocations->count() }}</span>
            </h3>
            <div class="header-actions">
                <div class="copy-section">
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
                <button class="btn-add" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
        </div>

        <div class="table-scroll">
            @if($allocations->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allocations as $key => $allocation)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <span class="subject-badge">
                                <i class="fas fa-book-open"></i>
                                {{ $allocation->subject->subject_name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <i class="fas fa-user"></i>
                            {{ $allocation->teacher->full_name ?? 'N/A' }}
                        </td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-edit" onclick="openEditModal({{ $allocation->id }}, {{ $allocation->teacher_id }})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn-delete" onclick="deleteAllocation({{ $allocation->id }})">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="no-data">
                <i class="fas fa-info-circle"></i>
                <p>No allocations found for this class</p>
                <small>Click "Add" to create a new allocation</small>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="allocation-section">
        <div class="no-data" style="padding: 50px;">
            <i class="fas fa-chalkboard-user" style="font-size: 2.5rem;"></i>
            <p>Please select a class to view allocations</p>
            <small>Use the filter above to select a class</small>
        </div>
    </div>
    @endif
</div>

<!-- ===== MODAL ===== -->
<div class="modal" id="allocationModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">
                <i class="fas fa-plus-circle"></i>
                <span id="modalTitleText">Add Allocation</span>
            </h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="allocationForm">
                <input type="hidden" id="allocation_id">
                <div class="form-group">
                    <label><i class="fas fa-book-open"></i> Subject <span class="req">*</span></label>
                    <select id="subject_id" required>
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-chalkboard-user"></i> Teacher <span class="req">*</span></label>
                    <select id="teacher_id" required>
                        <option value="">-- Select Teacher --</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
            <button type="button" class="btn-save" id="saveBtn">
                <i class="fas fa-save"></i> Save
            </button>
        </div>
    </div>
</div>

<script>
    let editId = null;

    function onClassChange() {
        let classId = document.getElementById('class_id').value;
        if(classId) {
            window.location.href = '{{ route("teacher.subject.allocation") }}?class_id=' + classId;
        }
    }

    function onStreamChange() {
        let classId = document.getElementById('class_id').value;
        let streamId = document.getElementById('stream_id').value;
        if(classId && streamId) {
            window.location.href = '{{ route("teacher.subject.allocation") }}?class_id=' + classId + '&stream_id=' + streamId;
        }
    }

    function applyFilter() {
        let classId = document.getElementById('class_id').value;
        let streamId = document.getElementById('stream_id')?.value || '';
        if(classId) {
            let url = '{{ route("teacher.subject.allocation") }}?class_id=' + classId;
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

    function openAddModal() {
        editId = null;
        document.getElementById('modalTitleText').innerText = 'Add Allocation';
        document.getElementById('allocation_id').value = '';
        document.getElementById('allocationForm').reset();
        document.getElementById('allocationModal').classList.add('show');
    }

    function openEditModal(id, teacherId) {
        editId = id;
        document.getElementById('modalTitleText').innerText = 'Edit Allocation';
        document.getElementById('allocation_id').value = id;
        document.getElementById('teacher_id').value = teacherId;
        
        fetch(`/admin/teacher-subject-allocation/get/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('subject_id').value = data.subject_id;
                document.getElementById('allocationModal').classList.add('show');
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('allocationModal').classList.add('show');
            });
    }

    function closeModal() {
        document.getElementById('allocationModal').classList.remove('show');
        document.getElementById('allocationForm').reset();
    }

    document.getElementById('saveBtn').addEventListener('click', function() {
        let subjectId = document.getElementById('subject_id').value;
        let teacherId = document.getElementById('teacher_id').value;
        let allocationId = document.getElementById('allocation_id').value;
        
        if(!subjectId || !teacherId) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please select both subject and teacher',
                confirmButtonColor: '#1e3c72'
            });
            return;
        }
        
        let classId = document.getElementById('class_id').value;
        let streamElement = document.getElementById('stream_id');
        let streamId = streamElement ? streamElement.value : null;
        
        let url = allocationId ? 
            '/admin/teacher-subject-allocation/update/' + allocationId : 
            '/admin/teacher-subject-allocation/store';
        
        let formData = {
            class_id: classId,
            stream_id: streamId,
            subject_id: subjectId,
            teacher_id: teacherId,
            _token: '{{ csrf_token() }}'
        };
        
        Swal.fire({
            title: 'Saving...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch(url, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                ...formData, 
                _method: allocationId ? 'PUT' : 'POST' 
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text || 'Server error');
                });
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '',
                    text: data.success,
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
                closeModal();
                setTimeout(() => location.reload(), 1500);
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
                text: 'Something went wrong: ' + error.message,
                confirmButtonColor: '#1e3c72'
            });
        });
    });

    function deleteAllocation(id) {
        Swal.fire({
            title: 'Delete Allocation?',
            text: 'This will remove the subject from this class.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Delete!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if(result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                fetch('/admin/teacher-subject-allocation/delete/' + id, {
                    method: 'DELETE',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.success,
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                        setTimeout(() => location.reload(), 1500);
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

    function copyFromPrevious() {
        let fromSession = document.getElementById('copy_session').value;
        let classId = document.getElementById('class_id').value;
        let streamId = document.getElementById('stream_id')?.value || null;
        
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
                text: 'Please select a session to copy from',
                confirmButtonColor: '#1e3c72'
            });
            return;
        }
        
        Swal.fire({
            title: 'Copy Allocations?',
            text: 'This will copy all subject allocations from previous session.',
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
                
                fetch('{{ route("teacher.subject.allocation.copy") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
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
                        }).then(() => {
                            location.reload();
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
        });
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

    // ===== CLOSE MODAL ON ESC =====
    document.addEventListener('keydown', function(e) {
        if(e.key === 'Escape') {
            closeModal();
        }
    });

    // ===== CLOSE MODAL ON OVERLAY CLICK =====
    document.getElementById('allocationModal')?.addEventListener('click', function(e) {
        if(e.target === this) {
            closeModal();
        }
    });
</script>
@endsection