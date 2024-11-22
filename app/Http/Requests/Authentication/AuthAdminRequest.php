<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class AuthAdminRequest extends FormRequest
{
    /**
     * Determine if the admin is authorized to make this request.
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
        return [];
    }

    public function registerRules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255', 'alpha'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/'
            ],
        ];
    }

    /**
     * Validation rules for login.
     *
     * @return array<string, mixed>
     */
    public function loginRules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }

}
