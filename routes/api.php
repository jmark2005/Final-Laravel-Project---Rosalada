<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController; // Ensure this is imported

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Fixed the typo here: removed the stray 'a' after AuthController::class, 'user'
    Route::get('/user', [AuthController::class, 'user']);

    // Resource routes
    Route::apiResource('users', UserController::class);
    Route::apiResource('students', StudentController::class); // ADDED THIS
    Route::apiResource('applicants', ApplicantController::class);
    Route::apiResource('scholarships', ScholarshipController::class);
    Route::apiResource('requirements', RequirementController::class);
    Route::apiResource('applications', ApplicationController::class);
    Route::apiResource('documents', DocumentController::class);

    // Reports
    Route::get('/reports/applicants', [ReportController::class, 'applicantReport']);
    Route::get('/reports/scholarships', [ReportController::class, 'scholarshipReport']);
    Route::get('/reports/approved', [ReportController::class, 'approvedScholars']);
});