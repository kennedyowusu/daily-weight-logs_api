<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\HealthDataResource;
use App\Http\Resources\v1\UserResource;
use App\Http\Resources\v1\WeightLogResource;
use App\Models\HealthData;
use App\Models\User;

/**
 * @group Admin
 *
 * Endpoints for managing users, health data, and weight logs as an admin.
 */
class AdminController extends Controller
{
    /**
     * Display a list of all users.
     *
     * @response 200 {
     *  "data": [
     *    {
     *      "id": 1,
     *      "name": "John Doe",
     *      "username": "johndoe",
     *      "email": "johndoe@example.com",
     *      "role": "user",
     *      ...
     *    }
     *  ],
     *  "links": {
     *      "first": "http://example.com?page=1",
     *      ...
     *  }
     * }
     */
    public function index()
    {
        $users = User::where('role', 'user')->paginate(10);
        return UserResource::collection($users);
    }

    /**
     * View details of a specific user.
     *
     * @urlParam user int required The ID of the user.
     * @response 200 {
     *   "id": 1,
     *   "name": "John Doe",
     *   "username": "johndoe",
     *   ...
     * }
     */
    public function show(User $user)
    {
        $user = User::findOrFail($user->id);
        return new UserResource($user);
    }

    /**
     * Delete a user.
     *
     * @urlParam user int required The ID of the user to delete.
     * @response 204 {}
     * @response 403 {"message": "Cannot delete an admin account."}
     */
    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return response()->json(['message' => 'Cannot delete an admin account.'], 403);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully.'] , 204);
    }

    /**
     * Promote a user to admin.
     *
     * @urlParam user int required The ID of the user to promote.
     * @response 200 {"message": "User promoted to admin successfully."}
     */
    public function promote(User $user)
    {
        if ($user->role === 'admin') {
            return response()->json(['message' => 'User is already an admin.'], 400);
        }

        $user->role = 'admin';
        $user->save();

        return response()->json(['message' => 'User promoted to admin successfully.']);
    }

    /**
     * View all health data.
     *
     * @response 200 {
     *  "data": [
     *    {
     *      "id": 1,
     *      "user_id": 1,
     *      "height": 170,
     *      ...
     *    }
     *  ]
     * }
     */
    public function viewHealthData()
    {
        $healthData = HealthData::paginate(10);
        return HealthDataResource::collection($healthData);
    }

    /**
     * View a specific health data record.
     *
     * @urlParam healthData int required The ID of the health data record.
     * @response 200 {
     *   "id": 1,
     *   "user_id": 1,
     *   ...
     * }
     */
    public function showHealthData(HealthData $healthData)
    {
        return new HealthDataResource($healthData);
    }

    /**
     * Delete a health data record.
     *
     * @urlParam healthData int required The ID of the health data record.
     * @response 200 {"message": "Health data record deleted successfully."}
     */
    public function destroyHealthData(HealthData $healthData)
    {
        $healthData->delete();
        return response()->json(['message' => 'Health data record deleted successfully.'], 200);
    }

    /**
     * View all weight logs for a user.
     *
     * @urlParam user int required The ID of the user.
     * @response 200 {
     *  "data": [
     *    {
     *      "id": 1,
     *      "user_id": 1,
     *      "weight": 70,
     *      ...
     *    }
     *  ]
     * }
     */
    public function viewWeightLogs(User $user)
    {
        $weightLogs = $user->weightLogs()->paginate(10);
        return WeightLogResource::collection($weightLogs);
    }

    /**
     * View a specific weight log.
     *
     * @urlParam user int required The ID of the user.
     * @urlParam weightLog int required The ID of the weight log.
     * @response 200 {
     *   "id": 1,
     *   "user_id": 1,
     *   "weight": 70,
     *   ...
     * }
     */
    public function showWeightLog(User $user, $weightLog)
    {
        $weightLog = $user->weightLogs()->find($weightLog);
        return new WeightLogResource($weightLog);
    }

    /**
     * Delete a weight log.
     *
     * @urlParam user int required The ID of the user.
     * @urlParam weightLog int required The ID of the weight log.
     * @response 200 {"message": "Weight log deleted successfully."}
     */
    public function destroyWeightLog(User $user, $weightLog)
    {
        $user->weightLogs()->find($weightLog)->delete();
        return response()->json(['message' => 'Weight log deleted successfully.'], 200);
    }
}
