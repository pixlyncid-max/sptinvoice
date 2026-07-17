<!-- Sidebar -->
<div>
    <!-- Sidebar backdrop (mobile) -->
    <div class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden"
         x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         @click="sidebarOpen = false"
         aria-hidden="true" x-cloak></div>

    <!-- Sidebar -->
    <div id="sidebar"
         class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-[260px] shrink-0 transition-transform duration-200 ease-in-out"
         style="background: linear-gradient(180deg, #2c3e6b 0%, #1e2d52 100%);"
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'">
         
        <!-- Sidebar header -->
        <div class="flex justify-between items-center px-5 mb-8 mt-6">
            <!-- Logo -->
            <a class="flex items-center justify-start" href="{{ route('dashboard') }}">
                <img src="{{ asset('storage/worklyy.png') }}" alt="Workly" class="h-9 w-auto object-contain">
            </a>
            <!-- Close button -->
            <button class="lg:hidden text-slate-300 hover:text-white" @click="sidebarOpen = false" aria-controls="sidebar" :aria-expanded="sidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                </svg>
            </button>
        </div>

        <!-- Links -->
        <div class="flex-1 px-3">
            @if(!Auth::user()->isSuperadmin())
            <nav class="space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect></svg>
                    <span class="ml-3">Dashboard</span>
                </a>

                <!-- Clients -->
                <a href="{{ route('clients.index') }}" class="sidebar-nav-item {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span class="ml-3">Clients</span>
                </a>

                <!-- Invoices -->
                <a href="{{ route('invoices.index') }}" class="sidebar-nav-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    <span class="ml-3">Invoice</span>
                </a>

                <!-- Penawaran -->
                <a href="{{ route('penawaran.index') }}" class="sidebar-nav-item {{ request()->routeIs('penawaran.*') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg>
                    <span class="ml-3">Penawaran</span>
                </a>

                <!-- Rate Card -->
                <a href="{{ route('rate-cards.index') }}" class="sidebar-nav-item {{ request()->routeIs('rate-cards.*') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                    <span class="ml-3">Rate Card</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="text-[10px] uppercase text-white/30 font-semibold tracking-widest px-4 mb-2">Kepegawaian</p>
                </div>

                <!-- Karyawan -->
                <a href="{{ route('employees.index') }}" class="sidebar-nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                    <span class="ml-3">Karyawan</span>
                </a>

                <!-- Absensi -->
                <a href="{{ route('attendance.index') }}" class="sidebar-nav-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span class="ml-3">Absensi</span>
                </a>

                <!-- Slip Gaji -->
                <a href="{{ route('salary.index') }}" class="sidebar-nav-item {{ request()->routeIs('salary.*') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    <span class="ml-3">Slip Gaji</span>
                </a>

                <!-- Inventaris -->
                <a href="{{ route('inventaris.index') }}" class="sidebar-nav-item {{ request()->routeIs('inventaris.*') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    <span class="ml-3">Inventaris</span>
                </a>
            </nav>
            @endif



            @if(!Auth::user()->isSuperadmin())
            <div class="mt-8">
                <p class="text-[10px] uppercase text-white/30 font-semibold tracking-widest px-4 mb-3">Settings</p>
                <nav class="space-y-1">
                    <a href="{{ route('profile.edit') }}" class="sidebar-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"></path></svg>
                        <span class="ml-3">Settings</span>
                    </a>
                </nav>
            </div>
            @endif

            @if(Auth::user()->isSuperadmin())
            <div class="mt-8">
                <p class="text-[10px] uppercase text-white/30 font-semibold tracking-widest px-4 mb-3">SUPERADMIN</p>
                <nav class="space-y-1">
                    <a href="{{ route('inventaris-categories.index') }}" class="sidebar-nav-item {{ request()->routeIs('inventaris-categories.*') ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <span class="ml-3">Kategori Inventaris</span>
                    </a>
                    <a href="{{ route('divisions.index') }}" class="sidebar-nav-item {{ request()->routeIs('divisions.*') ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <span class="ml-3">Divisi</span>
                    </a>
                    <a href="{{ route('positions.index') }}" class="sidebar-nav-item {{ request()->routeIs('positions.*') ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <polyline points="17 11 19 13 23 9"></polyline>
                        </svg>
                        <span class="ml-3">Jabatan</span>
                    </a>
                    <a href="{{ route('penawaran-templates.index') }}" class="sidebar-nav-item {{ request()->routeIs('penawaran-templates.*') ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="12" y1="18" x2="12" y2="12"></line>
                            <line x1="9" y1="15" x2="15" y2="15"></line>
                        </svg>
                        <span class="ml-3">Template Penawaran</span>
                    </a>
                    <a href="{{ route('banks.index') }}" class="sidebar-nav-item {{ request()->routeIs('banks.*') ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect>
                            <line x1="2" y1="10" x2="22" y2="10"></line>
                        </svg>
                        <span class="ml-3">Kelola Bank</span>
                    </a>
                    <a href="{{ route('settings.index') }}" class="sidebar-nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"></path>
                        </svg>
                        <span class="ml-3">Konfigurasi Sistem</span>
                    </a>
                    <a href="{{ route('users.index') }}" class="sidebar-nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span class="ml-3">Kelola Admin</span>
                    </a>
                </nav>
            </div>
            @endif
        </div>
        
        <!-- Sidebar footer -->
        <div class="px-3 pb-5 pt-4 mt-auto border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-nav-item w-full text-red-300 hover:text-red-200 hover:bg-red-500/15">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span class="ml-3">Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>
