<?php

namespace App\Http\Controllers;


use App\Models\Contest;
use App\Models\Registration;
use App\Models\User;
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
        foreach ($res as $key => $value) {
            array_push($newArray, [
                'name'=>$value['Name']??"",
                'email'=>$value['Email Address']??"",
                'phone'=>$value['Contact Number']??"",
                'student_id'=>$value['ID']??"",
                'section'=>$value['Section']??"",
                'lab_teacher_name'=>$value['Lab Course Teacher Name']??"",
                'tshirt_size'=>$value['T-Shirt Size']??"",
                'gender'=>"N/A",
                'department'=>"CSE",
            ]);
        }

        foreach ($newArray as $key => $value) {
         $usr =   User::updateOrCreate([
              'email'=>$value['email'],
            ],[
                'name'=>$value['name'],
                'email'=>$value['email'],
                'password'=>bcrypt(Str::random(10)),
            ]);

         $registration = Registration::updateOrCreate([
             'user_id'=>$usr->id,
             'contest_id'=>2,
         ],$value);


        }
        return Registration::all();
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
