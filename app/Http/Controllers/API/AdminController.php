<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Candidate;
use App\Models\Application;
use App\Http\Resources\PostResource;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function getApprovedJobs()
     {
        $page = request()->query('page', 1);

         $perPage = request()->query('perPage', 10);
         $posts = Post::where('status', 'approved')->withCount('applications')->paginate($perPage, ['*'], 'page', $page);
         return PostResource::collection($posts);
     }
     
    public function getPendingJobs(){
        $perPage = request()->query('perPage', 10);
        $page = request()->query('page', 1);

        $posts = Post::where('status', 'pending')->paginate($perPage, ['*'], 'page', $page); 
        return  PostResource::collection($posts);
    }
    

    public function getRejectedJobs(Request $request)
    {
        $page = request()->query('page', 1);
        $perPage = $request->query('perPage', 10);
        $posts = Post::where('status', 'rejected')->paginate($perPage, ['*'], 'page', $page);

        return PostResource::collection($posts);
    }
    public function index()
    {
        //
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    
    public function update(Request $request, string $id)
    {
        $post = Post::findorFail($id);
        $post->update(['status' =>$request['status']]);
        return response()->json(['message' => 'Post updated successfully'], 200);
    }
    public function getCandidates(){
        $candidates = Candidate::withCount('applications')->with('applications')->get();
        return response()->json(['candidates' => $candidates], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
