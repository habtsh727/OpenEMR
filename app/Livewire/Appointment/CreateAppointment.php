<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\Encounter;
use App\Rules\AvailableTimeSlot;
use App\Services\AppointmentService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateAppointment extends Component
{
    // Form fields
    public $patient_id;
    public $doctor_id;
    public $visit_type = 'consultation';
    public $appointment_date;
    public $appointment_time;
    public $time_slot;
    public $status = 'scheduled';
    public $additional_notes;
    public $payment_status = 'unpaid';
    public $payment_amount;
    public $related_order_type = 'none';
    public $related_order_id;
    public $is_request = false;

    // Patient search - OPTIMIZED
    public $patientSearch = '';
    public $searchResults = [];
    public $showPatientSearch = false;
    public $selectedPatient = null;
    public $quickPatientMode = false;
    
    // Search performance tracking
    public $searchTime = 0;
    public $totalResults = 0;
    
    // Quick patient creation
    public $first_name = '';
    public $middle_name = '';
    public $last_name = '';
    public $mother_name = '';
    public $gender = '';
    public $phone_number1 = '';
    public $phone_number2 = '';
    public $emergency_person = '';
    public $emergency_contact = '';
    public $emergency_person_relationship = '';
    public $region = '';
    public $region_zone = '';
    public $region_woreda = '';
    public $date_of_birth = '';

    // Cached data
    public $recentPatients = [];
    public $popularPatients = [];

    // Time slots
    public $availableSlots = [];
    public $selectedSlot = null;

    // Related orders
    public $relatedOrders = [];
    public $showRelatedOrderSearch = false;

    // Success/Error
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $appointmentService;
    
    // Debounce time in milliseconds
    protected $debounceTime = 300;

    public function boot(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function mount()
    {
        $this->appointment_date = now()->format('Y-m-d');
        $this->loadRecentPatients();
        $this->loadPopularPatients();
    }

    /**
     * Load recent patients from cache or database
     */
    protected function loadRecentPatients()
    {
        $this->recentPatients = Cache::remember('recent_patients', 3600, function () {
            return Patient::select('id', 'first_name', 'middle_name', 'last_name', 'card_number', 'phone_number1', 'gender', 'date_of_birth')
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($patient) {
                    return [
                        'id' => $patient->id,
                        'name' => $patient->first_name . ' ' . $patient->middle_name . ' ' . $patient->last_name,
                        'card_number' => $patient->card_number,
                        'phone' => $patient->phone_number1,
                        'gender' => $patient->gender,
                        'age' => $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 'N/A',
                    ];
                });
        });
    }

    /**
     * Load most frequently booked patients
     */
    protected function loadPopularPatients()
    {
        $this->popularPatients = Cache::remember('popular_patients', 3600, function () {
            return Patient::select('patients.id', 'patients.first_name', 'patients.middle_name', 'patients.last_name', 
                                   'patients.card_number', 'patients.phone_number1', 'patients.gender', 'patients.date_of_birth')
                ->join('appointments', 'patients.id', '=', 'appointments.patient_id')
                ->groupBy('patients.id', 'patients.first_name', 'patients.middle_name', 'patients.last_name', 
                          'patients.card_number', 'patients.phone_number1', 'patients.gender', 'patients.date_of_birth')
                ->orderByRaw('COUNT(appointments.id) DESC')
                ->limit(5)
                ->get()
                ->map(function ($patient) {
                    return [
                        'id' => $patient->id,
                        'name' => $patient->first_name . ' ' . $patient->middle_name . ' ' . $patient->last_name,
                        'card_number' => $patient->card_number,
                        'phone' => $patient->phone_number1,
                        'gender' => $patient->gender,
                        'age' => $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 'N/A',
                    ];
                });
        });
    }

    /**
     * OPTIMIZED Patient Search Method
     * Uses multiple strategies for fast results:
     * 1. Exact matches first
     * 2. Prefix matching with indexes
     * 3. Full-text search as fallback
     * 4. Caching for frequent searches
     */
    public function updatedPatientSearch()
    {
        $searchTerm = trim($this->patientSearch);
        
        if (strlen($searchTerm) < 2) {
            $this->searchResults = [];
            $this->showPatientSearch = false;
            return;
        }

        $startTime = microtime(true);

        // Try to get from cache first (for repeated searches)
        $cacheKey = 'patient_search_' . md5($searchTerm);
        $this->searchResults = Cache::remember($cacheKey, 60, function () use ($searchTerm) {
            return $this->performOptimizedSearch($searchTerm);
        });

        $this->totalResults = count($this->searchResults);
        $this->searchTime = round((microtime(true) - $startTime) * 1000, 2);
        $this->showPatientSearch = true;
    }

    /**
     * Perform optimized search using multiple strategies
     */
    protected function performOptimizedSearch($searchTerm)
    {
        $results = collect();

        // Strategy 1: Exact matches (fastest)
        $exactMatches = $this->searchExactMatches($searchTerm);
        $results = $results->merge($exactMatches);

        // If we have enough results, return early
        if ($results->count() >= 10) {
            return $results->take(10)->values();
        }

        // Strategy 2: Prefix matches (fast)
        $prefixMatches = $this->searchPrefixMatches($searchTerm);
        $results = $results->merge($prefixMatches)->unique('id');

        if ($results->count() >= 10) {
            return $results->take(10)->values();
        }

        // Strategy 3: Full-text search (slower but comprehensive)
        $fullTextMatches = $this->searchFullText($searchTerm);
        $results = $results->merge($fullTextMatches)->unique('id');

        return $results->take(10)->values();
    }

    /**
     * Search for exact matches on card number or phone
     */
    protected function searchExactMatches($searchTerm)
    {
        return Patient::select('id', 'first_name', 'middle_name', 'last_name', 'card_number', 'phone_number1', 'gender', 'date_of_birth')
            ->where('card_number', $searchTerm)
            ->orWhere('phone_number1', $searchTerm)
            ->limit(5)
            ->get()
            ->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->first_name . ' ' . $patient->middle_name . ' ' . $patient->last_name,
                    'card_number' => $patient->card_number,
                    'phone' => $patient->phone_number1,
                    'gender' => $patient->gender,
                    'age' => $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 'N/A',
                    'match_type' => 'exact',
                ];
            });
    }

    /**
     * Search for prefix matches on name fields (uses indexes)
     */
    protected function searchPrefixMatches($searchTerm)
    {
        // Use LIKE with prefix only (starts with) for better index usage
        return Patient::select('id', 'first_name', 'middle_name', 'last_name', 'card_number', 'phone_number1', 'gender', 'date_of_birth')
            ->where('first_name', 'like', $searchTerm . '%')
            ->orWhere('middle_name', 'like', $searchTerm . '%')
            ->orWhere('last_name', 'like', $searchTerm . '%')
            ->orWhere('card_number', 'like', $searchTerm . '%')
            ->orWhere('phone_number1', 'like', $searchTerm . '%')
            ->limit(10)
            ->get()
            ->map(function ($patient) use ($searchTerm) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->first_name . ' ' . $patient->middle_name . ' ' . $patient->last_name,
                    'card_number' => $patient->card_number,
                    'phone' => $patient->phone_number1,
                    'gender' => $patient->gender,
                    'age' => $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 'N/A',
                    'match_type' => 'prefix',
                    'highlight' => $this->getHighlightedName($patient, $searchTerm),
                ];
            });
    }

    /**
     * Full-text search for comprehensive results
     */
    protected function searchFullText($searchTerm)
    {
        // Using raw SQL for FULLTEXT search if available
        if ($this->hasFullTextIndex()) {
            return DB::select("
                SELECT id, first_name, middle_name, last_name, card_number, phone_number1, gender, date_of_birth,
                       MATCH(first_name, middle_name, last_name) AGAINST(? IN BOOLEAN MODE) as relevance
                FROM patients
                WHERE MATCH(first_name, middle_name, last_name) AGAINST(? IN BOOLEAN MODE)
                ORDER BY relevance DESC
                LIMIT 10
            ", [$searchTerm . '*', $searchTerm . '*']);
        }

        // Fallback to comprehensive LIKE search
        return Patient::select('id', 'first_name', 'middle_name', 'last_name', 'card_number', 'phone_number1', 'gender', 'date_of_birth')
            ->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('middle_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('card_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('phone_number1', 'like', '%' . $searchTerm . '%');
            })
            ->limit(10)
            ->get()
            ->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->first_name . ' ' . $patient->middle_name . ' ' . $patient->last_name,
                    'card_number' => $patient->card_number,
                    'phone' => $patient->phone_number1,
                    'gender' => $patient->gender,
                    'age' => $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 'N/A',
                    'match_type' => 'full',
                ];
            });
    }

    /**
     * Check if fulltext index exists
     */
    protected function hasFullTextIndex()
    {
        return Cache::remember('has_fulltext_index', 3600, function () {
            $result = DB::select("
                SELECT COUNT(*) as count
                FROM information_schema.statistics
                WHERE table_schema = DATABASE()
                AND table_name = 'patients'
                AND index_type = 'FULLTEXT'
            ");
            return !empty($result) && $result[0]->count > 0;
        });
    }

    /**
     * Highlight matching parts in name
     */
    protected function getHighlightedName($patient, $searchTerm)
    {
        $fullName = $patient->first_name . ' ' . $patient->middle_name . ' ' . $patient->last_name;
        $pattern = '/(' . preg_quote($searchTerm, '/') . ')/i';
        return preg_replace($pattern, '<mark>$1</mark>', $fullName);
    }

    /**
     * Search with type-ahead optimization (for instant results)
     */
    public function searchTypeAhead($searchTerm)
    {
        if (strlen($searchTerm) < 1) {
            return [];
        }

        return Cache::remember('typeahead_' . $searchTerm, 5, function () use ($searchTerm) {
            return Patient::select('id', 'first_name', 'last_name', 'card_number')
                ->where('first_name', 'like', $searchTerm . '%')
                ->orWhere('last_name', 'like', $searchTerm . '%')
                ->orWhere('card_number', 'like', $searchTerm . '%')
                ->limit(5)
                ->get()
                ->map(function ($patient) {
                    return [
                        'id' => $patient->id,
                        'display' => $patient->first_name . ' ' . $patient->last_name . ' (' . $patient->card_number . ')',
                    ];
                });
        });
    }

    public function selectPatient($patientId)
    {
        $this->selectedPatient = Patient::find($patientId);
        $this->patient_id = $patientId;
        $this->patientSearch = $this->selectedPatient->first_name . ' ' . $this->selectedPatient->last_name;
        $this->showPatientSearch = false;
        
        // Clear search cache for this term
        Cache::forget('patient_search_' . md5($this->patientSearch));
    }

    public function clearPatient()
    {
        $this->selectedPatient = null;
        $this->patient_id = null;
        $this->patientSearch = '';
        $this->searchResults = [];
    }

    public function toggleQuickPatient()
    {
        $this->quickPatientMode = !$this->quickPatientMode;
        $this->resetQuickPatientForm();
    }

    public function resetQuickPatientForm()
    {
        $this->reset([
            'first_name', 'middle_name', 'last_name', 'mother_name', 'gender',
            'phone_number1', 'phone_number2', 'emergency_person', 'emergency_contact',
            'emergency_person_relationship', 'region', 'region_zone', 'region_woreda', 'date_of_birth'
        ]);
    }

    public function saveQuickPatient()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'phone_number1' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
        ]);

        try {
            DB::transaction(function () {
                $patient = Patient::create([
                    'card_number' => $this->generateCardNumber(),
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                    'mother_name' => $this->mother_name,
                    'gender' => $this->gender,
                    'phone_number1' => $this->phone_number1,
                    'phone_number2' => $this->phone_number2,
                    'emergency_person' => $this->emergency_person,
                    'emergency_contact' => $this->emergency_contact,
                    'emergency_person_relationship' => $this->emergency_person_relationship,
                    'region' => $this->region,
                    'region_zone' => $this->region_zone,
                    'region_woreda' => $this->region_woreda,
                    'date_of_birth' => $this->date_of_birth,
                    'created_by' => Auth::id(),
                ]);

                $this->selectedPatient = $patient;
                $this->patient_id = $patient->id;
                $this->patientSearch = $patient->first_name . ' ' . $patient->last_name;
                
                // Clear relevant caches
                Cache::forget('recent_patients');
                Cache::forget('popular_patients');
            });

            $this->quickPatientMode = false;
            $this->resetQuickPatientForm();
            
            $this->showAlertMessage('Patient created successfully!', 'success');

        } catch (\Exception $e) {
            $this->showAlertMessage('Error creating patient: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Generate unique card number
     */
    protected function generateCardNumber()
    {
        $prefix = 'PAT';
        $timestamp = now()->format('ymd');
        $random = strtoupper(substr(uniqid(), -4));
        
        return $prefix . '-' . $timestamp . '-' . $random;
    }

    // Doctor and Time Slot Methods
    public function updatedDoctorId()
    {
        $this->loadAvailableSlots();
    }

    public function updatedAppointmentDate()
    {
        $this->loadAvailableSlots();
    }

    protected function loadAvailableSlots()
    {
        if ($this->doctor_id && $this->appointment_date) {
            $this->availableSlots = $this->appointmentService->generateTimeSlots(
                $this->doctor_id,
                $this->appointment_date
            );
        }
    }

    public function selectSlot($slotTime)
    {
        $this->appointment_time = $slotTime;
        $this->selectedSlot = $slotTime;
        
        // Find the selected slot display
        foreach ($this->availableSlots as $slot) {
            if ($slot['time'] === $slotTime) {
                $this->time_slot = $slot['display'];
                break;
            }
        }
    }

    // Related Order Methods
    public function updatedRelatedOrderType()
    {
        $this->relatedOrders = [];
        $this->related_order_id = null;
        
        if ($this->related_order_type !== 'none') {
            $this->loadRelatedOrders();
        }
    }

    protected function loadRelatedOrders()
    {
        // Cache order lookups
        $cacheKey = 'orders_' . $this->related_order_type;
        
        $this->relatedOrders = Cache::remember($cacheKey, 300, function () {
            // This would depend on your order types
            if ($this->related_order_type === 'lab') {
                return []; // Fetch lab orders
            } elseif ($this->related_order_type === 'medication') {
                return []; // Fetch medication orders
            } elseif ($this->related_order_type === 'rehab') {
                return []; // Fetch rehab orders
            }
            return [];
        });
    }

    // Validation Rules
    protected function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'visit_type' => 'required|in:consultation,follow-up,lab_review,rehab_milestone,emergency,other',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => [
                'required',
                new AvailableTimeSlot($this->doctor_id, $this->appointment_date)
            ],
            'payment_amount' => 'nullable|numeric|min:0',
            'additional_notes' => 'nullable|string|max:1000',
        ];
    }

    protected function messages()
    {
        return [
            'patient_id.required' => 'Please select a patient',
            'doctor_id.required' => 'Please select a doctor',
            'appointment_date.required' => 'Please select an appointment date',
            'appointment_date.after_or_equal' => 'Appointment date cannot be in the past',
            'appointment_time.required' => 'Please select an appointment time',
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $appointment = Appointment::create([
                    'patient_id' => $this->patient_id,
                    'doctor_id' => $this->doctor_id,
                    'visit_type' => $this->visit_type,
                    'appointment_date' => $this->appointment_date,
                    'appointment_time' => $this->appointment_time,
                    'time_slot' => $this->time_slot,
                    'status' => $this->is_request ? 'requested' : 'scheduled',
                    'additional_notes' => $this->additional_notes,
                    'payment_status' => $this->payment_status,
                    'payment_amount' => $this->payment_amount,
                    'related_order_type' => $this->related_order_type,
                    'related_order_id' => $this->related_order_id,
                    'created_by' => Auth::id(),
                ]);

                // Clear relevant caches
                Cache::forget('recent_patients');
                Cache::forget('popular_patients');
            });

            session()->flash('success', 'Appointment created successfully!');
            
            return redirect()->route('appointments.index');

        } catch (\Exception $e) {
            $this->showAlertMessage('Failed to create appointment: ' . $e->getMessage(), 'error');
        }
    }

    public function showAlertMessage($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
        
        $this->dispatch('alert-shown');
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        return view('livewire.appointment.create-appointment', [
            'doctors' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get(),
            'visitTypes' => [
                'consultation' => 'Consultation',
                'follow-up' => 'Follow-up',
                'lab_review' => 'Lab Review',
                'rehab_milestone' => 'Rehab Milestone',
                'emergency' => 'Emergency',
                'other' => 'Other',
            ],
            'paymentStatuses' => [
                'unpaid' => 'Unpaid',
                'partially_paid' => 'Partially Paid',
                'paid' => 'Paid',
                'waived' => 'Waived',
            ],
            'orderTypes' => [
                'none' => 'None',
                'lab' => 'Lab Order',
                'medication' => 'Medication Order',
                'rehab' => 'Rehab Order',
            ],
        ]);
    }
}