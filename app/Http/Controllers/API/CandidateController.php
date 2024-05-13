<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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

    public function appliedApplications(Request $request) {
        $candidate = app('App\Http\Controllers\API\AuthController')->getUserDataByRole($request->bearerToken())['user'];
        $candidate = Candidate::findOrFail($candidate->id);
        return ApplicationResource::collection($candidate->applications);
    }

    public function applyToPost(StoreApplicationRequest $request) {
        $candidate = app('App\Http\Controllers\API\AuthController')->getUserDataByRole($request->bearerToken())['user'];

        $validated = $request->validated();

        $checkPost = Post::findOrFail($validated['post_id']);
        $application = new Application();
        $application->candidate_id = $candidate->id;
        $application->post_id = $validated['post_id'];
        $resumeData = null;
        if ($validated['resume']) {
            $resumeData = app('App\Http\Controllers\API\AuthController')->uploadFileToCloudinary($request, 'resume');
        }
        $application->resume = $resumeData;
        $application->email = $validated['email'] ?? null;
        $application->phone = $validated['phone'] ?? null;
        $application->status = 'pending';

        $application->save();
        return response()->json(ApplicationResource::make($application));
    }

    public function cancelApplication(string $app_id) {
        $application = Application::find($app_id);

        return response()->json(["status" => "success", "data" => $application]);
    }
}
