<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contests = Contest::query()->with(['media'])
            ->paginate(9);
        return view('contests.index', compact('contests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Contest $contest,Request $request)
    {
        // Get the previous URL
        $previousUrl = url()->previous();

        // Parse the host of the previous URL
        $host = parse_url($previousUrl, PHP_URL_HOST);

        // Check if the host matches 'cpc.daffodilvarsity.edu.bd'
        if ($host === 'cpc.daffodilvarsity.edu.bd') {

            // Redirect to another route, e.g., 'home'
            return redirect()->route('home');
        }

        $registered = Registration::where('user_id', auth()->user()?->id)->where('contest_id', $contest->id)->count();

        $SEOData = new \RalphJSmit\Laravel\SEO\Support\SEOData(
            title: $contest->name,
            description:  Str::limit(strip_tags($contest->description)) ,
            image: $contest->getFirstMediaUrl('contest-banner-images'),
        );
        return view('contests.show', compact('contest', 'registered','SEOData'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contest $contest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contest $contest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contest $contest)
    {
        //
    }
}
