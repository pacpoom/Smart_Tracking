<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:view vendors|create vendors|edit vendors|delete vendors', ['only' => ['index']]);
        // $this->middleware('permission:create vendors', ['only' => ['create', 'store']]);
        // $this->middleware('permission:edit vendors', ['only' => ['edit', 'update', 'download']]);
        // $this->middleware('permission:delete vendors', ['only' => ['destroy', 'bulkDestroy']]);
    }

    public function index(Request $request)
    {
        $query = Vendor::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('vendor_code', 'like', '%' . $search . '%');
            });
        }

        $vendors = $query->paginate(10);
        return view('vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('vendors.create');
    }

    public function store(Request $request)
    {
        // 1. แก้ไข Validation: ทำให้ vendor_code ไม่จำเป็นต้องกรอก
        $request->validate([
            'vendor_code' => 'nullable|string|unique:vendors,vendor_code',
            'name' => 'required|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:2048',
            'register_date' => 'nullable|date',
            'expire_date' => 'nullable|date|after_or_equal:register_date',
        ]);

        $data = $request->except(['attachment', 'vendor_code']);

        // 2. สร้าง Vendor ก่อนเพื่อเอา ID
        $vendor = new Vendor($data);
        $vendor->save();

        // 3. กำหนด Vendor Code
        if (empty($request->vendor_code)) {
            // สร้างรหัสอัตโนมัติถ้าไม่ได้กรอก
            $vendor->vendor_code = 'V' . str_pad($vendor->id, 5, '0', STR_PAD_LEFT);
        } else {
            // ใช้รหัสที่กรอกเข้ามา
            $vendor->vendor_code = $request->vendor_code;
        }

        // 4. จัดการไฟล์แนบ
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('public/vendor_attachments');
            $vendor->attachment_path = $path;
        }

        // 5. บันทึกข้อมูลทั้งหมดอีกครั้ง
        $vendor->save();

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
    }

    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'vendor_code' => 'required|string|unique:vendors,vendor_code,'.$vendor->id,
            'name' => 'required|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:2048',
            'register_date' => 'nullable|date',
            'expire_date' => 'nullable|date|after_or_equal:register_date',
        ]);

        $data = $request->except('attachment');

        if ($request->hasFile('attachment')) {
            if ($vendor->attachment_path) {
                Storage::delete($vendor->attachment_path);
            }
            $path = $request->file('attachment')->store('public/vendor_attachments');
            $data['attachment_path'] = $path;
        }

        $vendor->update($data);
        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor)
    {
        if ($vendor->attachment_path) {
            Storage::delete($vendor->attachment_path);
        }
        $vendor->delete();
        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:vendors,id',
        ]);
        
        $vendors = Vendor::whereIn('id', $request->ids)->get();
        foreach ($vendors as $vendor) {
            if ($vendor->attachment_path) {
                Storage::delete($vendor->attachment_path);
            }
            $vendor->delete();
        }

        return redirect()->route('vendors.index')->with('success', 'Selected vendors have been deleted successfully.');
    }

    public function download(Vendor $vendor)
    {
        if ($vendor->attachment_path && Storage::exists($vendor->attachment_path)) {
            return Storage::download($vendor->attachment_path);
        }
        return redirect()->back()->with('error', 'File not found.');
    }
}
