<?php

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
use App\Livewire\DoctorQueue\DoctorQueue;
use App\Livewire\DoctorConsultation\DoctorConsultation;
use App\Livewire\Admin\Users;

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
    Route::get('patients/nursing', Nursing::class)->name('patient.nursing');
    Route::get('patients/{patient}/create-triage', NurseTriage::class)->name('create-triage');
    Route::get('patients/{patient}/view-detail', PatientHistory::class)->name('view-detail');

    Route::get('/doctor/queue',DoctorQueue::class)->name('doctor.queue');
    Route::get('patients/{patient}/take', [DoctorQueue::class,'take'])->name('doctor.take');

    Route::get('doctor/consult/{queue}', DoctorConsultation::class)->name('doctor.consult');  

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
    });





});

require __DIR__.'/auth.php';
