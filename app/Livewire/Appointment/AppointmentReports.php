<?php
// app/Livewire/Appointment/AppointmentReports.php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentReports extends Component
{
    use WithPagination;

    public $dateFrom;
    public $dateTo;
    public $doctorId = '';
    public $reportType = 'summary'; // summary, detailed, doctor, type, trends
    public $groupBy = 'day'; // day, week, month
    public $selectedMetric = 'all'; // all, completed, missed, cancelled

    // Data containers
    public $summary = [];
    public $doctorStats = [];
    public $typeStats = [];
    public $dailyStats = [];
    public $weeklyStats = [];
    public $monthlyStats = [];
    public $trendData = [];
    public $peakHours = [];

    protected $queryString = ['dateFrom', 'dateTo', 'doctorId', 'reportType', 'groupBy'];

    public function mount()
    {
        $this->dateFrom = Carbon::now('Africa/Addis_Ababa')->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now('Africa/Addis_Ababa')->endOfMonth()->format('Y-m-d');
        $this->generateReport();
    }

    public function updatedDateFrom()
    {
        $this->generateReport();
    }

    public function updatedDateTo()
    {
        $this->generateReport();
    }

    public function updatedDoctorId()
    {
        $this->generateReport();
    }

    public function updatedReportType()
    {
        $this->generateReport();
    }

    public function updatedGroupBy()
    {
        $this->generateReport();
    }

    public function generateReport()
    {
        $this->generateSummary();
        $this->generateDoctorStats();
        $this->generateTypeStats();
        $this->generateDailyStats();
        $this->generateWeeklyStats();
        $this->generateMonthlyStats();
        $this->generateTrendData();
        $this->generatePeakHours();
    }

    protected function generateSummary()
    {
        $query = Appointment::whereBetween('appointment_date', [$this->dateFrom, $this->dateTo]);

        if ($this->doctorId) {
            $query->where('doctor_id', $this->doctorId);
        }

        $total = (clone $query)->count();
        
        $this->summary = [
            'total' => $total,
            'scheduled' => (clone $query)->where('status', 'scheduled')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'missed' => (clone $query)->where('status', 'missed')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'rescheduled' => (clone $query)->where('status', 'rescheduled')->count(),
            'checked_in' => (clone $query)->whereNotNull('checked_in_at')->count(),
            'with_notes' => (clone $query)->whereNotNull('doctor_notes')->count(),
            'completion_rate' => $total > 0 ? round(((clone $query)->where('status', 'completed')->count() / $total) * 100, 1) : 0,
            'missed_rate' => $total > 0 ? round(((clone $query)->where('status', 'missed')->count() / $total) * 100, 1) : 0,
        ];
    }

    protected function generateDoctorStats()
    {
        $this->doctorStats = DB::table('appointments')
            ->join('users', 'users.id', '=', 'appointments.doctor_id')
            ->select(
                'users.id as doctor_id',
                'users.name as doctor_name',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "scheduled" THEN 1 ELSE 0 END) as scheduled'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN status = "missed" THEN 1 ELSE 0 END) as missed'),
                DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled'),
                DB::raw('SUM(CASE WHEN status = "rescheduled" THEN 1 ELSE 0 END) as rescheduled')
            )
            ->whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
            ->when($this->doctorId, fn($q) => $q->where('appointments.doctor_id', $this->doctorId))
            ->groupBy('users.id', 'users.name')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function ($stat) {
                $stat->completion_rate = $stat->total > 0 ? round(($stat->completed / $stat->total) * 100, 1) : 0;
                return $stat;
            });
    }

    protected function generateTypeStats()
    {
        $this->typeStats = DB::table('appointments')
            ->select(
                'visit_type',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN status = "missed" THEN 1 ELSE 0 END) as missed'),
                DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled')
            )
            ->whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
            ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId))
            ->groupBy('visit_type')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function ($item) {
                $item->visit_type_label = ucfirst(str_replace('-', ' ', $item->visit_type));
                $item->completion_rate = $item->total > 0 ? round(($item->completed / $item->total) * 100, 1) : 0;
                return $item;
            });
    }

    protected function generateDailyStats()
    {
        $this->dailyStats = DB::table('appointments')
            ->select(
                DB::raw('DATE(appointment_date) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN status = "missed" THEN 1 ELSE 0 END) as missed'),
                DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled')
            )
            ->whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
            ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId))
            ->groupBy(DB::raw('DATE(appointment_date)'))
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $item->formatted_date = Carbon::parse($item->date)->format('M d, Y');
                $item->day_name = Carbon::parse($item->date)->format('l');
                $item->completion_rate = $item->total > 0 ? round(($item->completed / $item->total) * 100, 1) : 0;
                return $item;
            });
    }

    protected function generateWeeklyStats()
    {
        $this->weeklyStats = DB::table('appointments')
            ->select(
                DB::raw('YEAR(appointment_date) as year'),
                DB::raw('WEEK(appointment_date) as week'),
                DB::raw('MIN(appointment_date) as week_start'),
                DB::raw('MAX(appointment_date) as week_end'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN status = "missed" THEN 1 ELSE 0 END) as missed')
            )
            ->whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
            ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId))
            ->groupBy(DB::raw('YEAR(appointment_date)'), DB::raw('WEEK(appointment_date)'))
            ->orderBy('year')
            ->orderBy('week')
            ->get()
            ->map(function ($item) {
                $item->week_range = Carbon::parse($item->week_start)->format('M d') . ' - ' . Carbon::parse($item->week_end)->format('M d, Y');
                $item->completion_rate = $item->total > 0 ? round(($item->completed / $item->total) * 100, 1) : 0;
                return $item;
            });
    }

   protected function generateMonthlyStats()
{
    $this->monthlyStats = DB::table('appointments')
        ->select(
            DB::raw('YEAR(appointment_date) as year'),
            DB::raw('MONTH(appointment_date) as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
            DB::raw('SUM(CASE WHEN status = "missed" THEN 1 ELSE 0 END) as missed')
        )
        ->whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
        ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId))
        ->groupBy(DB::raw('YEAR(appointment_date)'), DB::raw('MONTH(appointment_date)'))
        ->orderBy('year')
        ->orderBy('month')
        ->get()
        ->map(function ($item) {
            $item->month_name = Carbon::createFromDate($item->year, $item->month, 1)->format('F Y');
            $item->month_key = $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            $item->completion_rate = $item->total > 0 ? round(($item->completed / $item->total) * 100, 1) : 0;
            return $item;
        });
}

    protected function generateTrendData()
    {
        // Calculate trends compared to previous period
        $currentPeriod = [
            'start' => $this->dateFrom,
            'end' => $this->dateTo
        ];
        
        $daysDiff = Carbon::parse($this->dateFrom)->diffInDays(Carbon::parse($this->dateTo));
        $previousStart = Carbon::parse($this->dateFrom)->subDays($daysDiff)->format('Y-m-d');
        $previousEnd = Carbon::parse($this->dateFrom)->subDay()->format('Y-m-d');

        $currentQuery = Appointment::whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
            ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId));
        
        $previousQuery = Appointment::whereBetween('appointment_date', [$previousStart, $previousEnd])
            ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId));

        $currentTotal = (clone $currentQuery)->count();
        $previousTotal = (clone $previousQuery)->count();
        
        $currentCompleted = (clone $currentQuery)->where('status', 'completed')->count();
        $previousCompleted = (clone $previousQuery)->where('status', 'completed')->count();

        $this->trendData = [
            'total_change' => $previousTotal > 0 ? round((($currentTotal - $previousTotal) / $previousTotal) * 100, 1) : 0,
            'completed_change' => $previousCompleted > 0 ? round((($currentCompleted - $previousCompleted) / $previousCompleted) * 100, 1) : 0,
            'previous_total' => $previousTotal,
            'previous_completed' => $previousCompleted,
        ];
    }

    protected function generatePeakHours()
    {
        $this->peakHours = DB::table('appointments')
            ->select(
                DB::raw('HOUR(appointment_time) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
            ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId))
            ->groupBy(DB::raw('HOUR(appointment_time)'))
            ->orderBy('hour')
            ->get()
            ->map(function ($item) {
                $item->formatted_hour = Carbon::createFromTime($item->hour, 0)->format('h:i A');
                $item->percentage = $this->summary['total'] > 0 ? round(($item->count / $this->summary['total']) * 100, 1) : 0;
                return $item;
            });
    }

    public function getDetailedAppointmentsProperty()
    {
        return Appointment::with(['patient', 'doctor'])
            ->whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
            ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId))
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->paginate(15);
    }

    public function exportToCsv()
    {
        $fileName = 'appointment_report_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['Date', 'Patient', 'Doctor', 'Type', 'Time', 'Status', 'Checked In', 'Notes']);

            // Data
            $appointments = Appointment::with(['patient', 'doctor'])
                ->whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
                ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId))
                ->orderBy('appointment_date')
                ->orderBy('appointment_time')
                ->get();

            foreach ($appointments as $app) {
                fputcsv($file, [
                    $app->appointment_date->format('Y-m-d'),
                    $app->patient->first_name . ' ' . $app->patient->last_name,
                    'Dr. ' . $app->doctor->name,
                    ucfirst(str_replace('-', ' ', $app->visit_type)),
                    $app->appointment_time->format('h:i A'),
                    ucfirst($app->status),
                    $app->checked_in_at ? $app->checked_in_at->format('h:i A') : 'No',
                    $app->doctor_notes ? 'Yes' : 'No',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportToPdf()
    {
        $this->dispatch('export-pdf', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'doctorId' => $this->doctorId,
        ]);
    }

    public function render()
    {
        return view('livewire.appointment.appointment-reports', [
            'doctors' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->orderBy('name')->get(),
            'summary' => $this->summary,
            'doctorStats' => $this->doctorStats,
            'typeStats' => $this->typeStats,
            'dailyStats' => $this->dailyStats,
            'weeklyStats' => $this->weeklyStats,
            'monthlyStats' => $this->monthlyStats,
            'trendData' => $this->trendData,
            'peakHours' => $this->peakHours,
        ]);
    }
}