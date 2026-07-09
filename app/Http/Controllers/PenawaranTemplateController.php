<?php

namespace App\Http\Controllers;

use App\Models\PenawaranTemplate;
use Illuminate\Http\Request;

class PenawaranTemplateController extends Controller
{
    public function index()
    {
        $templates = PenawaranTemplate::all();
        return view('penawaran-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('penawaran-templates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:penawaran_templates,code',
            'tujuan' => 'nullable|string',
            'lingkup' => 'nullable|string',
            'jenis_pekerjaan_intro' => 'nullable|string',
            'prasyarat' => 'nullable|string',
            'penutup' => 'nullable|string',
        ]);

        PenawaranTemplate::create($data);

        return redirect()->route('penawaran-templates.index')->with('success', 'Template penawaran berhasil dibuat.');
    }

    public function edit(PenawaranTemplate $penawaranTemplate)
    {
        return view('penawaran-templates.edit', compact('penawaranTemplate'));
    }

    public function update(Request $request, PenawaranTemplate $penawaranTemplate)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:penawaran_templates,code,' . $penawaranTemplate->id,
            'tujuan' => 'nullable|string',
            'lingkup' => 'nullable|string',
            'jenis_pekerjaan_intro' => 'nullable|string',
            'prasyarat' => 'nullable|string',
            'penutup' => 'nullable|string',
        ]);

        $penawaranTemplate->update($data);

        return redirect()->route('penawaran-templates.index')->with('success', 'Template penawaran berhasil diperbarui.');
    }

    public function destroy(PenawaranTemplate $penawaranTemplate)
    {
        $penawaranTemplate->delete();
        return redirect()->route('penawaran-templates.index')->with('success', 'Template penawaran berhasil dihapus.');
    }
}
