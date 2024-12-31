<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeightLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'weight' => $this->weight,
            'time_of_day' => ucfirst($this->time_of_day),
            'logged_at' => $this->logged_at->toDayDateTimeString(),
            'bmi' => $this->bmi,
            'user_id' => [
                'name' => $this->user->name,
            ],
        ];
    }
}
