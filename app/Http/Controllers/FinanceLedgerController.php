<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FinanceGroup;
use App\Models\FinanceLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $groups = FinanceGroup::where('status', 'active')
            ->orderBy('group_name')
            ->get();
        
        $query = FinanceLedger::with('group');
        
        // ✅ SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ledger_name', 'like', "%{$search}%")
                  ->orWhere('ledger_code', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('gst_no', 'like', "%{$search}%");
            });
        }
        
        // ✅ FILTER BY GROUP
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }
        
        // ✅ FILTER BY STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $ledgers = $query->orderBy('ledger_name')
            ->paginate(20)
            ->withQueryString();
        
        // ✅ AJAX REQUEST FOR SEARCH
        if ($request->ajax()) {
            return view('finance.ledger_table', compact('ledgers'))->render();
        }
        
        return view('finance.ledger_creation', compact('groups', 'ledgers'));
    }

    /**
     * Store a newly created ledger.
     */
    public function storeLedger(Request $request)
    {
        $request->validate([
            'ledger_name' => 'required|string|max:255|unique:finance_ledgers',
            'group_id' => 'required|exists:finance_groups,id',
            'mobile' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'gst_no' => 'nullable|string|max:50',
            'opening_balance' => 'nullable|numeric|min:0',
            'balance_type' => 'required|in:debit,credit',
            'status' => 'required|in:active,inactive',
        ]);
        
        $ledger = FinanceLedger::create([
            'ledger_name' => $request->ledger_name,
            'ledger_code' => $request->ledger_code,
            'group_id' => $request->group_id,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'address' => $request->address,
            'gst_no' => $request->gst_no,
            'opening_balance' => $request->opening_balance ?? 0,
            'balance_type' => $request->balance_type,
            'status' => $request->status,
            'remarks' => $request->remarks,
            'created_by' => Auth::id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Ledger created successfully!',
            'ledger' => $ledger->load('group')
        ]);
    }

    /**
     * Show the form for editing the specified ledger.
     */
    public function edit($id)
    {
        $ledger = FinanceLedger::with('group')->findOrFail($id);
        $groups = FinanceGroup::where('status', 'active')->orderBy('group_name')->get();
        
        return response()->json([
            'success' => true,
            'ledger' => $ledger,
            'groups' => $groups
        ]);
    }

    /**
     * Update the specified ledger.
     */
    public function updateLedger(Request $request, $id)
    {
        $ledger = FinanceLedger::findOrFail($id);
        
        $request->validate([
            'ledger_name' => 'required|string|max:255|unique:finance_ledgers,ledger_name,' . $id,
            'group_id' => 'required|exists:finance_groups,id',
            'mobile' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'gst_no' => 'nullable|string|max:50',
            'opening_balance' => 'nullable|numeric|min:0',
            'balance_type' => 'required|in:debit,credit',
            'status' => 'required|in:active,inactive',
        ]);
        
        $ledger->update([
            'ledger_name' => $request->ledger_name,
            'ledger_code' => $request->ledger_code,
            'group_id' => $request->group_id,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'address' => $request->address,
            'gst_no' => $request->gst_no,
            'opening_balance' => $request->opening_balance ?? 0,
            'balance_type' => $request->balance_type,
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Ledger updated successfully!',
            'ledger' => $ledger->load('group')
        ]);
    }

    /**
     * Remove the specified ledger.
     */
    public function deleteLedger($id)
    {
        $ledger = FinanceLedger::findOrFail($id);
        $ledger->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Ledger deleted successfully!'
        ]);
    }

    // ============================================
    // GROUP CRUD
    // ============================================

    public function getGroups(Request $request)
    {
        $search = $request->search;
        $query = FinanceGroup::where('status', 'active');
        
        if ($search) {
            $query->where('group_name', 'like', "%{$search}%");
        }
        
        $groups = $query->orderBy('group_name')->get();
        
        return response()->json([
            'success' => true,
            'groups' => $groups
        ]);
    }

    public function storeGroup(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255|unique:finance_groups',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        
        $group = FinanceGroup::create([
            'group_name' => $request->group_name,
            'group_code' => $request->group_code,
            'description' => $request->description,
            'status' => $request->status,
            'parent_group_id' => $request->parent_group_id,
            'created_by' => Auth::id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Group created successfully!',
            'group' => $group
        ]);
    }

    public function updateGroup(Request $request, $id)
    {
        $group = FinanceGroup::findOrFail($id);
        
        $request->validate([
            'group_name' => 'required|string|max:255|unique:finance_groups,group_name,' . $id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        
        $group->update([
            'group_name' => $request->group_name,
            'group_code' => $request->group_code,
            'description' => $request->description,
            'status' => $request->status,
            'parent_group_id' => $request->parent_group_id,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Group updated successfully!',
            'group' => $group
        ]);
    }

    public function deleteGroup($id)
    {
        $group = FinanceGroup::findOrFail($id);
        
        if ($group->ledgers()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete group! It has ledgers assigned.'
            ], 422);
        }
        
        $group->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Group deleted successfully!'
        ]);
    }

    public function getLedgers(Request $request)
    {
        $search = $request->search;
        $groupId = $request->group_id;
        
        $query = FinanceLedger::with('group')->where('status', 'active');
        
        if ($search) {
            $query->where('ledger_name', 'like', "%{$search}%");
        }
        
        if ($groupId) {
            $query->where('group_id', $groupId);
        }
        
        $ledgers = $query->orderBy('ledger_name')->get();
        
        return response()->json([
            'success' => true,
            'ledgers' => $ledgers
        ]);
    }
}