<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berhenti Berlangganan (Unsubscribe) - {{ config('app.name', 'SPT Invoice') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 flex items-center justify-center min-h-screen p-4" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg border border-slate-200 p-8 text-center">
        @if(session('resubscribed'))
            <!-- Resubscribed State -->
            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Langganan Diaktifkan Kembali</h1>
            <p class="text-sm text-slate-600 mb-6">
                Alamat email Anda telah didaftarkan kembali untuk menerima pembaruan informasi dan penawaran dari kami.
            </p>
        @else
            <!-- Unsubscribed State -->
            <div class="w-16 h-16 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Berhasil Berhenti Berlangganan</h1>
            <p class="text-sm text-slate-600 mb-6">
                @if($contact)
                    Alamat email <strong>{{ $contact->email }}</strong> tidak akan menerima email campaign broadcast marketing berikutnya dari kami.
                @else
                    Alamat email Anda telah berhasil dihapus dari daftar penerima email marketing kami.
                @endif
            </p>

            @if($contact)
            <div class="pt-4 border-t border-slate-100">
                <p class="text-xs text-slate-400 mb-3">Tidak sengaja mengklik tautan ini?</p>
                <form action="{{ route('email-marketing.resubscribe', $token) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                        Klik di sini untuk berlangganan kembali
                    </button>
                </form>
            </div>
            @endif
        @endif

        <div class="mt-8 text-xs text-slate-400">
            &copy; {{ date('Y') }} {{ config('app.name', 'SPT Invoice') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
