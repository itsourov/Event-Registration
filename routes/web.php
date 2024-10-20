<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Payment\RegistrationPaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleLoginController;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/registration-form', function () {
    return view('registration-form');
})->middleware(['auth', 'verified'])->name('registration-form');


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
