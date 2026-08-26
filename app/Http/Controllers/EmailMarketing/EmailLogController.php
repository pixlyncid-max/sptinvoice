<?php

namespace App\Http\Controllers\EmailMarketing;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailLog::with('campaign');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('recipient_email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest('sent_at')->paginate(20)->withQueryString();

        $totalLogs = EmailLog::count();
        $sentLogs = EmailLog::where('status', 'sent')->count();
        $failedLogs = EmailLog::where('status', 'failed')->count();

        return view('email-marketing.logs.index', compact(
            'logs',
            'totalLogs',
            'sentLogs',
            'failedLogs'
        ));
    }

    public function clear(Request $request)
    {
        EmailLog::truncate();
        return redirect()->route('email-marketing.logs.index')->with('success', 'Semua riwayat log email berhasil dibersihkan.');
    }
}
