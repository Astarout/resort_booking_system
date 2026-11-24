<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[\p{L}]+(?:\s[\p{L}]+)*$/u',
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[\p{L}]+(?:\s[\p{L}]+)*$/u',
            ],

            'last_name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[\p{L}]+(?:\s[\p{L}]+)*$/u',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
            ],

            'phone' => [
                'required',
                'string',
                'unique:users',
                'regex:/^\+639\d{9}$/',
            ],

            'address' => [
                'required',
                'string',
                'max:255',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'max:25',
                'confirmed',
            ],
        ];
    }

    public function messages(): array
    {
        # Custom Error Messages

        return [
            # First Name
            'first_name.required' => 'Provide your first name',
            'first_name.max' => 'Maximum length of characters should be 50',
            'first_name.regex' => 'Your provided name is not valid',
            # Last Name
            'last_name.required' => 'Provide your last name',
            'last_name.max' => 'Maximum length of characters should be 50',
            'last_name.regex' => 'Your provided name is not valid',
            # Email
            'email.required' => 'Email field is required.',
            'email.string' => 'Email must be a string.',
            'email.email' => 'Email must be a valid email address.',
            'email.max' => 'Email may not be greater than 255 characters.',
            'email.unique' => 'Email address is already taken.',
            # Phone
            'phone.required' => 'Phone number field is required.',
            'phone.string' => 'Phone number must be a valid string.',
            'phone.regex' => 'Correct Format (e.g., +639XXXXXXXXX).',
            # Password
            'password.required' => 'Password field is required.',
            'password.string' => 'Password must be a valid string.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password may not be greater than 25 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}
