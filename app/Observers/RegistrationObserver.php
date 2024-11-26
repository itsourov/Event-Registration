<?php

namespace App\Observers;

use App\Mail\RegistrationStatusUpdated;
use App\Models\Registration;
use Illuminate\Support\Facades\Mail;

class RegistrationObserver
{
    /**
     * Handle the Registration "created" event.
     */
    public function created(Registration $registration): void
    {
        Mail::to($registration->email)->send(new RegistrationStatusUpdated($registration));
    }

    /**
     * Handle the Registration "updated" event.
     */
    public function updated(Registration $registration): void
    {

        // Check if the state value has changed
        if ($registration->isDirty('status')) {
            // Send the email
            Mail::to($registration->email)->later(rand(10,1000),new RegistrationStatusUpdated($registration));
        }
    }

    /**
     * Handle the Registration "deleted" event.
     */
    public function deleted(Registration $registration): void
    {
        //
    }

    /**
     * Handle the Registration "restored" event.
     */
    public function restored(Registration $registration): void
    {
        //
    }

    /**
     * Handle the Registration "force deleted" event.
     */
    public function forceDeleted(Registration $registration): void
    {
        //
    }
}
