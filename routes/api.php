<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\CandidateController;
use App\Http\Controllers\API\PostController ;
use App\Http\Controllers\API\EmployerController ;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('admin/approvedjobs', [AdminController::class, 'getApprovedJobs']);
Route::get('admin/pendingjobs', [AdminController::class, 'getPendingJobs']);
Route::put('admin/approve/{id}', [AdminController::class, 'update']);
Route::get('admin/candidates', [AdminController::class, 'getCandidates']);

Route::apiResource("posts",PostController::class);
Route::apiResource("employers",EmployerController::class);
Route::get("job-applications/{post_id}",[EmployerController::class,"getApplications"]);
Route::put("application-approval/{application_id}",[EmployerController::class,"approveApplication"]);

// Candidate Routes
Route::apiResource("candidates", CandidateController::class);
Route::get("candidates/{id}/applications", [CandidateController::class, "appliedApplications"]);
Route::post("candidates/{id}/applications", [CandidateController::class, "applyToPost"]);