<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        return [
            'name' => 'nullable|string',
            'email' => [
                Rule::unique('users', 'userable_id')->ignore($this->id),
                'email'
            ],
            'username' => [
                Rule::unique('users', 'userable_id')->ignore($this->id)
            ],
            'resume' => 'nullable|file|mimes:pdf',
            'education' => 'nullable|string',
            'faculty' => 'nullable|string',
            'city' => 'nullable|string',
            'experience_level' => 'nullable|in:junior,mid-senior,senior,manager,team-lead',
            'linkedin' => 'nullable|string|url',
            'github' => 'nullable|string|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
