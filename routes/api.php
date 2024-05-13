<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CandidateController;
use App\Http\Controllers\API\PostController ;
use App\Http\Controllers\API\EmployerController ;
use App\Http\Controllers\API\SkillController ;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Laravel\Socialite\Facades\Socialite;

// 
// Admin APIS
Route::get('admin/approvedjobs', [AdminController::class, 'getApprovedJobs'])->middleware('role:any');  
Route::get('admin/pendingjobs', [AdminController::class, 'getPendingJobs'])->middleware('role:any');  
Route::get('admin/rejectedjobs', [AdminController::class, 'getRejectedJobs'])->middleware('role:any'); 
Route::put('admin/approve/{id}', [AdminController::class, 'update'])->middleware('role:any');  
Route::get('admin/candidates', [AdminController::class, 'getCandidates'])->middleware('role:any'); 

// Posts APIS 
Route::get("posts/deleted", [PostController::class, 'deletedPosts'])->middleware('role:any'); 
Route::get('posts/restore/{id}', [PostController::class, 'restorePost'])->middleware('role:any'); 
Route::delete('posts/force-delete/{id}', [PostController::class, 'forceDelete'])->middleware('role:any'); 
Route::apiResource("posts",PostController::class)->middleware('role:any');
Route::apiResource("skills",SkillController::class);
// Employer
Route::apiResource("employers",EmployerController::class)->middleware('role:any'); 
Route::get("job-applications/{post_id}",[EmployerController::class,"getApplications"])->middleware('role:any');  
Route::put("application-approval/{application_id}",[EmployerController::class,"approveApplication"])->middleware('role:any');  

// Registeration
Route::post('EmpRegister', [AuthController::class, 'empRegister'])->middleware('role:any');  
Route::post('CandidateRegister', [AuthController::class, 'candidateRegister'])->middleware('role:any');  

// Login
Route::post('login', [AuthController::class, 'login'] )->middleware('role:any');  //without token

// Get user data from token (admin-employer-candidate)
Route::get('user', [AuthController::class, 'getUserData'] )->middleware('auth:sanctum'); //token any role

// Routes for email verification

 Route::get('/email/verify/{id}', function () {

    return redirect(env('FRONT_URL'));
    
})->name('verification.verify');


// Candidate Routes
Route::apiResource("candidates", CandidateController::class)->middleware('role:any');
Route::get("candidates/{id}/applications", [CandidateController::class, "appliedApplications"]);
Route::post("applications/{post_id}/apply", [CandidateController::class, "applyToPost"]);
Route::post("applications/{post_id}/cancel", [CandidateController::class, "cancelApplication"]);