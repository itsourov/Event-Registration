<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Registration;
use Illuminate\Http\Request;

class ContestRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contest $contest)
    {
       return view('contests.registrations.index', compact('contest'));
    }

    public function section(Contest $contest, $section)
    {
        $registrations = Registration::where('contest_id', $contest->id)
            ->where('section', $section)
            ->get();

        return view('contests.registrations.section', compact('contest', 'section', 'registrations'));
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
    public function show(Registration $registration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Registration $registration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Registration $registration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Registration $registration)
    {
        //
    }
}
