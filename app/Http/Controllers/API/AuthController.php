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
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Dotenv\Exception\ValidationException;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
   
    public function empRegister(StoreEmployerRequest $request){

        $validatedData = $request->validated();
        
        $logo = null;
        if($request['logo']){
            $logo = $this->uploadFileToCloudinary($request,'logo');
        }

        $employer = new Employer([
            'company_name' => $request['company_name'],
            'logo' => $logo ?? null,
        ]);
        
        $employer->save();
        
        $image = null;
        if($request['image']){
            $image = $this->uploadFileToCloudinary($request,'image');
        }

        $user = new User([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'username' => $validatedData['username'],
            'image' => $image,
            'role' => 'employer', 
        ]);
  
 
   
        $user->save(); 
        event(new Registered($user));
        $user->sendEmailVerificationNotification(); 
    
        $user->userable()->associate($employer); 
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Employer Created Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }

    public function candidateRegister(StoreCandidateRequest $request){
        $validatedData = $request->validated();
        $resume = null;
        if($request['resume']){
            $resume = $this->uploadFileToCloudinary($request,'resume');
        }

        $candidate = new Candidate([
            'resume' =>  $resume ?? null,
            'education' => $validatedData['education']??null,
            'faculty' => $validatedData['faculty']??null,
            'city' => $validatedData['city']??null,
            'experience_level' => $validatedData['experience_level']??null,
            'linkedin' => $validatedData['linkedin'] ?? null,
            'github' => $validatedData['github'] ?? null,
        ]);

        $candidate->save();
        $image = null;
        if($request['image']){
            $image = $this->uploadFileToCloudinary($request,'image');
        }

        $user = new User([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'username' => $validatedData['username'],
            'image'=>  $image ?? null,
            'role' => 'candidate', 
        ]);

        $user->save(); 
        event(new Registered($user));
        $user->sendEmailVerificationNotification() ;
    
        $user->userable()->associate($candidate); 
        $user->save();
    
        return response()->json([
            'status' => true,
            'message' => 'Candidate Created Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }
    
    public function login(Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $token = $user->createToken($request->email)->plainTextToken;
    
        return $this->getUserDataByRole($token);
    }
    
    public function getUserDataByRole($token){
        
        $currentRequestPersonalAccessToken = PersonalAccessToken::findToken($token);
        $user = $currentRequestPersonalAccessToken->tokenable;
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        if ($user->role == "admin") {
            return [
                'token' => $token,
                'user' => new UserResource($user),
            ];
        }
        elseif ($user->role == "employer") {
            $employer = Employer::find($user->userable_id);
            if ($employer) {
                return [
                    'token' => $token,
                    'user' => new EmployerResource($employer),
                ];
            } 
        } 
        elseif ($user->role == "candidate") {
            $candidate = Candidate::find($user->userable_id);
            if ($candidate) {
                return [
                    'token' => $token,
                    'user' => new CandidateResource($candidate),
                ];
            }
        }
    
        return response()->json(['error' => 'Invalid user role or no associated data found'], 400);
    }
    public function getUser(Request $request){
        
            $user = $request->user();
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        if ($user->role == "admin") {
            return [
                'user' => new UserResource($user),
            ];
        }
        elseif ($user->role == "employer") {
            $employer = Employer::find($user->userable_id);
            if ($employer) {
                return [
                    'user' => new EmployerResource($employer),
                ];
            } 
        } 
        elseif ($user->role == "candidate") {
            $candidate = Candidate::find($user->userable_id);
            if ($candidate) {
                return [

                    'user' => new CandidateResource($candidate),
                ];
            }
        }
    
        return response()->json(['error' => 'Invalid user role or no associated data found'], 400);
    }
    public function getRoleByToken($token){
        
        $currentRequestPersonalAccessToken = PersonalAccessToken::findToken($token);
        $user = $currentRequestPersonalAccessToken->tokenable;
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        if ($user->role) {
            return $user->role;
        }
        
    
        return response()->json(['error' => 'Invalid user role or no associated data found'], 400);
    }
    public function uploadFileToCloudinary($request, $field){
        $fileUrl = '';
        
        if ($request->hasFile($field)) {
            $client = new Client([
                'verify' => config('services.cloudinary.verify'),
            ]);
            
            try {
                $response = $client->request('POST', 'https://api.cloudinary.com/v1_1/deqwn8wr6/auto/upload', [
                    'multipart' => [
                        [
                            'name' => 'file',
                            'contents' => fopen($request->file($field)->getPathname(), 'r'),
                        ],
                        [
                            'name' => 'upload_preset',
                            'contents' => 'jdebs8xw', 
                        ],
                    ],
                ]);
                $cloudinaryResponse = json_decode($response->getBody()->getContents(), true);
                $url = $cloudinaryResponse['secure_url'] ?? null;
                
                $fileUrl = $url;
    
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => "Error uploading $field to Cloudinary",
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    
        return $fileUrl;
    }
    
}

