<?php

use App\Http\Controllers\Payment\RegistrationPaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/registration/{registration}/webhook', [RegistrationPaymentController::class, 'webhookCallback'] )->name('registration.payment.webhook');


