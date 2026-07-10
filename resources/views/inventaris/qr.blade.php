@extends('layouts.app')

@section('title')
Cetak Label Barcode - {{ $inventaris->kode_barang }}
@endsection

@section('actions')
<div class="flex gap-2">
    <a href="{{ route('inventaris.show', $inventaris) }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50 transition">
        Lihat Detail Barang
    </a>
    <a href="{{ route('inventaris.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50 transition">
        Kembali
    </a>
</div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Identitas Label & Sticker Aset</h3>
        <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-mono font-bold bg-slate-200 text-slate-800 border border-slate-300">
            {{ $inventaris->kode_barang }}
        </span>
    </div>
    
    <div class="p-8 flex flex-col items-center text-center">
        <!-- Asset Title Context -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-slate-900">{{ $inventaris->nama_barang }}</h2>
            @if($inventaris->nama_merk)
                <p class="text-sm text-slate-500 mt-1">Merk: <span class="font-semibold text-slate-700">{{ $inventaris->nama_merk }}</span></p>
            @endif
        </div>

        <!-- Barcode Container -->
        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 inline-block shadow-inner" id="barcode-box">
            <div class="mx-auto flex flex-col justify-center bg-white p-6 rounded-lg items-center gap-3">
                @php
                    $generator = new \Picqer\Barcode\BarcodeGeneratorSVG();
                @endphp
                <div class="overflow-hidden flex justify-center p-2 bg-white rounded-lg">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->margin(0)->generate(route('inventaris.qr-scan', $inventaris->kode_barang)) !!}
                </div>
                <div class="font-bold text-lg text-slate-800 tracking-wider mt-2">
                    {{ $inventaris->kode_barang }}
                </div>
            </div>
        </div>
        
        <p class="text-xs text-slate-500 mt-4 leading-relaxed max-w-[320px]">
            Scan Barcode di atas menggunakan scanner barcode atau lihat detail data aset ini secara publik.
        </p>

        <div class="w-full border-t border-slate-100 my-6"></div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full max-w-md">
            <button onclick="printQRCodeLabel()" class="inline-flex justify-center items-center gap-1.5 px-4 py-2.5 border border-transparent text-sm font-semibold rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-750 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak Label Sticker
            </button>
            <a href="{{ route('inventaris.qr-scan', $inventaris->kode_barang) }}" target="_blank" class="inline-flex justify-center items-center gap-1.5 px-4 py-2.5 border border-slate-300 text-sm font-semibold rounded-md shadow-xs text-slate-700 bg-white hover:bg-slate-50 transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                Buka Link Scan Publik
            </a>
        </div>
    </div>
</div>

<!-- Print-only hidden container for printing sticker labels -->
<div id="print-label-section" class="hidden">
    <!-- Outer container for the sticker matching the blue design -->
    <div style="width: 70mm; height: 25mm; box-sizing: border-box; font-family: 'Arial', sans-serif; background: url('{{ asset('storage/Background.png') }}') no-repeat center center; background-size: cover; background-color: #0d47a1; color: white; margin: auto; position: relative; padding: 2mm; display: flex; align-items: center; border-radius: 2mm;">
        
        <!-- Left: QR Code Box -->
        <div style="background: white; border-radius: 1mm; padding: 1mm; display: flex; align-items: center; justify-content: center; width: 21mm; height: 21mm; box-sizing: border-box; flex-shrink: 0; margin-right: 2.5mm;">
            <div style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(65)->margin(0)->generate(route('inventaris.qr-scan', $inventaris->kode_barang)) !!}
            </div>
        </div>

        <!-- Right: Text content -->
        <div style="display: flex; flex-direction: column; justify-content: center; flex-grow: 1; text-align: left; line-height: 1.1;">
            <div style="font-size: 4pt; letter-spacing: 0.2px; opacity: 0.9; margin-bottom: 0.5mm;">PROPERTY OF:</div>
            <div style="font-size: 6.5pt; font-weight: 900; letter-spacing: 0.2px; white-space: nowrap; margin-bottom: 1.5mm;">PT GANESHA ARTA ADIWANGSA</div>
            <div style="font-size: 4pt; opacity: 0.9; margin-bottom: 0.5mm;">Asset No.</div>
            <div style="font-size: 8pt; font-weight: bold; letter-spacing: 0.2px; white-space: nowrap;">{{ $inventaris->kode_barang }}</div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function printQRCodeLabel() {
        const printContent = document.getElementById("print-label-section").innerHTML;
        
        // Custom document window for printing to prevent main layout corruption
        const printWindow = window.open('', '_blank', 'width=600,height=400');
        printWindow.document.write('<html><head><title>Cetak Label Barcode - {{ $inventaris->kode_barang }}</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('@page { size: 70mm 25mm; margin: 0; } body { margin: 0; padding: 0; background: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; overflow: hidden; }');
        printWindow.document.write('</style></head><body>');
        printWindow.document.write(printContent);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        
        // Give browser a short moment to load/render
        setTimeout(function() {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }, 350);
    }
</script>
@endpush
