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
        // Get sections and count
        $registrations = Registration::where('contest_id', $contest->id)
            ->select('section', \DB::raw('count(*) as count'))
            ->groupBy('section')
            ->get();

        // Create an array with section as key and count as value
        $sectionsWithCounts = $registrations->mapWithKeys(function ($item) {
            return [$item->section => $item->count];
        })->toArray();

        // Get the sections as an array of keys
        $sections = array_keys($sectionsWithCounts);
        
        // Custom sorting function for sections
        usort($sections, function ($a, $b) {
            // For sections like 66_A, 66_B or 66-A, 66-B: compare the character after the separator
            if (preg_match('/^\d+[_\-][A-Za-z]/', $a) && preg_match('/^\d+[_\-][A-Za-z]/', $b)) {
                // Extract the character after the separator (either _ or -)
                $charA = preg_match('/^\d+[_\-]([A-Za-z])/', $a, $matchesA) ? $matchesA[1] : '';
                $charB = preg_match('/^\d+[_\-]([A-Za-z])/', $b, $matchesB) ? $matchesB[1] : '';
                return strcmp($charA, $charB);
            } 
            // For simple sections like A, B, C: compare from the first character
            else {
                return strcmp($a, $b);
            }
        });
        
        // Rebuild the array with the sorted keys
        $sectionCounts = [];
        $sectionTeachers = [];
        
        foreach ($sections as $section) {
            $sectionCounts[$section] = $sectionsWithCounts[$section];
            
            // Find the most frequently used teacher for this section
            $teacherCounts = [];
            $sectionRegistrations = Registration::where('contest_id', $contest->id)
                ->where('section', $section)
                ->get();
                
            foreach ($sectionRegistrations as $registration) {
                if (!empty($registration->lab_teacher_name)) {
                    $teacherCounts[$registration->lab_teacher_name] = ($teacherCounts[$registration->lab_teacher_name] ?? 0) + 1;
                }
            }
            
            $mostFrequentTeacher = null;
            $maxCount = 0;
            
            foreach ($teacherCounts as $teacher => $count) {
                if ($count > $maxCount) {
                    $mostFrequentTeacher = $teacher;
                    $maxCount = $count;
                }
            }
            
            $sectionTeachers[$section] = $mostFrequentTeacher;
        }
            
        return view('contests.registrations.index', compact('contest', 'sectionCounts', 'sectionTeachers'));
    }

    /**
     * Display the specified resource.
     */
    public function section(Contest $contest, $section = null)
    {
        // Check if section is null, try to get it from query parameters
        if ($section === null) {
            $section = request()->query('section');
            
            // If section is still null, redirect back to index
            if ($section === null) {
                return redirect()->route('contests.registrations.index', $contest->slug);
            }
        }
        
    

        $registrations = Registration::where('contest_id', $contest->id)
            ->where('section', $section)
            ->get();
            
        // Find the most frequently used teacher name for this section
        $teacherCounts = [];
        foreach ($registrations as $registration) {
            if (!empty($registration->lab_teacher_name)) {
                $teacherCounts[$registration->lab_teacher_name] = ($teacherCounts[$registration->lab_teacher_name] ?? 0) + 1;
            }
        }
        
        $mostFrequentTeacher = null;
        $maxCount = 0;
        
        foreach ($teacherCounts as $teacher => $count) {
            if ($count > $maxCount) {
                $mostFrequentTeacher = $teacher;
                $maxCount = $count;
            }
        }

        return view('contests.registrations.section', compact('contest', 'section', 'registrations', 'mostFrequentTeacher'));
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
