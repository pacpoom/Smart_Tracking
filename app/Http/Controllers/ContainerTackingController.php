<?php

namespace App\Http\Controllers;

use App\Models\ContainerTacking;
use App\Models\ContainerTackingPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive; // 1. เพิ่ม use statement นี้

class ContainerTackingController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:tack container photos', ['only' => ['create', 'store']]);
        // $this->middleware('permission:view container tackings', ['only' => ['index', 'show', 'showPhoto']]);
        // $this->middleware('permission:delete container tackings', ['only' => ['destroy', 'bulkDestroy']]);
        // $this->middleware('permission:download tacking photos', ['only' => ['downloadPhotosAsZip']]); // 2. เพิ่ม middleware นี้
    }

    // ... (index, create, store methods remain the same) ...
    public function index(Request $request)
    {
        $query = ContainerTacking::with(['containerOrderPlan.container', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('shipment', 'like', '%' . $search . '%')
                  ->orWhereHas('containerOrderPlan.container', function ($subQ) use ($search) {
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
            'container_order_plan_id' => 'required|exists:container_order_plans,id',
            'shipment' => 'nullable|string|max:255',
            'photos' => 'nullable|array|max:30',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $tacking = DB::transaction(function () use ($request) {
            $tacking = ContainerTacking::create([
                'job_type' => $request->job_type,
                'container_type' => $request->container_type,
                'transport_type' => $request->transport_type,
                'container_order_plan_id' => $request->container_order_plan_id,
                'shipment' => $request->shipment,
                'user_id' => Auth::id(),
            ]);

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photoType => $file) {
                    if ($file && $file->isValid()) {
                        $path = $file->store("tacking_photos/{$tacking->id}", 'public');
                        ContainerTackingPhoto::create([
                            'container_tacking_id' => $tacking->id,
                            'photo_type' => $photoType,
                            'file_path' => $path,
                        ]);
                    }
                }
            }
            return $tacking;
        });

        return redirect()->route('container-tacking.show', $tacking->id)->with('success', 'Container tacking data and photos saved successfully.');
    }


    public function show(ContainerTacking $containerTacking)
    {
        $containerTacking->load(['containerOrderPlan.container', 'user', 'photos']);
        return view('container-tacking.show', compact('containerTacking'));
    }

    public function showPhoto(ContainerTackingPhoto $photo)
    {
        if (!Storage::disk('public')->exists($photo->file_path)) {
            abort(404);
        }
        return response()->file(storage_path('app/public/' . $photo->file_path));
    }

    /**
     * Download all photos for a tacking record as a ZIP file.
     */
    public function downloadPhotosAsZip(ContainerTacking $containerTacking)
    {
        $zip = new ZipArchive;
        $fileName = 'tacking_photos_' . $containerTacking->id . '.zip';
        $zipPath = storage_path('app/public/' . $fileName);

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            $photos = $containerTacking->photos;

            foreach ($photos as $photo) {
                if (Storage::disk('public')->exists($photo->file_path)) {
                    $filePath = storage_path('app/public/' . $photo->file_path);
                    $zip->addFile($filePath, basename($photo->file_path));
                }
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function destroy(ContainerTacking $containerTacking)
    {
        DB::transaction(function () use ($containerTacking) {
            Storage::disk('public')->deleteDirectory("tacking_photos/{$containerTacking->id}");
            $containerTacking->delete();
        });

        return redirect()->route('container-tacking.index')->with('success', 'Tacking record deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:container_tackings,id',
        ]);

        DB::transaction(function () use ($request) {
            $tackings = ContainerTacking::whereIn('id', $request->ids)->get();
            foreach ($tackings as $tacking) {
                Storage::disk('public')->deleteDirectory("tacking_photos/{$tacking->id}");
                $tacking->delete();
            }
        });

        return redirect()->route('container-tacking.index')->with('success', 'Selected tacking records have been deleted successfully.');
    }
}
