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
        if($registration->status==RegistrationStatuses::PAID ){
            Notification::make()
                ->title("Already Paid")
                ->info()
                ->send();
            return redirect(route('home'));
        }
        if($registration->status==RegistrationStatuses::PENDING ){
            Notification::make()
                ->title("Pending Payment")
                ->body("Please allow us some time to verify your payment.")
                ->info()
                ->send();
            return redirect(route('home'));
        }
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
            }

            return response()->json([
                'message' => 'Error processing payment request.',
                'details' => $response->json(),
            ], 500);

        } catch (ConnectionException $e) {
            return response()->json([
                'message' => 'Error connecting to the Payment API.',
                'error' => $e->getMessage(),
            ], 503);
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

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Error verifying payment.',
                ], 500);
            }

            $responseData = $response->json();


            if ($responseData['metadata']['registration_id'] !== $registration->id ||
                $responseData['email'] !== $registration->email) {
                return response()->json([
                    'message' => 'Information mismatch. Please contact DIU ACM.',
                ], 400);
            }

            if ($responseData['status'] === 'COMPLETED') {
                $registration->status = RegistrationStatuses::PAID;
                $registration->extra = $responseData;
                $registration->save();

                Notification::make()
                    ->title("Payment Successful")
                    ->body("Your registration has been confirmed.")
                    ->success()
                    ->send();

            } elseif ($responseData['status'] === 'PENDING') {
                $registration->status = RegistrationStatuses::PENDING;
                $registration->extra = $responseData;
                $registration->save();

                Notification::make()
                    ->title("Payment Pending")
                    ->body("We will verify your payment.")
                    ->info()
                    ->send();
            }

            return redirect(route('registration.my-registration'));

        } catch (ConnectionException $e) {
            return response()->json([
                'message' => 'Error connecting to the Payment API.',
                'error' => $e->getMessage(),
            ], 503);
        }
    }

    public function cancel(Registration $registration)
    {
        Notification::make()
            ->title("Payment Cancelled")
            ->info()
            ->send();

        return redirect(route('registration.create'));
    }

    public function webhookCallback(Registration $registration, Request $request)
    {
        $headerApi = $request->header('RT-UDDOKTAPAY-API-KEY');

        if ($headerApi !== config('services.udpay.api_key')) {
            return response()->json(['message' => 'Unauthorized Action'], 401);
        }

        $data = $request->json()->all();

        if (empty($data)) {
            return response()->json(['message' => 'Invalid JSON data'], 400);
        }

        if ($data['metadata']['registration_id'] === $registration->id &&
            $data['email'] === $registration->email) {

            if ($data['status'] === 'COMPLETED') {
                $registration->status = RegistrationStatuses::PAID;
            } elseif ($data['status'] === 'PENDING') {
                $registration->status = RegistrationStatuses::PENDING;
            }

            $registration->extra = $data;
            $registration->save();
        }

        return response()->json(['message' => 'Webhook data processed successfully']);
    }
}
