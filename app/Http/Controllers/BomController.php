<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bom;

class BomController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 25);

        $query = Bom::with(['vcMaster', 'childMaterial']);


        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('vcMaster', function ($q) use ($search) {
                $q->where('vc_code', 'like', '%' . $search . '%');
            });
        }

        $boms = $query->orderBy('id', 'desc')->paginate($perPage);

        return view('bom.index', compact('boms', 'perPage'));
    }
}
