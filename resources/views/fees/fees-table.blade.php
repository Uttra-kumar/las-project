<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th width="60" style="text-align: center;">S.No</th>
                <th width="120">Fee ID</th>
                <th width="70">Fee Type</th>
                <th width="100">Status</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($feesTypes as $key => $feeType)
            <tr>
                <td style="text-align: center;">{{ $feesTypes->firstItem() + $key }}</td>
                <td><span class="feetype-id-badge">{{ $feeType->fee_type_id }}</span></td>
                <td><strong>{{ $feeType->name }}</strong></td>
                <td>
                    <span class="status-badge status-{{ $feeType->status }}">
                        {{ ucfirst($feeType->status) }}
                    </span>
                </td>
                <td>
                    <div class="action-btns">
                        <button class="btn-view" onclick="viewFeeType({{ $feeType->id }})">
                            <i class="fas fa-eye"></i> View
                        </button>
                           @if(Auth::user()->role=='admin')
                        <button class="btn-edit" onclick="openEditModal({{ $feeType->id }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-delete" onclick="deleteFeeType({{ $feeType->id }})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px;">
                    <i class="fas fa-rupee-sign" style="font-size: 2rem; color: #cbd5e1;"></i>
                    <p style="margin-top: 10px; color: #94a3b8;">No fee types found for this session</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($feesTypes->hasPages())
    <div class="pagination">
        {{ $feesTypes->links() }}
    </div>
    @endif
</div>