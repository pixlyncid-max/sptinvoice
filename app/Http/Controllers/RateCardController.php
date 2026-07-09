<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRateCardRequest;
use App\Models\RateCard;
use Illuminate\Http\Request;

class RateCardController extends Controller
{
    public function index(Request $request)
    {
        $query = RateCard::query();

        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_paket', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('sub_kategori', 'like', "%{$search}%");
            });
        }

        $rate_cards = $query->orderBy('divisi')->orderBy('sub_kategori')->orderBy('nama_paket')->paginate(20)->withQueryString();
        $kategori_list = RateCard::whereNotNull('kategori')->distinct()->pluck('kategori');
        $divisi_labels = RateCard::divisiLabels();

        return view('rate-cards.index', compact('rate_cards', 'kategori_list', 'divisi_labels'));
    }

    public function create()
    {
        $divisi_labels = RateCard::divisiLabels();
        return view('rate-cards.create', compact('divisi_labels'));
    }

    public function store(StoreRateCardRequest $request)
    {
        RateCard::create($request->validated());
        return redirect()->route('rate-cards.index')->with('success', 'Rate Card berhasil ditambahkan.');
    }

    public function edit(RateCard $rate_card)
    {
        $divisi_labels = RateCard::divisiLabels();
        return view('rate-cards.edit', compact('rate_card', 'divisi_labels'));
    }

    public function update(StoreRateCardRequest $request, RateCard $rate_card)
    {
        $rate_card->update($request->validated());
        return redirect()->route('rate-cards.index')->with('success', 'Rate Card berhasil diperbarui.');
    }

    public function destroy(RateCard $rate_card)
    {
        $rate_card->delete();
        return redirect()->route('rate-cards.index')->with('success', 'Rate Card berhasil dihapus.');
    }

    /**
     * Get rate card items filtered by divisi (used by penawaran form AJAX)
     */
    public function getItems(Request $request)
    {
        $query = RateCard::where('status', 'aktif');

        if ($request->filled('divisi')) {
            $divisi = trim(strtolower($request->divisi));
            $query->whereRaw('LOWER(divisi) = ?', [$divisi]);
        }

        $rateCards = $query->orderBy('sub_kategori')->orderBy('nama_paket')->get();

        return response()->json($rateCards);
    }
}
