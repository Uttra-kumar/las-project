<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th width="60">S.No</th>
                <th width="120">Session ID</th>
                <th width="120">Session Name</th>
                <th width="100">Status</th>
                <th width="130">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sessions as $key => $session)
            <tr>
                <td style="text-align: center;">{{ $sessions->firstItem() + $key }}</td>
                <td><span class="session-id-badge">{{ $session->session_id }}</span></td>
                <td>
                    <strong>{{ $session->session_name }}</strong>
                    @if($session->status == 'active')
                        <span style="margin-left: 10px; font-size: 0.65rem; background: #dcfce7; color: #16a34a; padding: 2px 8px; border-radius: 10px;">
                            <i class="fas fa-check-circle"></i> Available
                        </span>
                    @endif
                </td>
                <td>
                    <span class="status-badge status-{{ $session->status }}">
                        {{ ucfirst($session->status) }}
                    </span>
                </td>
                <td>
                    <div class="action-btns">
                        <button class="btn-edit" onclick="openEditModal({{ $session->id }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-delete" onclick="deleteSession({{ $session->id }})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px;">
                    <i class="fas fa-calendar-alt" style="font-size: 2rem; color: #cbd5e1;"></i>
                    <p style="margin-top: 10px; color: #94a3b8;">No sessions found</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($sessions->hasPages())
    <div class="pagination">
        {{ $sessions->links() }}
    </div>
    @endif
</div>