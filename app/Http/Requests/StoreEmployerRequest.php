<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployerRequest extends FormRequest
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
    public function rules()
    {
        return [
            'company_name' => 'string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user.name' => 'required|string|max:255',
            'user.email' => 'required|email|unique:users,email,NULL,id,userable_type,App\Models\Employer',
            'user.password' => 'required|string|min:8',
            'user.username' => 'required|string|max:255|unique:users,username,NULL,id,userable_type,App\Models\Employer',
            'user.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
