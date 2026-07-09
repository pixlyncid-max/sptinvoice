<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Employee;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventaris::query()->with('employee');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%")
                  ->orWhere('nama_merk', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $inventaris = $query->latest()->paginate(10)->withQueryString();

        return view('inventaris.index', compact('inventaris'));
    }

    public function create()
    {
        $employees = Employee::orderBy('nama', 'asc')->get();
        return view('inventaris.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:elektronik,furniture,alat_kerja,kendaraan',
            'nama_barang' => 'required|string|max:255',
            'nama_merk' => 'nullable|string|max:255',
            'tanggal_beli' => 'required|date',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        // Generate the kode_barang right before saving to prevent collision
        $validated['kode_barang'] = Inventaris::generateKode($validated['kategori'], $validated['tanggal_beli']);

        Inventaris::create($validated);

        return redirect()->route('inventaris.index')->with('success', 'Barang inventaris berhasil ditambahkan.');
    }

    public function show(Inventaris $inventaris)
    {
        $inventaris->load('employee');
        return view('inventaris.show', compact('inventaris'));
    }

    public function edit(Inventaris $inventaris)
    {
        $employees = Employee::orderBy('nama', 'asc')->get();
        return view('inventaris.edit', compact('inventaris', 'employees'));
    }

    public function update(Request $request, Inventaris $inventaris)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:elektronik,furniture,alat_kerja,kendaraan',
            'nama_barang' => 'required|string|max:255',
            'nama_merk' => 'nullable|string|max:255',
            'tanggal_beli' => 'required|date',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        $inventaris->update($validated);

        return redirect()->route('inventaris.index')->with('success', 'Barang inventaris berhasil diperbarui.');
    }

    public function destroy(Inventaris $inventaris)
    {
        $inventaris->delete();
        return redirect()->route('inventaris.index')->with('success', 'Barang inventaris berhasil dihapus.');
    }

    public function scanCamera()
    {
        return view('inventaris.scan');
    }

    public function showByCode($kode)
    {
        $inventaris = Inventaris::where('kode_barang', $kode)->firstOrFail();
        return redirect()->route('inventaris.show', $inventaris);
    }

    public function showQr(Inventaris $inventaris)
    {
        $inventaris->load('employee');
        return view('inventaris.qr', compact('inventaris'));
    }

    public function qrScan($kode)
    {
        $inventaris = Inventaris::where('kode_barang', $kode)->with('employee')->firstOrFail();
        return view('inventaris.qr-scan', compact('inventaris'));
    }
}
