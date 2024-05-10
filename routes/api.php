<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Models\Post;
use \App\Http\Controllers\API\PostController;
use \App\Http\Controllers\API\EmployerController ;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/home/posts' , function (Request $request) {

    $query = Post::query();

    if ($request->has('location'))
    {
        $query->where('location', $request->input('location'));
    }

    if ($request->has('work_type'))
    {
        $query->where('work_type', $request->input('work_type'));
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
            //   ->with('employer');
    }

    $res = $query->with('employer')->paginate(5);

    return $res;
});

Route::apiResource('posts' , PostController::class);
Route::apiResource("employers",EmployerController::class);
Route::get("job-applications/{post_id}",[EmployerController::class,"getApplications"]);
Route::put("application-approval/{application_id}",[EmployerController::class,"approveApplication"]);