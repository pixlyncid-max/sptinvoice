<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_attendance_pdf(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $employee = Employee::create([
            'nama' => 'John Doe',
            'jabatan' => 'Staff',
            'gaji_pokok' => 5000000,
            'uang_makan_per_hari' => 25000,
        ]);

        $response = $this->actingAs($admin)->get(route('attendance.export.pdf', [
            'month' => date('m'),
            'year' => date('Y'),
        ]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_export_attendance_excel(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $employee = Employee::create([
            'nama' => 'Jane Doe',
            'jabatan' => 'Manager',
            'gaji_pokok' => 8000000,
            'uang_makan_per_hari' => 35000,
        ]);

        $response = $this->actingAs($admin)->get(route('attendance.export.excel', [
            'month' => date('m'),
            'year' => date('Y'),
        ]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.ms-excel; charset=utf-8');
        $response->assertSee('LAPORAN ABSENSI KARYAWAN');
        $response->assertSee('Jane Doe');
    }

    public function test_admin_can_filter_attendance_by_search_query(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Employee::create([
            'nama' => 'Budi Santoso',
            'jabatan' => 'Developer',
            'gaji_pokok' => 6000000,
            'uang_makan_per_hari' => 30000,
        ]);

        Employee::create([
            'nama' => 'Siti Aminah',
            'jabatan' => 'Designer',
            'gaji_pokok' => 5500000,
            'uang_makan_per_hari' => 25000,
        ]);

        $response = $this->actingAs($admin)->get(route('attendance.index', [
            'month' => 8,
            'year' => 2026,
            'search' => 'Budi',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Siti Aminah');
    }

    public function test_admin_can_export_excel_with_search_filter(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Employee::create([
            'nama' => 'Budi Santoso',
            'jabatan' => 'Developer',
            'gaji_pokok' => 6000000,
            'uang_makan_per_hari' => 30000,
        ]);

        Employee::create([
            'nama' => 'Siti Aminah',
            'jabatan' => 'Designer',
            'gaji_pokok' => 5500000,
            'uang_makan_per_hari' => 25000,
        ]);

        $response = $this->actingAs($admin)->get(route('attendance.export.excel', [
            'month' => 8,
            'year' => 2026,
            'search' => 'Budi',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Siti Aminah');
    }
}
