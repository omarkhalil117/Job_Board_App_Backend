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
            'resume' => 'required',
            'education' => 'required',
            'faculty' => 'required',
            'city' => 'required',
            'experience_level' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'resume.required' => 'A resume is required',
            'education.required' => 'Education is required',
            'faculty.required' => 'Faculty is required',
            'city.required' => 'City is required',
            'experience_level.required' => 'Experience level is required',
        ];
    }
}
