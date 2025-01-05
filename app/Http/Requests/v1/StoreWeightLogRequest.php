<?php

namespace App\Http\Requests\v1;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;


class StoreWeightLogRequest extends FormRequest
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
            'weight' => 'required|numeric|min:30|max:300',
            'time_of_day' => 'required|in:morning,evening',
            'logged_at' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    // Extract only the date portion of logged_at
                    $loggedAtDate = Carbon::parse($value)->toDateString();
                    $today = now()->toDateString();

                    // Check if logged_at is not today's date
                    if ($loggedAtDate !== $today) {
                        $fail("The $attribute must be today's date.");
                    }
                },
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = $this->user();
            $loggedAt = Carbon::parse($this->input('logged_at'));
            $timeOfDay = $this->input('time_of_day');

            // Check if the logged_at time matches the specified time_of_day range
            $hour = $loggedAt->hour;

            if ($timeOfDay === 'morning' && ($hour < 6 || $hour > 11)) {
                $submittedTime = $loggedAt->format('g:i A');
                $validator->errors()->add('logged_at', "The logged_at time ({$submittedTime}) does not match the morning time range (6:00 AM - 11:59 AM).");
            }

            if ($timeOfDay === 'evening' && ($hour < 17 || $hour > 21)) {
                $submittedTime = $loggedAt->format('g:i A');
                $validator->errors()->add('logged_at', "The logged_at time ({$submittedTime}) does not match the evening time range (5:00 PM - 9:59 PM).");
            }

            // Check if the user already logged weight for the same time_of_day
            $existingLog = $user->weightLogs()
                ->whereDate('logged_at', $loggedAt->toDateString())
                ->where('time_of_day', $timeOfDay)
                ->first();

            if ($existingLog) {
                $validator->errors()->add('time_of_day', "You have already logged your weight for the {$timeOfDay} today.");
            }
        });
    }

    public function bodyParameters(): array
    {
        return [
            'weight' => [
                'description' => 'The weight of the user.',
                'example' => 75.5,
            ],
            'time_of_day' => [
                'description' => 'The time of day for the weight log (morning or evening).',
                'example' => 'morning',
            ],
            'logged_at' => [
                'description' => 'The date and time the weight was logged.',
                'example' => '2024-12-31 08:00:00',
            ],
        ];
    }
}
