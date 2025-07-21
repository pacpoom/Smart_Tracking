<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:view stock', ['only' => ['index']]);
        // $this->middleware('permission:adjust stock', ['only' => ['adjust']]);
        // $this->middleware('permission:create stock', ['only' => ['storePartAndStock']]);
    }

    public function index(Request $request)
    {
        $query = Part::with('stock');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('part_number', 'like', '%' . $search . '%')
                  ->orWhere('part_name_eng', 'like', '%' . $search . '%');
            });
        }

        $parts = $query->paginate(10);
        return view('stocks.index', compact('parts'));
    }

    public function storePartAndStock(Request $request)
    {
        $request->validate([
            'part_number' => 'required|string|unique:parts,part_number',
            'part_name_eng' => 'nullable|string|max:255',
            'part_name_thai' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'qty' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $part = Part::create($request->except('qty'));
            Stock::create(['part_id' => $part->id, 'qty' => $request->qty]);
        });

        return back()->with('success', 'New part and stock created successfully.');
    }

    /**
     * Adjust stock for a given part. Creates a stock record if it does not exist.
     */
    public function adjust(Request $request, Part $part)
    {
        $request->validate([
            'adjustment_qty' => 'required|integer|min:1|not_in:0',
            'action' => 'required|in:add,subtract',
        ]);

        DB::transaction(function () use ($request, $part) {
            // ค้นหา Stock record หรือสร้างใหม่ถ้ายังไม่มี
            $stock = Stock::firstOrCreate(
                ['part_id' => $part->id],
                ['qty' => 0] // กำหนดค่าเริ่มต้นเป็น 0 ถ้าสร้างใหม่
            );

            if ($request->action === 'add') {
                $stock->increment('qty', $request->adjustment_qty);
            } else { // subtract
                if ($stock->qty < $request->adjustment_qty) {
                    // ป้องกันสต็อกติดลบ
                    throw ValidationException::withMessages([
                       'adjustment_qty' => 'Cannot subtract more than the current quantity.',
                    ]);
                }
                $stock->decrement('qty', $request->adjustment_qty);
            }
        });

        return back()->with('success', 'Stock adjusted successfully.');
    }
}
