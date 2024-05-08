<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Models\Post;
use \App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\EmployerController ;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/home/posts' , function () {
    return Post::paginate(10)->where('status','=','approved');
});

Route::apiResource('posts' , PostController::class);

Route::get('/home/posts' , function () {
    return Post::paginate(10)->where('status','=','approved');
});

Route::apiResource('posts' , PostController::class);
Route::apiResource("posts",PostController::class);
Route::apiResource("employers",EmployerController::class);
Route::get("job-applications/{post_id}",[EmployerController::class,"getApplications"]);
Route::put("application-approval/{application_id}",[EmployerController::class,"approveApplication"]);