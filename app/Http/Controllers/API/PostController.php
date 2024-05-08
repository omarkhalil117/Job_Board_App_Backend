<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Http\Resources\PostResource;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('skills', 'employer')->get();
        return response()->json(["status" => "success", "data" => PostResource::collection($posts)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json(["status" => "success", "message" => "Post created successfully"]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json(["status" => "success", "data" => new PostResource($job)]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        return response()->json(["status" => "success", "data" => new PostResource($job)]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        return response()->json(["status" => "success", "message" => "Post deleted successfully"]);

    }
}
