<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Models\Post;
use \App\Http\Controllers\API\PostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/home/posts' , function () {
    return Post::paginate(10)->where('status','=','approved');
});

Route::apiResource('posts' , PostController::class);
