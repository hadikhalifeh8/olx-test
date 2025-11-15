<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'task_name' => 'required|between:5,100',
        ];
    }

        public function messages()
    {
        return [
            'user_id.required' => 'please Enter the user id',
            'user_id.exists' => 'The selected user id is invalid',

            'task_name.required' => 'please Enter the task name',
            'task_name.between' => 'Task name must be between 5 and 100 characters',
        ];
    }
}
