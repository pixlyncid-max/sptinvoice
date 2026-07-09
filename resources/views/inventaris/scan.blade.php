@extends('layouts.app')

@section('title', 'Scan QR Code Aset')

@section('actions')
<a href="{{ route('inventaris.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50 transition">
    Kembali
</a>
@endsection

@section('content')
<div class="max-w-2xl mx-auto grid grid-cols-1 gap-6">
    <!-- Camera Scanner Panel -->
    <div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                Scan Menggunakan Kamera HP / Web
            </h3>
        </div>

        <div class="p-6">
            <!-- Camera Selector and Controls -->
            <div class="mb-5 flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label for="camera-select" class="block text-xs font-semibold text-slate-500 uppercase mb-1">Pilih Kamera</label>
                    <select id="camera-select" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border bg-white">
                        <option value="">Mencari kamera aktif...</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button id="start-btn" onclick="startScanner()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark transition">
                        Aktifkan Kamera
                    </button>
                    <button id="stop-btn" onclick="stopScanner()" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 transition hidden">
                        Matikan Kamera
                    </button>
                </div>
            </div>

            <!-- Viewfinder Area -->
            <div class="relative bg-slate-950 rounded-xl overflow-hidden aspect-video max-w-lg mx-auto flex items-center justify-center border border-slate-800" id="viewfinder-container">
                <!-- Custom Scan Frame Overlay -->
                <div id="scan-frame" class="absolute w-48 h-48 sm:w-60 sm:h-60 border-2 border-indigo-500 rounded-lg pointer-events-none z-10 transition-colors duration-300 flex items-center justify-center">
                    <!-- Corner Brackets -->
                    <div class="absolute -top-1.5 -left-1.5 w-6 h-6 border-t-4 border-l-4 border-indigo-400 rounded-tl-sm"></div>
                    <div class="absolute -top-1.5 -right-1.5 w-6 h-6 border-t-4 border-r-4 border-indigo-400 rounded-tr-sm"></div>
                    <div class="absolute -bottom-1.5 -left-1.5 w-6 h-6 border-b-4 border-l-4 border-indigo-400 rounded-bl-sm"></div>
                    <div class="absolute -bottom-1.5 -right-1.5 w-6 h-6 border-b-4 border-r-4 border-indigo-400 rounded-br-sm"></div>
                    
                    <!-- Laser Line Scanning Effect -->
                    <div class="w-full h-0.5 bg-indigo-500 shadow-[0_0_10px_#6366f1] animate-[scan_2.5s_ease-in-out_infinite]"></div>
                </div>

                <!-- Live Feed Holder -->
                <div id="reader" class="w-full h-full bg-slate-950"></div>

                <!-- Text placeholder/loading -->
                <div id="viewfinder-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 p-6 text-center z-0">
                    <svg class="w-12 h-12 text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-300">Kamera Belum Aktif</p>
                    <p class="text-xs text-slate-500 mt-1">Klik tombol "Aktifkan Kamera" dan izinkan akses webcam untuk memulai pemindaian.</p>
                </div>
            </div>

            <!-- Status Logs -->
            <div id="status-bar" class="mt-4 bg-slate-50 border border-slate-200 rounded-lg p-3 text-sm text-center text-slate-500 font-medium">
                Siap melakukan pemindaian aset...
            </div>
        </div>
    </div>

    <!-- Fallback File Scanner Panel -->
    <div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Alternatif: Upload Gambar QR</h3>
        </div>
        <div class="p-6">
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Upload Foto Sticker QR Code</label>
            <div class="flex items-center justify-center w-full">
                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100 transition">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                        <p class="text-xs font-medium text-slate-600">Klik untuk upload atau drag & drop file gambar</p>
                        <p class="text-[10px] text-slate-400 mt-1">Format PNG, JPG, JPEG</p>
                    </div>
                    <input type="file" id="qr-file-input" accept="image/*" class="hidden" onchange="scanFromFile(this)" />
                </label>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes scan {
        0%, 100% { transform: translateY(-45%); }
        50% { transform: translateY(45%); }
    }
</style>

@push('scripts')
<!-- Load HTML5-QRCode Library from CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" integrity="sha512-r6rDA7W6ZeQhKF8GP75xNHbKjaVch3IPd5Ydz9q4TOOTEVv/pLDQ4j0tIBfknU9h9yW0xP4A2A5sK424deePbQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    let html5QrCode = null;
    const viewfinder = document.getElementById("viewfinder-placeholder");
    const scanFrame = document.getElementById("scan-frame");
    const statusBar = document.getElementById("status-bar");
    const cameraSelect = document.getElementById("camera-select");
    const startBtn = document.getElementById("start-btn");
    const stopBtn = document.getElementById("stop-btn");

    document.addEventListener("DOMContentLoaded", function() {
        // Enforce scan frame hide initially
        scanFrame.style.display = "none";

        // Query available cameras on page load
        Html5Qrcode.getCameras().then(cameras => {
            if (cameras && cameras.length > 0) {
                cameraSelect.innerHTML = "";
                cameras.forEach((camera, index) => {
                    const opt = document.createElement("option");
                    opt.value = camera.id;
                    opt.text = camera.label || `Kamera ${index + 1}`;
                    cameraSelect.appendChild(opt);
                });
            } else {
                cameraSelect.innerHTML = '<option value="">Tidak ada kamera terdeteksi</option>';
            }
        }).catch(err => {
            console.error("Gagal mendeteksi kamera:", err);
            cameraSelect.innerHTML = '<option value="">Izin akses kamera diblokir / tidak didukung</option>';
        });
    });

    // Play Synthesizer success chime (pure JS, no audio files)
    function playBeepSuccess() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = "sine";
            osc.frequency.setValueAtTime(880, ctx.currentTime); // high A pitch
            gain.gain.setValueAtTime(0.1, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.35);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.35);
        } catch(e) {
            console.log("Synthesizer audio blocked by user gesture restrictions.");
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        console.log("QR Code Terbaca:", decodedText);
        
        // Find asset code format GAA-number (case insensitive)
        const match = decodedText.match(/GAA-\d+/i);
        if (match) {
            playBeepSuccess();
            const assetCode = match[0].toUpperCase();
            
            // Visual green feedback
            scanFrame.classList.remove("border-indigo-500");
            scanFrame.classList.add("border-emerald-500", "bg-emerald-500/10");
            statusBar.innerHTML = `<span class="text-emerald-600 font-bold">Aset Terdeteksi: ${assetCode}! Mengalihkan...</span>`;
            
            // Stop camera and redirect
            stopScanner();
            
            setTimeout(() => {
                // Redirect via our bridge route
                window.location.href = "{{ url('inventaris/code') }}/" + assetCode;
            }, 800);
        } else {
            statusBar.innerHTML = '<span class="text-red-500">QR Code valid, tapi bukan stiker aset GAA resmi!</span>';
            scanFrame.classList.remove("border-indigo-500");
            scanFrame.classList.add("border-red-500");
            setTimeout(() => {
                scanFrame.classList.add("border-indigo-500");
                scanFrame.classList.remove("border-red-500");
            }, 2000);
        }
    }

    function startScanner() {
        const cameraId = cameraSelect.value;
        if (!cameraId) {
            alert("Harap pilih kamera terlebih dahulu.");
            return;
        }

        // Hide viewfinder placeholder, show frame
        viewfinder.style.display = "none";
        scanFrame.style.display = "flex";
        startBtn.classList.add("hidden");
        stopBtn.classList.remove("hidden");
        statusBar.innerHTML = "Menghubungkan ke streaming kamera...";

        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            cameraId, 
            {
                fps: 12,
                qrbox: (width, height) => {
                    const size = Math.min(width, height) * 0.70;
                    return { width: size, height: size };
                }
            },
            onScanSuccess,
            (errorMessage) => {
                // verbose scanning, keep status silent during scans
            }
        ).then(() => {
            statusBar.innerHTML = '<span class="text-indigo-600">Kamera Aktif. Tempatkan sticker QR Code di dalam bingkai.</span>';
        }).catch(err => {
            console.error("Gagal mengaktifkan kamera:", err);
            statusBar.innerHTML = '<span class="text-red-500">Gagal mengakses kamera. Pastikan akses diizinkan.</span>';
            stopScanner();
        });
    }

    function stopScanner() {
        startBtn.classList.remove("hidden");
        stopBtn.classList.add("hidden");
        scanFrame.style.display = "none";
        viewfinder.style.display = "flex";
        statusBar.innerHTML = "Kamera dimatikan.";

        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode = null;
            }).catch(err => console.error("Error stopping camera stream:", err));
        }
    }

    // Fallback file scanner parsing
    function scanFromFile(input) {
        if (!input.files || input.files.length === 0) return;
        const file = input.files[0];
        
        statusBar.innerHTML = "Membaca gambar QR Code...";
        const fileScanner = new Html5Qrcode("reader");
        
        fileScanner.scanFile(file, true)
            .then(decodedText => {
                onScanSuccess(decodedText, null);
                fileScanner.clear();
            })
            .catch(err => {
                console.error("File scanning error:", err);
                statusBar.innerHTML = '<span class="text-red-500">Gagal mendeteksi QR Code di gambar ini. Pastikan gambar tajam dan jelas.</span>';
                fileScanner.clear();
            });
    }
</script>
@endpush
