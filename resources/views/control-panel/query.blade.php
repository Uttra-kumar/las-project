@extends('layouts.app')

@section('title', 'Query Management')

@section('content')
<style>
    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .query-container {
        animation: fadeInUp 0.35s ease;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ===== HEADER ===== */
    .query-header {
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
    .query-header h2 {
        font-size: 0.95rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }
    .query-header h2 i {
        color: #fbbf24;
    }
    .header-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
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
        color: white;
    }

    /* ===== FILTER ===== */
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
        min-width: 700px;
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
    .badge-query {
        background: #fef3c7;
        color: #b45309;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 0.55rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-solve {
        background: #dcfce7;
        color: #15803d;
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
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
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
        .query-header {
            padding: 10px 14px;
            flex-direction: column;
            text-align: center;
        }
        .query-header h2 {
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
        .query-header {
            padding: 8px 12px;
        }
        .query-header h2 {
            font-size: 0.8rem;
        }
        .btn-csv {
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
        .badge-query, .badge-solve {
            font-size: 0.5rem;
            padding: 1px 8px;
        }
        .header-actions {
            flex-direction: column;
            align-items: stretch;
        }
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
        .sidebar, .top-header, .filter-card, .btn-csv,
        .btn-filter, .btn-reset, .action-btns, .modal-overlay,
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
        .query-container {
            padding: 0 !important;
            margin: 0 !important;
            animation: none !important;
            max-width: 100% !important;
        }
        .query-header {
            background: #1a365d !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            padding: 8px 12px !important;
        }
        .query-header h2 {
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
        .badge-query, .badge-solve {
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

<div class="query-container">
    <!-- ===== HEADER ===== -->
    <div class="query-header">
        <h2>
            <i class="fas fa-question-circle"></i>
            Query Management
        </h2>
        <div class="header-actions">
            <a href="{{ route('control-panel.query.export', request()->query()) }}" class="btn-csv">
                <i class="fas fa-file-csv"></i> CSV
            </a>
        </div>
    </div>

    <!-- ===== FILTER ===== -->
    <div class="filter-card">
        <form method="GET" class="filter-row">
            <div class="filter-item">
                <label><i class="fas fa-search"></i> Search</label>
                <input type="text" name="search" placeholder="Name, Mobile, Email..." value="{{ request('search') }}">
            </div>
            <div class="filter-item">
                <label><i class="fas fa-circle"></i> Status</label>
                <select name="status">
                    <option value="">-- All --</option>
                    <option value="query" {{ request('status') == 'query' ? 'selected' : '' }}>⏳ Query</option>
                    <option value="solve" {{ request('status') == 'solve' ? 'selected' : '' }}>✅ Solve</option>
                </select>
            </div>
            <div class="filter-item filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('control-panel.query') }}" class="btn-reset">
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
                        <th width="140">Date</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th width="100">Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($queries as $index => $q)
                    <tr>
                        <td>{{ $queries->firstItem() + $index }}</td>
                        <td>{{ $q->created_at ? date('d-m-Y h:i A', strtotime($q->created_at)) : '-' }}</td>
                        <td><strong>{{ $q->name }}</strong></td>
                        <td>{{ $q->mobile }}</td>
                        <td>{{ $q->email }}</td>
                        <td>{{ Str::limit($q->subject, 25) }}</td>
                        <td>
                            <span class="badge-{{ $q->status }}">
                                {{ ucfirst($q->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-view" onclick="viewQuery({{ $q->id }})" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-edit" onclick="editQuery({{ $q->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if(Auth::user()->role=="admin")
                                <button class="btn-delete" onclick="deleteQuery({{ $q->id }})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="no-data">
                            <i class="fas fa-question-circle"></i>
                            <p>No queries found</p>
                            <small>Try adjusting your search filters</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($queries->hasPages())
        <div class="pagination-wrapper">
            {{ $queries->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- ============================================ -->
<!-- VIEW MODAL -->
<!-- ============================================ -->
<div class="modal-overlay" id="viewModal">
    <div class="modal-content" style="max-width:550px;">
        <div class="modal-header">
            <h3><i class="fas fa-eye"></i> Query Details</h3>
            <button class="modal-close" onclick="closeViewModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="view-detail" id="viewContent">
                <div class="row"><span class="label">Date:</span><span class="value" id="view_date">-</span></div>
                <div class="row"><span class="label">Name:</span><span class="value" id="view_name">-</span></div>
                <div class="row"><span class="label">Mobile:</span><span class="value" id="view_mobile">-</span></div>
                <div class="row"><span class="label">Email:</span><span class="value" id="view_email">-</span></div>
                <div class="row"><span class="label">Subject:</span><span class="value" id="view_subject">-</span></div>
                <div class="row"><span class="label">Message:</span><span class="value" id="view_message">-</span></div>
                <div class="row"><span class="label">Status:</span><span class="value" id="view_status">-</span></div>
                <div class="row"><span class="label">Remarks:</span><span class="value" id="view_remarks">-</span></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeViewModal()">Close</button>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- EDIT MODAL -->
<!-- ============================================ -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Update Query</h3>
            <button class="modal-close" onclick="closeEditModal()"><i class="fas fa-times"></i></button>
        </div>
        <form id="editForm">
            <div class="modal-body">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id">
                
                <div class="form-group">
                    <label><i class="fas fa-circle"></i> Status</label>
                    <select name="status" id="edit_status">
                        <option value="query">⏳ Query</option>
                        <option value="solve">✅ Solve</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-comment"></i> Remarks</label>
                    <textarea name="remarks" id="edit_remarks" rows="3" placeholder="Add remarks..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ============================================
// VIEW QUERY
// ============================================
function viewQuery(id) {
    fetch(`/control-panel/query/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const q = data.query;
                document.getElementById('view_date').textContent = q.created_at ? new Date(q.created_at).toLocaleString('en-IN') : '-';
                document.getElementById('view_name').textContent = q.name;
                document.getElementById('view_mobile').textContent = q.mobile;
                document.getElementById('view_email').textContent = q.email;
                document.getElementById('view_subject').textContent = q.subject;
                document.getElementById('view_message').textContent = q.message;
                document.getElementById('view_status').textContent = q.status.charAt(0).toUpperCase() + q.status.slice(1);
                document.getElementById('view_remarks').textContent = q.remarks || '-';
                document.getElementById('viewModal').classList.add('active');
            }
        })
        .catch(error => console.error('Error:', error));
}

function closeViewModal() {
    document.getElementById('viewModal').classList.remove('active');
}

// ============================================
// EDIT QUERY
// ============================================
function editQuery(id) {
    fetch(`/control-panel/query/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const q = data.query;
                document.getElementById('edit_id').value = q.id;
                document.getElementById('edit_status').value = q.status;
                document.getElementById('edit_remarks').value = q.remarks || '';
                document.getElementById('editModal').classList.add('active');
            }
        })
        .catch(error => console.error('Error:', error));
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}

// ============================================
// UPDATE QUERY
// ============================================
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const data = {
        status: document.getElementById('edit_status').value,
        remarks: document.getElementById('edit_remarks').value,
        _token: '{{ csrf_token() }}',
        _method: 'PUT'
    };
    const id = document.getElementById('edit_id').value;
    
    const btn = this.querySelector('.btn-save');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    
    Swal.fire({
        title: 'Updating...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch(`/control-panel/query/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(response => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Update';
        
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'Updated! 🎉',
                text: response.message,
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
            closeEditModal();
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
        btn.innerHTML = '<i class="fas fa-save"></i> Update';
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
// DELETE QUERY
// ============================================
function deleteQuery(id) {
    Swal.fire({
        title: 'Delete Query?',
        text: 'Are you sure you want to delete this query?',
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
            
            fetch(`/control-panel/query/${id}`, {
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
// CLOSE MODALS
// ============================================
document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) closeViewModal();
});
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeViewModal();
        closeEditModal();
    }
});

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