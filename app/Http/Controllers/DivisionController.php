<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        // Automatically sync divisions from RateCard
        $rateCardDivisions = \App\Models\RateCard::distinct()->pluck('divisi');
        foreach ($rateCardDivisions as $name) {
            if ($name) {
                Division::firstOrCreate(['name' => $name]);
            }
        }

        $divisions = Division::withCount('employees')->orderBy('name')->get();
        return view('divisions.index', compact('divisions'));
    }

    public function create()
    {
        return view('divisions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Division::create($data);

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dibuat.');
    }

    public function edit(Division $division)
    {
        return view('divisions.edit', compact('division'));
    }

    public function update(Request $request, Division $division)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $division->update($data);

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Division $division)
    {
        if ($division->employees()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus divisi yang masih memiliki karyawan.');
        }
        
        $division->delete();
        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dihapus.');
    }
}
