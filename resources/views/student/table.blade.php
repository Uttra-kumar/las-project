<div class="table-wrapper">
    <table class="student-table">
        <thead>
            <tr>
                <th width="50">S.No</th>
               
                <th>Class</th>
                <th>Student Name</th>
                <th>Father Name</th>
                <th>DOB</th>
                <th>Gender</th>
                <th>Mobile</th>
                <th>Type</th>
                <th width="60">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $key => $student)
            <tr>
                <td>{{ $students->firstItem() + $key }}</td>
                
                <td><strong>{{ $student->class->class_name ?? 'N/A' }}</strong></td>
                <td>{{ $student->full_name }}</td>
                <td>{{ $student->father_name ?? '-' }}</td>
                <td>{{ $student->dob ? date('d-m-Y', strtotime($student->dob)) : '-' }}</td>
                <td>{{ $student->gender ?? '-' }}</td>
                <td>{{ $student->mobile }}</td>
                <td>
                    <span class="type-badge {{ $student->is_hosteler ? 'type-hostel' : 'type-nonhostel' }}">
                        {{ $student->is_hosteler ? 'Hosteler' : 'Non Hostel' }}
                    </span>
                </td>
                <td class="action-cell">
                    <button class="three-lines" onclick="toggleMenu({{ $student->id }})">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="dropdown-menu" id="menu-{{ $student->id }}">
                        <a onclick="viewProfile({{ $student->id }})">
                            <i class="fas fa-user-circle"></i> Profile
                        </a>
                        <a onclick="editStudent({{ $student->id }})">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a onclick="feesStudent({{ $student->id }})">
                            <i class="fas fa-rupee-sign"></i> Fees
                        </a>
                        @if(Auth::user()->role=='admin')
                        <a onclick="deleteStudent({{ $student->id }}, '{{ $student->full_name }}')">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 40px;">
                    <i class="fas fa-users" style="font-size: 2rem; color: #cbd5e1;"></i>
                    <p style="margin-top: 10px; color: #94a3b8;">No students found</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($students->hasPages())
    <div class="pagination">
        {{ $students->links() }}
    </div>
    @endif
</div>