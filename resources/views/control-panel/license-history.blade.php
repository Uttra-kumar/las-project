{{-- resources/views/control-panel/license-history.blade.php --}}

@extends('layouts.app')

@section('title', 'License History')

@section('content')
<style>
    .history-container {
        animation: fadeIn 0.3s ease;
        max-width: 1400px;
        margin: 0 auto;
    }

    .history-header {
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

    .history-header h2 {
        font-size: 0.95rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }
    .history-header h2 i {
        color: #fbbf24;
    }

    .status-badge {
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-block;
    }
    .status-badge.active {
        background: #dcfce7;
        color: #15803d;
    }
    .status-badge.inactive {
        background: #fef3c7;
        color: #b45309;
    }
    .status-badge.expired {
        background: #fee2e2;
        color: #dc2626;
    }

    .action-badge {
        padding: 3px 12px;
        border-radius: 12px;
        font-size: 0.6rem;
        font-weight: 600;
        display: inline-block;
    }
    .action-badge.activated {
        background: #dbeafe;
        color: #1e40af;
    }
    .action-badge.renewed {
        background: #dcfce7;
        color: #15803d;
    }
    .action-badge.expired {
        background: #fee2e2;
        color: #dc2626;
    }
    .action-badge.updated {
        background: #fef3c7;
        color: #b45309;
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .summary-card {
        background: white;
        border-radius: 10px;
        padding: 14px 16px;
        border: 1px solid #e2e8f0;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .summary-card .number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e3c72;
    }
    .summary-card .label {
        font-size: 0.6rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-top: 4px;
    }

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
        min-width: 600px;
    }

    .data-table th {
        text-align: left;
        padding: 10px 14px;
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
        padding: 8px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .data-table tbody tr {
        transition: background 0.2s ease;
    }
    .data-table tbody tr:hover {
        background: #f8fafc;
    }

    .pagination-wrapper {
        padding: 12px 16px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: center;
        background: #fafbfc;
    }

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

    @media (max-width: 768px) {
        .history-header {
            flex-direction: column;
            text-align: center;
        }
        .data-table {
            font-size: 0.65rem;
            min-width: 500px;
        }
        .data-table th,
        .data-table td {
            padding: 6px 10px;
        }
        .summary-cards {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 480px) {
        .summary-cards {
            grid-template-columns: 1fr;
        }
        .data-table {
            font-size: 0.6rem;
            min-width: 400px;
        }
        .data-table th,
        .data-table td {
            padding: 4px 8px;
        }
        .data-table th {
            font-size: 0.5rem;
        }
    }
</style>

<div class="history-container">
    {{-- HEADER --}}
    <div class="history-header">
        <h2>
            <i class="fas fa-history"></i>
            License History
        </h2>
        <div>
            <span style="font-size:0.65rem; background:rgba(255,255,255,0.12); padding:3px 14px; border-radius:16px;">
                <i class="fas fa-key"></i> 
                {{ $license->status == 'active' ? '✅ Active' : ($license->status == 'expired' ? '❌ Expired' : '🔒 Inactive') }}
                @if($license->status == 'active' && $daysLeft !== null)
                    | {{ $daysLeft }} days left
                @endif
            </span>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="summary-cards">
        <div class="summary-card">
            <div class="number">{{ $history->total() }}</div>
            <div class="label">Total Activities</div>
        </div>
        <div class="summary-card" style="border-left: 3px solid #3b82f6;">
            <div class="number">{{ $history->where('action', 'activated')->count() }}</div>
            <div class="label">Activations</div>
        </div>
        <div class="summary-card" style="border-left: 3px solid #10b981;">
            <div class="number">{{ $history->where('action', 'renewed')->count() }}</div>
            <div class="label">Renewals</div>
        </div>
        <div class="summary-card" style="border-left: 3px solid #ef4444;">
            <div class="number">{{ $history->where('action', 'expired')->count() }}</div>
            <div class="label">Expirations</div>
        </div>
        <div class="summary-card" style="border-left: 3px solid #f59e0b;">
            <div class="number">{{ $currentExpiry ?? 'N/A' }}</div>
            <div class="label">Current Expiry</div>
        </div>
    </div>

    {{-- HISTORY TABLE --}}
    <div class="table-wrapper">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Action</th>
                        <th>Old Expiry</th>
                        <th>New Expiry</th>
                        <th>Status</th>
                        <th>Performed By</th>
                        <th>IP Address</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $index => $record)
                    <tr>
                        <td>{{ $history->firstItem() + $index }}</td>
                        <td>
                            <span class="action-badge {{ $record->action }}">
                                <i class="fas 
                                    {{ $record->action == 'activated' ? 'fa-unlock' : '' }}
                                    {{ $record->action == 'renewed' ? 'fa-sync-alt' : '' }}
                                    {{ $record->action == 'expired' ? 'fa-times-circle' : '' }}
                                    {{ $record->action == 'updated' ? 'fa-edit' : '' }}
                                "></i>
                                {{ ucfirst($record->action) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $oldExp = \App\Helpers\LicenseHelper::decrypt($record->old_expiry_date);
                            @endphp
                            {{ $oldExp ?? '-' }}
                        </td>
                        <td>
                            @php
                                $newExp = \App\Helpers\LicenseHelper::decrypt($record->new_expiry_date);
                            @endphp
                            {{ $newExp ?? '-' }}
                        </td>
                        <td>
                            <span class="status-badge {{ strtolower($record->status ?? 'inactive') }}">
                                {{ $record->status ?? 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            @if($record->user)
                                <i class="fas fa-user"></i> {{ $record->user->name ?? 'N/A' }}
                            @else
                                <span style="color:#94a3b8;">System</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-family:monospace; font-size:0.65rem;">
                                {{ $record->ip_address ?? '-' }}
                            </span>
                        </td>
                        <td style="white-space:nowrap;">
                            {{ $record->created_at ? $record->created_at->format('d-m-Y H:i:s') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="no-data">
                                <i class="fas fa-history"></i>
                                <p>No license history found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($history->hasPages())
        <div class="pagination-wrapper">
            {{ $history->links() }}
        </div>
        @endif
    </div>
</div>
@endsection