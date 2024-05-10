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

Route::get('admin/approvedjobs', [AdminController::class, 'getApprovedJobs']);
Route::get('admin/pendingjobs', [AdminController::class, 'getPendingJobs']);
Route::put('admin/approve/{id}', [AdminController::class, 'update']);
Route::get('admin/candidates', [AdminController::class, 'getCandidates']);

Route::apiResource("posts",PostController::class);
Route::apiResource("employers",EmployerController::class);
Route::get("job-applications/{post_id}",[EmployerController::class,"getApplications"]);
Route::put("application-approval/{application_id}",[EmployerController::class,"approveApplication"]);
// Auth
Route::middleware('auth:sanctum')->get('/user', function () {
    $user = Auth::user(); // Retrieve the authenticated user

    // Access the custom claims from the token payload
    $userData =  auth()->$user->currentAccessToken()->tokenable['user'] ?? null;

    // Extract user data from the token payload
    $userId = $userData['id'] ?? null;
    $userName = $userData['name'] ?? null;
    $userEmail = $userData['email'] ?? null;

    // Access the role from the token payload
    $role = $userData['role'] ?? null; // Assuming 'role' is a key in the user's data
    dd(  $role);
    return response()->json([
        'user_id' => $userId,
        'user_name' => $userName,
        'user_email' => $userEmail,
        'role' => $role,
    ]);
});
Route::post('/auth/EmpRegister', [AuthController::class, 'storeEmp']);


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
    $token = $user->createToken($request->device_name, [
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            // Add any additional user data you want to include in the token
        ]
    ])->plainTextToken;
 
    return response()->json(['token' => $token]);
});
