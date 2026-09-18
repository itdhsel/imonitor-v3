<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;

class MonitorController extends Controller
{
    // Display the main monitoring dashboard with Range Filters
    public function index(Request $request)
    {
        $query = Patient::query(); 

        // 1. Get Start and End Dates (Defaults to TODAY if empty)
        $startDate = $request->input('start_date', date('Y-m-d'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        // 2. Apply Date Range Filter
        $query->whereBetween('date', [$startDate, $endDate]);

        // 3. Apply Ward Dropdown Filter (Exact match instead of 'like')
        if ($request->filled('ward')) {
            $query->where('ward', $request->input('ward'));
        }

        // 4. Order by newest first, and paginate (50 per page)
        $patients = $query->orderBy('patient_stamp', 'desc')->paginate(50);

        return view('monitor.index', compact('patients'));
    }

    // Insert new patient record
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

        $validatedData['remarks'] = '';
        $validatedData['takenby'] = '';
        $validatedData['statusready'] = '00:00:00';
        $validatedData['statuscollected'] = '00:00:00';

        Patient::create($validatedData);

        return redirect()->route('monitor.index')->with('success', 'Patient record added successfully.');
    }

    // Update patient status
    public function update(Request $request, $no)
    {
        $patient = Patient::findOrFail($no);

        $validatedData = $request->validate([
            'status' => 'required|string|max:35',
            'remarks' => 'nullable|string|max:100',
            'statusready' => 'nullable',
            'statuscollected' => 'nullable',
        ]);

        $patient->update($validatedData);

        return redirect()->route('monitor.index')->with('success', 'Patient record updated successfully.');
    }

    // Delete patient record
    public function destroy($no)
    {
        $patient = Patient::findOrFail($no);
        $patient->delete();

        return redirect()->route('monitor.index')->with('success', 'Patient record deleted successfully.');
    }

    public function searchMrn(Request $request)
    {
        $mrn = $request->query('mrn');
        
        if (!$mrn) {
            return response()->json(['success' => false]);
        }

        // Search the patientlist table
        $patient = \Illuminate\Support\Facades\DB::table('patientlist')
            ->where('mrn', $mrn)
            ->first();

        if ($patient) {
            return response()->json([
                'success' => true, 
                'patient_name' => $patient->patient_name 
            ]);
        }

        return response()->json(['success' => false]);
    }
}