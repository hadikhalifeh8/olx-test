<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            //'user_id' => 'required|exists:users,id',
            'description' => 'required',
        ];
    }



    public function messages()
    {
         return [
            // 'user_id.required' => 'User ID is required',
            // 'user_id.exists' => 'User ID must exist in users table',
            'description.required' => 'Please enter a comment',
        ];
    }
}