<?php

namespace App\Http\Controllers;

use App\Models\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FileUploadController extends Controller
{
    public function index(Request $request)
    {
        $query = UploadedFile::with('user')->latest();

        // Filter by filename or doc number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('original_filename', 'like', '%' . $search . '%')
                    ->orWhere('document_number', 'like', '%' . $search . '%');
            });
        }

        // === เพิ่ม: Filter by category ===
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date . ' 23:59:59']);
        }

        $files = $query->paginate(15);
        return view('files.index', compact('files'));
    }

    public function create()
    {
        return view('files.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'nullable|string|in:accounting,import_export',
            'document_number' => ['nullable', 'string', 'max:255', Rule::unique('uploaded_files')->where(function ($query) use ($request) {
                return $query->where('document_number', $request->document_number);
            })],
            'files' => 'required|array|max:10',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,zip,rar|max:20480',
        ]);

        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();
            $path = $file->store('public/uploads');
            $hashedName = basename($path);

            UploadedFile::create([
                'category' => $request->category, // <-- เพิ่มการบันทึก category
                'document_number' => $request->document_number,
                'original_filename' => $originalName,
                'hashed_filename' => $hashedName,
                'file_path' => $path,
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('files.index')->with('success', 'Files uploaded successfully!');
    }

    // (ฟังก์ชัน edit, update, download, destroy เหมือนเดิม)
    public function edit(UploadedFile $file)
    {
        return view('files.edit', compact('file'));
    }

    public function update(Request $request, UploadedFile $file)
    {
        $request->validate([
            'filename_no_ext' => 'required|string|max:200',
            'document_number' => ['nullable', 'string', 'max:255', \Illuminate\Validation\Rule::unique('uploaded_files')->ignore($file->id)],
            'category' => 'nullable|string|in:accounting,import_export',
        ]);

        $extension = pathinfo($file->original_filename, PATHINFO_EXTENSION);
        $newOriginalFilename = $request->filename_no_ext . '.' . $extension;

        $file->update([
            'original_filename' => $newOriginalFilename,
            'document_number' => $request->document_number,
            'category' => $request->category,
        ]);

        return redirect()->route('files.index')->with('success', 'File details updated successfully.');
    }

    public function download(UploadedFile $file)
    {
        if (Storage::exists($file->file_path)) {
            return Storage::download($file->file_path, $file->original_filename);
        }
        return redirect()->back()->with('error', 'File not found.');
    }

    public function destroy(UploadedFile $file)
    {
        if (Storage::exists($file->file_path)) {
            Storage::delete($file->file_path);
        }
        $file->delete();
        return redirect()->route('files.index')->with('success', 'File deleted successfully.');
    }
}
