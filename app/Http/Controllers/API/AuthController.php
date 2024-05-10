<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployerRequest;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
       
    }
    public function storeEmp(StoreEmployerRequest $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validated();
        
        // Create the employer
        $employer = new Employer([
            'company_name' => $validatedData['company_name'],
            'logo' => $validatedData['logo'],
        ]);
        
        // Save the employer
        $employer->save();
        
        // Create the user associated with the employer
        $user = new User([
            'name' => $validatedData['user']['name'],
            'email' => $validatedData['user']['email'],
            'password' => bcrypt($validatedData['user']['password']),
            'username' => $validatedData['user']['username'],
            'image' => $validatedData['user']['image'] ?? null,
            'role' => 'employer', // Assign the role here
        ]);
        
        // Associate the user with the employer
        $employer->user()->save($user);
    
        return response()->json([
            'status' => true,
            'message' => 'Employer Created Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
