<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController ;
use App\Http\Controllers\API\EmployerController ;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Laravel\Socialite\Facades\Socialite;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('admin/approvedjobs', [AdminController::class, 'getApprovedJobs'])->middleware('role:any');  
Route::get('admin/pendingjobs', [AdminController::class, 'getPendingJobs'])->middleware('role:any');  
Route::put('admin/approve/{id}', [AdminController::class, 'update'])->middleware('role:any');  
Route::get('admin/candidates', [AdminController::class, 'getCandidates'])->middleware('role:any');  

Route::apiResource("posts",PostController::class)->middleware('role:any');  
Route::apiResource("employers",EmployerController::class)->middleware('role:any');  
Route::get("job-applications/{post_id}",[EmployerController::class,"getApplications"])->middleware('role:any');  
Route::put("application-approval/{application_id}",[EmployerController::class,"approveApplication"])->middleware('role:any');  

Route::post('/auth/EmpRegister', [AuthController::class, 'storeEmp'])->middleware('role:any');  
Route::get('/auth/index', [AuthController::class, 'index'])->middleware('role:employer,admin');  
Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);
 
    $user = User::where('email', $request->email)->first();
 
    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Create a token with custom claims
    //$token = $user->createToken($request->device_name, [
       // 'user' => [
       //     'id' => $user->id,
        //    'name' => $user->name,
          //  'email' => $user->email,
       //     'role' =>  $user->role
            // Add any additional user data you want to include in the token
        //]
       
    //])->plainTextToken;
    return $user->createToken($request->device_name)->plainTextToken;
    //return response()->json(['token' => $token]);
})->middleware('role:any');  
