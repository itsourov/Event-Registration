<?php

namespace App\Http\Controllers\Payment;

use App\Enums\RegistrationStatuses;
use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Settings\SiteSettings;
use Filament\Notifications\Notification;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegistrationPaymentController extends Controller
{
    public function payment(Registration $registration, SiteSettings $siteSettings)
    {


        try {
            $response = Http::withHeaders([
                'RT-UDDOKTAPAY-API-KEY' => config('services.udpay.api_key'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post(config('services.udpay.url') . '/api/checkout-v2', [
                'full_name' => $registration->name,
                'email' => $registration->email,
                'amount' => $siteSettings->registration_fee,
                'redirect_url' => route('registration.payment.success', $registration),
                'cancel_url' => route('registration.payment.cancel', $registration),
                'webhook_url' => route('registration.payment.webhook', $registration),
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
                if ($response->json('status') != 'COMPLETED') {
                    Notification::make()
                        ->title("Payment Pending")
                        ->info()
                        ->send();
                    return redirect(route('registration-form'));
                }
                if ($response->json('metadata')['registration_id'] == $registration->id && $response->json('status') == 'COMPLETED' && $response->json('email') == $registration->email) {
                    $registration->status = RegistrationStatuses::PAID;
                    $registration->extra = $response->json();
                    $registration->save();
                    Notification::make()
                        ->title("Payment Successful")
                        ->body("Your registration has been confirmed.")
                        ->success()
                        ->send();
                    return redirect(route('all-registrations'));
                }else if ($response->json('metadata')['registration_id'] == $registration->id && $response->json('status') == 'PENDING' && $response->json('email') == $registration->email) {
                    $registration->status = RegistrationStatuses::PENDING;
                    $registration->extra = $response->json();
                    $registration->save();
                    Notification::make()
                        ->title("Payment Pending")
                        ->body("We will verify your payment.")
                        ->info()
                        ->send();
                    return redirect(route('all-registrations'));
                }  else {
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

    public function webhookCallback(Registration $registration, Request $request)
    {

        $headerApi = $request->header('RT-UDDOKTAPAY-API-KEY');

        // Verify the API key
        if ($headerApi !== config('services.udpay.api_key')) {
            return response()->json(['message' => 'Unauthorized Action'], 401); // Unauthorized
        }

        // Get the JSON data from the request body
        $data = $request->json()->all();

        // Check if JSON data was successfully parsed
        if (empty($data)) {
            return response()->json(['message' => 'Invalid JSON data'], 400); // Bad Request
        }


      if($data['metadata']['registration_id'] == $registration->id && $data['email'] == $registration->email) {

          if( $data['status'] == 'COMPLETED'){

              $registration->status = RegistrationStatuses::PAID;
              $registration->extra = $data;
              $registration->save();
          }
         else if( $data['status'] == 'PENDING'){

              $registration->status = RegistrationStatuses::PENDING;
              $registration->extra = $data;
              $registration->save();
          }
      }
        return response()->json(['message' => 'Webhook data processed successfully']);
    }
}
