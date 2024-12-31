<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @group Authentication
 *
 * Endpoints for user authentication.
 */
class AuthenticationController extends Controller
{
    /**
     * Register a new user.
     *
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam username string required The username. Example: johndoe
     * @bodyParam email string required The email address. Example: johndoe@example.com
     * @bodyParam password string required The password. Example: password
     * @bodyParam password_confirmation string required The password confirmation. Example: password
     * @response 201 {
     *   "message": "Registration successful.",
     *   ...
     * }
     */
    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'country' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'country' => $request->country,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Login a user.
     *
     * @bodyParam email string required The email address. Example: johndoe@example.com
     * @bodyParam password string required The password. Example: password
     * @response 200 {
     *   "message": "Login successful.",
     *   ...
     * }
     * @response 401 {"message": "Invalid credentials."}
     */
    public function loginUser(Request $request)
    {
        // Validate request inputs
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and the password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        // Generate a token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'user' => new UserResource($user),
            'token' => $token,
        ], 200);
    }

    /**
     * Logout the authenticated user.
     *
     * @response 200 {"message": "Logout successful."}
     */
    public function logoutUser(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful.',
        ], 200);
    }
}
