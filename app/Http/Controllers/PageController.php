<?php

namespace App\Http\Controllers;


use App\Enums\RegistrationStatuses;
use App\Models\Contest;
use App\Models\Registration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class PageController extends Controller
{
    public function home()
    {
        $contests = cache()->remember('home', 7200, function () {
            return Contest::take(2)->with(['media'])->get();
        });

        return view('home', compact('contests'));
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function temp()
    {
        $res = Http::get('https://livetvbd.me/csvjson.json')->json();
        $newArray = [];
        $i = 0;
        foreach ($res as $key => $value) {
            $i++;
            if ($i < 150) continue;
            $payment_phone = "";
            $transactionID = "";
            if ($value['Choose your payment method'] == 'bkash') {
                $payment_phone = $value['bKash Mobile No (Which used for payment)'];
                $transactionID = $value['bKash Transaction Id'];
            } else if ($value['Choose your payment method'] == 'Nagad') {
                $payment_phone = $value['Nagad Mobile No (Which used for payment)'];
                $transactionID = $value['Nagad Transaction Id'];
            } else if ($value['Choose your payment method'] == 'Rocket') {
                $payment_phone = $value['Rocket Mobile No (Which used for payment)'];
                $transactionID = $value['Rocket Transaction Id'];
            }
            array_push($newArray, [
                'name' => $value['Name'] ?? "",
                'email' => $value['Email Address'] ?? "",
                'phone' => $value['Contact Number'] ?? "",
                'student_id' => $value['ID'] ?? "",
                'section' => $value['Section'] ?? "",
                'lab_teacher_name' => $value['Lab Course Teacher Name'] ?? "",
                'tshirt_size' => $value['T-Shirt Size'] ?? "",
                'gender' => "N/A",
                'department' => "CSE",
                'payment_method' => ($value['Choose your payment method'] == 'bkash') ? 'Bkash' : $value['Choose your payment method'],
                'payment_phone' => $payment_phone,
                'payment_transaction_id' => $transactionID,
                'status' => $value['Payment'] == 'Done' ? RegistrationStatuses::PAID->value : RegistrationStatuses::PENDING->value,
                'created_at' => $value['Timestamp'] ?? "10/17/3000 22:43:17",
                'extra' => null,
            ]);
        }


        foreach ($newArray as $key => $value) {
            $usr = User::updateOrCreate([
                'email' => $value['email'],
            ], [
                'name' => $value['name'],
                'email' => $value['email'],
                'password' => bcrypt(Str::random(10)),
            ]);

            $registration = Registration::updateOrCreate([
                'user_id' => $usr->id,
                'contest_id' => 2,
            ], $value);
            $registration->created_at = Carbon::createFromFormat('m/d/Y H:i:s', $value['created_at'], 'Asia/Dhaka');
            $registration->save();
        }
        return Registration::all();

    }

    public function updatePayment()
    {
        $res = Http::get('https://livetvbd.me/payment-status.json')->json('Sheet1');
        foreach ($res as $key => $value) {
            $reg = Registration::where('user_id', $value['User id'])->where('contest_id', $value['Contest id'])->first();
            if (!$reg) {
                dump($value);
                continue;
            }
            if ($reg->email == $value['Email']) {
                $reg->update([
                    'status' => (($value['Status'] ?? "") == 'Paid') ? RegistrationStatuses::PAID : RegistrationStatuses::PENDING,
                ]);
            }
        }
        return $res;
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    public function termsAndConditions()
    {
        return view('pages.terms-and-conditions');
    }
}
