<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenawaranRequest;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Penawaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PenawaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Penawaran::with('client');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('perihal')) {
            $query->where('perihal', $request->perihal);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_penawaran', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhereHas('client', fn($cq) => $cq->where('nama', 'like', "%{$search}%"));
            });
        }

        $penawarans = $query->latest()->paginate(10)->withQueryString();

        return view('penawaran.index', compact('penawarans'));
    }

    public function create()
    {
        $clients = Client::orderBy('nama')->get();
        $nomor = Penawaran::generateNomor();
        return view('penawaran.create', compact('clients', 'nomor'));
    }

    public function store(StorePenawaranRequest $request)
    {
        $data = $request->validated();
        $items = $data['items'];
        unset($data['items']);

        $subtotal = collect($items)->sum(fn($item) => $item['qty'] * $item['harga_satuan']);
        $diskon = $data['diskon'] ?? 0;
        $pajakPersen = $data['pajak_persen'] ?? 0;
        $pajakLabel = $data['pajak_label'] ?? 'Pajak';
        
        $isTermin = str_contains(strtolower($pajakLabel), 'termin') || str_contains(strtolower($pajakLabel), 'dp');
        
        $setelahDiskon = $subtotal - $diskon;
        $pajakAmount = ($pajakPersen / 100) * $setelahDiskon;
        $data['subtotal'] = $subtotal;
        $data['diskon'] = $diskon;
        $data['total'] = $isTermin ? $pajakAmount : ($setelahDiskon + $pajakAmount);

        $penawaran = Penawaran::create($data);

        foreach ($items as $item) {
            $penawaran->items()->create([
                'deskripsi' => $item['deskripsi'],
                'keterangan' => $item['keterangan'] ?? null,
                'kategori_layanan' => $item['kategori_layanan'] ?? 'Fee Pekerjaan',
                'qty' => $item['qty'] ?? 1,
                'harga_satuan' => $item['harga_satuan'] ?? 0,
                'subtotal' => ($item['qty'] ?? 1) * ($item['harga_satuan'] ?? 0),
            ]);
        }

        return redirect()->route('penawaran.index')->with('success', 'Penawaran berhasil dibuat.');
    }

    public function show(Penawaran $penawaran)
    {
        return redirect()->route('penawaran.edit', $penawaran);
    }

    public function edit(Penawaran $penawaran)
    {
        $penawaran->load('items', 'client');
        $clients = Client::orderBy('nama')->get();
        return view('penawaran.edit', compact('penawaran', 'clients'));
    }

    public function update(StorePenawaranRequest $request, Penawaran $penawaran)
    {
        $data = $request->validated();
        $items = $data['items'];
        unset($data['items']);

        $subtotal = collect($items)->sum(fn($item) => $item['qty'] * $item['harga_satuan']);
        $diskon = $data['diskon'] ?? 0;
        $pajakPersen = $data['pajak_persen'] ?? 0;
        $pajakLabel = $data['pajak_label'] ?? 'Pajak';
        
        $isTermin = str_contains(strtolower($pajakLabel), 'termin') || str_contains(strtolower($pajakLabel), 'dp');
        
        $setelahDiskon = $subtotal - $diskon;
        $pajakAmount = ($pajakPersen / 100) * $setelahDiskon;
        $data['subtotal'] = $subtotal;
        $data['diskon'] = $diskon;
        $data['total'] = $isTermin ? $pajakAmount : ($setelahDiskon + $pajakAmount);

        $penawaran->update($data);
        $penawaran->items()->delete();

        foreach ($items as $item) {
            $penawaran->items()->create([
                'deskripsi' => $item['deskripsi'],
                'keterangan' => $item['keterangan'] ?? null,
                'kategori_layanan' => $item['kategori_layanan'] ?? 'Fee Pekerjaan',
                'qty' => $item['qty'] ?? 1,
                'harga_satuan' => $item['harga_satuan'] ?? 0,
                'subtotal' => ($item['qty'] ?? 1) * ($item['harga_satuan'] ?? 0),
            ]);
        }

        return redirect()->route('penawaran.index')->with('success', 'Penawaran berhasil diperbarui.');
    }

    public function destroy(Penawaran $penawaran)
    {
        $penawaran->delete();
        return redirect()->route('penawaran.index')->with('success', 'Penawaran berhasil dihapus.');
    }

    public function print(Penawaran $penawaran)
    {
        $penawaran->load('client', 'items');
        return view('penawaran.print', compact('penawaran'));
    }

    public function exportPdf(Penawaran $penawaran)
    {
        $penawaran->load('client', 'items');
        $pdf = Pdf::loadView('penawaran.print', compact('penawaran'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('Penawaran-' . $penawaran->nomor_penawaran . '.pdf');
    }

    public function updateStatus(Request $request, Penawaran $penawaran)
    {
        $request->validate(['status' => 'required|in:draft,dikirim,disetujui,ditolak']);
        $penawaran->update(['status' => $request->status]);
        return back()->with('success', 'Status penawaran berhasil diperbarui.');
    }

    public function convertToInvoice(Penawaran $penawaran)
    {
        $penawaran->load('items');
        
        $invoiceData = [
            'nomor_invoice' => Invoice::generateNomor(),
            'client_id' => $penawaran->client_id,
            'tanggal_invoice' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(30)->toDateString(),
            'status' => 'draft',
            'catatan' => $penawaran->catatan,
            'subtotal' => $penawaran->subtotal,
            'pajak_persen' => $penawaran->pajak_persen ?? 0,
            'pajak_label' => $penawaran->pajak_label ?? 'Pajak',
            'total' => $penawaran->total,
        ];

        $invoice = Invoice::create($invoiceData);

        // Only convert Fee Pekerjaan items (with actual prices) to invoice
        foreach ($penawaran->items->where('kategori_layanan', 'Fee Pekerjaan') as $item) {
            $invoice->items()->create([
                'deskripsi' => $item->deskripsi,
                'qty' => $item->qty,
                'harga_satuan' => $item->harga_satuan,
                'subtotal' => $item->subtotal,
            ]);
        }

        $penawaran->update(['status' => 'disetujui']);

        return redirect()->route('invoices.edit', $invoice)->with('success', 'Penawaran berhasil diubah menjadi Invoice.');
    }
}
