<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
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
            'job_title'=> ['required' , 'string', 'max:50'],
            'description' => ['required', 'string', 'min:50'],
            'responsibilities' => ['required', 'string', 'min:50'],
            'qualifications' => ['required', 'string', 'min:50'],
            'start_salary' => ['numeric'],
            'end_salary' => ['numeric'],            
            'location' => ['required'],
            'work_type' => ['required', Rule::in(['remote', 'on-site', 'hybrid'])],
            'skills' => [ 'required' , 'exists:skills,id'],
            
        ];
    }
    public function messages():  array  {
        return [
            'description.required' => 'The description field is required.',
            'description.min' => 'The description must be at least :min characters.',
            'responsibilities.required' => 'The responsibilities field is required.',
            'responsibilities.min' => 'The responsibilities must be at least :min characters.',
            'qualifications.required' => 'The qualifications field is required.',
            'qualifications.min' => 'The qualifications must be at least :min characters.',
            'start_salary.numeric' => 'The start salary must be a number.',
            'end_salary.numeric' => 'The end salary must be a number.',
            'location.required' => 'The location field is required.',
            'work_type.required' => 'The work type field is required.',
            'work_type.in' => 'The selected work type is invalid.',
            'skills.required' => 'At least one skill is required.',
            'skills.exists' => 'One or more selected skills do not exist.',
           
        ];
    }
}
