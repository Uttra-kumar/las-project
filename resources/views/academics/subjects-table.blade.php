<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th width="60">S.No</th>
                <th width="100">Subject ID</th>
                <th width="200">Subject Name</th>
                <th width="100">Status</th>
                <th width="100">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subjects as $key => $subject)
            <tr>
                <td style="text-align: center;">{{ $subjects->firstItem() + $key }}</td>
                <td><span class="subject-id-badge">{{ $subject->subject_id }}</span></td>
                <td><strong>{{ $subject->subject_name }}</strong></td>
                <td>
                    <span class="status-badge status-{{ $subject->status }}">
                        {{ ucfirst($subject->status) }}
                    </span>
                </td>
                <td>
                    @if(Auth::user()->role=='admin')
                    <div class="action-btns">
                        <button class="btn-edit" onclick="openEditModal({{ $subject->id }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-delete" onclick="deleteSubject({{ $subject->id }}, '{{ $subject->subject_name }}')">
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
                    <p style="margin-top: 10px; color: #94a3b8;">No subjects found</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($subjects->hasPages())
    <div class="pagination">
        {{ $subjects->links() }}
    </div>
    @endif
</div>