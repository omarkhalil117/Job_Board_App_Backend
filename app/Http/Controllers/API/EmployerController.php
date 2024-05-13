<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;


use App\Models\Employer;
use App\Models\User;
use App\Models\Post;

use App\Models\Application;
use App\Http\Resources\EmployerResource;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\PostResource;


class EmployerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employers = Employer::with('user')->get();
        return response()->json(["status" => "success", "data" => EmployerResource::collection($employers)]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return response()->json(["status" => "success", "message" => "Employer created successfully"]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Employer $employer)
    {
        $employers = Employer::with('user')->findOrFail($employer->id);
        return response()->json(["status" => "success", 
        "data" => new EmployerResource($employer)
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, Employer $employer)
    {
        try {
            DB::beginTransaction();
    
            // Update the user
            $user = $employer->user;
            $user->update($request->all());
    
            // Update the employer
            $employer->update($request->all());
    
            DB::commit();
    
            return response()->json([
                "status" => "success",
                "data" => new EmployerResource($employer)
                
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                "status" => "error",
                "message" => "Failed to update user and employer",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employer $employer)
    {
        //
        return response()->json(["status" => "success", "message" => "Employer deleted successfully"]);

    }
    public function getEmployerJobs(Request $request, string $employer_id)
        {

            $perPage = $request->query('perPage', 3);
            $perPage = max(1, min(10, intval($perPage)));

            $employer = Employer::findOrFail($employer_id);

            $jobs = $employer->posts()->paginate($perPage);

            return response()->json([
                "status" => "success",
                "jobs" => $jobs,
                
            ]);
        }
    public function getApplications( string $post_id ){
        $perPage = request()->query('perPage', 2);
        $post = new PostResource(Post::find($post_id));
        $apps = Application::where("post_id",$post_id)->with("candidate")->paginate($perPage)
        ;
        return response()->json(["status" => "success", "post" => $post, "applications" => $apps]);

    }
    public function approveApplication(Request $request, string $application_id)
    {
        $application = Application::find($application_id);
        
        if ($application) {
            $application->update(['status' =>$request['status']]);
            return response()->json(["message" => "Application approved successfully"],200);
        } else {
            return response()->json(["message" => "Application not found"], 404);
        }
    }

}
