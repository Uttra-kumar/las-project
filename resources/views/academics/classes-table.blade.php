<table class="data-table">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Class ID</th>
            <th>Class Name</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($classes as $key => $class)
        <tr>
            <td>{{ $classes->firstItem() + $key }} </td>
            <td><span class="class-id-badge">{{ $class->class_id }}</span></td>
            <td><strong>{{ $class->class_name }}</strong></td>
            <td>
                <span class="status-badge status-{{ $class->status }}">
                    {{ ucfirst($class->status) }}
                </span>
            </td>
            <td>
                @if(Auth::user()->role=='admin')
                <div class="action-btns">
                    <button class="btn-edit" onclick="openEditModal({{ $class->id }})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn-delete" onclick="deleteClass({{ $class->id }})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" style="text-align: center; padding: 40px;">
                <i class="fas fa-book" style="font-size: 2rem; color: #cbd5e1;"></i>
                <p style="margin-top: 10px; color: #94a3b8;">No classes found</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($classes->hasPages())
<div class="pagination">
    {{ $classes->links() }}
</div>
@endif