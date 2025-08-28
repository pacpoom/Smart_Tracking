<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\PartRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exports\PartRequestExport;
use Maatwebsite\Excel\Facades\Excel;

class PartRequestController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:view all part requests', ['only' => ['index', 'export']]);
        // $this->middleware('permission:create part requests', ['only' => ['create', 'store']]);
        // $this->middleware('permission:approve part requests', ['only' => ['edit', 'update', 'download', 'downloadDeliveryDocument']]);
    }

    // ... (index and export methods remain the same) ...

    public function index(Request $request)
    {
        $query = PartRequest::with(['user', 'part'])->latest();

        if ($request->filled('part_number')) {
            $query->whereHas('part', function ($q) use ($request) {
                $q->where('part_number', 'like', '%' . $request->part_number . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('required_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('required_date', '<=', $request->end_date);
        }

        $requests = $query->paginate(10);
        return view('part-requests.index', compact('requests'));
    }

    /**
     * Export part requests to an Excel file.
     */
    public function export(Request $request)
    {
        $query = PartRequest::with(['user', 'part'])->latest();

        if ($request->filled('part_number')) {
            $query->whereHas('part', function ($q) use ($request) {
                $q->where('part_number', 'like', '%' . $request->part_number . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('required_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('required_date', '<=', $request->end_date);
        }

        return Excel::download(new PartRequestExport($query), 'part_requests.xlsx');
    }
    
    public function create()
    {
        return view('part-requests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_id' => ['required', 'exists:parts,id', function ($attribute, $value, $fail) {
                $part = Part::with('stock')->find($value);
                if (!$part || !$part->stock || $part->stock->qty <= 0) {
                    $fail('The selected part is out of stock.');
                }
            }],
            'quantity' => ['required', 'integer', 'min:1', function ($attribute, $value, $fail) use ($request) {
                $part = Part::with('stock')->find($request->part_id);
                if ($part && $part->stock && $value > $part->stock->qty) {
                    $fail("The requested quantity ({$value}) exceeds the available stock ({$part->stock->qty}).");
                }
            }],
            'required_date' => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string',
            'foc_no' => 'nullable|string|max:255', // เพิ่ม validation
            'attachment' => 'nullable|file|mimes:xls,xlsx,doc,docx,ppt,pptx,jpg,jpeg,png,zip,pdf,msg|max:10240',
        ]);

        $data = $request->except('attachment');
        $data['user_id'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('public/part_request_attachments');
            $data['attachment_path'] = $path;
        }

        PartRequest::create($data);

        return redirect()->route('part-requests.create')->with('success', 'Part request submitted successfully.');
    }

    // ... (edit, update, and download methods remain the same) ...
    public function edit(PartRequest $partRequest)
    {
        return view('part-requests.edit', compact('partRequest'));
    }

    public function update(Request $request, PartRequest $partRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,delivery',
            'delivery_date' => 'nullable|date',
            'arrival_date' => 'nullable|date|after_or_equal:delivery_date',
            'delivery_document' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:10240',
        ]);

        $data = $request->except('delivery_document');

        if ($request->hasFile('delivery_document')) {
            if ($partRequest->delivery_document_path) {
                Storage::delete($partRequest->delivery_document_path);
            }
            $path = $request->file('delivery_document')->store('public/delivery_documents');
            $data['delivery_document_path'] = $path;
        }

        $partRequest->update($data);

        return redirect()->route('part-requests.index')->with('success', 'Part request updated successfully.');
    }

    public function download(PartRequest $partRequest)
    {
        if ($partRequest->attachment_path && Storage::exists($partRequest->attachment_path)) {
            return Storage::download($partRequest->attachment_path);
        }
        return redirect()->back()->with('error', 'File not found.');
    }

    public function downloadDeliveryDocument(PartRequest $partRequest)
    {
        if ($partRequest->delivery_document_path && Storage::exists($partRequest->delivery_document_path)) {
            return Storage::download($partRequest->delivery_document_path);
        }
        return redirect()->back()->with('error', 'Delivery document not found.');
    }
}
