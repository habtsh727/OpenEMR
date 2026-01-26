<?php

use App\Http\Livewire\Employees\Manage;

use App\Livewire\Encounters\TriageIndex;
use App\Livewire\OrderLab\LabDashboard;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\ServiceCategory;
use App\Livewire\Services\Service;
use App\Livewire\Patients\Patients;
use App\Livewire\CardFee\CardFee;
use App\Livewire\Payments\Payments;
use App\Livewire\Payments\PaymentDetail;
use App\Livewire\Nursing\Nursing;
use App\Livewire\NurseTriage\NurseTriage;
use App\Livewire\PatientHistory\PatientHistory;

use App\Livewire\Admin\Users;
use App\Livewire\Config\AssessmentTemplates;
use App\Livewire\Config\ChiefComplaintTemplates;
use App\Livewire\Config\ExaminationTemplates;
// use App\Livewire\Config\ChiefComplaintTemplates;
use App\Livewire\Config\MedicalHistoryTemplates;
use App\Livewire\Doctor\AssessmentForm;
use App\Livewire\Doctor\ChiefComplaint;
use App\Livewire\Doctor\ConsultationWorkflow;
use App\Livewire\Doctor\DoctorQueue;
use App\Livewire\Doctor\ExaminationForm;
use App\Livewire\Employees\Manage as EmployeesManage;
use App\Livewire\OrderLab\CashierLabPayment;
use App\Livewire\OrderLab\DoctorLabOrderCreate;
use App\Livewire\OrderLab\DoctorLabResults;
use App\Livewire\OrderLab\LabTestManager;
use App\Models\Encounter;

Route::get('/', function () {
    return redirect()->route('dashboard');
})
    ->middleware('auth')
    ->name('home');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');



    Route::get('service-category', ServiceCategory::class)->name('service-category');
    Route::get('services', Service::class)->name('services');
    Route::get('patients', Patients::class)->name('patients');
    Route::get('card-fee', CardFee::class)->name('card-fee');
    Route::get('payments', Payments::class)->name('payments');

    // Route::get('payments-detail', PaymentDetail::class)->name('payments-detail'); 
    Route::get('patients/{patient}/payments', PaymentDetail::class)->name('payments-detail');
    Route::get('/triage/encounters', TriageIndex::class)->name('triage.encounters');
    Route::get('patients/nursing', Nursing::class)->name('patient.nursing');
    Route::get('patients/{patient}/create-triage', NurseTriage::class)->name('create-triage');
    Route::get('patients/{patient}/view-detail', PatientHistory::class)->name('view-detail');

    // Route::get('/doctor/queue', DoctorQueue::class)->name('doctor.queue');
    // Route::get('patients/{patient}/take', [DoctorQueue::class, 'take'])->name('doctor.take');

    // Route::get('doctor/consult/{queue}', DoctorConsultation::class)->name('doctor.consult');

    Route::prefix('pharmacy')->group(function () {
        Route::get('/items', \App\Livewire\Pharmacy\Item\Index::class)->name('pharmacy.items');

        Route::get('/masters', \App\Livewire\Pharmacy\Master\Index::class)
            ->name('pharmacy.masters');


        Route::get('/batches', \App\Livewire\Pharmacy\Batch\Index::class)
            ->name('pharmacy.batches');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('users', Users::class)->name('users');
        Route::get('roles', \App\Livewire\Admin\Roles::class)->name('roles');
        Route::get('employees', EmployeesManage::class)->name('employees');
    });
    Route::get('/patients/{patient}/profile', \App\Livewire\PatientProfile::class)->name('patients.profile');
    Route::get(
        '/consultation/{encounter}/medical-history',
        ConsultationWorkflow::class
    )
        ->name('consultation.medical-history');
    Route::get(
        '/consultation/{encounter}/chief-complaint',
        ChiefComplaint::class
    )
        ->name('consultation.chief-complaint');
});
Route::middleware(['auth'])->group(function () {
    // Doctor queue page
    Route::get('/doctor/queue', DoctorQueue::class)->name('doctor.queue');

    // Consultation workflow routes
    Route::get('/consultation/{encounter}/medical-history', ConsultationWorkflow::class)
        ->name('consultation.medical-history');

    Route::get('/consultation/{encounter}/chief-complaint', ChiefComplaint::class)
        ->name('consultation.chief-complaint');

    Route::get('/consultation/{encounter}/examination', ExaminationForm::class)
        ->name('consultation.examination');

    Route::get('/consultation/{encounter}/assessment', AssessmentForm::class)
        ->name('consultation.assessment');
});
Route::middleware(['auth'])->group(function () {
    // Config Templates
    Route::get('/config/medical-history-templates', MedicalHistoryTemplates::class)
        ->name('config.medical-history-templates');
    Route::get('/config/chief-complaint-templates', ChiefComplaintTemplates::class)
        ->name('config.chief-complaint-templates');
    Route::get('/config/examinations', ExaminationTemplates::class)->name('examination-templates');
    Route::get('/config/assessment-templates', AssessmentTemplates::class)->name('config.assessment-templates');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Doctor Routes
    Route::get('/doctor/{encounter}/lab-orders/create', DoctorLabOrderCreate::class)
        ->middleware(['can:create lab order'])
        ->name('lab-orders.create');
    // Cashier Routes
    Route::get('/lab-orders/payments', CashierLabPayment::class)
        ->middleware(['can:pay lab order'])
        ->name('lab-orders.payments');

    // Laboratory Routes
    Route::get('/lab-dashboard', LabDashboard::class)
        ->middleware(['can:collect sample'])
        ->name('lab.dashboard');
    Route::get('/doctor/lab-results', action: DoctorLabResults::class)->name('doctor.lab-results');
    Route::get('/doctor/patients/{patient}/lab-results', DoctorLabResults::class)->name('doctor.patient.lab-results');
    // Modal routes (will be called via Livewire)
    Route::get('/lab-tests', LabTestManager::class)->name('lab-tests.index');
});
require __DIR__ . '/auth.php';
