<?php

namespace App\Http\Controllers\api\v1\user;

use App\Events\UserDeleted;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\UpdateUserRequest;
use App\Http\Resources\v1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @group User
 *
 * Endpoints for managing user profiles.
 */
class UserController extends Controller
{
    /**
     * View the authenticated user's profile.
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "John Doe",
     *   ...
     * }
     */
    public function view(Request $request)
    {
        $user = $request->user();
        return new UserResource($user);
    }

    /**
     * Update the authenticated user's profile.
     *
     * @authenticated
     * @bodyParam name string The updated name. Example: Jane Doe
     * @response 200 {
     *   "id": 1,
     *   "name": "Jane Doe",
     *   ...
     * }
     */
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();

        if ($request->user()->cannot('update', $user)) {
            abort(403);
        }

        $validatedData = $request->validated();
        $user->update($validatedData);

        return new UserResource($user);
    }

    /**
     * Delete the authenticated user's account.
     *
     * @response 200 {"message": "User deleted successfully."}
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($request->user()->cannot('delete', $user)) {
            abort(403);
        }

        // Log the deletion event
        Log::info('User account deleted', [
            'user_id' => $user->id,
            'email' => $user->email,
            'deleted_at' => now()->toDateTimeString(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Delete related data and user
        DB::transaction(function () use ($user) {
            $user->weightLogs()->delete();
            $user->healthData()->delete();
            $user->forceDelete(); // Ensure hard delete
            event(new UserDeleted($user));
        });

        return response()->json(['message' => 'User deleted successfully.'], 200);
    }
}
