<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
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
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Users (Kelola Admin)
    Route::resource('users', UserController::class)->except(['show']);

    // Clients
    Route::get('clients/template', [ClientController::class, 'downloadTemplate'])->name('clients.template');
    Route::post('clients/import', [ClientController::class, 'importExcel'])->name('clients.import');
    Route::resource('clients', ClientController::class);
    Route::get('clients/{client}/detail', [ClientController::class, 'detail'])->name('clients.detail');

    // Invoices
    Route::resource('invoices', InvoiceController::class);
    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'exportPdf'])->name('invoices.pdf');
    Route::patch('invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.status');
    Route::post('invoices/{invoice}/duplicate', [InvoiceController::class, 'duplicate'])->name('invoices.duplicate');

    // Penawaran
    Route::resource('penawaran', PenawaranController::class);
    Route::get('penawaran/{penawaran}/print', [PenawaranController::class, 'print'])->name('penawaran.print');
    Route::get('penawaran/{penawaran}/pdf', [PenawaranController::class, 'exportPdf'])->name('penawaran.pdf');
    Route::patch('penawaran/{penawaran}/status', [PenawaranController::class, 'updateStatus'])->name('penawaran.status');
    Route::post('penawaran/{penawaran}/convert', [PenawaranController::class, 'convertToInvoice'])->name('penawaran.convert');

    // Rate Card
    Route::resource('rate-cards', RateCardController::class);
    Route::get('rate-cards-items', [RateCardController::class, 'getItems'])->name('rate-cards.items');

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
    Route::resource('inventaris', \App\Http\Controllers\InventarisController::class);
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

// Public QR code scan route (no auth)
Route::get('inventaris/qr/{kode}', [InventarisController::class, 'qrScan'])->name('inventaris.qr-scan');

require __DIR__.'/auth.php';
