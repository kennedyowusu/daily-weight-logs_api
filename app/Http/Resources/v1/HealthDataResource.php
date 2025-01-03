<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthDataResource extends JsonResource
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
            'height' => number_format($this->height, 2) . ' m',
            'weight_goal' => $this->weight_goal,
            'user_id' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ]
        ];
    }
}
