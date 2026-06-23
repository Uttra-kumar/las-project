<table class="users-table">
    <thead>
        <tr>
            <th style="width: 50px;">S.No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Role</th>
            <th>Status</th>
            <th style="width: 120px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $key => $user)
        <tr>
            <td style="text-align: center;">{{ $users->firstItem() + $key }}</td>
            <td>
                <i class="fas fa-user-circle" style="color: #667eea; margin-right: 8px;"></i>
                {{ $user->name }}
            </td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->mobile }}</td>
            <td>
                <span class="role-badge role-{{ $user->role }}">
                    {{ ucfirst($user->role) }}
                </span>
            </td>
            <td>
                <span class="status-{{ $user->status == 1 ? 'active' : 'inactive' }}"></span>
                {{ $user->status == 1 ? 'Active' : 'Inactive' }}
            </td>
            <td>
                 @if(Auth::user()->role=='admin')
                <div class="action-btns">
                    <button class="btn-edit" onclick="openEditModal({{ $user->id }})">
                        <i class="fas fa-edit"></i>
                    </button>
                    @if($user->id != auth()->id())
                    <button class="btn-delete" onclick="deleteUser({{ $user->id }})">
                        <i class="fas fa-trash"></i>
                    </button>
                    @endif
                </div>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align: center; padding: 40px;">
                <i class="fas fa-users" style="font-size: 2rem; color: #cbd5e1;"></i>
                <p style="margin-top: 10px; color: #94a3b8;">No users found</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($users->hasPages())
<div class="pagination">
    {{ $users->links() }}
</div>
@endif