<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUsers extends FormRequest
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
             'email' => 'required|string|email',
             'password' => 'required|string|min:6',
        ];
    }


    public function messages()
    {
        return [
            'email.required' => 'please Enter the email',
            'email.email' => 'Please enter a valid email address',

            'password.required' => 'please Enter the password',
            'password.min' => 'Password must be at least 6 characters',
        ];
    }

}
