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
}
