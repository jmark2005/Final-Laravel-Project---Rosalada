<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scholarship;

class ScholarshipController extends Controller
{
    public function index()
    {
        // We include 'remaining_slots' which you defined in your model
        $scholarships = Scholarship::all()->append('remaining_slots');
        
        return response()->json([
            'success' => true,
            'data' => $scholarships
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'provider'    => 'required|string|max:255',
            'slots'       => 'required|integer|min:1',
            'amount'      => 'required|numeric',
            'deadline'    => 'required|date',
            'status'      => 'required|string|in:active,inactive,closed',
        ]);

        $scholarship = Scholarship::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Scholarship created successfully!',
            'data'    => $scholarship
        ], 201);
    }

    public function show(string $id)
    {
        $scholarship = Scholarship::find($id);

        if (!$scholarship) {
            return response()->json(['message' => 'Scholarship not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $scholarship->append('remaining_slots')
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $scholarship = Scholarship::find($id);

        if (!$scholarship) {
            return response()->json(['message' => 'Scholarship not found'], 404);
        }

        $validated = $request->validate([
            'name'        => 'string|max:255',
            'description' => 'string',
            'provider'    => 'string|max:255',
            'slots'       => 'integer|min:1',
            'amount'      => 'numeric',
            'deadline'    => 'date',
            'status'      => 'string|in:active,inactive,closed',
        ]);

        $scholarship->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Scholarship updated successfully!',
            'data'    => $scholarship
        ], 200);
    }

    public function destroy(string $id)
    {
        $scholarship = Scholarship::find($id);

        if (!$scholarship) {
            return response()->json(['message' => 'Scholarship not found'], 404);
        }

        $scholarship->delete();

        return response()->json([
            'success' => true,
            'message' => 'Scholarship deleted successfully'
        ], 200);
    }
}