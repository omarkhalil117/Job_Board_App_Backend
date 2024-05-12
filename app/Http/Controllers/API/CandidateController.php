<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCandidateRequest;
use App\Http\Resources\CandidateResource;
use App\Models\Candidate;
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
        $candidateApplications = Candidate::findOrFail($id);
        // return CandidateResource::collection($candidateApplications);
        return response()->json($candidateApplications->applications);
    }

    public function applyToPost(Request $request) {

    }
}
