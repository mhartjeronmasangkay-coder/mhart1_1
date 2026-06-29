<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * GET /api/profile
     * Get authenticated user's profile
     */
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * PUT /api/profile
     * Update authenticated user's profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Validate input
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
            'username' => 'sometimes|string|max:255|regex:/^[a-zA-Z0-9_-]+$/|unique:users,username,' . $user->id,
            'password' => 'sometimes|string|min:3',
        ]);

        // Update only provided fields
        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }
        if (isset($validated['username'])) {
            $user->username = $validated['username'];
        }
        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
            'data' => $user,
        ]);
    }

    /**
     * DELETE /api/profile
     * Delete authenticated user account
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $userId = $user->id;

        // Revoke all tokens
        $user->tokens()->delete();

        // Delete user
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully!',
        ]);
    }
}