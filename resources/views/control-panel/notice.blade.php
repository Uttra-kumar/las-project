@extends('layouts.app')

@section('title', 'Notice Management')

@section('content')
<style>
    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    
    .notice-container {
        animation: fadeInUp 0.35s ease;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ===== HEADER ===== */
    .notice-header {
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
    .notice-header h2 {
        font-size: 0.95rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }
    .notice-header h2 i {
        color: #fbbf24;
    }
    .header-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    
    .btn-add {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: white;
        padding: 5px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.65rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-weight: 600;
        height: 34px;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);
    }
    .btn-add:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(16, 185, 129, 0.3);
        color: white;
    }
    .btn-add i {
        font-size: 0.7rem;
    }
    
    .btn-print {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border: none;
        color: white;
        padding: 5px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.65rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-weight: 600;
        height: 34px;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(245, 158, 11, 0.2);
    }
    .btn-print:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(245, 158, 11, 0.3);
    }
    
    .btn-csv {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        border: none;
        color: white;
        padding: 5px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.65rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-weight: 600;
        height: 34px;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(139, 92, 246, 0.2);
    }
    .btn-csv:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(139, 92, 246, 0.3);
    }

    /* ===== FILTER CARD ===== */
    .filter-card {
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
    .filter-item {
        flex: 1;
        min-width: 120px;
    }
    .filter-item label {
        display: block;
        font-size: 0.6rem;
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 3px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .filter-item label i {
        color: #3b82f6;
        margin-right: 3px;
    }
    .filter-item input,
    .filter-item select {
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
    .filter-item input:focus,
    .filter-item select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        background: white;
    }
    
    .filter-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    
    .btn-filter {
        background: linear-gradient(135deg, #1e3c72, #2d4a7a);
        border: none;
        color: white;
        padding: 5px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 34px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(30, 60, 114, 0.2);
        text-decoration: none;
    }
    .btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(30, 60, 114, 0.3);
    }
    .btn-reset {
        background: linear-gradient(135deg, #64748b, #475569);
        border: none;
        color: white;
        padding: 5px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 34px;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .btn-reset:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(100, 116, 139, 0.3);
    }
    .btn-filter i, .btn-reset i {
        font-size: 0.7rem;
    }

    /* ===== TABLE ===== */
    .table-wrapper {
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
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.75rem;
        min-width: 650px;
    }
    .data-table th {
        text-align: left;
        padding: 8px 12px;
        background: #f8fafc;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        text-transform: uppercase;
        font-size: 0.6rem;
        color: #1e3c72;
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

    /* ===== BADGES ===== */
    .badge-published {
        background: #dcfce7;
        color: #15803d;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 0.55rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-unpublished {
        background: #fef3c7;
        color: #b45309;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 0.55rem;
        font-weight: 600;
        display: inline-block;
    }

    /* ===== ACTION BUTTONS ===== */
    .action-btns {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    .btn-view, .btn-edit, .btn-delete {
        background: none;
        border: none;
        cursor: pointer;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.6rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        height: 28px;
        font-weight: 600;
    }
    .btn-view {
        background: #dbeafe;
        color: #2563eb;
    }
    .btn-view:hover {
        background: #bfdbfe;
        transform: scale(1.05);
    }
    .btn-edit {
        background: #fef3c7;
        color: #f59e0b;
    }
    .btn-edit:hover {
        background: #fde68a;
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

    /* ===== PAGINATION ===== */
    .pagination-wrapper {
        padding: 12px 16px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: center;
        background: #fafbfc;
    }
    .pagination-wrapper nav {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    .pagination-wrapper a,
    .pagination-wrapper span {
        padding: 4px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        font-size: 0.7rem;
        text-decoration: none;
        color: #1e3c72;
        transition: all 0.2s ease;
    }
    .pagination-wrapper a:hover {
        background: #f1f5f9;
        transform: translateY(-1px);
    }
    .pagination-wrapper .active span {
        background: #1e3c72;
        color: white;
        border-color: #1e3c72;
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

    /* ============================================
       MODAL
       ============================================ */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        animation: fadeInUp 0.3s ease;
    }
    .modal-overlay.active {
        display: flex;
    }
    .modal-content {
        background: white;
        border-radius: 12px;
        max-width: 550px;
        width: 92%;
        max-height: 90vh;
        overflow-y: auto;
        padding: 0;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: slideUp 0.3s ease;
    }
    .modal-content::-webkit-scrollbar {
        width: 4px;
    }
    .modal-content::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 18px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 12px 12px 0 0;
    }
    .modal-header h3 {
        font-size: 0.85rem;
        color: #1e3c72;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
    }
    .modal-header h3 i {
        color: #f59e0b;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: #94a3b8;
        padding: 0 4px;
        transition: all 0.2s;
    }
    .modal-close:hover {
        color: #dc2626;
        transform: rotate(90deg);
    }
    .modal-body {
        padding: 16px 18px;
    }
    .modal-footer {
        padding: 12px 18px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        background: #f8fafc;
        border-radius: 0 0 12px 12px;
    }

    /* ===== FORM ===== */
    .form-group {
        margin-bottom: 12px;
    }
    .form-group label {
        display: block;
        font-size: 0.6rem;
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 3px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .form-group label .required {
        color: #dc2626;
        font-weight: 700;
    }
    .form-group label i {
        color: #3b82f6;
        margin-right: 3px;
        width: 14px;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
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
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        background: white;
    }
    .form-group textarea {
        min-height: 80px;
        resize: vertical;
        height: auto;
    }

    .btn-save {
        background: linear-gradient(135deg, #1e3c72, #2d4a7a);
        border: none;
        color: white;
        padding: 6px 24px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        height: 34px;
        box-shadow: 0 2px 6px rgba(30, 60, 114, 0.25);
    }
    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(30, 60, 114, 0.35);
    }
    .btn-save i {
        font-size: 0.8rem;
    }
    .btn-cancel {
        background: #e2e8f0;
        border: none;
        color: #475569;
        padding: 6px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
        height: 34px;
    }
    .btn-cancel:hover {
        background: #cbd5e1;
        transform: translateY(-1px);
    }

    /* ===== VIEW DETAIL ===== */
    .view-detail .row {
        display: flex;
        padding: 4px 0;
        border-bottom: 1px dashed #f1f5f9;
        font-size: 0.8rem;
    }
    .view-detail .row:last-child {
        border-bottom: none;
    }
    .view-detail .label {
        font-weight: 600;
        color: #64748b;
        min-width: 100px;
        flex-shrink: 0;
    }
    .view-detail .value {
        color: #1e293b;
        font-weight: 500;
    }

    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (max-width: 992px) {
        .filter-row {
            gap: 8px;
        }
        .filter-item {
            min-width: 100px;
        }
        .data-table {
            font-size: 0.7rem;
            min-width: 600px;
        }
    }

    @media (max-width: 768px) {
        .notice-header {
            padding: 10px 14px;
            flex-direction: column;
            text-align: center;
        }
        .notice-header h2 {
            font-size: 0.85rem;
        }
        .header-actions {
            width: 100%;
            justify-content: center;
        }
        .filter-card {
            padding: 10px 12px;
        }
        .filter-row {
            flex-direction: column;
            gap: 6px;
        }
        .filter-item {
            min-width: 100%;
            width: 100%;
        }
        .filter-actions {
            width: 100%;
            display: flex;
            gap: 6px;
        }
        .filter-actions .btn-filter,
        .filter-actions .btn-reset {
            flex: 1;
            justify-content: center;
        }
        .data-table {
            font-size: 0.65rem;
            min-width: 550px;
        }
        .data-table th,
        .data-table td {
            padding: 4px 8px;
        }
        .action-btns {
            flex-direction: column;
            gap: 3px;
        }
        .btn-view, .btn-edit, .btn-delete {
            font-size: 0.55rem;
            padding: 2px 6px;
            height: 24px;
            justify-content: center;
        }
        .modal-content {
            max-width: 95%;
            padding: 0;
        }
        .modal-body {
            padding: 12px 14px;
        }
        .view-detail .row {
            font-size: 0.7rem;
        }
        .view-detail .label {
            min-width: 80px;
        }
    }

    @media (max-width: 480px) {
        .notice-header {
            padding: 8px 12px;
        }
        .notice-header h2 {
            font-size: 0.8rem;
        }
        .btn-add, .btn-print, .btn-csv {
            font-size: 0.6rem;
            padding: 4px 10px;
            height: 30px;
        }
        .filter-card {
            padding: 8px 10px;
        }
        .filter-item label {
            font-size: 0.55rem;
        }
        .filter-item input,
        .filter-item select {
            font-size: 0.7rem;
            padding: 4px 8px;
            height: 30px;
        }
        .btn-filter, .btn-reset {
            font-size: 0.6rem;
            padding: 4px 10px;
            height: 30px;
        }
        .data-table {
            font-size: 0.6rem;
            min-width: 480px;
        }
        .data-table th,
        .data-table td {
            padding: 3px 6px;
        }
        .data-table th {
            font-size: 0.5rem;
        }
        .badge-published, .badge-unpublished {
            font-size: 0.5rem;
            padding: 1px 8px;
        }
        .header-actions {
            flex-direction: column;
            align-items: stretch;
        }
        .header-actions .btn-add,
        .header-actions .btn-print,
        .header-actions .btn-csv {
            width: 100%;
            justify-content: center;
        }
        .filter-actions {
            flex-direction: column;
        }
        .filter-actions .btn-filter,
        .filter-actions .btn-reset {
            width: 100%;
        }
        .modal-header h3 {
            font-size: 0.75rem;
        }
        .form-group label {
            font-size: 0.55rem;
        }
        .form-group input,
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
        .view-detail .row {
            font-size: 0.65rem;
            padding: 3px 0;
        }
        .view-detail .label {
            min-width: 70px;
        }
        .pagination-wrapper a,
        .pagination-wrapper span {
            font-size: 0.6rem;
            padding: 3px 8px;
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

    @media (max-width: 380px) {
        .data-table {
            font-size: 0.55rem;
            min-width: 400px;
        }
        .data-table th,
        .data-table td {
            padding: 2px 4px;
        }
        .data-table th {
            font-size: 0.45rem;
        }
        .modal-content {
            max-width: 98%;
        }
        .modal-body {
            padding: 8px 10px;
        }
        .view-detail .row {
            font-size: 0.6rem;
        }
        .view-detail .label {
            min-width: 60px;
        }
    }

    /* ============================================
       PRINT
       ============================================ */
    @media print {
        .sidebar, .top-header, .filter-card, .btn-print, .btn-csv,
        .btn-filter, .btn-reset, .btn-add, .action-btns, .modal-overlay,
        .header-actions, .filter-actions, .no-print, .pagination-wrapper {
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
        .notice-container {
            padding: 0 !important;
            margin: 0 !important;
            animation: none !important;
            max-width: 100% !important;
        }
        .notice-header {
            background: #1a365d !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            padding: 8px 12px !important;
        }
        .notice-header h2 {
            font-size: 12px !important;
        }
        .table-wrapper {
            border: 1px solid #000 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        .data-table th {
            background: #e0e0e0 !important;
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
        .badge-published, .badge-unpublished {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
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

<div class="notice-container">
    <!-- ===== HEADER ===== -->
    <div class="notice-header">
        <h2>
            <i class="fas fa-bullhorn"></i>
            Notice Management
        </h2>
        <div class="header-actions">
            <button class="btn-print" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
            <a href="{{ route('control-panel.notice.export', request()->query()) }}" class="btn-csv">
                <i class="fas fa-file-csv"></i> CSV
            </a>
            <button class="btn-add" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Add Notice
            </button>
        </div>
    </div>

    <!-- ===== FILTER ===== -->
    <div class="filter-card">
        <form method="GET" class="filter-row">
            <div class="filter-item">
                <label><i class="fas fa-search"></i> Search</label>
                <input type="text" name="search" placeholder="Search by title..." value="{{ request('search') }}">
            </div>
            <div class="filter-item">
                <label><i class="fas fa-circle"></i> Status</label>
                <select name="status">
                    <option value="">-- All --</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Published</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Unpublished</option>
                </select>
            </div>
            <div class="filter-item">
                <label><i class="fas fa-calendar-alt"></i> From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="filter-item">
                <label><i class="fas fa-calendar-alt"></i> To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="filter-item filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('control-panel.notice') }}" class="btn-reset">
                    <i class="fas fa-undo-alt"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="table-wrapper">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th width="100">Date</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th width="100">Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notices as $index => $notice)
                    <tr>
                        <td>{{ $notices->firstItem() + $index }}</td>
                        <td>{{ $notice->notice_date ? date('d-m-Y', strtotime($notice->notice_date)) : '-' }}</td>
                        <td><strong>{{ $notice->title }}</strong></td>
                        <td>{{ Str::limit($notice->description, 50) }}</td>
                        <td>
                            <span class="badge-{{ $notice->status == '1' ? 'published' : 'unpublished' }}">
                                {{ $notice->status == '1' ? 'Published' : 'Unpublished' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-view" onclick="viewNotice({{ $notice->id }})" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-edit" onclick="editNotice({{ $notice->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete" onclick="deleteNotice({{ $notice->id }})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="no-data">
                            <i class="fas fa-bullhorn"></i>
                            <p>No notices found</p>
                            <small>Click "Add Notice" to create a new notice</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($notices->hasPages())
        <div class="pagination-wrapper">
            {{ $notices->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- ============================================ -->
<!-- ADD/EDIT MODAL -->
<!-- ============================================ -->
<div class="modal-overlay" id="noticeModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-bullhorn"></i> <span id="modalTitle">Add Notice</span></h3>
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>
        <form id="noticeForm">
            <div class="modal-body">
                @csrf
                <input type="hidden" id="notice_id" name="notice_id" value="">
                
                <div class="form-group">
                    <label><i class="fas fa-calendar-day"></i> Date <span class="required">*</span></label>
                    <input type="date" name="notice_date" id="notice_date" value="{{ date('Y-m-d') }}" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-heading"></i> Title <span class="required">*</span></label>
                    <input type="text" name="title" id="title" placeholder="Enter notice title" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Description</label>
                    <textarea name="description" id="description" rows="3" placeholder="Enter notice description"></textarea>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-circle"></i> Status <span class="required">*</span></label>
                    <select name="status" id="status" required>
                        <option value="1">✅ Published</option>
                        <option value="0">⏸ Unpublished</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-comment"></i> Remarks</label>
                    <input type="text" name="remarks" id="remarks" placeholder="Any remarks...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-save" id="submitBtn">
                    <i class="fas fa-save"></i> <span id="submitText">Save Notice</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================ -->
<!-- VIEW MODAL -->
<!-- ============================================ -->
<div class="modal-overlay" id="viewModal">
    <div class="modal-content" style="max-width:550px;">
        <div class="modal-header">
            <h3><i class="fas fa-eye"></i> Notice Details</h3>
            <button class="modal-close" onclick="closeViewModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="view-detail" id="viewContent">
                <div class="row"><span class="label">Date:</span><span class="value" id="view_date">-</span></div>
                <div class="row"><span class="label">Title:</span><span class="value" id="view_title">-</span></div>
                <div class="row"><span class="label">Description:</span><span class="value" id="view_desc">-</span></div>
                <div class="row"><span class="label">Status:</span><span class="value" id="view_status">-</span></div>
                <div class="row"><span class="label">Remarks:</span><span class="value" id="view_remarks">-</span></div>
                <div class="row"><span class="label">Created By:</span><span class="value" id="view_created_by">-</span></div>
                <div class="row"><span class="label">Created At:</span><span class="value" id="view_created_at">-</span></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeViewModal()">Close</button>
        </div>
    </div>
</div>

<script>
// ============================================
// OPEN ADD MODAL
// ============================================
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Notice';
    document.getElementById('submitText').textContent = 'Save Notice';
    document.getElementById('noticeForm').reset();
    document.getElementById('notice_id').value = '';
    document.getElementById('notice_date').value = '{{ date('Y-m-d') }}';
    document.getElementById('noticeModal').classList.add('active');
}

// ============================================
// CLOSE MODAL
// ============================================
function closeModal() {
    document.getElementById('noticeModal').classList.remove('active');
}

// ============================================
// VIEW NOTICE
// ============================================
function viewNotice(id) {
    fetch(`/control-panel/notice/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const n = data.notice;
                document.getElementById('view_date').textContent = n.notice_date ? new Date(n.notice_date).toLocaleDateString('en-IN') : '-';
                document.getElementById('view_title').textContent = n.title;
                document.getElementById('view_desc').textContent = n.description || '-';
                document.getElementById('view_status').textContent = n.status == '1' ? 'Published' : 'Unpublished';
                document.getElementById('view_remarks').textContent = n.remarks || '-';
                document.getElementById('view_created_by').textContent = n.creator?.name || '-';
                document.getElementById('view_created_at').textContent = n.created_at ? new Date(n.created_at).toLocaleString('en-IN') : '-';
                document.getElementById('viewModal').classList.add('active');
            }
        })
        .catch(error => console.error('Error:', error));
}

function closeViewModal() {
    document.getElementById('viewModal').classList.remove('active');
}

// ============================================
// EDIT NOTICE
// ============================================
function editNotice(id) {
    fetch(`/control-panel/notice/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const n = data.notice;
                document.getElementById('modalTitle').textContent = 'Edit Notice';
                document.getElementById('submitText').textContent = 'Update Notice';
                document.getElementById('notice_id').value = n.id;
                document.getElementById('notice_date').value = n.notice_date;
                document.getElementById('title').value = n.title;
                document.getElementById('description').value = n.description || '';
                document.getElementById('status').value = n.status;
                document.getElementById('remarks').value = n.remarks || '';
                document.getElementById('noticeModal').classList.add('active');
            }
        })
        .catch(error => console.error('Error:', error));
}

// ============================================
// SAVE/UPDATE NOTICE
// ============================================
document.getElementById('noticeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    const id = document.getElementById('notice_id').value;
    const url = id ? `/control-panel/notice/${id}` : '/control-panel/notice/store';
    const method = id ? 'PUT' : 'POST';
    
    if (!data.notice_date || !data.title) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Please fill all required fields',
            confirmButtonColor: '#1e3c72'
        });
        return;
    }
    
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    
    Swal.fire({
        title: id ? 'Updating...' : 'Saving...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(response => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> ' + (id ? 'Update Notice' : 'Save Notice');
        
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success! 🎉',
                text: response.message,
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
                text: response.message || 'Something went wrong',
                confirmButtonColor: '#1e3c72'
            });
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> ' + (id ? 'Update Notice' : 'Save Notice');
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Something went wrong!',
            confirmButtonColor: '#1e3c72'
        });
    });
});

// ============================================
// DELETE NOTICE
// ============================================
function deleteNotice(id) {
    Swal.fire({
        title: 'Delete Notice?',
        text: 'Are you sure you want to delete this notice?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then(result => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            fetch(`/control-panel/notice/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message,
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
                        text: data.message || 'Something went wrong',
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

// ============================================
// CLOSE MODALS ON OVERLAY CLICK
// ============================================
document.getElementById('noticeModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) closeViewModal();
});

// ============================================
// CLOSE ON ESCAPE KEY
// ============================================
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
        closeViewModal();
    }
});

// ============================================
// ENTER KEY SUPPORT FOR FILTER
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.filter-item input, .filter-item select').forEach(el => {
        el.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
    });
});
</script>
@endsection