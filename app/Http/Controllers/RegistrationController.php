<?php

namespace App\Http\Controllers;

use App\Enums\RegistrationStatuses;
use App\Models\Contest;
use App\Models\Registration;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function create(Contest $contest)
    {

        if ($contest->registration_deadline < now()) {

            Notification::make()
                ->title("You are late!")
                ->body("Contest registration deadline has been passed.")
                ->warning()
                ->send();
            return redirect(route('contests.show', $contest));
        }
        $registration = Registration::where('user_id', auth()->user()->id)->where('contest_id', $contest->id)->first();

        if ($registration?->status == RegistrationStatuses::PAID || $registration?->status == RegistrationStatuses::PENDING) {

            return redirect(route('contests.registration.myRegistration', $contest));
        }
        return view('contests.registration.create', compact('contest'));
    }

    public function myRegistration(Contest $contest)
    {

        $registration = Registration::where('user_id', auth()->user()->id)->where('contest_id', $contest->id)->first();

        if (!$registration) {
            return redirect(route('contests.registration.form', $contest));
        }
        return view('registration.my-registration', compact('registration'));
    }
}
