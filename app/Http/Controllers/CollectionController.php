<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $wards = ["2C","4A","4B","4C","4D","5A","5B","5C","5D","6A","6B","6C","6D","7A","7B","7C","7D","8A","8B","8C","8D","9A","9B","9C","9D","10A","10B","10C","10D","11B","11C","NICU","HDW","BURN UNIT","LABOUR ROOM","ICU","ED","OTHERS"];
        
        // Default to today if no dates are provided
        $startDate = $request->input('start_date', date('Y-m-d'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $selectedWard = $request->input('ward');

        $query = Patient::whereBetween('date', [$startDate, $endDate]);

        if ($selectedWard) {
            $query->where('ward', $selectedWard);
        }

        // Limit to 50 items per page
        $patients = $query->orderBy('date', 'desc')->orderBy('time', 'desc')->paginate(50);

        return view('collection.index', compact('wards', 'selectedWard', 'startDate', 'endDate', 'patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'selectedData' => 'required|array',
            'nurseName' => 'required|string|max:100'
        ]);

        // Bulk update the selected patients
        Patient::whereIn('no', $request->selectedData)->update([
            'takenby' => strtoupper($request->nurseName),
            'status' => 'COLLECTED BY STAFF NURSE/PPK'
        ]);

        return back()->with('success', 'Medications successfully collected by ' . strtoupper($request->nurseName));
    }
}