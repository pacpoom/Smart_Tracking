<?php

namespace App\Http\Controllers;

use App\Models\ContainerTacking;
use App\Models\ContainerTackingPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ContainerTackingController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:tack container photos', ['only' => ['create', 'store']]);
        // $this->middleware('permission:view container tackings', ['only' => ['index', 'show']]);
    }

    public function index(Request $request)
    {
        $query = ContainerTacking::with(['container', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('shipment', 'like', '%' . $search . '%')
                  ->orWhereHas('container', function ($subQ) use ($search) {
                      $subQ->where('container_no', 'like', '%' . $search . '%');
                  });
            });
        }

        $tackings = $query->latest()->paginate(15);
        return view('container-tacking.index', compact('tackings'));
    }

    public function create()
    {
        return view('container-tacking.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_type' => 'required|string',
            'container_type' => 'required|string',
            'transport_type' => 'required|string',
            'container_id' => 'required|exists:containers,id',
            'shipment' => 'nullable|string|max:255',
            'photos' => 'nullable|array|max:30',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // แก้ไข: ให้ DB::transaction คืนค่า object ที่สร้างขึ้นมา
        $tacking = DB::transaction(function () use ($request) {
            $tacking = ContainerTacking::create([
                'job_type' => $request->job_type,
                'container_type' => $request->container_type,
                'transport_type' => $request->transport_type,
                'container_id' => $request->container_id,
                'shipment' => $request->shipment,
                'user_id' => Auth::id(),
            ]);

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photoType => $file) {
                    if ($file->isValid()) {
                        $path = $file->store("tacking_photos/{$tacking->id}", 'public');
                        ContainerTackingPhoto::create([
                            'container_tacking_id' => $tacking->id,
                            'photo_type' => $photoType,
                            'file_path' => $path,
                        ]);
                    }
                }
            }
            return $tacking; // คืนค่า object
        });

        // แก้ไข: Redirect ไปยังหน้า show พร้อมกับ ID
        return redirect()->route('container-tacking.show', $tacking->id)->with('success', 'Container tacking data and photos saved successfully.');
    }

    public function show(ContainerTacking $containerTacking)
    {
        $containerTacking->load(['container', 'user', 'photos']);
        return view('container-tacking.show', compact('containerTacking'));
    }

    public function showPhoto(ContainerTackingPhoto $photo)
    {
        if (!Storage::disk('public')->exists($photo->file_path)) {
            abort(404);
        }
        return response()->file(storage_path('app/public/' . $photo->file_path));
    }
}
