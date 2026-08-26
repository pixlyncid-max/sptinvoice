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

        <!-- Social Media Buttons -->
        <div style="background-color: #0f172a; padding: 22px 20px; text-align: center;">
            <p style="color: #94a3b8; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 12px 0;">
                Terhubung Dengan Kami:
            </p>
            
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                <tr>
                    <!-- WhatsApp -->
                    <td style="padding: 0 6px;">
                        <a href="https://wa.me/6285711533331" target="_blank" style="display: inline-block; background-color: #25D366; color: #ffffff; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: bold;">
                            💬 WhatsApp
                        </a>
                    </td>
                    <!-- Instagram -->
                    <td style="padding: 0 6px;">
                        <a href="https://www.instagram.com/konsultanborneo" target="_blank" style="display: inline-block; background-color: #E1306C; color: #ffffff; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: bold;">
                            📷 Instagram
                        </a>
                    </td>
                    <!-- Facebook -->
                    <td style="padding: 0 6px;">
                        <a href="https://www.facebook.com/konsultanborneo" target="_blank" style="display: inline-block; background-color: #1877F2; color: #ffffff; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: bold;">
                            📘 Facebook
                        </a>
                    </td>
                    <!-- Threads -->
                    <td style="padding: 0 6px;">
                        <a href="https://www.threads.net/@konsultanborneo" target="_blank" style="display: inline-block; background-color: #000000; color: #ffffff; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: bold; border: 1px solid #334155;">
                            🧵 Threads
                        </a>
                    </td>
                    <!-- TikTok -->
                    <td style="padding: 0 6px;">
                        <a href="https://www.tiktok.com/@konsultanborneo" target="_blank" style="display: inline-block; background-color: #010101; color: #ffffff; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: bold; border: 1px solid #334155;">
                            🎵 TikTok
                        </a>
                    </td>
                </tr>
            </table>

            <div style="margin-top: 16px; font-size: 11px; color: #64748b; line-height: 1.5;">
                <p style="margin: 0;">&copy; {{ date('Y') }} Konsultan Borneo. All rights reserved.</p>
                <p style="margin: 4px 0 0 0;">Konsultasi Perpajakan, Keuangan, Perizinan & Digital</p>
            </div>
        </div>
    </div>
</body>
</html>
