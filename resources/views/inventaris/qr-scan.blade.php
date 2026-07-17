<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Inventaris Aset - {{ $inventaris->kode_barang }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS (via CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f3f4f6;
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 16px 16px;
        }
    </style>
</head>
<body class="antialiased text-slate-800 min-h-screen flex flex-col justify-between py-6 px-4">
    <!-- Main content container -->
    <div class="w-full max-w-md mx-auto my-auto bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden transition-all duration-300 hover:shadow-2xl">
        <!-- Banner/Header -->
        <div class="relative bg-slate-900 text-white p-6 overflow-hidden">
            <!-- Decorative gradient orb -->
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-600 rounded-full blur-2xl opacity-60"></div>
            
            <div class="relative flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold tracking-tight text-white/90">Inventaris Barang</h1>
                    <p class="text-xs text-slate-400">Sistem Informasi Aset Perusahaan</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-mono font-extrabold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                    {{ $inventaris->kode_barang }}
                </span>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-6 space-y-6">
            <!-- Asset Main Info -->
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block mb-1">Nama Barang</span>
                <h2 class="text-xl font-bold text-slate-900 leading-tight">{{ $inventaris->nama_barang }}</h2>
                @if($inventaris->nama_merk)
                    <p class="text-sm text-slate-500 mt-0.5">Merk: <span class="font-semibold text-slate-700">{{ $inventaris->nama_merk }}</span></p>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-5">
                <!-- Kategori -->
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block mb-1">Kategori</span>
                    @php
                        $catColors = [
                            'elektronik' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                            'furniture' => 'bg-amber-50 text-amber-700 border-amber-100',
                            'alat_kerja' => 'bg-sky-50 text-sky-700 border-sky-100',
                            'kendaraan' => 'bg-purple-50 text-purple-700 border-purple-100'
                        ];
                        $catColor = $catColors[$inventaris->kategori] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $catColor }}">
                        {{ $inventaris->kategori_label }}
                    </span>
                </div>

                <!-- Tanggal Beli -->
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block mb-1">Tanggal Beli</span>
                    <span class="text-sm font-medium text-slate-800">{{ $inventaris->tanggal_beli->format('d M Y') }}</span>
                </div>
            </div>

            <!-- Kondisi -->
            <div class="border-t border-slate-100 pt-5">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block mb-1.5">Kondisi Barang</span>
                @php
                    $condColors = [
                        'baik' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        'rusak_ringan' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                        'rusak_berat' => 'bg-red-50 text-red-700 border-red-100'
                    ];
                    $condColor = $condColors[$inventaris->kondisi] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold border {{ $condColor }}">
                    {{ $inventaris->kondisi_label }}
                </span>
            </div>

            <!-- Pengguna / Penanggung Jawab -->
            <div class="border-t border-slate-100 pt-5">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block mb-2">Penanggung Jawab / User</span>
                @if($inventaris->employee)
                    <div class="flex items-center bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-700 font-bold text-base border border-slate-300">
                            {{ substr($inventaris->employee->nama, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-bold text-slate-900">{{ $inventaris->employee->nama }}</div>
                            @if(!empty($inventaris->employee->position->nama) || !empty($inventaris->employee->division->nama))
                            <div class="text-xs text-slate-600 mt-0.5">
                                {{ implode(' • ', array_filter([$inventaris->employee->position->nama ?? null, $inventaris->employee->division->nama ?? null])) }}
                            </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 p-3 rounded-xl text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <span class="font-medium">Aset ini belum ditugaskan/diserahkan ke Karyawan.</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer / Branding -->
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
            <span>Verified Asset System</span>
            <span>&copy; {{ date('Y') }} Company Portal</span>
        </div>
    </div>

    <!-- Small footer -->
    <div class="text-center text-xs text-slate-400 py-4">
        Sistem Informasi Manajemen Aset Perusahaan • Terproteksi enkripsi QR
    </div>
</body>
</html>
