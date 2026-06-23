<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th width="60">S.No</th>
                <th width="100">Master ID</th>
                <th width="40">Class</th>
                <th width="100">Fee Type</th>
                <th width="100">Amount</th>
                <th width="80">Status</th>
                <th width="130" >Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($feesMasters as $key => $fee)
            <tr>
                <td style="text-align: center;">{{ $feesMasters->firstItem() + $key }}</td>
                <td><span class="master-id-badge">{{ $fee->master_id }}</span></td>
                <td><span class="class-badge">{{ $fee->class->class_name ?? 'N/A' }}</span></td>
                <td><span class="feetype-badge">{{ $fee->feesType->name ?? 'N/A' }}</span></td>
                <td><span class="amount-badge">₹ {{ number_format($fee->amount, 2) }}</span></td>
                <td>
                    <span class="status-badge status-{{ $fee->status }}">
                        {{ ucfirst($fee->status) }}
                    </span>
                </td>
                <td>
                    <div class="action-btns">
                        <button class="btn-view" onclick="viewFeesMaster({{ $fee->id }})">
                            <i class="fas fa-eye"></i> View
                        </button>
                        @if(Auth::user()->role=='admin')
                        <button class="btn-edit" onclick="openEditModal({{ $fee->id }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-delete" onclick="deleteFeesMaster({{ $fee->id }})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px;">
                    <i class="fas fa-layer-group" style="font-size: 2rem; color: #cbd5e1;"></i>
                    <p style="margin-top: 10px; color: #94a3b8;">No fee structures found for this session</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($feesMasters->hasPages())
    <div class="pagination">
        {{ $feesMasters->links() }}
    </div>
    @endif
</div>