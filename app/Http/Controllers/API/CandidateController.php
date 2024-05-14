<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\StoreCandidateRequest;
use App\Http\Requests\UpdateCandidateRequest;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\CandidateResource;
use App\Http\Resources\UserResource;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function update(UpdateCandidateRequest $request, Candidate $candidate)
    {
        
        try {
            DB::beginTransaction();
            $updatedCandidate = Candidate::findOrFail($candidate->id);
            $updatedUser = User::findOrFail($updatedCandidate->user->id);
            if (!empty($request['password'])) {
                $request['password'] = bcrypt($request['password']);
            }
            $updatedCandidate->update($request->all());
            $updatedUser->update($request->all());
            if (!empty($request['resume'])) {
                $updatedCandidate->resume = app('App\Http\Controllers\API\AuthController')->uploadFileToCloudinary($request, 'resume');
                $updatedCandidate->save();
            }
            if (!empty($request['image'])) {
                $updatedUser->image = app('App\Http\Controllers\API\AuthController')->uploadFileToCloudinary($request, 'image');
                $updatedUser->save();
            }
            DB::commit();
            $updatedCandidate->refresh();
            return response()->json(["status" => "updated", "data" => CandidateResource::make($updatedCandidate)]);
        }
        catch(Exception $e) {
            DB::rollBack();
        }
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
        try {
            $candidate = app('App\Http\Controllers\API\AuthController')->getUserDataByRole($request->bearerToken())['user'];
    
            $validated = $request->validated();
    
            $application = new Application();
            $application->candidate_id = $candidate->id;
            $application->post_id = $validated['post_id'];
            $resumeData = null;
            if (!empty($validated['resume'])) {
                $resumeData = app('App\Http\Controllers\API\AuthController')->uploadFileToCloudinary($request, 'resume');
            }
            $application->resume = $resumeData;
            $application->email = $validated['email'] ?? null;
            $application->phone = $validated['phone'] ?? null;
            $application->status = 'pending';
    
            $application->save();
            return response()->json(ApplicationResource::make($application));
        }
        catch(Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function cancelApplication(Request $request) {
        $application = Application::findOrFail($request['app_id']);
        
        $application->delete();
        
        return response()->json(["status" => "deleted successfully", "data" => $application]);
    }
}
