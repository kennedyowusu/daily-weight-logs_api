<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class StoreHealthDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow the logged-in user is authorized to create their own health data
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
            'height' => 'required|numeric|min:1|max:2.5',
            'weight_goal' => 'required|in:gain,lose,maintain',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'height.min' => 'The height must be at least 1 meter.',
            'height.max' => 'The height must be at most 2.5 meters.',
            'weight_goal.in' => 'The weight_goal must be one of: gain, lose, maintain.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'height' => [
                'description' => 'The height of the user in meters.',
                'example' => 1.75,
            ],
            'weight_goal' => [
                'description' => 'The user\'s weight goal.',
                'example' => 'gain',
            ],
        ];
    }
}
