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

use App\Livewire\Doctor\CreateImagingOrder;
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

// ==================== DOCTOR ROUTES ====================
Route::middleware(['auth'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/rehab/order/{rehabEncounter}', DoctorRehabOrder::class)
        ->middleware('can:create_rehab_order')
        ->name('rehab.order');
    Route::get('/rehab-queue', RehabQueue::class)
        ->middleware('can:create_rehab_order')
        ->name('rehab.queue');
    Route::get('/rehab/review/{id}', RehabReview::class)
        ->middleware('can:review_rehab_order')
        ->name('rehab.review');
});

// ==================== REHAB ROUTES ====================
Route::middleware(['auth'])->prefix('rehab')->name('rehab.')->group(function () {
    Route::get('/queue', Queue::class)
        ->middleware('can:view_rehab_queue')
        ->name('queue');
    Route::get('/questionnaire/{id}', QuestionnaireForm::class)
        ->middleware('can:fill_rehab_questionnaire')
        ->name('questionnaire');

    Route::get('/templates', TemplateIndex::class)
        ->middleware('can:view_rehab_templates')
        ->name('templates.index');
    Route::get('/templates/create', TemplateForm::class)
        ->middleware('can:create_rehab_template')
        ->name('templates.create');
    Route::get('/templates/edit/{id}', TemplateForm::class)
        ->middleware('can:edit_rehab_template')
        ->name('templates.edit');
    Route::get('/templates/{id}/questions', TemplateQuestions::class)
        ->middleware('can:manage_rehab_template_questions')
        ->name('templates.questions');

    Route::get('/bed-manager/queue', BedSelectionQueue::class)
        ->middleware('can:manage_rehab_bed_selection')
        ->name('bed-manager.queue');

    Route::get('/cashier/queue', RehabPaymentQueue::class)
        ->middleware('can:view_rehab_cashier_queue')
        ->name('cashier.queue');

    Route::get('/treatment-queue', RehabTreatmentQueue::class)
        ->middleware('can:view_rehab_treatment_queue')
        ->name('treatment.queue');
    Route::get('/treatment/{id}', RehabTreatmentPage::class)
        ->middleware('can:manage_rehab_treatment')
        ->name('treatment');
    Route::get('/treatment-types', RehabTreatmentTypeManager::class)
        ->middleware('can:manage_rehab_treatment_types')
        ->name('treatment-types');
});

// ==================== REHAB PACKAGES & FINANCE ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/rehab-packages', RehabPackageList::class)
        ->middleware('can:view_rehab_packages')
        ->name('rehab.packages.index');
    Route::get('/rehab-packages/create', RehabPackageForm::class)
        ->middleware('can:create_rehab_package')
        ->name('rehab.packages.create');
    Route::get('/rehab-packages/{id}/edit', RehabPackageForm::class)
        ->middleware('can:edit_rehab_package')
        ->name('rehab.packages.edit');

    Route::get('/cashier/rehab/payments', RehabPaymentQueue::class)
        ->middleware('can:view_rehab_cashier_queue')
        ->name('cashier.rehab.payments');
    Route::get('/cashier/rehab/payment/{rehabOrder}', RehabPaymentProcess::class)
        ->middleware('can:process_rehab_cashier_payment')
        ->name('cashier.rehab.payment');
    Route::get('/payment-details/{orderId}', App\Livewire\Rehab\Cashier\PatientPaymentDetails::class)
        ->middleware('can:view_rehab_cashier_queue')
        ->name('rehab.cashier.payment-details');
    Route::get('rehab/reports', App\Livewire\Rehab\Cashier\PaymentReports::class)
        ->middleware('can:view_rehab_finance_report')
        ->name('rehab.payment.reports');
    Route::get('/rehab/finance-report', RehabFinanceReport::class)
        ->middleware('can:view_rehab_finance_report')
        ->name('rehab.finance.report');
});

// ==================== HOME & DASHBOARD ====================
Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth')->name('home');

Route::get('/dashboard', \App\Livewire\Dashboard::class)
    ->middleware('auth')
    ->name('dashboard');

// ==================== SETTINGS ====================
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Services & Categories
    Route::get('service-category', ServiceCategory::class)
        ->middleware('can:manage_service_categories')
        ->name('service-category');
    Route::get('services', Service::class)
        ->middleware('can:manage_services')
        ->name('services');
    Route::get('card-fee', CardFee::class)
        ->middleware('can:manage_card_fee')
        ->name('card-fee');

    // Patients
    Route::get('patients', Patients::class)
        ->middleware('can:view_patients')
        ->name('patients');

    // Payments
    Route::get('payments', Payments::class)
        ->middleware('can:view_payments')
        ->name('payments');

    Route::get('patients/{patient}/payments', PaymentDetail::class)
        ->middleware('can:view_payment_details')
        ->name('payments-detail');

    // Triage
    Route::get('/triage/encounters', TriageIndex::class)
        ->middleware('can:view_triage_encounters')
        ->name('triage.encounters');

    // Pharmacy
    Route::prefix('pharmacy')->group(function () {
        Route::get('/items', \App\Livewire\Pharmacy\Item\Index::class)
            ->middleware('can:manage_pharmacy_items')
            ->name('pharmacy.items');
        Route::get('/masters', \App\Livewire\Pharmacy\Master\Index::class)
            ->middleware('can:manage_pharmacy_masters')
            ->name('pharmacy.masters');
        Route::get('/batches', \App\Livewire\Pharmacy\Batch\Index::class)
            ->middleware('can:manage_pharmacy_batches')
            ->name('pharmacy.batches');
    });

    // Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('users', Users::class)
            ->middleware('can:view_user')
            ->name('users');
        Route::get('roles', \App\Livewire\Admin\Roles::class)
            ->middleware('can:manage_roles')
            ->name('roles');
        Route::get('employees', EmployeesManage::class)
            ->middleware('can:view_employee')
            ->name('employees');
    });

    // Patient Profile
    Route::get('/patients/{patient}/profile', \App\Livewire\PatientProfile::class)
        ->middleware('can:view_patient_profile')
        ->name('patients.profile');

    // Consultation Workflow
    Route::get('/consultation/{encounter}/medical-history', ConsultationWorkflow::class)
        ->middleware('can:manage_medical_history')
        ->name('consultation.medical-history');
    Route::get('/consultation/{encounter}/chief-complaint', ChiefComplaint::class)
        ->middleware('can:manage_chief_complaint')
        ->name('consultation.chief-complaint');
});

// ==================== DOCTOR CONSULTATION ROUTES ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/doctor/queue', DoctorQueue::class)
        ->middleware('can:view_doctor_queue')
        ->name('doctor.queue');

    Route::get('/consultation/{encounter}/medical-history', ConsultationWorkflow::class)
        ->middleware('can:manage_medical_history')
        ->name('consultation.medical-history');

    Route::get('/consultation/{encounter}/chief-complaint', ChiefComplaint::class)
        ->middleware('can:manage_chief_complaint')
        ->name('consultation.chief-complaint');

    Route::get('/consultation/{encounter}/examination', ExaminationForm::class)
        ->middleware('can:manage_examination')
        ->name('consultation.examination');

    Route::get('/consultation/{encounter}/assessment', AssessmentForm::class)
        ->middleware('can:manage_assessment')
        ->name('consultation.assessment');
});

// ==================== CONFIGURATION TEMPLATES ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/config/medical-history-templates', MedicalHistoryTemplates::class)
        ->middleware('can:manage_medical_history_templates')
        ->name('config.medical-history-templates');
    Route::get('/config/chief-complaint-templates', ChiefComplaintTemplates::class)
        ->middleware('can:manage_chief_complaint_templates')
        ->name('config.chief-complaint-templates');
    Route::get('/config/examinations', ExaminationTemplates::class)
        ->middleware('can:manage_examination_templates')
        ->name('examination-templates');
    Route::get('/config/assessment-templates', AssessmentTemplates::class)
        ->middleware('can:manage_assessment_templates')
        ->name('config.assessment-templates');
});

// ==================== LABORATORY ROUTES ====================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/doctor/{encounter}/lab-orders/create', DoctorLabOrderCreate::class)
        ->middleware('can:create_lab_order')
        ->name('lab-orders.create');

    Route::get('/lab-orders/payments', CashierLabPayment::class)
        ->middleware('can:view_lab_payments')
        ->name('lab-orders.payments');

    Route::get('/lab-dashboard', LabDashboard::class)
        ->middleware('can:view_lab_dashboard')
        ->name('lab.dashboard');

    Route::get('/doctor/lab-results', DoctorLabResults::class)
        ->middleware('can:view_lab_results')
        ->name('doctor.lab-results');
    Route::get('/doctor/patients/{patient}/lab-results', DoctorLabResults::class)
        ->middleware('can:view_patient_lab_results')
        ->name('doctor.patient.lab-results');

    Route::get('/lab-tests', LabTestManager::class)
        ->middleware('can:manage_lab_tests')
        ->name('lab-tests.index');
});

// ==================== IMAGING / RADIOLOGY ROUTES ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/doctor/encounter/{encounter}/imaging/order', CreateImagingOrder::class)
        ->middleware('can:create_imaging_order')
        ->name('doctor.imaging.order');

    Route::get('/doctor/encounter/{encounter}/imaging/results', ViewImagingResults::class)
        ->middleware('can:view_imaging_results')
        ->name('doctor.imaging.results');

    Route::get('/cashier/imaging-payments', ImagingPayments::class)
        ->middleware('can:view_imaging_payments')
        ->name('cashier.imaging');

    Route::get('/radiology/dashboard', RadiologyDashboard::class)
        ->middleware('can:view_radiology_dashboard')
        ->name('radiology.dashboard');

    Route::get('/admin/imaging-types', ManageImagingTypes::class)
        ->middleware('can:manage_imaging_types')
        ->name('admin.imaging-types');

    Route::get('/admin/body-parts', ManageBodyParts::class)
        ->middleware('can:manage_body_parts')
        ->name('admin.body-parts');
});

// ==================== MEDICATION / PHARMACY ROUTES ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/doctor/encounter/{encounter}/medication/order', DoctorMedicationOrderComponent::class)
        ->middleware('can:create_medication_order')
        ->name('doctor.medication.order');

    Route::get('/doctor/custom-medications/manage', CustomMedicationFormComponent::class)
        ->middleware('can:manage_custom_medications')
        ->name('doctor.custom-medications.manage');

    Route::get('/cashier/medication-orders/queue', OrderQueueComponent::class)
        ->middleware('can:view_cashier_medication_orders')
        ->name('cashier.medication.orders');

    Route::get('/pharmacy/dashboard', \App\Livewire\Pharmacy\PharmacyDashboardComponent::class)
        ->middleware('can:view_pharmacy_dashboard')
        ->name('pharmacy.dashboard');

    Route::get('/prescriptions/{prescription}/download', [PrescriptionController::class, 'download'])
        ->middleware('can:download_prescription')
        ->name('prescriptions.download');

    Route::get('/prescriptions/{prescription}/print', [PrescriptionController::class, 'print'])
        ->middleware('can:print_prescription')
        ->name('prescriptions.print');

    Route::get('/reports/medications', DoctorMedicationOrderComponent::class)
        ->middleware('can:view_medication_orders')
        ->name('reports.medications');

    Route::get('/pharmacy/custom-medications', \App\Livewire\Pharmacy\CustomMedicationComponent::class)
        ->middleware('can:manage_custom_medications')
        ->name('pharmacy.custom-medications');

    Route::get('/pharmacy/report/sales', SalesReport::class)
        ->middleware('can:view_pharmacy_sales_report')
        ->name('pharmacy.report.sales');
});

// ==================== REFERRAL ROUTES ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/referrals/create/{encounter}', App\Livewire\Referral\CreateReferral::class)
        ->middleware('can:create_referral')
        ->name('referrals.create');

    Route::get('/referrals/queue', App\Livewire\Referral\ReferralQueue::class)
        ->middleware('can:view_referral_queue')
        ->name('referrals.queue');

    Route::get('/referrals/{referral}', App\Livewire\Referral\ViewReferral::class)
        ->middleware('can:view_referral')
        ->name('referrals.view');

    Route::get('/referrals/{referral}/submit-result', App\Livewire\Referral\SubmitResult::class)
        ->middleware('can:submit_referral_result')
        ->name('referrals.submit-result');

    Route::get('/referrals/{referral}/print', PrintReferral::class)
        ->middleware('can:print_referral')
        ->name('referrals.print');

    Route::get('/beds', BedIndex::class)
        ->middleware('can:view_beds')
        ->name('beds.index');
});

// ==================== INVENTORY / CONSUMABLES ====================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('inventory')->group(function () {
        Route::get('/consumables', ConsumableManager::class)
            ->middleware('can:manage_consumables')
            ->name('inventory.consumables');
    });
});

// ==================== VITAL TYPES ====================
Route::middleware('auth')->group(function () {
    Route::get('/vital-types', Index::class)
        ->middleware('can:manage_vital_types')
        ->name('vital-types.index');
});

// ==================== FINANCIAL REPORTS ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/reports/registration-payments', RegistrationPaymentReport::class)
        ->middleware('can:view_registration_payment_report')
        ->name('reports.registration-payments');

    Route::get('/reports/lab-payments', LabPaymentReport::class)
        ->middleware('can:view_lab_payment_report')
        ->name('reports.lab-payments');

    Route::get('/reports/imaging-payments', ImagingPaymentReport::class)
        ->middleware('can:view_imaging_payment_report')
        ->name('reports.imaging-payments');

    Route::get('/reports/pharmacy-payments', PharmacyPaymentReport::class)
        ->middleware('can:view_pharmacy_payment_report')
        ->name('reports.pharmacy-payments');

    Route::get('/reports/rehab-payments', RehabPaymentReport::class)
        ->middleware('can:view_rehab_payment_report')
        ->name('reports.rehab-payments');

    Route::get('/reports/bed-payments', BedPaymentReport::class)
        ->middleware('can:view_bed_payment_report')
        ->name('reports.bed-payments');

    Route::get('/patients/{patientId}/finance', App\Livewire\Patient\PatientFinanceReport::class)
        ->middleware('can:view_patient_finance')
        ->name('patients.finance');
});

// ==================== DOCTOR APPOINTMENT ROUTES ====================
Route::middleware(['auth'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/appointments/today', DoctorTodayAppointments::class)
        ->middleware('can:view_today_appointments')
        ->name('appointments.today');
    Route::get('/appointments/create', CreateAppointment::class)
        ->middleware('can:create_appointment')
        ->name('appointments.create');
    Route::get('/appointments/upcoming', UpcomingAppointments::class)
        ->middleware('can:view_upcoming_appointments')
        ->name('appointments.upcoming');
    Route::get('/appointments/all', AllAppointments::class)
        ->middleware('can:view_all_appointments')
        ->name('appointments.all');
    Route::get('/appointments/calendar', CalendarView::class)
        ->middleware('can:view_appointment_calendar')
        ->name('appointments.calendar');
    Route::get('/appointments/reports', AppointmentReports::class)
        ->middleware('can:view_appointment_reports')
        ->name('appointments.reports');
    Route::get('/appointments/{id}', AppointmentDetail::class)
        ->middleware('can:view_appointment_details')
        ->name('appointments.detail');
});

// ==================== PHARMACY WALK-IN ROUTES ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/pharmacy/walkin/create', PharmacistOrder::class)
        ->middleware('can:create_walkin_order')
        ->name('pharmacy.walkin.create');

    Route::get('/pharmacy/walkin/payment', CashierPayment::class)
        ->middleware('can:process_walkin_payment')
        ->name('pharmacy.walkin.payment');

    Route::get('/pharmacy/walkin/dispense', PharmacistDispense::class)
        ->middleware('can:dispense_walkin_medication')
        ->name('pharmacy.walkin.dispense');

    Route::get('/pharmacy/walkin/report', WalkinSalesReport::class)
        ->middleware('can:view_walkin_sales_report')
        ->name('pharmacy.walkin.report');
});

// ==================== CUPPING ROUTES ====================
Route::get('/cupping/doctor/order/{encounter}', DoctorCuppingOrder::class)
    ->middleware('can:create_cupping_order')
    ->name('cupping.doctor.order');

Route::get('/cupping/admin/packages', \App\Livewire\Cupping\AdminCuppingPackageManager::class)
    ->middleware('can:manage_cupping_admin_packages')
    ->name('cupping.admin.packages');

Route::get('/cupping/cashier/queue', \App\Livewire\Cupping\CashierCuppingQueue::class)
    ->middleware('can:process_cupping_payment')
    ->name('cupping.cashier.queue');

Route::get('/cupping/treatment/session/{session}', \App\Livewire\Cupping\TreatmentCuppingSession::class)
    ->middleware('can:manage_cupping_treatment')
    ->name('cupping.treatment.session');

Route::get('cupping/treatment/queue', TreatmentCuppingQueue::class)
    ->middleware('can:view_cupping_treatment_queue')
    ->name('cupping.treatment.queue');

Route::get('/cupping/results', \App\Livewire\Cupping\CuppingResult::class)
    ->middleware('can:view_cupping_results')
    ->name('cupping.results');

Route::get('/cupping/types', CuppingTypeManager::class)
    ->middleware('can:manage_cupping_types')
    ->name('cupping.types');

Route::get('/cupping/locations', CuppingLocationManager::class)
    ->middleware('can:manage_cupping_locations')
    ->name('cupping.locations');

Route::get('/cupping/sales-report', \App\Livewire\Cupping\CuppingSalesReport::class)
    ->middleware('can:view_cupping_sales_report')
    ->name('cupping.sales-report');

require __DIR__ . '/auth.php';
