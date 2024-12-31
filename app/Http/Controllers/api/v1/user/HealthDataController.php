<?php

namespace App\Http\Controllers\api\v1\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\StoreHealthDataRequest;
use App\Http\Requests\v1\UpdateHealthDataRequest;
use App\Http\Resources\v1\HealthDataResource;
use App\Models\HealthData;
use Illuminate\Http\Request;

/**
 * @group Health Data
 *
 * Endpoints for managing health data.
 */
class HealthDataController extends Controller
{

    /**
     * Store a new health data record.
     *
     * @bodyParam height float required The user's height in centimeters. Example: 170
     * @bodyParam weight_goal string required The weight goal. Example: gain
     * @response 201 {
     *   "id": 1,
     *   "user_id": 1,
     *   "height": 170,
     *   ...
     * }
     */
    public function store(StoreHealthDataRequest $request)
    {
        $user = $request->user();

        $healthData = $user->healthData()->create($request->validated());

        return new HealthDataResource($healthData);
    }

    /**
     * Display a specific health data record.
     *
     * @urlParam healthData int required The ID of the health data record.
     * @response 200 {
     *   "id": 1,
     *   "user_id": 1,
     *   ...
     * }
     */
    public function show(HealthData $healthData, Request $request)
    {
        if ($request->user()->cannot('view', $healthData)) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $request->user();

        return new HealthDataResource($healthData);
    }

    /**
     * Update a health data record.
     *
     * @urlParam healthData int required The ID of the health data record.
     * @bodyParam height float The updated height. Example: 175
     * @response 200 {
     *   "id": 1,
     *   "user_id": 1,
     *   ...
     * }
     */
    public function update(UpdateHealthDataRequest $request, HealthData $healthData)
    {
        if ($request->user()->cannot('update', $healthData)) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $healthData->update($request->validated());
        return new HealthDataResource($healthData);
    }

    /**
     * Delete a health data record.
     *
     * @urlParam healthData int required The ID of the health data record.
     * @response 200 {"message": "Health data deleted successfully."}
     */
    public function destroy(HealthData $healthData, Request $request)
    {
        if ($request->user()->cannot('delete', $healthData)) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $request->user();

        $healthData->delete();
        return response()->json(['message' => 'Health data deleted successfully.'], 200);
    }
}
