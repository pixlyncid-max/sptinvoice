<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('client');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_invoice', 'like', "%{$search}%")
                  ->orWhereHas('client', fn($cq) => $cq->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_invoice', [$request->dari, $request->sampai]);
        }

        $invoices = $query->latest()->paginate(10)->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $clients = Client::orderBy('nama')->get();
        $nomor = Invoice::generateNomor();
        $banks = \App\Models\Bank::where('is_active', true)->get();
        return view('invoices.create', compact('clients', 'nomor', 'banks'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        $data = $request->validated();
        $items = $data['items'];
        unset($data['items']);

        $subtotal = collect($items)->sum(fn($item) => $item['qty'] * $item['harga_satuan']);
        $pajakPersen = $data['pajak_persen'] ?? 0;
        $pajakLabel = $data['pajak_label'] ?? 'Pajak';
        
        $isTermin = str_contains(strtolower($pajakLabel), 'termin') || str_contains(strtolower($pajakLabel), 'dp');
        
        $pajakAmount = ($pajakPersen / 100) * $subtotal;
        $data['subtotal'] = $subtotal;
        $data['total'] = $isTermin ? $pajakAmount : ($subtotal + $pajakAmount);

        $invoice = Invoice::create($data);

        foreach ($items as $item) {
            $invoice->items()->create([
                'deskripsi' => $item['deskripsi'],
                'qty' => $item['qty'],
                'harga_satuan' => $item['harga_satuan'],
                'subtotal' => $item['qty'] * $item['harga_satuan'],
            ]);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dibuat.');
    }

    public function show(Invoice $invoice)
    {
        return redirect()->route('invoices.edit', $invoice);
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items', 'client');
        $clients = Client::orderBy('nama')->get();
        $banks = \App\Models\Bank::where('is_active', true)->get();
        return view('invoices.edit', compact('invoice', 'clients', 'banks'));
    }

    public function update(StoreInvoiceRequest $request, Invoice $invoice)
    {
        $data = $request->validated();
        $items = $data['items'];
        unset($data['items']);

        $subtotal = collect($items)->sum(fn($item) => $item['qty'] * $item['harga_satuan']);
        $pajakPersen = $data['pajak_persen'] ?? 0;
        $pajakLabel = $data['pajak_label'] ?? 'Pajak';
        
        $isTermin = str_contains(strtolower($pajakLabel), 'termin') || str_contains(strtolower($pajakLabel), 'dp');
        
        $pajakAmount = ($pajakPersen / 100) * $subtotal;
        $data['subtotal'] = $subtotal;
        $data['total'] = $isTermin ? $pajakAmount : ($subtotal + $pajakAmount);

        $invoice->update($data);
        $invoice->items()->delete();

        foreach ($items as $item) {
            $invoice->items()->create([
                'deskripsi' => $item['deskripsi'],
                'qty' => $item['qty'],
                'harga_satuan' => $item['harga_satuan'],
                'subtotal' => $item['qty'] * $item['harga_satuan'],
            ]);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil diperbarui.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dihapus.');
    }

    public function print(Invoice $invoice)
    {
        $invoice->load('client', 'items');
        return view('invoices.print', compact('invoice'));
    }

    public function exportPdf(Invoice $invoice)
    {
        $invoice->load('client', 'items');
        $pdf = Pdf::loadView('invoices.print', compact('invoice'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('Invoice-' . $invoice->nomor_invoice . '.pdf');
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate(['status' => 'required|in:draft,dikirim,dibayar,batal']);
        $invoice->update(['status' => $request->status]);
        return back()->with('success', 'Status invoice berhasil diperbarui.');
    }

    public function duplicate(Invoice $invoice)
    {
        $invoice->load('items');
        $newInvoice = $invoice->replicate();
        $newInvoice->nomor_invoice = Invoice::generateNomor();
        $newInvoice->status = 'draft';
        $newInvoice->tanggal_invoice = now()->toDateString();
        $newInvoice->tanggal_jatuh_tempo = now()->addDays(30)->toDateString();
        $newInvoice->save();

        foreach ($invoice->items as $item) {
            $newItem = $item->replicate();
            $newItem->invoice_id = $newInvoice->id;
            $newItem->save();
        }

        return redirect()->route('invoices.edit', $newInvoice)->with('success', 'Invoice berhasil diduplikasi.');
    }
}
