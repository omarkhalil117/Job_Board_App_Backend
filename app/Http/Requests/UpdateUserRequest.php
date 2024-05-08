<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => ['required'],
            'email' => [ Rule::unique('users')->ignore($this->id),'required'],
            'username' => [ 'required'],
            'company_name' => [ 'required'],
            ];
    }


    public function messages():  array  {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.uniqe' => 'This Email is taken',
            'company_name'=>'Company Name is required'
        ];
    }
}
