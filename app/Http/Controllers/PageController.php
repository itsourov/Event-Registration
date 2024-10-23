<?php

	namespace App\Http\Controllers;


    use App\Models\Contest;


	class PageController extends Controller
	{
        public function home()
        {
            $contests = Contest::take(2)->with(['media'])->get();
            return view('home',compact('contests'));
        }
		public function faq()
		{
			return view('pages.faq');
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
