<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required', 'email', 'string',
            ],

            'password' => [
                'required', 'min:8', 'max:25',
            ],
        ];
    }

    public function messages(): array
    {
        # custom error messages
        return [
            # email error messages
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            # password error messages
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.max' => 'Password must not be greater than 25 characters',
        ];
    }
}
