<?php

namespace App\Http\Requests\ResetPassword;

use Illuminate\Foundation\Http\FormRequest;

class AdminResetPasswordRequest extends FormRequest
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
        return $this->isMethod('post') ? $this->onSendNotification() : $this->onUpdatePassword();
    }

    private function onUpdatePassword(): array
    {
        return [
            'token' => ['required' , 'string' , 'max:255'],
            'password' => ['required' , 'string' , 'min:8' , 'max:255' , 'confirmed' , 'regex:/[a-z]/' , 'regex:/[A-Z]/'],
        ];
    }
    private function onSendNotification(): array
    {
        return [
            'email' => ['required', 'string','email','max:255'],
        ];
    }
}
