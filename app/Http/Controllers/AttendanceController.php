<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller
{
    private function getAttendancePeriod($month, $year)
    {
        $startDate = Carbon::createFromDate($year, $month, 28)->subMonth();
        $endDate = Carbon::createFromDate($year, $month, 27);
        
        $period = [];
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $period[] = $current->copy();
            $current->addDay();
        }
        return [$startDate, $endDate, $period];
    }

    public function index(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        list($startDate, $endDate, $period) = $this->getAttendancePeriod($month, $year);

        $employees = Employee::with(['attendances' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        }])->orderBy('id', 'asc')->get();

        return view('attendance.index', compact('employees', 'month', 'year', 'period'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,sakit,ijin,telat_1,telat_2',
        ]);

        Attendance::updateOrCreate(
            ['employee_id' => $request->employee_id, 'tanggal' => $request->tanggal],
            ['status' => $request->status]
        );

        return response()->json(['success' => true]);
    }

    public function bulkStore(Request $request)
    {
        $data = $request->all();

        foreach ($data['attendances'] as $employeeId => $dates) {
            foreach ($dates as $dateStr => $attr) {
                $tanggal = Carbon::parse($dateStr);
                $status = is_array($attr) ? ($attr['status'] ?? null) : $attr;
                $lembur = is_array($attr) ? ($attr['lembur'] ?? 0) : 0;
                $surat = 0;
                
                if ($status === 'sakit_surat') {
                    $status = 'sakit';
                    $surat = 1;
                }
                
                if (!$status && $tanggal->isWeekend()) {
                    $status = 'libur';
                }

                if ($status || $lembur > 0) {
                    Attendance::updateOrCreate(
                        ['employee_id' => $employeeId, 'tanggal' => $tanggal->format('Y-m-d')],
                        [
                            'status' => $status,
                            'lembur_jam' => $lembur,
                            'sakit_dengan_surat' => $surat
                        ]
                    );
                }
            }
        }

        return redirect()->back()->with('success', 'Absensi berhasil disimpan.');
    }

    public function dailyStore(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'daily_attendance' => 'required|array'
        ]);

        $tanggalStr = $request->tanggal;
        
        foreach ($request->daily_attendance as $employeeId => $status) {
            $surat = 0;
            
            if ($status === 'sakit_surat') {
                $status = 'sakit';
                $surat = 1;
            }

            if ($status) {
                Attendance::updateOrCreate(
                    ['employee_id' => $employeeId, 'tanggal' => $tanggalStr],
                    [
                        'status' => $status,
                        'sakit_dengan_surat' => $surat
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Absensi harian berhasil disimpan.');
    }

    public function exportPdf(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        list($startDate, $endDate, $period) = $this->getAttendancePeriod($month, $year);

        $employees = Employee::with(['attendances' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        }])->orderBy('id', 'asc')->get();

        $pdf = Pdf::loadView('attendance.pdf', compact('employees', 'month', 'year', 'period'))
                  ->setPaper('a4', 'landscape');
        
        return $pdf->download("Absensi-{$month}-{$year}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        list($startDate, $endDate, $period) = $this->getAttendancePeriod($month, $year);

        $employees = Employee::with(['attendances' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        }])->orderBy('id', 'asc')->get();

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Absensi-{$month}-{$year}.xls");
        
        return view('attendance.excel', compact('employees', 'month', 'year', 'period'));
    }
}
