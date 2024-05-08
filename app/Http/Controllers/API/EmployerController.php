<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        return  EmployerResource::collection($employers);  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Employer $employer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, Employer $employer)
    {

        $user_id = $employer->user->id;
        $user = User::findOrFail($user_id);
      
        $request_parms = $request->all();
        
        $user->update($request_parms);
        $employer->update($request_parms);
        
        return new EmployerResource($employer) ;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employer $employer)
    {
        //
    }
    public function getApplications( string $post_id ){
        $post = new PostResource(Post::find($post_id));
        $apps = Application::where("post_id",$post_id)->with("candidate")->get();
        return response()->json(["post" => $post, "applications" => $apps]) ;
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
