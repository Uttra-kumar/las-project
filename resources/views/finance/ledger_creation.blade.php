@extends('layouts.app')

@section('title', 'Ledger Creation - Finance')

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
    
    .finance-container {
        animation: fadeInUp 0.35s ease;
        max-width: 100%;
    }
    
    /* ===== HEADER ===== */
    .finance-header {
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
    .finance-header h2 {
        font-size: 0.95rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }
    .finance-header h2 i {
        color: #fbbf24;
    }
    .key-hint {
        background: rgba(255,255,255,0.12);
        padding: 3px 12px;
        border-radius: 16px;
        font-size: 0.6rem;
        border: 1px solid rgba(255,255,255,0.1);
        font-family: monospace;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .key-hint kbd {
        background: rgba(255,255,255,0.2);
        padding: 1px 6px;
        border-radius: 3px;
        font-size: 0.55rem;
    }
    
    /* ===== FORM CARD ===== */
    .form-card {
        background: white;
        border-radius: 10px;
        padding: 16px 18px;
        margin-bottom: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px 14px;
    }
    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 14px;
    }
    .form-group {
        margin-bottom: 0;
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
        min-height: 34px;
        resize: vertical;
        height: auto;
        padding: 5px 10px;
    }
    
    .full-width {
        grid-column: 1 / -1;
    }
    
    /* ===== GROUP BUTTON ===== */
    .group-input-wrapper {
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .group-input-wrapper select {
        flex: 1;
    }
    .btn-group-create {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border: none;
        color: white;
        padding: 0 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.6rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
        height: 34px;
        white-space: nowrap;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(245, 158, 11, 0.2);
    }
    .btn-group-create:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(245, 158, 11, 0.3);
    }
    .btn-group-create i {
        font-size: 0.6rem;
    }
    
    /* ===== FORM ACTIONS ===== */
    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1.5px solid #f1f5f9;
        justify-content: flex-end;
        flex-wrap: wrap;
    }
    .btn-reset-form {
        padding: 6px 20px;
        border: 1.5px solid #e2e8f0;
        background: white;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 600;
        color: #475569;
        transition: all 0.3s ease;
        height: 34px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-reset-form:hover {
        background: #f1f5f9;
        transform: translateY(-1px);
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
        background: linear-gradient(135deg, #2d4a7a, #1e3c72);
    }
    .btn-save i {
        font-size: 0.8rem;
    }
    
    /* ===== SEARCH CARD ===== */
    .search-card {
        background: white;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .search-row {
        display: flex;
        gap: 10px;
        align-items: flex-end;
        flex-wrap: wrap;
    }
    .search-item {
        flex: 1;
        min-width: 120px;
    }
    .search-item label {
        display: block;
        font-size: 0.55rem;
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 3px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .search-item label i {
        color: #3b82f6;
        margin-right: 3px;
    }
    .search-item input,
    .search-item select {
        width: 100%;
        padding: 5px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.7rem;
        background: #fafbfc;
        transition: all 0.25s ease;
        height: 34px;
        font-family: inherit;
        font-weight: 500;
    }
    .search-item input:focus,
    .search-item select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        background: white;
    }
    
    .search-actions {
        display: flex;
        gap: 6px;
        align-items: flex-end;
    }
    .btn-search {
        background: linear-gradient(135deg, #1e3c72, #2d4a7a);
        border: none;
        color: white;
        padding: 5px 16px;
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
    }
    .btn-search:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(30, 60, 114, 0.3);
    }
    .btn-search i {
        font-size: 0.7rem;
    }
    .btn-reset {
        background: linear-gradient(135deg, #64748b, #475569);
        border: none;
        color: white;
        padding: 5px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 34px;
        transition: all 0.3s ease;
    }
    .btn-reset:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(100, 116, 139, 0.3);
    }
    
    /* ===== TABLE ===== */
    .table-wrapper {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .table-header {
        padding: 10px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .table-header h4 {
        font-size: 0.75rem;
        color: #1e3c72;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 700;
    }
    .table-header h4 i {
        color: #3b82f6;
    }
    .table-header .count-badge {
        background: #1e3c72;
        color: white;
        padding: 1px 10px;
        border-radius: 12px;
        font-size: 0.55rem;
        font-weight: 600;
    }
    
    .table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding: 0 2px;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.7rem;
        min-width: 550px;
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
    .badge-active {
        background: #dcfce7;
        color: #15803d;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 0.55rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-inactive {
        background: #fee2e2;
        color: #dc2626;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 0.55rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-debit {
        background: #dbeafe;
        color: #1e40af;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 0.55rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-credit {
        background: #fef3c7;
        color: #b45309;
        padding: 2px 10px;
        border-radius: 4px;
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
    .btn-edit, .btn-delete {
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.65rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        height: 28px;
    }
    .btn-edit {
        color: #f59e0b;
        background: #fef3c7;
    }
    .btn-edit:hover {
        background: #fde68a;
        transform: scale(1.05);
    }
    .btn-delete {
        color: #dc2626;
        background: #fee2e2;
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
        padding: 40px 20px;
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
    
    /* ===== MODALS ===== */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
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
        max-width: 500px;
        width: 92%;
        max-height: 90vh;
        overflow-y: auto;
        padding: 0;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: slideDown 0.3s ease;
    }
    .modal-content::-webkit-scrollbar {
        width: 4px;
    }
    .modal-content::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .modal-header {
        padding: 12px 18px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
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
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    .modal-close:hover {
        background: #f1f5f9;
        color: #dc2626;
        transform: rotate(90deg);
    }
    .modal-body {
        padding: 18px;
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
    
    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .form-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .search-row {
            gap: 8px;
        }
        .search-item {
            min-width: 100px;
        }
        .data-table {
            font-size: 0.65rem;
            min-width: 500px;
        }
    }
    
    @media (max-width: 768px) {
        .finance-header {
            padding: 10px 14px;
            flex-direction: column;
            text-align: center;
        }
        .finance-header h2 {
            font-size: 0.85rem;
        }
        .key-hint {
            font-size: 0.55rem;
        }
        .form-card {
            padding: 12px 14px;
        }
        .form-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px 12px;
        }
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
        .search-card {
            padding: 10px 12px;
        }
        .search-row {
            flex-direction: column;
            gap: 6px;
        }
        .search-item {
            min-width: 100%;
            width: 100%;
        }
        .search-actions {
            width: 100%;
            display: flex;
            gap: 6px;
        }
        .search-actions .btn-search,
        .search-actions .btn-reset {
            flex: 1;
            justify-content: center;
        }
        .form-actions {
            flex-direction: column;
        }
        .form-actions .btn-reset-form,
        .form-actions .btn-save {
            width: 100%;
            justify-content: center;
        }
        .table-header {
            flex-direction: column;
            text-align: center;
        }
        .data-table {
            font-size: 0.6rem;
            min-width: 450px;
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
            padding: 2px 6px;
            height: 24px;
            justify-content: center;
        }
        .group-input-wrapper {
            flex-direction: column;
        }
        .group-input-wrapper select {
            width: 100%;
        }
        .btn-group-create {
            width: 100%;
            justify-content: center;
        }
        .modal-content {
            width: 95%;
            max-width: 100%;
        }
        .modal-body {
            padding: 14px;
        }
        .pagination-wrapper nav {
            gap: 3px;
        }
        .pagination-wrapper a,
        .pagination-wrapper span {
            padding: 3px 8px;
            font-size: 0.6rem;
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
        .finance-header {
            padding: 8px 12px;
        }
        .finance-header h2 {
            font-size: 0.8rem;
        }
        .form-card {
            padding: 10px 12px;
        }
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-group label {
            font-size: 0.55rem;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            font-size: 0.7rem;
            padding: 4px 8px;
            height: 30px;
        }
        .form-group textarea {
            min-height: 30px;
        }
        .btn-save {
            font-size: 0.7rem;
            padding: 5px 16px;
            height: 32px;
        }
        .btn-reset-form {
            font-size: 0.7rem;
            padding: 5px 16px;
            height: 32px;
        }
        .btn-search, .btn-reset {
            font-size: 0.6rem;
            padding: 4px 12px;
            height: 30px;
        }
        .btn-group-create {
            font-size: 0.55rem;
            height: 30px;
        }
        .data-table {
            font-size: 0.55rem;
            min-width: 380px;
        }
        .data-table th,
        .data-table td {
            padding: 3px 6px;
        }
        .data-table th {
            font-size: 0.5rem;
        }
        .badge-active,
        .badge-inactive {
            font-size: 0.45rem;
            padding: 1px 8px;
        }
        .table-header h4 {
            font-size: 0.7rem;
        }
        .modal-header h3 {
            font-size: 0.75rem;
        }
        .modal-body {
            padding: 12px;
        }
        .modal-footer {
            flex-direction: column;
        }
        .modal-footer .btn-save,
        .modal-footer .btn-cancel {
            width: 100%;
            justify-content: center;
        }
        .search-actions {
            flex-direction: column;
        }
        .search-actions .btn-search,
        .search-actions .btn-reset {
            width: 100%;
        }
    }
    
    /* ===== PRINT ===== */
    @media print {
        .sidebar, .top-header, .filter-card, .search-card, .form-card,
        .btn-print, .btn-csv, .btn-save, .btn-reset-form, .btn-group-create,
        .action-btns, .table-footer, .no-print, .search-actions,
        .form-actions, .modal-overlay, .finance-header .key-hint {
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
        .finance-container {
            padding: 0 !important;
            margin: 0 !important;
            animation: none !important;
        }
        .finance-header {
            background: #1e3c72 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            padding: 8px 12px !important;
        }
        .table-wrapper {
            border: 1px solid #000 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
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
        .badge-active,
        .badge-inactive {
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

<div class="finance-container">
    <!-- ===== HEADER ===== -->
    <div class="finance-header">
        <h2>
            <i class="fas fa-book"></i>
            Ledger Creation
        </h2>
        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
            <span class="key-hint">
                <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>1</kbd> New Group
            </span>
        </div>
    </div>

    <!-- ===== LEDGER FORM ===== -->
    <div class="form-card">
        <form id="ledgerForm">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Ledger Name <span class="required">*</span></label>
                    <input type="text" name="ledger_name" id="ledger_name" placeholder="e.g. M/s Traders" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-code"></i> Ledger Code</label>
                    <input type="text" name="ledger_code" id="ledger_code" placeholder="e.g. LED001">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-folder"></i> Group <span class="required">*</span></label>
                    <div class="group-input-wrapper">
                        <select name="group_id" id="group_id" required>
                            <option value="">Select Group</option>
                            @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn-group-create" onclick="openGroupModal()" title="Ctrl+Shift+1">
                            <i class="fas fa-plus"></i> New
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Mobile</label>
                    <input type="text" name="mobile" id="mobile" placeholder="9876543210">
                </div>
            </div>
            
            <div class="form-grid" style="margin-top:8px;">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" id="email" placeholder="traders@email.com">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> GST No.</label>
                    <input type="text" name="gst_no" id="gst_no" placeholder="22AAAAA0000A1Z5">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-money-bill"></i> Opening Balance</label>
                    <input type="number" name="opening_balance" id="opening_balance" step="0.01" value="0">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-arrow-right"></i> Balance Type</label>
                    <select name="balance_type" id="balance_type">
                        <option value="debit">Debit</option>
                        <option value="credit">Credit</option>
                    </select>
                </div>
            </div>
            
            <div class="form-grid" style="margin-top:8px;">
                <div class="form-group" style="grid-column: span 2;">
                    <label><i class="fas fa-home"></i> Address</label>
                    <textarea name="address" id="address" rows="2" placeholder="Enter full address..."></textarea>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-circle"></i> Status</label>
                    <select name="status" id="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-comment"></i> Remarks</label>
                    <input type="text" name="remarks" id="remarks" placeholder="Any remarks...">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="reset" class="btn-reset-form">
                    <i class="fas fa-undo"></i> Reset
                </button>
                <button type="submit" class="btn-save" id="ledgerSubmitBtn">
                    <i class="fas fa-save"></i> <span id="ledgerBtnText">Save Ledger</span>
                </button>
            </div>
            
            <input type="hidden" id="ledger_edit_id" value="">
        </form>
    </div>

    <!-- ===== SEARCH & FILTER ===== -->
    <div class="search-card">
        <div class="search-row">
            <div class="search-item">
                <label><i class="fas fa-search"></i> Search</label>
                <input type="text" id="searchInput" placeholder="Search ledgers..." value="{{ request('search') }}">
            </div>
            <div class="search-item">
                <label><i class="fas fa-folder"></i> Group</label>
                <select id="groupFilter">
                    <option value="">All Groups</option>
                    @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                        {{ $group->group_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="search-item">
                <label><i class="fas fa-circle"></i> Status</label>
                <select id="statusFilter">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="search-actions">
                <button class="btn-search" onclick="applyFilters()">
                    <i class="fas fa-search"></i> Search
                </button>
                <button class="btn-reset" onclick="resetFilters()">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- ===== LEDGER LIST ===== -->
    <div class="table-wrapper" id="ledgerTableWrapper">
        <div class="table-header">
            <h4>
                <i class="fas fa-list"></i> Ledger List
                <span class="count-badge">{{ $ledgers->total() }}</span>
            </h4>
        </div>
        
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Ledger Name</th>
                        <th>Group</th>
                        <th>Mobile</th>
                        <th>Opening Balance</th>
                        <th>Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody id="ledgerTableBody">
                    @if($ledgers->count() > 0)
                        @foreach($ledgers as $index => $ledger)
                        <tr>
                            <td>{{ $ledgers->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $ledger->ledger_name }}</strong>
                              
                            </td>
                            <td>{{ $ledger->group->group_name ?? 'N/A' }}</td>
                            <td>{{ $ledger->mobile ?? '-' }}</td>
                            <td>
                                <span class="badge-{{ $ledger->balance_type ?? 'debit' }}">
                                    ₹{{ number_format($ledger->opening_balance ?? 0, 2) }}
                                    {{ ucfirst($ledger->balance_type ?? 'Debit') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-{{ $ledger->status == 'active' ? 'active' : 'inactive' }}">
                                    {{ ucfirst($ledger->status ?? 'Active') }}
                                </span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <button class="btn-edit" onclick="editLedger({{ $ledger->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-delete" onclick="deleteLedger({{ $ledger->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">
                                <div class="no-data">
                                    <i class="fas fa-book"></i>
                                    <p>No ledgers found</p>
                                    <small>Try adjusting your search filters</small>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if($ledgers->hasPages())
        <div class="pagination-wrapper">
            {{ $ledgers->links() }}
        </div>
        @endif
    </div>
</div>

<!-- ============================================ -->
<!-- GROUP MODAL -->
<!-- ============================================ -->
<div class="modal-overlay" id="groupModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-folder-plus"></i> <span id="groupModalTitle">Create New Group</span></h3>
            <button class="modal-close" onclick="closeGroupModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="groupForm">
            <div class="modal-body">
                <input type="hidden" id="group_edit_id" value="">
                
                <div class="form-group" style="margin-bottom:12px;">
                    <label>Group Name <span class="required">*</span></label>
                    <input type="text" name="group_name" id="group_name" placeholder="e.g. Sundry Debtors" required>
                </div>
                <div class="form-group" style="margin-bottom:12px;">
                    <label>Group Code</label>
                    <input type="text" name="group_code" id="group_code" placeholder="e.g. GRP001">
                </div>
                <div class="form-group" style="margin-bottom:12px;">
                    <label>Description</label>
                    <textarea name="description" id="group_description" rows="2" placeholder="Group description..."></textarea>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Status</label>
                    <select name="status" id="group_status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-reset-form" onclick="closeGroupModal()">
                    Cancel
                </button>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> <span id="groupBtnText">Save Group</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================ -->
<!-- EDIT LEDGER MODAL -->
<!-- ============================================ -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content" style="max-width:700px;">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Edit Ledger</h3>
            <button class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editLedgerForm">
            <div class="modal-body">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_ledger_id" value="">
                
                <div class="form-grid" style="grid-template-columns:1fr 1fr;">
                    <div class="form-group">
                        <label>Ledger Name <span class="required">*</span></label>
                        <input type="text" name="ledger_name" id="edit_ledger_name" required>
                    </div>
                    <div class="form-group">
                        <label>Ledger Code</label>
                        <input type="text" name="ledger_code" id="edit_ledger_code">
                    </div>
                </div>
                
                <div class="form-grid" style="grid-template-columns:1fr 1fr; margin-top:8px;">
                    <div class="form-group">
                        <label>Group <span class="required">*</span></label>
                        <select name="group_id" id="edit_group_id" required>
                            <option value="">Select Group</option>
                            @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mobile</label>
                        <input type="text" name="mobile" id="edit_mobile">
                    </div>
                </div>
                
                <div class="form-grid" style="grid-template-columns:1fr 1fr; margin-top:8px;">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_email">
                    </div>
                    <div class="form-group">
                        <label>GST No.</label>
                        <input type="text" name="gst_no" id="edit_gst_no">
                    </div>
                </div>
                
                <div class="form-grid" style="grid-template-columns:1fr 1fr; margin-top:8px;">
                    <div class="form-group">
                        <label>Opening Balance</label>
                        <input type="number" name="opening_balance" id="edit_opening_balance" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Balance Type</label>
                        <select name="balance_type" id="edit_balance_type">
                            <option value="debit">Debit</option>
                            <option value="credit">Credit</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-grid" style="grid-template-columns:1fr 1fr; margin-top:8px;">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="edit_status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Remarks</label>
                        <input type="text" name="remarks" id="edit_remarks">
                    </div>
                </div>
                
                <div class="form-group" style="margin-top:8px;">
                    <label>Address</label>
                    <textarea name="address" id="edit_address" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-reset-form" onclick="closeEditModal()">
                    Cancel
                </button>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Update Ledger
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ============================================
// KEYBOARD SHORTCUT - Ctrl+Shift+1
// ============================================
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.shiftKey && e.key === '1') {
        e.preventDefault();
        openGroupModal();
    }
});

// ============================================
// GROUP MODAL FUNCTIONS
// ============================================
function openGroupModal() {
    document.getElementById('groupModal').classList.add('active');
    document.getElementById('group_edit_id').value = '';
    document.getElementById('groupModalTitle').textContent = 'Create New Group';
    document.getElementById('groupBtnText').textContent = 'Save Group';
    document.getElementById('groupForm').reset();
}

function closeGroupModal() {
    document.getElementById('groupModal').classList.remove('active');
    document.getElementById('groupForm').reset();
}

// ============================================
// SAVE GROUP (AJAX)
// ============================================
document.getElementById('groupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    const editId = document.getElementById('group_edit_id').value;
    const url = editId ? `/finance/groups/${editId}` : '/finance/groups/store';
    const method = editId ? 'PUT' : 'POST';
    
    Swal.fire({
        title: 'Saving...',
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
        if (response.success) {
            const select = document.getElementById('group_id');
            const option = document.createElement('option');
            option.value = response.group.id;
            option.textContent = response.group.group_name;
            select.appendChild(option);
            select.value = response.group.id;
            
            const filterSelect = document.getElementById('groupFilter');
            const filterOption = document.createElement('option');
            filterOption.value = response.group.id;
            filterOption.textContent = response.group.group_name;
            filterSelect.appendChild(filterOption);
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message,
                timer: 1000,
                showConfirmButton: false
            });
            
            closeGroupModal();
        } else {
            Swal.fire('Error!', response.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error!', 'Something went wrong!', 'error');
        console.error('Error:', error);
    });
});

// ============================================
// SAVE LEDGER (AJAX)
// ============================================
document.getElementById('ledgerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    const editId = document.getElementById('ledger_edit_id').value;
    const url = editId ? `/ledgers/${editId}` : '/ledgers/store';
    const method = editId ? 'PUT' : 'POST';
    
    if (!data.ledger_name || !data.group_id) {
        Swal.fire('Error', 'Please fill all required fields', 'error');
        return;
    }
    
    Swal.fire({
        title: 'Saving...',
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
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message,
                timer: 1500,
                showConfirmButton: false
            });
            
            document.getElementById('ledger_edit_id').value = '';
            document.getElementById('ledgerBtnText').textContent = 'Save Ledger';
            document.getElementById('ledgerForm').reset();
            
            setTimeout(() => location.reload(), 1500);
        } else {
            Swal.fire('Error!', response.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error!', 'Something went wrong!', 'error');
        console.error('Error:', error);
    });
});

// ============================================
// EDIT LEDGER - Open Modal
// ============================================
function editLedger(id) {
    Swal.fire({
        title: 'Loading...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch(`/ledgers/${id}/edit`)
        .then(response => response.json())
        .then(response => {
            Swal.close();
            
            if (response.success) {
                const ledger = response.ledger;
                
                document.getElementById('edit_ledger_id').value = ledger.id;
                document.getElementById('edit_ledger_name').value = ledger.ledger_name;
                document.getElementById('edit_ledger_code').value = ledger.ledger_code || '';
                document.getElementById('edit_group_id').value = ledger.group_id;
                document.getElementById('edit_mobile').value = ledger.mobile || '';
                document.getElementById('edit_email').value = ledger.email || '';
                document.getElementById('edit_address').value = ledger.address || '';
                document.getElementById('edit_gst_no').value = ledger.gst_no || '';
                document.getElementById('edit_opening_balance').value = ledger.opening_balance;
                document.getElementById('edit_balance_type').value = ledger.balance_type;
                document.getElementById('edit_status').value = ledger.status;
                document.getElementById('edit_remarks').value = ledger.remarks || '';
                
                document.getElementById('editModal').classList.add('active');
            } else {
                Swal.fire('Error', 'Failed to load ledger data', 'error');
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire('Error', 'Something went wrong!', 'error');
            console.error('Error:', error);
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
    document.getElementById('editLedgerForm').reset();
}

// ============================================
// UPDATE LEDGER (AJAX)
// ============================================
document.getElementById('editLedgerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    const id = document.getElementById('edit_ledger_id').value;
    
    if (!data.ledger_name || !data.group_id) {
        Swal.fire('Error', 'Please fill all required fields', 'error');
        return;
    }
    
    Swal.fire({
        title: 'Updating...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch(`/ledgers/${id}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message,
                timer: 1500,
                showConfirmButton: false
            });
            
            closeEditModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            Swal.fire('Error!', response.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error!', 'Something went wrong!', 'error');
        console.error('Error:', error);
    });
});

// ============================================
// DELETE LEDGER
// ============================================
function deleteLedger(id) {
    Swal.fire({
        title: 'Delete Ledger?',
        text: 'Are you sure you want to delete this ledger?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Delete'
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/ledgers/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            });
        }
    });
}

// ============================================
// SEARCH & FILTER
// ============================================
function applyFilters() {
    const search = document.getElementById('searchInput').value;
    const groupId = document.getElementById('groupFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    let url = '{{ route("finance.ledger.creation") }}?';
    if (search) url += `search=${encodeURIComponent(search)}&`;
    if (groupId) url += `group_id=${groupId}&`;
    if (status) url += `status=${status}&`;
    
    window.location.href = url;
}

function resetFilters() {
    window.location.href = '{{ route("finance.ledger.creation") }}';
}

// Enter key for search
document.getElementById('searchInput').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});

// Close modals on overlay click
document.getElementById('groupModal').addEventListener('click', function(e) {
    if (e.target === this) closeGroupModal();
});
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (document.getElementById('groupModal').classList.contains('active')) closeGroupModal();
        if (document.getElementById('editModal').classList.contains('active')) closeEditModal();
    }
});
</script>
@endsection