<?php

namespace App\Http\Controllers;

use App\Models\ContainerTransaction;
use Illuminate\Http\Request;

class ContainerTransactionController extends Controller
{
    function __construct()
    {
        //$this->middleware('permission:view container transactions');
    }

    public function index(Request $request)
    {
        $query = ContainerTransaction::with(['containerOrderPlan.container', 'user', 'yardLocation']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('containerOrderPlan.container', function ($q) use ($search) {
                $q->where('container_no', 'like', '%' . $search . '%');
            });
        }

        $transactions = $query->latest('transaction_date')->paginate(15);
        return view('container-transactions.index', compact('transactions'));
    }
}
