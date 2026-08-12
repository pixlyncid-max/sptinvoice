<?php

namespace App\Http\Controllers;

use App\Exports\GaaDataTemplateExport;
use App\Imports\GaaDataImport;
use App\Models\GaaData;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GaaDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = GaaData::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('npwp', 'like', "%{$search}%")
                  ->orWhere('kpp', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('pic_nama', 'like', "%{$search}%")
                  ->orWhere('pic_nik', 'like', "%{$search}%");
            });
        }

        if ($request->filled('checklist_coretax')) {
            $query->where('checklist_coretax', $request->checklist_coretax);
        }

        $gaaList = $query->orderBy('nama_perusahaan', 'asc')->paginate(15)->withQueryString();

        return view('gaa.index', compact('gaaList'));
    }

    /**
     * Download Excel template for Data GAA.
     */
    public function downloadTemplate()
    {
        return Excel::download(new GaaDataTemplateExport(), 'Template-Data-GAA.xlsx');
    }

    /**
     * Import Data GAA from Excel file.
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            Excel::import(new GaaDataImport(), $request->file('file_excel'));
            return redirect()->route('gaa.index')->with('success', 'Data GAA berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->route('gaa.index')->with('error', 'Gagal mengimpor data GAA: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gaa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'kpp' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'password_email' => 'nullable|string|max:255',
            'djp_user' => 'nullable|string|max:255',
            'djp_password' => 'nullable|string|max:255',
            'user_npwp_16' => 'nullable|string|max:255',
            'pic_nik' => 'nullable|string|max:50',
            'pic_nama' => 'nullable|string|max:255',
            'coretax_password' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'checklist_coretax' => 'required|string|max:50',
        ]);

        GaaData::create($validated);

        return redirect()->route('gaa.index')->with('success', 'Data GAA berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $gaa = GaaData::findOrFail($id);
        return view('gaa.edit', compact('gaa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $gaa = GaaData::findOrFail($id);

        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'kpp' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'password_email' => 'nullable|string|max:255',
            'djp_user' => 'nullable|string|max:255',
            'djp_password' => 'nullable|string|max:255',
            'user_npwp_16' => 'nullable|string|max:255',
            'pic_nik' => 'nullable|string|max:50',
            'pic_nama' => 'nullable|string|max:255',
            'coretax_password' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'checklist_coretax' => 'required|string|max:50',
        ]);

        $gaa->update($validated);

        return redirect()->route('gaa.index')->with('success', 'Data GAA berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $gaa = GaaData::findOrFail($id);
        $gaa->delete();

        return redirect()->route('gaa.index')->with('success', 'Data GAA berhasil dihapus!');
    }

    /**
     * Remove all resources from storage.
     */
    public function destroyAll()
    {
        GaaData::query()->delete();

        return redirect()->route('gaa.index')->with('success', 'Semua Data GAA berhasil dihapus!');
    }
}
