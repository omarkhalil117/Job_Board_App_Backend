<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckUserRole;
use App\Http\Requests\StoreCandidateRequest;
use App\Http\Requests\StoreEmployerRequest;
use App\Http\Resources\CandidateResource;
use App\Http\Resources\EmployerResource;
use App\Http\Resources\UserResource;
use App\Models\Candidate;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function empRegister(StoreEmployerRequest $request){
        $validatedData = $request->validated();
        
        $employer = new Employer([
            'company_name' => $validatedData['company_name'],
            'logo' => $validatedData['logo'],
        ]);
        
    
        $employer->save();
        
   
        $user = new User([
            'name' => $validatedData['user']['name'],
            'email' => $validatedData['user']['email'],
            'password' => bcrypt($validatedData['user']['password']),
            'username' => $validatedData['user']['username'],
            'image' => $validatedData['user']['image'] ?? null,
            'role' => 'employer', 
        ]);
        

        $employer->user()->save($user);
    
        return response()->json([
            'status' => true,
            'message' => 'Employer Created Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }

    public function candidateRegister(StoreCandidateRequest $request){
        $validatedData = $request->validated();
        
        $candidate = new Candidate([
            'resume' => $validatedData['resume'],
            'education' => $validatedData['education'],
            'faculty' => $validatedData['faculty'],
            'city' => $validatedData['city'],
            'experience_level' => $validatedData['experience_level'],
            'linkedin' => $validatedData['linkedin'] ?? null,
            'github' => $validatedData['github'] ?? null,
        ]);

        $candidate->save();

        $user = new User([
            'name' => $validatedData['user']['name'],
            'email' => $validatedData['user']['email'],
            'password' => bcrypt($validatedData['user']['password']),
            'username' => $validatedData['user']['username'],
            'image' => $validatedData['user']['image'] ?? null,
            'role' => 'candidate',
        ]);
    

        $candidate->user()->save($user);
    
        return response()->json([
            'status' => true,
            'message' => 'Candidate Created Successfully',
            'token' =>  $token = $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }
    
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
     
        $user = User::where('email', $request->email)->first();
     
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $user->createToken($request->device_name)->plainTextToken;
    }
     
    public function getUserData(Request $request){
        $user = $request->user();

        if ($user->role == "admin") {
            return new UserResource($user);
        }
        elseif ($user->role == "employer") {
            $employer = Employer::find($user->userable_id);
            
            if ($employer) {
                return new EmployerResource($employer);
            } 
        } elseif ($user->role == "candidate") {
            $candidate = Candidate::find($user->userable_id);
           // new CandidateResource($candidate);
            if ($candidate) {
                return new CandidateResource($candidate);
            }
        } else {
            return response()->json(['error' => 'Invalid user role'], 400);
        }
    }

}
