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
use Illuminate\Support\Str;
use Dotenv\Exception\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Facades\Socialite;



// 
// Admin APIS
Route::get('admin/approvedjobs', [AdminController::class, 'getApprovedJobs'])->middleware('role:admin');  
Route::get('admin/pendingjobs', [AdminController::class, 'getPendingJobs'])->middleware('role:admin');  
Route::get('admin/rejectedjobs', [AdminController::class, 'getRejectedJobs'])->middleware('role:admin'); 
Route::put('admin/approve/{id}', [AdminController::class, 'update'])->middleware('role:admin');  
Route::get('admin/candidates', [AdminController::class, 'getCandidates'])->middleware('role:admin');

// Posts APIS 
Route::get("posts/deleted", [PostController::class, 'deletedPosts'])->middleware('role:admin,employer'); 
Route::put('posts/restore/{id}', [PostController::class, 'restorePost'])->middleware('role:admin,employer'); 
Route::delete('posts/force-delete/{id}', [PostController::class, 'forceDelete'])->middleware('role:admin,employer'); 



Route::get('/posts/titles', function(Request $request) {
    return Post::select('job_title')->pluck('job_title')->toArray();
});

Route::get('/posts/locations', function(Request $request) {
    $locations = Post::select('location')->where('status','approved')->distinct()->pluck('location')->toArray();
    $titles = Post::select('job_title')->where('status','approved')->distinct()->pluck('job_title')->toArray();

    $data = [
        "locations"=> $locations,
        "titles"=> $titles
    ];

    return response()->json($data);
});

Route::apiResource('posts' , PostController::class)->middleware('role:any');
// Route::apiResource('applications' , ApplicationController::class);
Route::apiResource("skills",SkillController::class);
// Employer
Route::apiResource("employers",EmployerController::class)->middleware('role:employer'); 
Route::get("jobs/employer/{employer_id}",[EmployerController::class,"getEmployerJobs"])->middleware('role:employer');  
Route::get("job-applications/{post_id}",[EmployerController::class,"getApplications"])->middleware('role:employer');  
Route::put("application-approval/{application_id}",[EmployerController::class,"approveApplication"])->middleware('role:employer'); 

// Registeration
Route::post('EmpRegister', [AuthController::class, 'empRegister'])->middleware('role:any');  
Route::post('CandidateRegister', [AuthController::class, 'candidateRegister'])->middleware('role:any');  

// Login
Route::post('login', [AuthController::class, 'login'] )->middleware('role:any');  //without token

// Get user data from token (admin-employer-candidate)
Route::get('user', [AuthController::class, 'getUser'] )->middleware('auth:sanctum'); //token any role

// Routes for email verification

 Route::get('/email/verify/{id}', function () {

    // return redirect(env('FRONT_URL'));
return redirect(env('FRONT_URL').'?verify=true');

})->name('verification.verify');


Route::post('/email/verified', function (Request $request) {
    $request->validate([
        'timestamp' => 'required|date',
        'email' => 'required|email', 
    ]);

    // Find the user by email
    $user = App\Models\User::where('email', $request->email)->first();

    if ($user) {
        $user->email_verified_at = now();
        $user->save();

        return response()->json(['message' => 'Email verified successfully']);
    } else {
        return response()->json(['error' => 'User not found'], 404);
    }
})->middleware('auth:sanctum')->name('verified');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
 
    $status = Password::sendResetLink(
        $request->only('email')
    );
 
    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60), 
            ]);

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect(env('FRONT_URL'))->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

// Candidate Routes
Route::get("candidates/applications", [CandidateController::class, "appliedApplications"])->middleware('role:candidate');
Route::apiResource("candidates", CandidateController::class)->middleware('role:candidate,admin');
Route::post("applications", [CandidateController::class, "applyToPost"])->middleware('role:candidate');
Route::delete("applications", [CandidateController::class, "cancelApplication"])->middleware('role:candidate');

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

    $query->where('status','approved');
    
    $res = $query->with('employer')->paginate(5);

    return $res;
});


