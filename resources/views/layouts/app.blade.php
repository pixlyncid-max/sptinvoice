<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Workly') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <!-- Vite CSS/JS (Alpine is included here) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased bg-background text-text-primary" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Content Wrapper -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            
            <!-- Navbar -->
            <x-navbar />

            <!-- Main Content Area -->
            <main class="w-full grow p-6 lg:p-8">
                <!-- Page Header -->
                <div class="sm:flex sm:items-center sm:justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">@yield('title')</h1>
                    </div>
                    <!-- Right actions -->
                    <div class="mt-4 sm:mt-0">
                        @yield('actions')
                    </div>
                </div>

                <x-alert />

                @yield('content')
            </main>
            
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
