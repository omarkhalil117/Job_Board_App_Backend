<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CandidateController;
use \App\Models\Post;
use \App\Http\Controllers\API\PostController;
use \App\Http\Controllers\API\EmployerController ;
use \App\Http\Controllers\API\ApplicationController ;
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



Route::get('/posts/titles', function(Request $request) {
    return Post::select('job_title')->pluck('job_title')->toArray();
});

Route::get('/posts/locations', function(Request $request) {
    $locations = Post::select('location')->pluck('location')->toArray();
    $titles = Post::select('job_title')->pluck('job_title')->toArray();

    $data = [
        "locations"=> $locations,
        "titles"=> $titles
    ];

    return response()->json($data);
});

Route::apiResource('posts' , PostController::class)->middleware('role:any');
Route::apiResource('applications' , ApplicationController::class);
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
Route::get('/email/verify', function () {
    return response()->json([
        'message' => 'Please check your email for the verification link.'
    ]);
})->middleware('auth:sanctum')->name('verification.notice');


// Handle email verification
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json([
        'message' => 'Email verified successfully.',
    ], 200);
    
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

// Resend verification link
Route::post('email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return response()->json([
        'message' => 'Verification link sent!'
    ]);
})->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
// Candidate Routes
Route::apiResource("candidates", CandidateController::class);
Route::get("candidates/{id}/applications", [CandidateController::class, "appliedApplications"]);
Route::post("applications/{post_id}/apply", [CandidateController::class, "applyToPost"]);
Route::post("applications/{post_id}/cancel", [CandidateController::class, "cancelApplication"]);

// home end points
Route::get('/home/posts' , function (Request $request) {

    $query = Post::query();

    if ($request->has('location'))
    {
        $query->where('location', $request->input('location'));
    }

    if ($request->has('work_type'))
    {
        $workTypes = explode(',', $request->input('work_type'));
        $query->whereIn('work_type', $workTypes);
    }

    if ($request->has('job_title'))
    {
        $keyword = $request->input('job_title');
        $query->where('job_title', 'like' , '%'.$keyword.'%');
    }

    if ($request->has('salary'))
    {
        $salary = $request->input('salary');
        $query->where('start_salary', '<=' , $salary)
              ->where('end_salary', '>=', $salary);
    }

    
    $res = $query->with('employer')->paginate(5);

    return $res;
});