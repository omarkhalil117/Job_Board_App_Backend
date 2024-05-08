<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\AdminController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('admin/approvedjobs', [AdminController::class, 'getApprovedJobs']);
Route::get('admin/pendingjobs', [AdminController::class, 'getPendingJobs']);
Route::put('admin/approve/{id}', [AdminController::class, 'update']);
Route::get('admin/candidates', [AdminController::class, 'getCandidates']);