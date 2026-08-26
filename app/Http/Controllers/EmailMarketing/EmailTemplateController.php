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
        $defaultBody = "<p>Yth, PT {{company}},</p>\n\n"
            . "<p>Perkenalkan, kami dari <strong>Konsultan Borneo</strong>, perusahaan yang bergerak di bidang konsultasi perpajakan, keuangan, perizinan, dan digital.</p>\n\n"
            . "<p>Kami memahami bahwa pengelolaan perpajakan perusahaan sering kali menghadapi berbagai tantangan, mulai dari administrasi dan pelaporan pajak, perubahan regulasi, hingga penanganan kewajiban perpajakan yang membutuhkan perhatian khusus. Apabila tidak ditangani dengan tepat, hal tersebut berpotensi menimbulkan risiko bagi perusahaan di kemudian hari.</p>\n\n"
            . "<p>Beberapa hal yang dapat menjadi perhatian Bapak/Ibu antara lain:</p>\n"
            . "<ul>\n"
            . "    <li>Perubahan dan pembaruan regulasi perpajakan yang berdampak pada kegiatan usaha.</li>\n"
            . "    <li>Optimalisasi kepatuhan dan administrasi perpajakan perusahaan.</li>\n"
            . "    <li>Peningkatan pengawasan oleh Direktorat Jenderal Pajak.</li>\n"
            . "    <li>Pentingnya <em>tax planning</em> yang tepat untuk mendukung efisiensi dan keberlanjutan bisnis.</li>\n"
            . "</ul>\n\n"
            . "<p>Melalui email ini, kami ingin membuka kesempatan untuk berdiskusi mengenai kondisi dan kendala perpajakan yang saat ini dihadapi oleh perusahaan Bapak/Ibu.</p>\n\n"
            . "<p>Tim konsultan kami siap membantu memberikan pendampingan sesuai dengan kebutuhan perusahaan, mulai dari evaluasi permasalahan, konsultasi, hingga penyusunan solusi dan strategi perpajakan yang lebih tepat.</p>\n\n"
            . "<p>Apabila Bapak/Ibu berkenan untuk berdiskusi lebih lanjut, silakan menghubungi tim kami melalui:<br>\n"
            . "📞 <strong>0857-1153-3331</strong><br>\n"
            . "Instagram: <strong>@konsultanborneo</strong></p>\n\n"
            . "<p>Kami percaya bahwa setiap perusahaan memiliki kondisi dan kebutuhan yang berbeda. Oleh karena itu, kami terbuka untuk mendengarkan terlebih dahulu kendala yang Bapak/Ibu hadapi sebelum menentukan solusi yang paling sesuai.</p>\n\n"
            . "<p>Semoga kami dapat berkesempatan untuk berdiskusi dan membantu mendukung kelancaran pengelolaan perpajakan perusahaan Bapak/Ibu.</p>\n\n"
            . "<p>Salam hangat,<br>\n<strong>Konsultan Borneo</strong></p>\n\n"
            . "<p style=\"font-size: 12px; color: #64748b; margin-top: 30px; border-top: 1px dashed #cbd5e1; padding-top: 10px;\">\n"
            . "Jika Anda tidak ingin menerima informasi ini lagi, silakan <a href=\"{{unsubscribe_url}}\" style=\"color: #94a3b8; text-decoration: underline;\">berhenti berlangganan di sini</a>.\n</p>";

        return view('email-marketing.templates.create', compact('defaultBody'));
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
