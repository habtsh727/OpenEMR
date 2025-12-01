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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});


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



require __DIR__.'/auth.php';
