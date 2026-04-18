<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['applicant', 'application'])->get();
        return response()->json([
            'success' => true,
            'data' => $documents
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'applicant_id'    => 'required|exists:applicants,id',
            'application_id'  => 'nullable|exists:applications,id',
            'document_type'   => 'required|string|max:255',
            'file'            => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status'          => 'nullable|in:pending,verified,rejected',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents', $fileName, 'public');

        $document = Document::create([
            'applicant_id'   => $request->applicant_id,
            'application_id' => $request->application_id,
            'document_type'  => $request->document_type,
            'file_name'      => $fileName,
            'file_path'      => $filePath,
            'status'         => $request->status ?? 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully',
            'data'    => $document
        ], 201);
    }

    public function show($id)
    {
        $document = Document::with(['applicant', 'application'])->find($id);

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $document
        ]);
    }

    public function update(Request $request, $id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }

        $request->validate([
            'document_type'  => 'sometimes|string|max:255',
            'status'         => 'sometimes|in:pending,verified,rejected',
            'application_id' => 'nullable|exists:applications,id',
        ]);

        // Handle optional file replacement
        if ($request->hasFile('file')) {
            $request->validate(['file' => 'file|mimes:pdf,jpg,jpeg,png|max:5120']);

            // Delete old file
            Storage::disk('public')->delete($document->file_path);

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');

            $document->file_name = $fileName;
            $document->file_path = $filePath;
        }

        $document->update($request->only(['document_type', 'status', 'application_id']));

        return response()->json([
            'success' => true,
            'message' => 'Document updated successfully',
            'data'    => $document
        ]);
    }

    public function destroy($id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully'
        ]);
    }
}
