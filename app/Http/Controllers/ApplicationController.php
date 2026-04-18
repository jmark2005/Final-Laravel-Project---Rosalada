<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::all();
        return response()->json([
            'success' => true,
            'data' => $applications
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'applicant_id' => 'required|exists:applicants,id',
            'scholarship_id' => 'required|exists:scholarships,id',
            'status' => 'required|in:pending,under_review,approved,rejected',
            'application_date' => 'required|date',
            'remarks' => 'nullable|string'
        ]);

        $application = Application::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Application created successfully',
            'data' => $application
        ], 201);
    }

    public function show($id)
    {
        $application = Application::with(['applicant', 'scholarship', 'documents'])->find($id);
        if (!$application) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $application
        ]);
    }

    public function update(Request $request, $id)
    {
        $application = Application::find($id);
        if (!$application) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found'
            ], 404);
        }

        $request->validate([
            'status' => 'sometimes|in:pending,under_review,approved,rejected',
            'remarks' => 'nullable|string'
        ]);

        $application->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Application updated successfully',
            'data' => $application
        ]);
    }

    public function destroy($id)
    {
        $application = Application::find($id);
        if (!$application) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found'
            ], 404);
        }
        $application->delete();
        return response()->json([
            'success' => true,
            'message' => 'Application deleted successfully'
        ]);
    }
}
