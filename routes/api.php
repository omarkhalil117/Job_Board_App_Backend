<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PostController ;
use App\Http\Controllers\API\EmployerController ;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource("posts",PostController::class);
Route::apiResource("employers",EmployerController::class);
Route::get("getApp/{post_id}",[EmployerController::class,"getApplications"]);