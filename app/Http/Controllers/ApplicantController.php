<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function index()
    {
        $applicants = Applicant::all();
        return response()->json([
            'success' => true,
            'data' => $applicants
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:applicants',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'course' => 'nullable|string|max:255',
            'year_level' => 'nullable|string|max:50',
            'status' => 'nullable|in:pending,approved,rejected'
        ]);

        $applicant = Applicant::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Applicant created successfully',
            'data' => $applicant
        ], 201);
    }

    public function show($id)
    {
        $applicant = Applicant::with(['applications', 'documents'])->find($id);
        if (!$applicant) {
            return response()->json([
                'success' => false,
                'message' => 'Applicant not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $applicant
        ]);
    }

    public function update(Request $request, $id)
    {
        $applicant = Applicant::find($id);
        if (!$applicant) {
            return response()->json([
                'success' => false,
                'message' => 'Applicant not found'
            ], 404);
        }

        $applicant->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Applicant updated successfully',
            'data' => $applicant
        ]);
    }

    public function destroy($id)
    {
        $applicant = Applicant::find($id);
        if (!$applicant) {
            return response()->json([
                'success' => false,
                'message' => 'Applicant not found'
            ], 404);
        }
        $applicant->delete();
        return response()->json([
            'success' => true,
            'message' => 'Applicant deleted successfully'
        ]);
    }
}