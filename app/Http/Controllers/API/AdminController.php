<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\PostResource;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function getApprovedJobs(){
        $posts = Post::where('status', 'approved')->get(); 
        return  PostResource::collection($posts);
    }
    public function getPendingJobs(){
        $posts = Post::where('status', 'pending')->get(); 
        return response()->json(['posts' => $posts], 200);
        return  PostResource::collection($posts);
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

    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
