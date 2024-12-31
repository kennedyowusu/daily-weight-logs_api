<?php

namespace App\Http\Requests\v1;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWeightLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->id === $this->weightLog->user_id;
        false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'weight' => 'required|numeric|min:30|max:300',
            'time_of_day' => 'required|in:morning,evening',
            'logged_at' => 'required|date',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            $user = $this->user();
            $loggedAt = Carbon::parse($this->input('logged_at'));

            // Check if another weight log exists for the same day and time_of_day
            $existingWeightLog = $user->weightLogs()
                ->whereDate('logged_at', $loggedAt->toDateString())
                ->where('time_of_day', $this->input('time_of_day'))
                ->where('id', '!=', $this->weightLog->id) // Exclude the current weight log
                ->first();

            if ($existingWeightLog) {
                $validator->errors()->add('logged_at', 'Another weight log already exists for this time of day.');
            }
        });
    }

    public function bodyParameters(): array
    {
        return [
            'weight' => [
                'description' => 'The weight of the user in kilograms.',
                'example' => 75,
            ],
            'time_of_day' => [
                'description' => 'The time of day when the weight was logged.',
                'example' => 'morning',
            ],
            'logged_at' => [
                'description' => 'The date and time when the weight was logged.',
                'example' => '2021-08-01 08:00:00',
            ],
        ];
    }
}
