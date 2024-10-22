<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Payment\RegistrationPaymentController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleLoginController;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/registration/create', [RegistrationController::class,'create'])->middleware(['auth', 'verified'])->name('registration.create');
Route::get('/registration/my-registration', [RegistrationController::class,'myRegistration'])->middleware(['auth', 'verified'])->name('registration.my-registration');

Route::get('/registrations', function () {

    return view('all-registrations');
})->name('all-registrations');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');



Route::middleware('guest')->group(function () {
    Route::get('/auth/google/redirect', function () {
        return Socialite::driver('google')->redirect();
    })->name('login');

    Route::get('/auth/google/callback', [GoogleLoginController::class, 'googleCallback']);


});
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::get('/registration/{registration}/payment', [RegistrationPaymentController::class, 'payment'])->name('registration.payment.create');
Route::get('/registration/{registration}/success', [RegistrationPaymentController::class, 'success'])->name('registration.payment.success');
Route::get('/registration/{registration}/cancel', [RegistrationPaymentController::class, 'cancel'])->name('registration.payment.cancel');
