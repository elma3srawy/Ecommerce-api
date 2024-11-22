<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class AuthUserRequest extends FormRequest
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
        return [];
    }

    /**
     * Handles the registration of a new user.
     * Validates the input data, creates a new user in the database, and returns a response array.
     *
     * @return array<string, mixed> Returns an array containing status and message.
     */
    public function registerRules(): array
    {
        return [
            'name' => ['required' , 'string' , 'min:3' , 'max:255' , 'alpha'],
            'phone' => ['required' , 'string' , 'regex:/^(011|010|012|015)\d{8}$/' ,'unique:users,phone'],
            'email' => ['required' , 'string', 'email' , 'max:255' , 'unique:users,email'],
            'password' => ['required' , 'string' , 'min:8' , 'max:255' , 'confirmed' , 'regex:/[a-z]/' , 'regex:/[A-Z]/']
        ];
    }

    /**
     * Authenticates a user based on provided credentials.
     * Checks the credentials, and if valid, returns a response array with authentication details.
     *
     * @return array<string, mixed> Returns an array containing status, token, or error messages.
     */
    public function loginRules(): array
    {
        return [
            'phone' => ['required', 'string', 'regex:/^(011|010|012|015)\d{8}$/'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }
}
