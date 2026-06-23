@forelse($ledgers as $index => $ledger)
<tr>
    <td>{{ $ledgers->firstItem() + $index }}</td>
    <td><strong>{{ $ledger->ledger_name }}</strong></td>
    <td>{{ $ledger->group->group_name ?? 'N/A' }}</td>
    <td>{{ $ledger->mobile ?? '-' }}</td>
    <td>
        <span class="badge-{{ $ledger->balance_type }}">
            {{ ucfirst($ledger->balance_type) }}
        </span>
        ₹{{ number_format($ledger->opening_balance, 2) }}
    </td>
    <td>
        <span class="badge-{{ $ledger->status }}">
            {{ ucfirst($ledger->status) }}
        </span>
    </td>
    <td>
        <div class="action-btns">
            <button class="btn-edit" onclick="editLedger({{ $ledger->id }})" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
            <button class="btn-delete" onclick="deleteLedger({{ $ledger->id }})" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="no-data">
        <i class="fas fa-book"></i>
        <p>No ledgers found</p>
    </td>
</tr>
@endforelse