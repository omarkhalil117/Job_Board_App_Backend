<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
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
            'post_id' => 'required|exists:posts,id',
            'resume' => 'required_without_all:email,phone|mimes:pdf|max:2048',
            'email' => 'required_without:resume|email',
            'phone' => 'required_without:resume|regex:/^([0-9\s\-\+\(\)]*)$/',
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
            'resume.required_without_all' => 'Please upload your resume.',
            'email.required_without' => 'The email field is required if resume is not provided.',
            'email.email' => 'Please provide a valid email address.',
            'phone.required_without' => 'The phone field is required if resume is not provided.',
            'phone.regex' => 'Please provide a valid phone number.',
        ];
    }
}
