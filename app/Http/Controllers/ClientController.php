<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('perusahaan', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clients = $query->withCount(['invoices', 'penawaran'])->latest()->paginate(10)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        Client::create($request->validated());
        return redirect()->route('clients.index')->with('success', 'Client berhasil ditambahkan.');
    }

    public function show(Client $client)
    {
        return redirect()->route('clients.detail', $client);
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(StoreClientRequest $request, Client $client)
    {
        $client->update($request->validated());
        return redirect()->route('clients.index')->with('success', 'Client berhasil diperbarui.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client berhasil dihapus.');
    }

    public function detail(Client $client)
    {
        $invoices = $client->invoices()->latest()->get();
        $penawarans = $client->penawaran()->latest()->get();
        
        return view('clients.detail', compact('client', 'invoices', 'penawarans'));
    }

    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ClientTemplateExport(), 'Template-Client.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ClientImport(), $request->file('file_excel'));
            return redirect()->route('clients.index')->with('success', 'Data client berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('clients.index')->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}
