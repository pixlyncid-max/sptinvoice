<?php

namespace App\Http\Controllers\EmailMarketing;

use App\Http\Controllers\Controller;
use App\Models\EmailContact;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailTemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailTemplate::with('creator');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $templates = $query->latest()->paginate(10)->withQueryString();

        return view('email-marketing.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('email-marketing.templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        $validated['created_by'] = Auth::id();

        EmailTemplate::create($validated);

        return redirect()->route('email-marketing.templates.index')->with('success', 'Template email berhasil dibuat.');
    }

    public function edit(EmailTemplate $template)
    {
        return view('email-marketing.templates.edit', compact('template'));
    }

    public function update(Request $request, EmailTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        $template->update($validated);

        return redirect()->route('email-marketing.templates.index')->with('success', 'Template email berhasil diperbarui.');
    }

    public function destroy(EmailTemplate $template)
    {
        $template->delete();
        return redirect()->route('email-marketing.templates.index')->with('success', 'Template email berhasil dihapus.');
    }

    public function duplicate(EmailTemplate $template)
    {
        $newTemplate = $template->replicate();
        $newTemplate->name = $template->name . ' (Salinan)';
        $newTemplate->created_by = Auth::id();
        $newTemplate->save();

        return redirect()->route('email-marketing.templates.index')->with('success', 'Template email berhasil diduplikasi.');
    }

    public function preview(EmailTemplate $template, Request $request)
    {
        // Pick a sample contact if available, or sample dummy
        $contact = null;
        if ($request->filled('contact_id')) {
            $contact = EmailContact::find($request->contact_id);
        }
        if (!$contact) {
            $contact = EmailContact::where('is_subscribed', true)->first();
        }

        $sampleData = [
            'name' => $contact ? $contact->name : 'Budi Santoso',
            'email' => $contact ? $contact->email : 'budi@example.com',
            'company' => $contact ? ($contact->company ?? 'PT Maju Bersama') : 'PT Maju Bersama',
            'unsubscribe_url' => $contact ? $contact->unsubscribe_url : url('/email/unsubscribe/sample-token'),
        ];

        $rendered = $template->render($sampleData);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'subject' => $rendered['subject'],
                'body' => $rendered['body'],
            ]);
        }

        return view('email-marketing.templates.preview', compact('template', 'rendered', 'sampleData'));
    }
}
