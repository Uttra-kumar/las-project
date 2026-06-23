@extends('layouts.app')

@section('title', 'Control Panel')

@section('content')
<style>
    .cp-container {
        animation: fadeIn 0.3s ease;
    }

    /* Compact Header */
    .cp-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    a{text-decoration: none;}
    .cp-header h1 {
        font-size: 1.2rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cp-header p {
        font-size: 0.7rem;
        opacity: 0.9;
        margin: 0;
    }

    .cp-badge {
        background: rgba(255,255,255,0.2);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
    }

    /* Super Compact Grid - 12 items */
    .cp-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    /* Compact Card */
    .cp-card {
        background: white;
        border-radius: 10px;
        padding: 3px 5px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: block;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
    }

    .cp-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-color: transparent;
    }

    /* Compact Icons */
    .cp-icon {
        width: 40px;
        height: 40px;
        margin: 0 auto 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.2rem;
        transition: all 0.2s;
        color: white;
    }

    .cp-card:hover .cp-icon {
        transform: scale(1.05);
    }

    .cp-title {
        font-size: 0.7rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 2px;
    }

    .cp-desc {
        font-size: 0.55rem;
        color: #64748b;
    }

    /* Icon Colors - Row 1 */
    .ic-users { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .ic-session { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .ic-logs { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .ic-settings { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    .ic-database { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    .ic-receipt { background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); }
    .ic-delete { background: linear-gradient(135deg, #f87171 0%, #ef4444 100%); }
    /* Icon Colors - Row 2 (New) */
    .ic-fees { background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%); }
    .ic-academic { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
    .ic-report { background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); }
    .ic-notify { background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); }
    .ic-backup { background: linear-gradient(135deg, #84cc16 0%, #4d7c0f 100%); }
    .ic-maintenance { background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); }

    /* Compact Stats */
    .cp-stats {
        background: white;
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
    }

    .cp-stats h3 {
        font-size: 0.8rem;
        margin-bottom: 12px;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    .stat-item {
        background: #f8fafc;
        padding: 8px 10px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stat-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        color: white;
    }

    .stat-info h4 {
        font-size: 0.65rem;
        color: #64748b;
        margin-bottom: 2px;
    }

    .stat-info p {
        font-size: 0.9rem;
        font-weight: bold;
        color: #1e293b;
        margin: 0;
    }

    /* Compact Quick Actions */
    .cp-actions {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    .action-btn {
        background: white;
        border: 1px solid #e2e8f0;
        padding: 8px 12px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.7rem;
        color: #1e293b;
    }

    .action-btn:hover {
        background: #f8fafc;
        border-color: #667eea;
    }

    .action-btn i {
        font-size: 0.8rem;
        color: #667eea;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .cp-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 900px) {
        .cp-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
        .cp-actions {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .cp-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .stats-row {
            grid-template-columns: 1fr;
        }
        .cp-actions {
            grid-template-columns: 1fr;
        }
        .cp-header {
            flex-direction: column;
            text-align: center;
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="cp-container">
    <!-- Compact Header -->
    <div class="cp-header">
        <div>
            <h1><i class="fas fa-laptop-code"></i> Control Panel</h1>
            <p>System administration & configuration</p>
        </div>
        <div class="cp-badge">
            <i class="fas fa-shield-alt"></i> Admin Access
        </div>
    </div>

    <!-- 12 Icons Grid (6 + 6 New) -->
    <div class="cp-grid">
        <!-- Row 1 - Existing -->
        <a href="{{ route('users.index') }}" class="cp-card">
            <div class="cp-icon ic-users"><i class="fas fa-users"></i></div>
            <div class="cp-title">User Management</div>
        </a>

        <a href="{{ route('sessions.index') }}" class="cp-card">
            <div class="cp-icon ic-session"><i class="fas fa-calendar-alt"></i></div>
            <div class="cp-title">Session</div>
        </a>

        <a href="{{ route('edit.logs') }}" class="cp-card">
            <div class="cp-icon ic-logs"><i class="fas fa-history"></i></div>
            <div class="cp-title">Edit Logs</div>
        </a>

        <a href="{{ route('settings.index') }}" class="cp-card">
            <div class="cp-icon ic-settings"><i class="fas fa-cog"></i></div>
            <div class="cp-title">School Settings</div>
         
        </a>

        <a href="{{ route('backup.index') }}" class="cp-card">
            <div class="cp-icon ic-database"><i class="fas fa-database"></i></div>
            <div class="cp-title">Backup</div>
        </a>

        <a href="{{ route('receipt.setting') }}" class="cp-card">
            <div class="cp-icon ic-receipt"><i class="fas fa-receipt"></i></div>
            <div class="cp-title">Receipt</div>
        </a>

        <!-- Row 2 - New 6 Items -->
        <a href="{{ route('delete.logs') }}" class="cp-card">
            <div class="cp-icon ic-delete"><i class="fas fa-trash-alt"></i></div>
            <div class="cp-title">Delete Logs</div>
        </a>

        <a href="{{ route('control-panel.notice') }}" class="cp-card" >
            <div class="cp-icon ic-academic"><i class="fas fa-bullhorn"></i></div>
            <div class="cp-title">Notice</div>
        </a>

        <a href="{{ route('control-panel.gallery') }}" class="cp-card" >
            <div class="cp-icon ic-report"><i class="fas fa-images"></i></div>
            <div class="cp-title">Gallery</div>
        </a>

        <a href="{{route('control-panel.query')}}" class="cp-card">
            <div class="cp-icon ic-notify"><i class="fas fa-bell"></i></div>
            <div class="cp-title">Query</div>
        </a>

        <div class="cp-card" onclick="showComingSoon('Backup')">
            <div class="cp-icon ic-backup"><i class="fas fa-database"></i></div>
            <div class="cp-title">Backup</div>
        </div>

        <a href="{{ route('control-panel.license-history') }}" class="cp-card" >
            <div class="cp-icon ic-maintenance"><i class="fas fa-tools"></i></div>
            <div class="cp-title">License</div>
        </a>
    </div>

  
    <!-- Compact Actions -->
    
</div>

<script>
    function showComingSoon(feature) {
        Swal.fire({
            title: feature,
            text: 'Coming soon!',
            icon: 'info',
            confirmButtonColor: '#667eea',
            timer: 1500,
            showConfirmButton: false
        });
    }
</script>
@endsection