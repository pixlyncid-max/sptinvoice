<?php

namespace App\Http\Controllers\EmailMarketing;

use App\Exports\EmailContactExport;
use App\Exports\EmailContactTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\EmailContactImport;
use App\Models\EmailContact;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class EmailContactController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailContact::query();

        if ($request->filled('subscription')) {
            if ($request->subscription === 'subscribed') {
                $query->where('is_subscribed', true);
            } elseif ($request->subscription === 'unsubscribed') {
                $query->where('is_subscribed', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $contacts = $query->latest()->paginate(15)->withQueryString();

        $totalContacts = EmailContact::count();
        $subscribedCount = EmailContact::where('is_subscribed', true)->count();
        $unsubscribedCount = EmailContact::where('is_subscribed', false)->count();

        return view('email-marketing.contacts.index', compact(
            'contacts',
            'totalContacts',
            'subscribedCount',
            'unsubscribedCount'
        ));
    }

    public function create()
    {
        return view('email-marketing.contacts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:email_contacts,email',
            'company' => 'nullable|string|max:255',
            'is_subscribed' => 'nullable|boolean',
        ]);

        $validated['is_subscribed'] = $request->has('is_subscribed') ? (bool) $request->is_subscribed : true;
        $validated['unsubscribe_token'] = Str::random(32);

        EmailContact::create($validated);

        return redirect()->route('email-marketing.contacts.index')->with('success', 'Kontak berhasil ditambahkan.');
    }

    public function edit(EmailContact $contact)
    {
        return view('email-marketing.contacts.edit', compact('contact'));
    }

    public function update(Request $request, EmailContact $contact)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:email_contacts,email,' . $contact->id,
            'company' => 'nullable|string|max:255',
            'is_subscribed' => 'nullable|boolean',
        ]);

        $validated['is_subscribed'] = $request->has('is_subscribed') ? (bool) $request->is_subscribed : false;

        $contact->update($validated);

        return redirect()->route('email-marketing.contacts.index')->with('success', 'Kontak berhasil diperbarui.');
    }

    public function destroy(EmailContact $contact)
    {
        $contact->delete();
        return redirect()->route('email-marketing.contacts.index')->with('success', 'Kontak berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:email_contacts,id',
        ]);

        $count = EmailContact::whereIn('id', $request->ids)->delete();

        return redirect()->route('email-marketing.contacts.index')->with('success', "{$count} kontak berhasil dihapus.");
    }

    public function toggleSubscription(EmailContact $contact)
    {
        $contact->update([
            'is_subscribed' => !$contact->is_subscribed,
        ]);

        $status = $contact->is_subscribed ? 'diaktifkan (Subscribed)' : 'dinonaktifkan (Unsubscribed)';
        return back()->with('success', "Status langganan kontak berhasil {$status}.");
    }

    public function downloadTemplate()
    {
        return Excel::download(new EmailContactTemplateExport(), 'Template-Kontak-Email.xlsx');
    }

    public function exportExcel()
    {
        return Excel::download(new EmailContactExport(), 'Data-Kontak-Email-' . date('Y-m-d') . '.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            $import = new EmailContactImport();
            Excel::import($import, $request->file('file_excel'));

            $imported = $import->getImportedCount();
            $skipped = $import->getSkippedCount();

            return redirect()->route('email-marketing.contacts.index')->with(
                'success',
                "Import selesai: {$imported} kontak berhasil ditambahkan, {$skipped} kontak diabaikan (duplikat/tidak valid)."
            );
        } catch (\Exception $e) {
            return redirect()->route('email-marketing.contacts.index')->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}
