<?php
// app/Livewire/Appointment/AppointmentReports.php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AppointmentReports extends Component
{
    use WithPagination;

    public $dateFrom;
    public $dateTo;
    public $doctorId = '';
    public $reportType = 'summary'; // summary, detailed, doctor, type

    public $summary = [];
    public $doctorStats = [];
    public $typeStats = [];
    public $dailyStats = [];

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

    public function generateReport()
    {
        $this->generateSummary();
        $this->generateDoctorStats();
        $this->generateTypeStats();
        $this->generateDailyStats();
    }

    protected function generateSummary()
    {
        $query = Appointment::whereBetween('appointment_date', [$this->dateFrom, $this->dateTo]);

        if ($this->doctorId) {
            $query->where('doctor_id', $this->doctorId);
        }

        $this->summary = [
            'total' => (clone $query)->count(),
            'scheduled' => (clone $query)->where('status', 'scheduled')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'missed' => (clone $query)->where('status', 'missed')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'rescheduled' => (clone $query)->where('status', 'rescheduled')->count(),
            'checked_in' => (clone $query)->whereNotNull('checked_in_at')->count(),
            'with_notes' => (clone $query)->whereNotNull('doctor_notes')->count(),
        ];
    }

    protected function generateDoctorStats()
    {
        $this->doctorStats = DB::table('appointments')
            ->join('users', 'users.id', '=', 'appointments.doctor_id')
            ->select(
                'users.name as doctor_name',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "scheduled" THEN 1 ELSE 0 END) as scheduled'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN status = "missed" THEN 1 ELSE 0 END) as missed'),
                DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled')
            )
            ->whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
            ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId))
            ->groupBy('users.id', 'users.name')
            ->get();
    }

    protected function generateTypeStats()
    {
        $this->typeStats = DB::table('appointments')
            ->select(
                'visit_type',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
            )
            ->whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
            ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId))
            ->groupBy('visit_type')
            ->get()
            ->map(function ($item) {
                $item->visit_type_label = ucfirst(str_replace('-', ' ', $item->visit_type));
                return $item;
            });
    }

    protected function generateDailyStats()
    {
        $this->dailyStats = DB::table('appointments')
            ->select(
                DB::raw('DATE(appointment_date) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
            )
            ->whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
            ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId))
            ->groupBy(DB::raw('DATE(appointment_date)'))
            ->orderBy('date')
            ->get();
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

    public function render()
    {
        return view('livewire.appointment.appointment-reports', [
            'doctors' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get(),
        ]);
    }
}