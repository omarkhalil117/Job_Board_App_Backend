<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\AuthController;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\StoreCandidateRequest;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\CandidateResource;
use App\Http\Resources\UserResource;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Post;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidates = Candidate::all();

        return CandidateResource::collection($candidates);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCandidateRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidate $candidate)
    {
        $result = Candidate::with('user')->findOrFail($candidate->id);
        return response()->json(["status" => "success", "data" => new CandidateResource($result)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Candidate $candidate)
    {
        return response()->json(["status" => "updated", "data" => $candidate]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidate $candidate)
    {
        return response()->json(["status" => "success", "message" => "Candidate deleted successfully"]);
    }

    public function appliedApplications(Request $request, string $id) {
        $candidate = Candidate::findOrFail($id);
        return ApplicationResource::collection($candidate->applications);
    }

    public function applyToPost(StoreApplicationRequest $request, string $post_id) {
        $user = app('App\Http\Controllers\API\AuthController')->getUserDataByRole($request);
        return response()->json(["status" => "success", "user" => $user]);

        $validated = $request->validated();

        $candidate = Candidate::find(5);

        $checkPost = Post::findOrFail($post_id);
        $application = new Application();
        $application->candidate_id = $candidate->id;
        $application->post_id = $post_id;
        $application->resume = $validated->resume ?? null;
        $application->email = $validated->email ?? null;
        $application->phone = $validated->phone ?? null;
        $application->status = 'pending';

        // $application->save();
        return response()->json($application);
    }

    public function cancelApplication(string $app_id) {
        $application = Application::find($app_id);

        return response()->json(["status" => "success", "data" => $application]);
    }
}
