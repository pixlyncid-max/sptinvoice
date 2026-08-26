<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmailMarketing\EmailCampaignController;
use App\Http\Controllers\EmailMarketing\EmailContactController;
use App\Http\Controllers\EmailMarketing\EmailLogController;
use App\Http\Controllers\EmailMarketing\EmailTemplateController;
use App\Http\Controllers\EmailMarketing\UnsubscribeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GaaDataController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PenawaranController;
use App\Http\Controllers\PenawaranTemplateController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RateCardController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard (Redirects marketing to email marketing, karyawan to GAA)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Accessible by all roles)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes blocked for Marketing role (Business Core, GAA, Finance, Staff, etc.)
    Route::middleware(['not_marketing'])->group(function () {
        // Data GAA (Accessible by Admin, Superadmin, Karyawan)
        Route::get('gaa/template', [GaaDataController::class, 'downloadTemplate'])->name('gaa.template');
        Route::post('gaa/import', [GaaDataController::class, 'importExcel'])->name('gaa.import');
        Route::patch('gaa/{gaa}/toggle-checklist', [GaaDataController::class, 'toggleChecklist'])->name('gaa.toggle-checklist');
        Route::delete('gaa/destroy-all', [GaaDataController::class, 'destroyAll'])->name('gaa.destroy-all');
        Route::resource('gaa', GaaDataController::class);

        // Clients (Read-only for Karyawan, full access for Admin/Superadmin)
        Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('clients/{client}/detail', [ClientController::class, 'detail'])->name('clients.detail');

        // Invoices (Accessible by Admin, Superadmin, Karyawan)
        Route::resource('invoices', InvoiceController::class);
        Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'exportPdf'])->name('invoices.pdf');
        Route::patch('invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.status');
        Route::post('invoices/{invoice}/duplicate', [InvoiceController::class, 'duplicate'])->name('invoices.duplicate');

        // Penawaran (Accessible by Admin, Superadmin, Karyawan)
        Route::resource('penawaran', PenawaranController::class);
        Route::get('penawaran/{penawaran}/print', [PenawaranController::class, 'print'])->name('penawaran.print');
        Route::get('penawaran/{penawaran}/pdf', [PenawaranController::class, 'exportPdf'])->name('penawaran.pdf');
        Route::patch('penawaran/{penawaran}/status', [PenawaranController::class, 'updateStatus'])->name('penawaran.status');
        Route::post('penawaran/{penawaran}/convert', [PenawaranController::class, 'convertToInvoice'])->name('penawaran.convert');

        // Rate Card (Accessible by Admin, Superadmin, Karyawan)
        Route::resource('rate-cards', RateCardController::class);
        Route::get('rate-cards-items', [RateCardController::class, 'getItems'])->name('rate-cards.items');

        // Routes blocked for Karyawan role (Admin & Superadmin write operations)
        Route::middleware(['not_karyawan'])->group(function () {
            // Users (Kelola Admin & User)
            Route::resource('users', UserController::class)->except(['show']);

            // Clients Management (Create, Edit, Delete, Import, Export)
            Route::get('clients/template', [ClientController::class, 'downloadTemplate'])->name('clients.template');
            Route::post('clients/import', [ClientController::class, 'importExcel'])->name('clients.import');
            Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create');
            Route::post('clients', [ClientController::class, 'store'])->name('clients.store');
            Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
            Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update');
            Route::delete('clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

            // Karyawan (Employees)
            Route::post('employees/import', [EmployeeController::class, 'importExcel'])->name('employees.import');
            Route::get('employees/template', [EmployeeController::class, 'downloadTemplate'])->name('employees.template');
            Route::resource('employees', EmployeeController::class)->except(['show', 'create', 'edit']);

            // Absensi (Attendance)
            Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
            Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
            Route::post('attendance/daily', [AttendanceController::class, 'dailyStore'])->name('attendance.dailyStore');
            Route::post('attendance/bulk', [AttendanceController::class, 'bulkStore'])->name('attendance.bulkStore');
            Route::get('attendance/export/pdf', [AttendanceController::class, 'exportPdf'])->name('attendance.export.pdf');
            Route::get('attendance/export/excel', [AttendanceController::class, 'exportExcel'])->name('attendance.export.excel');

            // Slip Gaji (Salary)
            Route::get('salary', [SalaryController::class, 'index'])->name('salary.index');
            Route::get('salary/{employee}/slip', [SalaryController::class, 'printSlip'])->name('salary.slip');
            Route::post('salary/adjustment', [SalaryController::class, 'storeAdjustment'])->name('salary.adjustment');

            // Inventaris
            Route::get('inventaris/scan', [InventarisController::class, 'scanCamera'])->name('inventaris.scan');
            Route::get('inventaris/code/{kode}', [InventarisController::class, 'showByCode'])->name('inventaris.by-code');
            Route::get('inventaris/print-all-qr', [InventarisController::class, 'printAllQr'])->name('inventaris.print-all-qr');
            Route::resource('inventaris', \App\Http\Controllers\InventarisController::class)->parameters(['inventaris' => 'inventaris']);
            Route::resource('inventaris-categories', \App\Http\Controllers\InventarisCategoryController::class)->except(['create', 'show', 'edit']);
            Route::get('inventaris/{inventaris}/qr', [InventarisController::class, 'showQr'])->name('inventaris.qr');

            // Superadmin Routes
            Route::middleware(['superadmin'])->group(function () {
                Route::resource('divisions', DivisionController::class)->except(['show']);
                Route::resource('positions', PositionController::class)->except(['show']);
                Route::resource('penawaran-templates', PenawaranTemplateController::class)->except(['show']);
                Route::resource('banks', \App\Http\Controllers\BankController::class)->except(['show']);
                
                Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
                Route::patch('settings', [SettingController::class, 'update'])->name('settings.update');
            });
        });
    });

    // Email Marketing Routes (Accessible by Admin, Superadmin, and Marketing; Blocked for Karyawan)
    Route::middleware(['not_karyawan'])->prefix('email-marketing')->name('email-marketing.')->group(function () {
        // Contacts
        Route::get('contacts/template', [EmailContactController::class, 'downloadTemplate'])->name('contacts.template');
        Route::get('contacts/export', [EmailContactController::class, 'exportExcel'])->name('contacts.export');
        Route::post('contacts/import', [EmailContactController::class, 'importExcel'])->name('contacts.import');
        Route::delete('contacts/bulk-delete', [EmailContactController::class, 'bulkDelete'])->name('contacts.bulk-delete');
        Route::patch('contacts/{contact}/toggle-subscription', [EmailContactController::class, 'toggleSubscription'])->name('contacts.toggle-subscription');
        Route::resource('contacts', EmailContactController::class)->except(['show']);

        // Templates
        Route::post('templates/{template}/duplicate', [EmailTemplateController::class, 'duplicate'])->name('templates.duplicate');
        Route::get('templates/{template}/preview', [EmailTemplateController::class, 'preview'])->name('templates.preview');
        Route::resource('templates', EmailTemplateController::class)->except(['show']);

        // Campaigns
        Route::post('campaigns/{campaign}/retry-failed', [EmailCampaignController::class, 'retryFailed'])->name('campaigns.retry-failed');
        Route::resource('campaigns', EmailCampaignController::class)->except(['edit', 'update']);

        // Logs
        Route::get('logs', [EmailLogController::class, 'index'])->name('logs.index');
        Route::delete('logs/clear', [EmailLogController::class, 'clear'])->name('logs.clear');
    });
});

// Public QR code scan route (no auth)
Route::get('inventaris/qr/{kode}', [InventarisController::class, 'qrScan'])->name('inventaris.qr-scan');

// Public Unsubscribe route (no auth)
Route::get('email/unsubscribe/{token}', [UnsubscribeController::class, 'unsubscribe'])->name('email-marketing.unsubscribe');
Route::post('email/resubscribe/{token}', [UnsubscribeController::class, 'resubscribe'])->name('email-marketing.resubscribe');

require __DIR__.'/auth.php';
