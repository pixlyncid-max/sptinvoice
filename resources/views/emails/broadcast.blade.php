@php
    $whatsappUrl = \App\Models\Setting::get('social_whatsapp_url', 'https://wa.me/6285711533331');
    $instagramUrl = \App\Models\Setting::get('social_instagram_url', 'https://www.instagram.com/konsultanborneo');
    $facebookUrl = \App\Models\Setting::get('social_facebook_url', 'https://www.facebook.com/konsultanborneo');
    $threadsUrl = \App\Models\Setting::get('social_threads_url', 'https://www.threads.net/@konsultanborneo');
    $tiktokUrl = \App\Models\Setting::get('social_tiktok_url', 'https://www.tiktok.com/@konsultanborneo');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailSubject ?? 'Konsultan Borneo' }}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #1e293b; background-color: #f1f5f9; margin: 0; padding: 20px;">
    <div style="max-width: 620px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        
        <!-- Email Body Content -->
        <div style="padding: 32px 28px 24px 28px; font-size: 15px; line-height: 1.7; color: #334155;">
            {!! $content !!}
        </div>

        <!-- Fixed Footer Banner Image -->
        <div style="width: 100%; text-align: center; background-color: #f8fafc; border-top: 1px solid #f1f5f9; line-height: 0;">
            <img src="{{ url('storage/footer-email-fix.png') }}" alt="Konsultan Borneo" style="width: 100%; max-width: 100%; height: auto; display: block; border: 0;" />
        </div>

        <!-- Social Media Logos & Footer Section -->
        <div style="background-color: #0b132b; padding: 24px 20px; text-align: center;">
            <p style="color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin: 0 0 14px 0;">
                Terhubung Dengan Kami
            </p>
            
            <!-- Social Media Official Icons Table -->
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                <tr>
                    @if($whatsappUrl)
                    <td style="padding: 0 8px;" align="center" valign="middle">
                        <a href="{{ $whatsappUrl }}" target="_blank" title="WhatsApp" style="text-decoration: none; display: inline-block;">
                            <img src="{{ url('images/social/whatsapp.png') }}" width="36" height="36" alt="WhatsApp" style="display: block; border: 0; width: 36px; height: 36px; border-radius: 50%;" />
                        </a>
                    </td>
                    @endif

                    @if($instagramUrl)
                    <td style="padding: 0 8px;" align="center" valign="middle">
                        <a href="{{ $instagramUrl }}" target="_blank" title="Instagram" style="text-decoration: none; display: inline-block;">
                            <img src="{{ url('images/social/instagram.png') }}" width="36" height="36" alt="Instagram" style="display: block; border: 0; width: 36px; height: 36px; border-radius: 50%;" />
                        </a>
                    </td>
                    @endif

                    @if($facebookUrl)
                    <td style="padding: 0 8px;" align="center" valign="middle">
                        <a href="{{ $facebookUrl }}" target="_blank" title="Facebook" style="text-decoration: none; display: inline-block;">
                            <img src="{{ url('images/social/facebook.png') }}" width="36" height="36" alt="Facebook" style="display: block; border: 0; width: 36px; height: 36px; border-radius: 50%;" />
                        </a>
                    </td>
                    @endif

                    @if($threadsUrl)
                    <td style="padding: 0 8px;" align="center" valign="middle">
                        <a href="{{ $threadsUrl }}" target="_blank" title="Threads" style="text-decoration: none; display: inline-block;">
                            <img src="{{ url('images/social/threads.png') }}" width="36" height="36" alt="Threads" style="display: block; border: 0; width: 36px; height: 36px; border-radius: 50%;" />
                        </a>
                    </td>
                    @endif

                    @if($tiktokUrl)
                    <td style="padding: 0 8px;" align="center" valign="middle">
                        <a href="{{ $tiktokUrl }}" target="_blank" title="TikTok" style="text-decoration: none; display: inline-block;">
                            <img src="{{ url('images/social/tiktok.png') }}" width="36" height="36" alt="TikTok" style="display: block; border: 0; width: 36px; height: 36px; border-radius: 50%;" />
                        </a>
                    </td>
                    @endif
                </tr>
            </table>

            <div style="margin-top: 18px; font-size: 11px; color: #64748b; line-height: 1.6;">
                <p style="margin: 0; color: #cbd5e1; font-weight: 500;">Konsultan Borneo</p>
                <p style="margin: 2px 0 0 0; color: #64748b;">Konsultasi Perpajakan, Keuangan, Perizinan & Digital</p>
                <p style="margin: 6px 0 0 0; font-size: 10px; color: #475569;">&copy; {{ date('Y') }} All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
