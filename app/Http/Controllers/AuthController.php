<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        try {
            $result = $this->authService->register($request->all());

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully!',
                'data' => [
                    'token' => $result['token'],
                    'user' => $result['user'],
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => json_decode($e->getMessage(), true) ?? [],
            ], 422);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        try {
            $result = $this->authService->login(
                $request->input('email') ?? $request->input('username'),
                $request->input('password')
            );

            return response()->json([
                'success' => true,
                'message' => 'Login successful!',
                'data' => [
                    'token' => $result['token'],
                    'user' => $result['user'],
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully!',
        ]);
    }

    /**
     * Get current authenticated user
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }

    /**
     * Refresh authentication token
     */
    public function refreshToken(Request $request)
    {
        try {
            $newToken = $this->authService->refreshToken($request->user());

            return response()->json([
                'success' => true,
                'message' => 'Token refreshed successfully!',
                'data' => [
                    'token' => $newToken,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }
}