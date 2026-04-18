<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
    public function index()
    {
        $requirements = Requirement::with('scholarship')->get();
        return response()->json([
            'success' => true,
            'data' => $requirements
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'scholarship_id' => 'required|exists:scholarships,id',
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'is_mandatory'   => 'boolean',
        ]);

        $requirement = Requirement::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Requirement created successfully',
            'data'    => $requirement
        ], 201);
    }

    public function show($id)
    {
        $requirement = Requirement::with('scholarship')->find($id);

        if (!$requirement) {
            return response()->json([
                'success' => false,
                'message' => 'Requirement not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $requirement
        ]);
    }

    public function update(Request $request, $id)
    {
        $requirement = Requirement::find($id);

        if (!$requirement) {
            return response()->json([
                'success' => false,
                'message' => 'Requirement not found'
            ], 404);
        }

        $request->validate([
            'scholarship_id' => 'sometimes|exists:scholarships,id',
            'name'           => 'sometimes|string|max:255',
            'description'    => 'nullable|string',
            'is_mandatory'   => 'boolean',
        ]);

        $requirement->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Requirement updated successfully',
            'data'    => $requirement
        ]);
    }

    public function destroy($id)
    {
        $requirement = Requirement::find($id);

        if (!$requirement) {
            return response()->json([
                'success' => false,
                'message' => 'Requirement not found'
            ], 404);
        }

        $requirement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Requirement deleted successfully'
        ]);
    }
}
