<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Stream ID</th>
                <th>Class</th>
                <th>Stream Name</th>
                <th>Subjects</th>
                <th>Status</th>
                <th width="100">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($streams as $key => $stream)
            @php
                $subjectIds = $stream->subjects ?? [];
                $subjectNames = \App\Models\Subject::whereIn('id', $subjectIds)->pluck('subject_name')->toArray();
            @endphp
            <tr>
                <td>{{ $streams->firstItem() + $key }} </td>
                <td><span class="stream-id-badge">{{ $stream->stream_id }}</span></td>
                <td><strong>{{ $stream->class->class_name ?? 'N/A' }}</strong></td>
                <td><strong>{{ $stream->stream_name }}</strong></td>
                <td>
                    @foreach($subjectNames as $name)
                    <span class="subject-tag">{{ $name }}</span>
                    @endforeach
                </td>
                <td>
                    <span class="status-badge status-{{ $stream->status }}">
                        {{ ucfirst($stream->status) }}
                    </span>
                </td>
                <td>
                    <div class="action-btns">
                        <button class="btn-edit" onclick="openEditModal({{ $stream->id }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-delete" onclick="deleteStream({{ $stream->id }}, '{{ $stream->stream_name }}')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px;">
                    <i class="fas fa-layer-group" style="font-size: 2rem; color: #cbd5e1;"></i>
                    <p style="margin-top: 10px; color: #94a3b8;">No streams found. Click "Add Stream" to create one.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($streams->hasPages())
    <div class="pagination">
        {{ $streams->links() }}
    </div>
    @endif
</div>