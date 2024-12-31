<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHealthDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ensure that the authenticated user owns the health data being updated
        return $this->user()->id === $this->healthData->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'weight_goal' => 'sometimes|in:gain,lose,maintain',
        ];
    }

    public function messages(): array
    {
        return [
            'weight_goal.in' => 'The weight goal must be one of: gain, lose, maintain.',
            'weight_goal.required' => 'The weight goal field is required.',
        ];
    }

    protected function prepareForValidation()
    {
        // Check if the request contains disallowed fields like height
        $disallowedFields = ['height'];
        foreach ($disallowedFields as $field) {
            if ($this->has($field)) {
                $this->merge(['invalid_field' => $field]);
            }
        }
    }

    /**
     * Customize validation behavior for disallowed fields.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('invalid_field')) {
                $validator->errors()->add(
                    $this->input('invalid_field'),
                    'You are not allowed to update this field.'
                );
            }
        });
    }

    public function bodyParameters(): array
    {
        return [
            'weight_goal' => [
                'description' => 'The user\'s weight goal.',
                'example' => 'gain',
            ],
        ];
    }
}
