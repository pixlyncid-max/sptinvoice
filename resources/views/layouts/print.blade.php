<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Document') - {{ config('app.name', 'SPT Invoice') }}</title>
    
    <!-- Using Tailwind via CDN for easy printing if not built -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a3c5e',
                        accent: '#f59e0b',
                    }
                }
            }
        }
    </script>
    <style>
        @page { size: A4 portrait; margin: 0; }
        body { margin: 0; padding: 0; font-family: Arial, sans-serif; -webkit-print-color-adjust: exact; print-color-adjust: exact; background-color: white; }
        .print-container { width: 210mm; min-height: 297mm; padding: 0 20mm 20mm 20mm; margin: 0 auto; background: white; box-sizing: border-box; }
        @media print {
            body { background-color: white; }
            .print-container { box-shadow: none; margin: 0; padding: 0 15mm 15mm 15mm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-gray-100">


    <div class="print-container shadow-lg bg-white">
        @yield('content')
    </div>
</body>
</html>
