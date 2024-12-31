<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow only the authenticated user to update their profile
        return $this->user()->id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:users,username,' . $this->user()->id,
            'country' => 'nullable|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $this->user()->id,
            'password' => 'sometimes|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique' => 'The username has already been taken.',
            'email.unique' => 'The email has already been taken.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'The updated name of the user.',
                'example' => 'Jane Doe',
            ],
            'email' => [
                'description' => 'The updated email of the user.',
                'example' => 'janedoe@example.com',
            ],
        ];
    }

}
