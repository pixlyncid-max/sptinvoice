<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Penawaran;
use App\Models\RateCard;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients = Client::count();
        $totalInvoices = Invoice::count();
        $invoicesByStatus = Invoice::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
        $totalPenawaran = Penawaran::count();
        $penawaranByStatus = Penawaran::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
        $totalRateCard = RateCard::where('status', 'aktif')->count();
        $latestInvoices = Invoice::with('client')->latest()->take(5)->get();
        $latestPenawaran = Penawaran::with('client')->latest()->take(5)->get();
        $totalPendapatan = Invoice::where('status', 'dibayar')->sum('total');

        return view('dashboard', compact(
            'totalClients',
            'totalInvoices',
            'invoicesByStatus',
            'totalPenawaran',
            'penawaranByStatus',
            'totalRateCard',
            'latestInvoices',
            'latestPenawaran',
            'totalPendapatan'
        ));
    }
}
