<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Http\Resources\PostResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::with('skills', 'employer')->get();
        return response()->json(["status" => "success", "data" => PostResource::collection($posts)]);
    }


    public function store(StorePostRequest $request)
    {
        $employer = app('App\Http\Controllers\API\AuthController')->getUserDataByRole($request->bearerToken())['user'];
        $validatedData = $request->validated();
        
    
        // $validatedData['employer_id'] = auth()->user()->id;
        $validatedData['employer_id'] = $employer->id;
        $validatedData['status'] = "pending";

        $post_skills=$validatedData['skills'];
    
        try {
            $post = Post::create($validatedData);

            // $postSkillIds = explode(',', $post_skills);

            $post->skills()->attach($post_skills);
    
            return response()->json([
                "status" => "success",
                "message" => "Post created successfully",
                "post" => new PostResource($post),
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
     */public function show(Post $post)
         {
        $post = Post::with('skills', 'employer')->findOrFail($post->id);
        return response()->json(["status" => "success", "data" => $post]);
        }
        
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $request_parms = $request->all();
        $skills = $request_parms['skills'];
        $request_parms['status'] = $post->status;
        $updated_post =$post->update($request_parms);
        $post->skills()->sync($skills);
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
    public function deletedPosts( Request $request){
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        $employer_id = 1;
        $posts = Post::onlyTrashed()->where('employer_id', $employer_id)->paginate($perPage, ['*'], 'page', $page);
        return  PostResource::collection($posts);
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
