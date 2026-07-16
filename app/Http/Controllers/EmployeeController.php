<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['division', 'position'])->orderBy('id', 'asc')->get();
        $divisions = \App\Models\Division::all();
        $positions = \App\Models\Position::all();
        return view('employees.index', compact('employees', 'divisions', 'positions'));
    }

    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\EmployeeTemplateExport, 'template_karyawan.xlsx');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'nullable|string|max:255',
            'position_id' => 'required|exists:positions,id',
            'grade' => 'nullable|string|max:255',
            'tgl_masuk' => 'nullable|date',
            'bank' => 'nullable|string|max:255',
            'no_rekening' => 'nullable|string|max:255',
            'nama_rekening' => 'nullable|string|max:255',
            'gaji_pokok' => 'required|numeric|min:0',
        ]);

        $position = \App\Models\Position::find($validated['position_id']);
        $validated['jabatan'] = $position->name;
        $validated['division_id'] = null; // Division removed

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'nullable|string|max:255',
            'position_id' => 'required|exists:positions,id',
            'grade' => 'nullable|string|max:255',
            'tgl_masuk' => 'nullable|date',
            'bank' => 'nullable|string|max:255',
            'no_rekening' => 'nullable|string|max:255',
            'nama_rekening' => 'nullable|string|max:255',
            'gaji_pokok' => 'required|numeric|min:0',
        ]);

        $position = \App\Models\Position::find($validated['position_id']);
        $validated['jabatan'] = $position->name;
        $validated['division_id'] = null; // Division removed

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
