<?php

use App\Http\Controllers\PrescriptionController;
use App\Http\Livewire\Employees\Manage;
use App\Livewire\Appointment\AppointmentIndex;
use App\Livewire\Appointment\AppointmentRequests;
use App\Livewire\Appointment\CalendarView;
use App\Livewire\Appointment\CreateAppointment;
use App\Livewire\Appointment\DoctorTodayAppointments;
use App\Livewire\Appointment\TodayAppointments;
use App\Livewire\Appointment\UpcomingAppointments;
use App\Livewire\Doctor\RehabReview;
use App\Livewire\Encounters\TriageIndex;
use App\Livewire\OrderLab\LabDashboard;
use App\Livewire\Pharmacy\Walkin\WalkinSalesReport;
use App\Livewire\Referral\ReferralQueue;
use App\Livewire\Rehab\RehabTreatmentPage;
use App\Livewire\Rehab\RehabTreatmentQueue;
use App\Livewire\Rehab\RehabTreatmentTypeManager;
use App\Livewire\Report\BedPaymentReport;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\ServiceCategory;
use App\Livewire\Services\Service;
use App\Livewire\Patients\Patients;
use App\Livewire\CardFee\CardFee;
use App\Livewire\Payments\Payments;
use App\Livewire\Payments\PaymentDetail;
use App\Livewire\NurseTriage\NurseTriage;
use App\Livewire\PatientHistory\PatientHistory;

use App\Livewire\Admin\Users;
use App\Livewire\Cashier\MedicationOrders;
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
use App\Livewire\OrderLab\OrderMedicationPage;
use App\Models\Encounter;


use App\Livewire\Doctor\CreateImagingOrder;  // Updated namespace
use App\Livewire\Doctor\ViewImagingResults;
use App\Livewire\Cashier\ImagingPayments;
use App\Livewire\Radiology\RadiologyDashboard;
use App\Livewire\Admin\ManageImagingTypes;
use App\Livewire\Admin\ManageBodyParts;
use App\Livewire\Appointment\AllAppointments;
use App\Livewire\Appointment\AppointmentDetail;
use App\Livewire\Appointment\AppointmentReports;
use App\Livewire\Bed\BedIndex;
use App\Livewire\Cashier\CashierOrderQueueComponent;
use App\Livewire\Cashier\OrderQueueComponent;
use App\Livewire\Consumables\ConsumableManager;
use App\Livewire\Cupping\CuppingLocationManager;
use App\Livewire\Cupping\CuppingTypeManager;
use App\Livewire\Cupping\DoctorCuppingOrder;
use App\Livewire\Cupping\TreatmentCuppingQueue;
use App\Livewire\Doctor\CustomMedicationFormComponent;
use App\Livewire\Doctor\DoctorMedicationOrderComponent;
use App\Livewire\Doctor\RehabQueue;
use App\Livewire\Pharmacy\PharmacyQueueComponent;
use App\Livewire\Referral\CreateReferral;
use App\Livewire\Referral\PrintReferral;
use App\Livewire\Referral\SubmitResultModal;
use App\Livewire\VitalTypes\Index;
use App\Livewire\Forms\RehabPackageForm;
use App\Livewire\Forms\RehabPackageList;
use App\Livewire\Pharmacy\Report\SalesReport;
use App\Livewire\Pharmacy\Walkin\CashierPayment;
use App\Livewire\Pharmacy\Walkin\PharmacistDispense;
use App\Livewire\Pharmacy\Walkin\PharmacistOrder;
use App\Livewire\Rehab\BedManager\BedSelectionQueue;
use App\Livewire\Rehab\BedQueue;
use App\Livewire\Rehab\Cashier\RehabPaymentProcess;
use App\Livewire\Rehab\Cashier\RehabPaymentQueue;
use App\Livewire\Rehab\DoctorRehabOrder;
use App\Livewire\Rehab\QuestionnaireForm;
use App\Livewire\Rehab\Queue;
use App\Livewire\Rehab\RehabFinanceReport;
use App\Livewire\Rehab\RehabPackagesComponent;
use App\Livewire\Rehab\Template\Index as TemplateIndex;
use App\Livewire\Rehab\Template\Form as TemplateForm;
use App\Livewire\Rehab\Template\Questions as TemplateQuestions;
use App\Livewire\Report\ImagingPaymentReport;
use App\Livewire\Report\LabPaymentReport;
use App\Livewire\Report\PharmacyPaymentReport;
use App\Livewire\Report\RegistrationPaymentReport;
use App\Livewire\Report\RehabPaymentReport;

Route::middleware(['auth'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/rehab/order/{rehabEncounter}', DoctorRehabOrder::class)->name('rehab.order');
    Route::get('/rehab-queue', RehabQueue::class)->name('rehab.queue');
    Route::get('/rehab/review/{id}', RehabReview::class)->name('rehab.review');
});

Route::middleware(['auth'])->prefix('rehab')->name('rehab.')->group(function () {
    Route::get('/queue', Queue::class)->name('queue');
    Route::get('/questionnaire/{id}', QuestionnaireForm::class)->name('questionnaire');

    Route::get('/templates', TemplateIndex::class)->name('templates.index');
    Route::get('/templates/create', TemplateForm::class)->name('templates.create');
    Route::get('/templates/edit/{id}', TemplateForm::class)->name('templates.edit');
    Route::get('/templates/{id}/questions', TemplateQuestions::class)->name('templates.questions');

    Route::get('/bed-manager/queue', BedSelectionQueue::class)->name('bed-manager.queue');

    // Cashier routes
    Route::get('/cashier/queue', RehabPaymentQueue::class)->name('cashier.queue');

    Route::get('/treatment-queue', RehabTreatmentQueue::class)->name('treatment.queue');
    Route::get('/treatment/{id}', RehabTreatmentPage::class)->name('treatment');
    Route::get('/treatment-types', RehabTreatmentTypeManager::class)->name('treatment-types');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/rehab-packages', RehabPackageList::class)->name('rehab.packages.index');
    Route::get('/rehab-packages/create', RehabPackageForm::class)->name('rehab.packages.create');
    Route::get('/rehab-packages/{id}/edit', RehabPackageForm::class)->name('rehab.packages.edit');

    // Cashier routes
    Route::get('/cashier/rehab/payments', RehabPaymentQueue::class)->name('cashier.rehab.payments');
    Route::get('/cashier/rehab/payment/{rehabOrder}', RehabPaymentProcess::class)->name('cashier.rehab.payment');
    Route::get('/payment-details/{orderId}', App\Livewire\Rehab\Cashier\PatientPaymentDetails::class)->name('rehab.cashier.payment-details');
    Route::get('rehab/reports', App\Livewire\Rehab\Cashier\PaymentReports::class)->name('rehab.payment.reports');
    Route::get('/rehab/finance-report', RehabFinanceReport::class)->name('rehab.finance.report');
});

Route::get('/', function () {
    return redirect()->route('dashboard');
})
    ->middleware('auth')
    ->name('home');
Route::get('/dashboard', \App\Livewire\Dashboard::class)->middleware('auth')->name('dashboard');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

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

    Route::get('patients/{patient}/payments', PaymentDetail::class)->name('payments-detail');
    Route::get('/triage/encounters', TriageIndex::class)->name('triage.encounters');


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

    Route::get('/lab-tests', LabTestManager::class)->name('lab-tests.index');
});
Route::middleware(['auth'])->group(function () {
    // Doctor
    Route::get('/doctor/encounter/{encounter}/imaging/order', CreateImagingOrder::class)
        ->name('doctor.imaging.order');

    Route::get('/doctor/encounter/{encounter}/imaging/results', ViewImagingResults::class)
        ->name('doctor.imaging.results');

    // Cashier
    Route::get('/cashier/imaging-payments', ImagingPayments::class)
        ->name('cashier.imaging');

    // Radiology
    Route::get('/radiology/dashboard', RadiologyDashboard::class)
        ->name('radiology.dashboard');

    // Admin
    Route::get('/admin/imaging-types', ManageImagingTypes::class)
        ->name('admin.imaging-types');

    Route::get('/admin/body-parts', ManageBodyParts::class)
        ->name('admin.body-parts');
});


Route::middleware(['auth'])->group(function () {
    // Doctor Side
    Route::get('/doctor/encounter/{encounter}/medication/order', DoctorMedicationOrderComponent::class)
        ->name('doctor.medication.order');

    Route::get('/doctor/custom-medications/manage', CustomMedicationFormComponent::class)
        ->name('doctor.custom-medications.manage');
    Route::get('/cashier/medication-orders/queue', OrderQueueComponent::class)
        ->name('cashier.medication.orders');
    Route::get('/pharmacy/dashboard', \App\Livewire\Pharmacy\PharmacyDashboardComponent::class)
        ->name('pharmacy.dashboard');


    Route::get('/prescriptions/{prescription}/download', [PrescriptionController::class, 'download'])
        ->name('prescriptions.download')
        ->middleware('auth');

    Route::get('/prescriptions/{prescription}/print', [PrescriptionController::class, 'print'])
        ->name('prescriptions.print')
        ->middleware('auth');
    // Reports
    Route::get('/reports/medications', DoctorMedicationOrderComponent::class)
        ->middleware('role:admin|pharmacist')
        ->name('reports.medications');

    Route::get('/pharmacy/custom-medications', \App\Livewire\Pharmacy\CustomMedicationComponent::class)
        ->name('pharmacy.custom-medications');
    Route::get('/pharmacy/report/sales', SalesReport::class)->name('pharmacy.report.sales');
});

// routes/web.php (add these routes)
Route::middleware(['auth'])->group(function () {
    // Referral Routes
    Route::get('/referrals/create/{encounter}', App\Livewire\Referral\CreateReferral::class)
        ->name('referrals.create');

    Route::get('/referrals/queue', App\Livewire\Referral\ReferralQueue::class)
        ->name('referrals.queue');

    Route::get('/referrals/{referral}', App\Livewire\Referral\ViewReferral::class)
        ->name('referrals.view');

    Route::get('/referrals/{referral}/submit-result', App\Livewire\Referral\SubmitResult::class)
        ->name('referrals.submit-result');
    Route::get('/referrals/{referral}/print', PrintReferral::class)
        ->name('referrals.print');

    Route::get('/beds', BedIndex::class)->name('beds.index');
});
Route::middleware(['auth', 'verified'])->group(function () {
    // Consumables Management
    Route::prefix('inventory')->group(function () {
        Route::get('/consumables', action: ConsumableManager::class)
            ->name('inventory.consumables');
    });
});
Route::middleware('auth')->group(function () {
    Route::get('/vital-types', Index::class)->name('vital-types.index');
});


Route::middleware(['auth'])->group(function () {
    // Registration Payment Reports
    Route::get('/reports/registration-payments', RegistrationPaymentReport::class)
        ->name('reports.registration-payments');

    // Lab Payment Reports
    Route::get('/reports/lab-payments', LabPaymentReport::class)
        ->name('reports.lab-payments');

    // Imaging Payment Reports
    Route::get('/reports/imaging-payments', ImagingPaymentReport::class)
        ->name('reports.imaging-payments');

    Route::get('/reports/pharmacy-payments', PharmacyPaymentReport::class)
        ->name('reports.pharmacy-payments');
    Route::get('/reports/rehab-payments', RehabPaymentReport::class)
        ->name('reports.rehab-payments');
    Route::get('/reports/bed-payments', BedPaymentReport::class)
        ->name('reports.bed-payments');
    Route::get('/patients/{patientId}/finance', App\Livewire\Patient\PatientFinanceReport::class)
        ->name('patients.finance');
});

Route::middleware(['auth'])->prefix('doctor')->name('doctor.')->group(function () {
    // Appointment Routes
    Route::get('/appointments/today', DoctorTodayAppointments::class)
        ->name('appointments.today');
    Route::get('/appointments/create', CreateAppointment::class)
        ->name('appointments.create');
    Route::get('/appointments/upcoming', UpcomingAppointments::class)
        ->name('appointments.upcoming');
    Route::get('/appointments/all', AllAppointments::class)
        ->name('appointments.all');
    Route::get('/appointments/calendar', CalendarView::class)
        ->name('appointments.calendar');
    Route::get('/appointments/reports', AppointmentReports::class)
        ->name('appointments.reports');
    Route::get('/appointments/{id}', AppointmentDetail::class)
        ->name('appointments.detail');
});





Route::middleware(['auth'])->group(function () {
    Route::get('/pharmacy/walkin/create', PharmacistOrder::class)->name('pharmacy.walkin.create');
    Route::get('/pharmacy/walkin/payment', CashierPayment::class)->name('pharmacy.walkin.payment');
    Route::get('/pharmacy/walkin/dispense', PharmacistDispense::class)->name('pharmacy.walkin.dispense');
    Route::get('/pharmacy/walkin/report', WalkinSalesReport::class)->name('pharmacy.walkin.report');
});

// Route::prefix('cupping')->name('cupping.')->middleware(['auth'])->group(function () {

//     // Doctor Routes
//     Route::prefix('doctor')->name('doctor.')->group(function () {
//         Route::get('/order/{encounter}', DoctorCuppingOrder::class)->name('order');
//     });


// });
Route::get('/cupping/doctor/order/{encounter}', DoctorCuppingOrder::class)->name('cupping.doctor.order');
Route::get('/cupping/admin/packages', \App\Livewire\Cupping\AdminCuppingPackageManager::class)->name('cupping.admin.packages');
Route::get('/cupping/cashier/queue', \App\Livewire\Cupping\CashierCuppingQueue::class)->name('cupping.cashier.queue');
Route::get('/cupping/treatment/session/{session}', \App\Livewire\Cupping\TreatmentCuppingSession::class)->name('cupping.treatment.session');
Route::get('cupping/treatment/queue', TreatmentCuppingQueue::class)->name('cupping.treatment.queue');
Route::get('/cupping/results', \App\Livewire\Cupping\CuppingResult::class)->name('cupping.results');
Route::get('/cupping/types', CuppingTypeManager::class)->name('cupping.types');
Route::get('/cupping/locations', CuppingLocationManager::class)->name('cupping.locations');
Route::get('/cupping/sales-report', \App\Livewire\Cupping\CuppingSalesReport::class)->name('cupping.sales-report');

require __DIR__ . '/auth.php';
