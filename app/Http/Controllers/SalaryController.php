<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\SalaryAdjustment;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        $employees = Employee::with(['attendances' => function($query) use ($month, $year) {
            $query->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
        }, 'salaryAdjustments' => function($query) use ($month, $year) {
            $query->where('month', $month)->where('year', $year);
        }])->orderBy('id', 'asc')->get();

        foreach ($employees as $employee) {
            $stats = $this->calculateSalary($employee, $month, $year);
            $employee->salary_stats = $stats;
            $employee->adjustment = $employee->salaryAdjustments->first();
        }

        return view('salary.index', compact('employees', 'month', 'year'));
    }

    private function calculateSalary($employee, $month, $year)
    {
        $attendances = $employee->attendances;
        $adjustment = $employee->salaryAdjustments->where('month', $month)->where('year', $year)->first();
        
        $hadir = $attendances->where('status', 'hadir')->count();
        $telat_1 = $attendances->where('status', 'telat_1')->count();
        $telat_2 = $attendances->where('status', 'telat_2')->count();
        $sakit = $attendances->where('status', 'sakit')->count();
        $ijin = $attendances->where('status', 'ijin')->count();

        // Get settings from DB
        $s_ot_weekday = \App\Models\Setting::get('lembur_weekday_jam_pertama', 30000);
        $s_ot_weekday_extra = \App\Models\Setting::get('lembur_weekday_jam_berikutnya', 40000);
        $s_ot_weekend = \App\Models\Setting::get('lembur_weekend_per_jam', 50000);
        $s_bpjs_kes_pct = \App\Models\Setting::get('bpjs_kesehatan_persen', 1) / 100;
        $s_bpjs_tk_pct = \App\Models\Setting::get('bpjs_tk_persen', 2) / 100;
        $s_pph_pct = \App\Models\Setting::get('pph21_persen', 5) / 100;
        
        $s_late_penalty_1 = \App\Models\Setting::get('potongan_telat_dibawah_1_jam', 25000);
        $s_late_penalty_2 = \App\Models\Setting::get('potongan_telat_diatas_1_jam', 50000);
        $s_permit_penalty = \App\Models\Setting::get('potongan_ijin', 100000);
        $s_sick_penalty = \App\Models\Setting::get('potongan_sakit', 50000);
        $s_sick_surat_penalty = \App\Models\Setting::get('potongan_sakit_surat', 0);

        // Perhitungan Lembur Otomatis
        $lembur_calc = 0;
        $lembur_weekday_hours = 0;
        $lembur_weekend_hours = 0;

        foreach ($attendances as $a) {
            if ($a->lembur_jam > 0) {
                $jam = min($a->lembur_jam, 4); // Maks 4 jam
                if ($a->tanggal->isWeekend()) {
                    $lembur_calc += $jam * $s_ot_weekend;
                    $lembur_weekend_hours += $jam;
                } else {
                    $lembur_weekday_hours += $jam;
                    if ($jam >= 1) {
                        $lembur_calc += $s_ot_weekday; // 1 jam pertama
                        if ($jam > 1) {
                            $lembur_calc += ($jam - 1) * $s_ot_weekday_extra; // jam ke 2-4
                        }
                    }
                }
            }
        }

        $gaji_pokok = $employee->gaji_pokok;
        $isMagang = $employee->jabatan === 'Staff Magang';

        // Perhitungan Potongan BPJS (Otomatis)
        $s_min_gaji = (float) \App\Models\Setting::get('batas_minimal_gaji_kena_pajak_bpjs', 3000000);
        $gaji_pokok_val = (float) $gaji_pokok;
        $eligibleForDeductions = !$isMagang && ($gaji_pokok_val >= $s_min_gaji);

        $bpjs_kesehatan = $eligibleForDeductions ? ($gaji_pokok_val * $s_bpjs_kes_pct) : 0;
        $bpjs_tk = $eligibleForDeductions ? ($gaji_pokok_val * $s_bpjs_tk_pct) : 0;

        // Perhitungan Potongan Absensi
        $potongan_ijin = $attendances->where('status', 'ijin')->count() * $s_permit_penalty;
        $potongan_sakit_ts = $attendances->where('status', 'sakit')->where('sakit_dengan_surat', false)->count() * $s_sick_penalty;
        $potongan_sakit_surat = $attendances->where('status', 'sakit')->where('sakit_dengan_surat', true)->count() * $s_sick_surat_penalty;
        
        $total_hari_kerja = $hadir + $telat_1 + $telat_2;
        
        $potongan_telat_1 = $telat_1 * $s_late_penalty_1;
        $potongan_telat_2 = $telat_2 * $s_late_penalty_2;
        
        // Adjustments from manual input
        $reimburse = $adjustment ? $adjustment->reimburse : 0;
        $uang_kehadiran = $adjustment ? $adjustment->uang_kehadiran : 0;
        $transport = $adjustment ? $adjustment->transport : 0;
        $bonus = $adjustment ? $adjustment->bonus : 0;
        
        $pph21_manual = $adjustment ? $adjustment->pph21 : 0;
        $pph21_auto = $eligibleForDeductions ? ($gaji_pokok * $s_pph_pct) : 0;
        $pph21 = $pph21_manual ?: $pph21_auto; // Use manual if provided, else auto
        
        $pinjaman = $adjustment ? $adjustment->pinjaman : 0;
        $lain_lain_manual = $adjustment ? $adjustment->lain_lain : 0;

        $total_lembur = $lembur_calc;
        $total_penerimaan = $gaji_pokok + $reimburse + $uang_kehadiran + $total_lembur + $transport + $bonus;
        
        $total_potongan_absensi = $potongan_telat_1 + $potongan_telat_2 + $potongan_ijin + $potongan_sakit_ts;
        $total_potongan = $total_potongan_absensi + $bpjs_kesehatan + $bpjs_tk + $pph21 + $pinjaman + $lain_lain_manual;
        
        $gaji_bersih = $total_penerimaan - $total_potongan;

        return [
            'hadir' => $hadir,
            'telat_1' => $telat_1,
            'telat_2' => $telat_2,
            'sakit' => $sakit,
            'ijin' => $ijin,
            'total_hari_kerja' => $total_hari_kerja,
            'gaji_pokok' => $gaji_pokok,
            
            'lembur_calc' => $lembur_calc,
            'lembur_weekday_hours' => $lembur_weekday_hours,
            'lembur_weekend_hours' => $lembur_weekend_hours,
            'potongan_ijin' => $potongan_ijin,
            'potongan_sakit_ts' => $potongan_sakit_ts,
            'potongan_telat_1' => $potongan_telat_1,
            'potongan_telat_2' => $potongan_telat_2,
            
            'reimburse' => $reimburse,
            'uang_kehadiran' => $uang_kehadiran,
            'lembur' => $total_lembur,
            'transport' => $transport,
            'bonus' => $bonus,
            
            'bpjs_kesehatan' => $bpjs_kesehatan,
            'bpjs_tk' => $bpjs_tk,
            'pph21' => $pph21,
            'pinjaman' => $pinjaman,
            'lain_lain' => $lain_lain_manual,

            'total_penerimaan' => $total_penerimaan,
            'total_potongan' => $total_potongan,
            'gaji_bersih' => $gaji_bersih,
        ];
    }

    public function storeAdjustment(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required',
            'year' => 'required',
            'reimburse' => 'nullable|numeric',
            'uang_kehadiran' => 'nullable|numeric',
            'lembur' => 'nullable|numeric',
            'transport' => 'nullable|numeric',
            'bonus' => 'nullable|numeric',
            'bpjs_kesehatan' => 'nullable|numeric',
            'bpjs_tk' => 'nullable|numeric',
            'pph21' => 'nullable|numeric',
            'pinjaman' => 'nullable|numeric',
            'lain_lain' => 'nullable|numeric',
        ]);

        SalaryAdjustment::updateOrCreate(
            ['employee_id' => $data['employee_id'], 'month' => $data['month'], 'year' => $data['year']],
            $data
        );

        return redirect()->back()->with('success', 'Data gaji berhasil diperbarui.');
    }

    public function printSlip(Request $request, Employee $employee)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        $employee->load(['attendances' => function($query) use ($month, $year) {
            $query->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
        }, 'salaryAdjustments' => function($query) use ($month, $year) {
            $query->where('month', $month)->where('year', $year);
        }]);

        $stats = $this->calculateSalary($employee, $month, $year);
        $date = Carbon::createFromDate($year, $month, 1);

        $pdf = Pdf::loadView('salary.slip', compact('employee', 'stats', 'month', 'year', 'date'))
                  ->setPaper('a4', 'landscape');
        return $pdf->stream("Slip-Gaji-{$employee->nama}-{$date->format('F-Y')}.pdf");
    }
}
