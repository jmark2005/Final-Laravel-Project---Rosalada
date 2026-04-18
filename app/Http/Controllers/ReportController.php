<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Application;
use App\Models\Scholarship;

class ReportController extends Controller
{
    /**
     * Summary report of all applicants grouped by status.
     */
    public function applicantReport()
    {
        $total    = Applicant::count();
        $pending  = Applicant::where('status', 'pending')->count();
        $approved = Applicant::where('status', 'approved')->count();
        $rejected = Applicant::where('status', 'rejected')->count();

        $applicants = Applicant::with(['applications.scholarship'])->get();

        return response()->json([
            'success' => true,
            'summary' => [
                'total'    => $total,
                'pending'  => $pending,
                'approved' => $approved,
                'rejected' => $rejected,
            ],
            'data' => $applicants
        ]);
    }

    /**
     * Summary report of all scholarships with slot usage.
     */
    public function scholarshipReport()
    {
        $scholarships = Scholarship::withCount([
            'applications',
            'applications as approved_count' => fn($q) => $q->where('status', 'approved'),
            'applications as pending_count'  => fn($q) => $q->where('status', 'pending'),
            'applications as rejected_count' => fn($q) => $q->where('status', 'rejected'),
        ])->get()->map(function ($s) {
            $s->remaining_slots = $s->slots - $s->approved_count;
            return $s;
        });

        return response()->json([
            'success' => true,
            'data'    => $scholarships
        ]);
    }

    /**
     * List of all approved scholars with their scholarship details.
     */
    public function approvedScholars()
    {
        $approved = Application::with(['applicant', 'scholarship'])
            ->where('status', 'approved')
            ->get();

        return response()->json([
            'success' => true,
            'total'   => $approved->count(),
            'data'    => $approved
        ]);
    }
}
