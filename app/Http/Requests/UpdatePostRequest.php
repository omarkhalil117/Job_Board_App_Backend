<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
class UpdatePostRequest extends FormRequest
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
            'start_salary' => ['required', 'numeric'],
            'end_salary' => ['required', 'numeric'],            
            'location' => ['required'],
            'work_type' => ['required', Rule::in(['remote', 'on-site', 'hybrid'])],
            'application_deadline' => ['required', 'date', 'after_or_equal:' . Carbon::today()->toDateString()],
            
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
            'start_salary.required' => 'The start salary field is required.',
            'start_salary.numeric' => 'The start salary must be a number.',
            'end_salary.required' => 'The end salary field is required.',
            'end_salary.numeric' => 'The end salary must be a number.',
            'location.required' => 'The location field is required.',
            'work_type.required' => 'The work type field is required.',
            'work_type.in' => 'The selected work type is invalid.',
            'application_deadline.required' => 'The application deadline field is required.',
            'application_deadline.date' => 'The application deadline must be a valid date.',
            'application_deadline.after_or_equal' => 'The application deadline must be after or equal to today\'s date.',

        ];
    }
}
