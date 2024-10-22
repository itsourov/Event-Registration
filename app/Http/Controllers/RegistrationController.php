<?php

namespace App\Http\Controllers;

use App\Enums\RegistrationStatuses;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function create()
    {
        if (auth()->user()->registration?->status == RegistrationStatuses::PAID || auth()->user()->registration?->status == RegistrationStatuses::PENDING) {

            return redirect(route('registration.my-registration'));
        }
        return view('registration-form');
    }

    public function myRegistration()
    {

        if (!auth()->user()->registration) {
            return view('registration-form');
        }
        return view('registration.my-registration');
    }
}
