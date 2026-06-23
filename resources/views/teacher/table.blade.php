<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr><th>#</th><th>Teacher ID</th><th>Name</th><th>Mobile</th><th>Email</th><th>Experience</th><th>Status</th><th>Action</th></tr>
        </thead>
        <tbody>
            @forelse($teachers as $teacher)
            <tr>
               <td>{{ $teachers->firstItem() + $loop->index }}</td>
                <td><span class="teacher-id-badge">{{ $teacher->teacher_id }}</span></td>
                <td><i class="fas fa-user-graduate"></i> {{ $teacher->full_name }}</td>
                <td>{{ $teacher->mobile }}</td>
                <td>{{ $teacher->email ?? '-' }}</td>
                <td>{{ $teacher->experience }} yrs</td>
                <td>
                @if($teacher->status == 1)
                    <span class="badge-active">
                        <i class="fas fa-circle" style="font-size: 0.3rem;"></i> Active
                    </span>
                @else
                    <span class="badge-inactive">
                        <i class="fas fa-circle" style="font-size: 0.3rem;"></i> Inactive
                    </span>
                @endif
            </td>
                <td>
                    <div class="action-btns">
                        <button class="btn-view" onclick="viewTeacher({{ $teacher->id }})"><i class="fas fa-eye"></i></button>
                        @if(Auth::user()->role=="admin")
                        <button class="btn-edit" onclick="editTeacher({{ $teacher->id }})"><i class="fas fa-edit"></i></button>
                        <button class="btn-delete" onclick="deleteTeacher({{ $teacher->id }}, '{{ $teacher->full_name }}')"><i class="fas fa-trash-alt"></i></button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center; padding:40px;">No teachers found</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($teachers->hasPages()) <div class="pagination">{{ $teachers->links() }}</div> @endif
</div>

<script>
function viewTeacher(id) { window.open(`/teacher/${id}`, '_blank'); }
function editTeacher(id) { window.location.href = `/teacher/edit/${id}`; }
function deleteTeacher(id, name) {
    Swal.fire({ title: 'Delete?', text: `Delete ${name}?`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Yes' })
    .then((result) => {
        if(result.isConfirmed) {
            fetch(`/teacher/delete/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
            .then(res => res.json()).then(data => { if(data.success) { Swal.fire('Deleted!', data.success, 'success').then(() => location.reload()); } });
        }
    });
}
</script>