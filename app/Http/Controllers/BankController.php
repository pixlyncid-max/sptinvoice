<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $banks = \App\Models\Bank::latest()->paginate(10);
        return view('banks.index', compact('banks'));
    }

    public function create()
    {
        return view('banks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:100',
            'atas_nama' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        \App\Models\Bank::create([
            'nama_bank' => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama' => $request->atas_nama,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('banks.index')->with('success', 'Data bank berhasil ditambahkan.');
    }

    public function edit(\App\Models\Bank $bank)
    {
        return view('banks.edit', compact('bank'));
    }

    public function update(Request $request, \App\Models\Bank $bank)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:100',
            'atas_nama' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $bank->update([
            'nama_bank' => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama' => $request->atas_nama,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('banks.index')->with('success', 'Data bank berhasil diperbarui.');
    }

    public function destroy(\App\Models\Bank $bank)
    {
        // Prevent deletion if used in invoices
        if ($bank->invoices()->exists()) {
            return redirect()->route('banks.index')->with('error', 'Bank tidak dapat dihapus karena sudah digunakan dalam invoice.');
        }

        $bank->delete();
        return redirect()->route('banks.index')->with('success', 'Data bank berhasil dihapus.');
    }
}
