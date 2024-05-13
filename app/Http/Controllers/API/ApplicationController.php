<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $file = $request->file('cv')->getClientOriginalName();
        $email = $request->email;
        $phone = $request->phone;
        $post_id = $request->postId;
        $candidate_id = $request->candidateId;

        $application = new Application;

        $application->candidate_id = (int)$candidate_id;
        $application->post_id = (int)$post_id;
        $application->resume = $file;
        $application->email = $email;
        $application->phone = $phone;
        $application->status = 'pending';

        $application->save();

        return response()->json(['message'=> "Success" , ['file' => $file , 'email' => $email , 'phone' => $phone]]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        //
    }
}
