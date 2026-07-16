<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Semua QR Code Inventaris</title>
    <style>
        /* Base styling for browser view */
        body {
            margin: 0;
            padding: 20px;
            background: #f1f5f9;
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        /* Each label wrapper to show them clearly on screen */
        .label-wrapper {
            width: 70mm;
            height: 25mm;
            box-sizing: border-box;
            background: url('{{ asset('storage/Background.png') }}') no-repeat center center;
            background-size: cover;
            background-color: #0d47a1;
            color: white;
            position: relative;
            padding: 2mm;
            display: flex;
            align-items: center;
            border-radius: 2mm;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Print styles */
        @media print {
            @page {
                size: 70mm 25mm;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
                background: none;
                display: block;
            }
            .label-wrapper {
                box-shadow: none;
                border-radius: 0; /* Some printers might cut off border radius */
                page-break-after: always; /* Force each label onto a new page */
                margin: 0;
            }
            /* Remove page break from the last element to prevent empty page */
            .label-wrapper:last-child {
                page-break-after: auto;
            }
        }
    </style>
</head>
<body onload="window.print()">
    @foreach($inventarisList as $inventaris)
    <div class="label-wrapper">
        <!-- Left: QR Code Box -->
        <div style="background: white; border-radius: 1mm; padding: 1mm; display: flex; align-items: center; justify-content: center; width: 21mm; height: 21mm; box-sizing: border-box; flex-shrink: 0; margin-right: 2.5mm;">
            <div style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(65)->margin(0)->generate(route('inventaris.qr-scan', $inventaris->kode_barang)) !!}
            </div>
        </div>

        <!-- Right: Text content -->
        <div style="display: flex; flex-direction: column; justify-content: center; flex-grow: 1; text-align: left; line-height: 1.1;">
            <div style="font-size: 4pt; letter-spacing: 0.2px; opacity: 0.9; margin-bottom: 0.5mm;">PROPERTY OF:</div>
            <div style="font-size: 7pt; font-weight: bold; letter-spacing: 0.2px; white-space: nowrap; margin-bottom: 1.5mm;">
                PT GANESHA ARTA ADIWANGSA
            </div>
            <div style="font-size: 4pt; opacity: 0.9; margin-bottom: 0.5mm;">Asset No.</div>
            <div style="font-size: 7pt; font-weight: bold; letter-spacing: 0.2px; white-space: nowrap;">
                {{ $inventaris->kode_barang }}
            </div>
        </div>
    </div>
    @endforeach
</body>
</html>
