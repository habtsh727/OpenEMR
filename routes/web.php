<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\ServiceCategory;
use App\Livewire\Services\Service;
use App\Livewire\Patients\Patients;
use App\Livewire\CardFee\CardFee;
use App\Livewire\Payments\Payments;
use App\Livewire\Payments\PaymentDetail;

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
Route::get('patients/{patient}/payments', PaymentDetail::class)
     ->name('payments-detail');
require __DIR__.'/auth.php';
