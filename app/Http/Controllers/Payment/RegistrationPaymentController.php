<?php

namespace App\Http\Controllers\Payment;

use App\Enums\RegistrationStatuses;
use App\Http\Controllers\Controller;
use App\Models\Registration;
use Filament\Notifications\Notification;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegistrationPaymentController extends Controller
{
    public function payment(Registration $registration)
    {

        try {
            $response = Http::withHeaders([
                'RT-UDDOKTAPAY-API-KEY' => config('services.udpay.api_key'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post(config('services.udpay.url') . '/api/checkout-v2', [
                'full_name' => $registration->name,
                'email' => $registration->email,
                'amount' => 500,
                'redirect_url' => route('registration.payment.success', $registration),
                'cancel_url' => route('registration.payment.cancel', $registration),
                'return_type' => 'GET',
                'metadata' => [
                    'registration_id' => $registration->id,
                    'phone' => $registration->phone,
                    'student_id' => $registration->student_id,
                    'department' => $registration->department,
                    'section' => $registration->section,
                    'lab_teacher_name' => $registration->lab_teacher_name,
                    'tshirt_size' => $registration->tshirt_size,
                    'gender' => $registration->gender,
                ],

            ]);
            if ($response->successful() && $response->json('payment_url')) {
                return redirect($response->json('payment_url'));
            } else {
                return $response->json();
                return "There was an error connecting to the Payment API";
            }
        } catch (ConnectionException $e) {
            return "There was an error connecting to the Payment API. " . $e->getMessage();
        }

    }


    public function success(Registration $registration, Request $request)
    {
        try {
            $response = Http::withHeaders([
                'RT-UDDOKTAPAY-API-KEY' => config('services.udpay.api_key'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post(config('services.udpay.url') . '/api/verify-payment', [
                'invoice_id' => $request->invoice_id,
            ]);

            if ($response->successful()) {
                if($response->json('status') != 'COMPLETED'){
                    Notification::make()
                        ->title("Payment Pending")
                        ->info()
                        ->send();
                    return redirect(route('registration-form'));
                }
                if ($response->json('metadata')['registration_id'] == $registration->id &&$response->json('status') == 'COMPLETED' && $response->json('email') ==$registration->email ) {
                    $registration->status = RegistrationStatuses::PAYMENT_VERIFIED;
                    $registration->extra = $response->json();
                    $registration->save();
                    Notification::make()
                        ->title("Payment Successful")
                        ->body("Your registration has been confirmed.")
                        ->success()
                        ->send();
                    return redirect(route('all-registrations'));
                } else {
                    return "Information Mismatched, Please contact someone from DIU ACM. Dont worry your payment wont be lost.";
                }
            } else {
                return "There was an error connecting to the Payment API. Dont get worried, you wont loss your payment";
            }
        } catch (ConnectionException $e) {
            return "There was an error connecting to the Payment API. Dont get worried, you wont loss your payment. " . $e->getMessage();
        }


    }

    public function cancel(Registration $registration, Request $request)
    {
        Notification::make()
            ->title("Payment cancelled")
            ->info()
            ->send();
        return redirect(route('registration-form'));
    }
}
