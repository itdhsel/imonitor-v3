<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;

class MonitorController extends Controller
{
    // Display the main monitoring dashboard
    public function index()
    {
        // Fetch only 50 patients per page instead of ALL patients
        $patients = Patient::orderBy('patient_stamp', 'desc')->paginate(50);
        
        return view('monitor.index', compact('patients'));
    }

    // Replace 2-insertprocess.php
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'ward' => 'required|string|max:20',
            'patient_name' => 'required|string|max:100',
            'mrn' => 'required|string|max:50',
            'total_item' => 'required|integer',
            'total_item2' => 'required|integer',
            'supply' => 'required|string|max:15',
            'status' => 'required|string|max:35',
        ]);

        // Provide default empty values for legacy columns that don't allow NULL
        $validatedData['remarks'] = '';
        $validatedData['takenby'] = '';
        
        // Use a default time like '00:00:00' for the time columns
        $validatedData['statusready'] = '00:00:00';
        $validatedData['statuscollected'] = '00:00:00';

        Patient::create($validatedData);

        return redirect()->route('monitor.index')->with('success', 'Patient record added successfully.');
    }

    // Replace 2-updateprocess.php
    public function update(Request $request, $no)
    {
        $patient = Patient::findOrFail($no);

        $validatedData = $request->validate([
            'status' => 'required|string|max:35',
            'remarks' => 'nullable|string|max:100',
            'statusready' => 'nullable',
            'statuscollected' => 'nullable',
            // Add other fields you typically update
        ]);

        $patient->update($validatedData);

        return redirect()->route('monitor.index')->with('success', 'Patient record updated successfully.');
    }

    // Replace 2-deleteprocess.php (or equivalent)
    public function destroy($no)
    {
        $patient = Patient::findOrFail($no);
        $patient->delete();

        return redirect()->route('monitor.index')->with('success', 'Patient record deleted successfully.');
    }
}