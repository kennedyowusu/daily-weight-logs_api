<?php

namespace App\Http\Controllers\api\v1\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\StoreWeightLogRequest;
use App\Http\Requests\v1\UpdateWeightLogRequest;
use App\Http\Resources\v1\WeightLogResource;
use App\Models\WeightLog;
use Illuminate\Http\Request;

/**
 * @group Weight Logs
 *
 * Endpoints for managing weight logs.
 */
class WeightLogController extends Controller
{

    /**
     * List all weight logs.
     *
     * @queryParam time_of_day string Filter logs by morning or evening. Example: morning
     * @response 200 {
     *  "data": [
     *    {
     *      "id": 1,
     *      "weight": 75,
     *      "time_of_day": "morning",
     *      ...
     *    }
     *  ]
     * }
     */
    public function index(Request $request)
    {
        // Check if the user is authorized to view any logs
        if ($request->user()->cannot('viewAny', WeightLog::class)) {
            return response()->json(['message' => 'You are not authorized to view weight logs.'], 403);
        }

        $user = $request->user();

        // Fetch the filter value (morning or evening) from query parameters
        $timeOfDay = $request->query('time_of_day');

        $query = $user->role === 'admin'
            ? WeightLog::query()
            : $user->weightLogs();

        // Apply filter for time_of_day if provided
        if ($timeOfDay) {
            $query->where('time_of_day', $timeOfDay);
        }

        $weightLogs = $query->latest()->get();

        return WeightLogResource::collection($weightLogs);
    }

    /**
     * Create a new weight log.
     *
     * @bodyParam weight float required The weight of the user. Example: 75.5
     * @bodyParam time_of_day string required Morning or evening. Example: morning
     * @bodyParam logged_at datetime required The time the weight was logged. Example: 2024-12-31 08:00:00
     * @response 201 {
     *   "id": 1,
     *   "weight": 75.5,
     *   ...
     * }
     */
    public function store(StoreWeightLogRequest $request)
    {
        if ($request->user()->cannot('create', WeightLog::class)) {
            abort(403);
        }

        $user = $request->user();

        $bmi = $user->healthData ? $request->weight / (($user->healthData->height / 100) ** 2) : null;

        $weightLog = $user->weightLogs()->create([
            'weight' => $request->weight,
            'time_of_day' => $request->time_of_day,
            'logged_at' => $request->logged_at,
            'bmi' => $bmi,
        ]);

        return new WeightLogResource($weightLog);
    }

    /**
     * Show a specific weight log.
     *
     * @urlParam weightLog int required The ID of the weight log.
     * @response 200 {
     *   "id": 1,
     *   "weight": 75.5,
     *   ...
     * }
     */
    public function show(WeightLog $weightLog, Request $request)
    {
        if ($request->user()->cannot('view', $weightLog)) {
            abort(403);
        }

        $request->user();

        return new WeightLogResource($weightLog);
    }

    /**
     * Update a specific weight log.
     *
     * @urlParam weightLog int required The ID of the weight log.
     * @bodyParam weight float The updated weight. Example: 76.5
     * @response 200 {
     *   "id": 1,
     *   "weight": 76.5,
     *   ...
     * }
     */
    public function update(UpdateWeightLogRequest $request, WeightLog $weightLog)
    {
        if ($request->user()->cannot('update', $weightLog)) {
            abort(403);
        }

        $weightLog->update($request->validated());
        return new WeightLogResource($weightLog);
    }

    /**
     * Delete a specific weight log.
     *
     * @urlParam weightLog int required The ID of the weight log.
     * @response 204 {}
     */
    public function destroy(WeightLog $weightLog, Request $request)
    {
        if ($request->user()->cannot('delete', $weightLog)) {
            abort(403);
        }

        $request->user();

        $weightLog->delete();
        return response()->json(['message' => 'Weight log deleted successfully.'], 204);
    }
}
