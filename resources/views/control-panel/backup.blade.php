@extends('layouts.app')

@section('title', 'Database Backup')

@section('content')
<style>
    .backup-container {
        animation: fadeIn 0.3s ease;
        max-width: 1000px;
        margin: 0 auto;
    }

    .backup-header {
        background: linear-gradient(135deg, #1e3c72 0%, #0f2b4d 100%);
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

    .backup-header h2 {
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-create-backup {
        background: #10b981;
        border: none;
        color: white;
        padding: 8px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-create-backup:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16,185,129,0.4);
    }

    .btn-create-backup:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .backup-info {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.8rem;
        color: #166534;
    }

    .backup-info i {
        font-size: 1.2rem;
    }

    .table-wrapper {
        background: white;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }

    .data-table th {
        text-align: left;
        padding: 12px 15px;
        background: #f8fafc;
        font-weight: 600;
        color: #1e293b;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table td {
        padding: 10px 15px;
        border-bottom: 1px solid #e2e8f0;
        color: #475569;
    }

    .data-table tr:hover {
        background: #f8fafc;
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .badge-date {
        background: #e0e7ff;
        color: #4338ca;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.7rem;
        font-family: monospace;
    }

    .badge-size {
        background: #f1f5f9;
        color: #475569;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.7rem;
    }

    .action-btns {
        display: flex;
        gap: 8px;
    }

    .btn-download, .btn-delete {
        padding: 5px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-download {
        background: #dbeafe;
        color: #2563eb;
    }

    .btn-download:hover {
        background: #bfdbfe;
    }

    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
    }

    .no-data i {
        font-size: 2.5rem;
        display: block;
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .backup-header {
            flex-direction: column;
            text-align: center;
        }
        .btn-create-backup {
            width: 100%;
            justify-content: center;
        }
        .data-table {
            font-size: 0.7rem;
        }
        .data-table th, .data-table td {
            padding: 8px 10px;
        }
    }
</style>

<div class="backup-container">
    <div class="backup-header">
        <h2>
            <i class="fas fa-database"></i> Database Backup
        </h2>
        <button class="btn-create-backup" id="createBackupBtn" onclick="createBackup()">
            <i class="fas fa-plus-circle"></i> Create Backup
        </button>
    </div>

    <div class="backup-info">
        <i class="fas fa-info-circle"></i>
        <span>Backups are stored in <strong>storage/app/backups</strong> directory. Each backup contains the complete database dump.</span>
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Backup Name</th>
                    <th>Created Date</th>
                    <th>Size</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody id="backupTableBody">
                @forelse($backups as $key => $backup)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td><span class="badge-date">{{ $backup['filename'] }}</span></td>
                   <td>{{ date('d-m-Y h:i:s A', strtotime($backup['created_at'])) }}</td>
                    <td><span class="badge-size">{{ $backup['size_formatted'] }}</span></td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('backup.download', $backup['filename']) }}" class="btn-download">
                                <i class="fas fa-download"></i> Download
                            </a>
                            <button class="btn-delete" onclick="deleteBackup('{{ $backup['filename'] }}')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="no-data">
                            <i class="fas fa-database"></i>
                            <p>No backups found. Click "Create Backup" to create your first backup.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function createBackup() {
        const btn = document.getElementById('createBackupBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';

        Swal.fire({
            title: 'Creating Backup...',
            text: 'Please wait while we create the database backup',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('{{ route("backup.create") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Backup Created!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed!',
                    text: data.message
                });
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-plus-circle"></i> Create Backup';
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Something went wrong!'
            });
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus-circle"></i> Create Backup';
        });
    }

    function deleteBackup(filename) {
        Swal.fire({
            title: 'Delete Backup?',
            text: 'Are you sure you want to delete this backup? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Delete!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/backup/delete/${filename}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong!'
                    });
                });
            }
        });
    }
</script>
@endsection