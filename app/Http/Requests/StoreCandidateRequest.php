<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'resume' => 'required|file|mimes:pdf', 
            'education' => 'required|string',
            'faculty' => 'required|string',
            'city' => 'required|string',
            'experience_level' => 'required|string|in:junior,mid-senior,senior,manager,team-lead',
            'linkedin' => 'nullable|url',
            'github' => 'nullable|url',
            'user.name' => 'required|string',
            'user.email' => 'required|email|unique:users,email',
            'user.password' => 'required|string|min:6',
            'user.username' => 'required|string|unique:users,username',
            'user.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
