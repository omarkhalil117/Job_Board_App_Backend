<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidateRequest extends FormRequest
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
        $userId = $this->user()->id;

        return [
            'name' => 'nullable|string',
            'email' => 'nullable|string|email|unique:users,email',
            'username' => 'nullable|string|unique:users,username',
            'education' => 'nullable|string',
            'faculty' => 'nullable|string',
            'city' => 'nullable|string',
            'experience_level' => 'nullable|in:junior,mid-senior,senior,manager,team-lead',
            'linkedin' => 'nullable|string|url',
            'github' => 'nullable|string|url',
            'image' => 'nullable|string|url',
        ];
    }
}
