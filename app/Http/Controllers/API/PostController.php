<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\StorePostRequest;


use App\Models\Post;
use App\Http\Resources\PostResource;
class PostController extends Controller
{

    public function index()
    {
        $posts = Post::with('skills', 'employer')->get();
        return response()->json(["status" => "success", "data" => PostResource::collection($posts)]);
    }


    public function store(StorePostRequest $request)
    {
        $validatedData = $request->validated();
    
        // $validatedData['employer_id'] = auth()->user()->id;
        $validatedData['employer_id'] = 1;
        $validatedData['status'] = "pending";
    
        try {
            $post = Post::create($validatedData);
    
            return response()->json([
                "status" => "success",
                "message" => "Post created successfully",
                "post" => $post
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Failed to create post",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        return response()->json(["status" => "success", "data" => new PostResource($job)]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $request_parms = $request->all();
        $request_parms['status'] = $post->status;
        $updated_post =$post->update($request_parms);
        if ($updated_post) {
            return response()->json(["status" => "success", "data" => new PostResource($post)]);
        } else {
            return response()->json(["status" => "error", "message" => "Failed to update post"], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(["status" => "success", "message" => "Post deleted successfully"]);

    }
    public function deletedPosts(){
        $posts = Post::onlyTrashed()->get();
        return response()->json(["status" => "success", "data" => PostResource::collection($posts)]);
    }
    public function restorePost(string $id){
        $post = Post::withTrashed()->find($id);
        $post->restore();
        return response()->json(["status" => "success", "message" => "Post restored successfully"]);
    }
    public function forceDelete(string $id){
        $post = Post::withTrashed()->find($id);
        $post->forceDelete();
        return response()->json(["status" => "success", "message" => "Post deleted permanently"]);
        
    }


}
