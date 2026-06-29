<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * AuthService - Handles authentication business logic
 * 
 * Responsibilities:
 * - User registration with validation
 * - User login with security checks
 * - Token management
 * - Password handling
 */
class AuthService
{
    /**
     * Register a new user
     * Simplified for testing - auto-generate email and name
     * 
     * @param array{username: string, password: string} $data
     * @throws \Exception
     */
    public function register(array $data): array
    {
        // Validate input
        $this->validateRegistration($data);

        // Check if username already exists
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $query = User::where('username', $data['username']);
        if ($query->exists()) {
            throw new \Exception('Username already exists', 422);
        }

        // Auto-generate email and name for testing
        $username = $data['username'];
        $email = $username . '@test.local';
        $name = $username;

        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => Hash::make($data['password']),
            'role' => 'student',
            'is_active' => true,
        ]);

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Authenticate user login
     * 
     * @throws \Exception
     */
    public function login(string $email, string $password): array
    {
        /** @var User|null $user */
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $user = User::where('email', $email)
            ->orWhere('username', $email)
            ->first();

        if (!$user) {
            throw new \Exception('Invalid credentials', 401);
        }

        if (!$user->is_active) {
            throw new \Exception('Account is inactive', 403);
        }

        if (!Hash::check($password, $user->password)) {
            // Log failed attempt for security
            Log::warning('Failed login attempt', [
                'email' => $email,
                'ip' => request()->ip(),
            ]);
            throw new \Exception('Invalid credentials', 401);
        }

        // Revoke old tokens for security
        $user->tokens()->delete();

        // Generate new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Logout user - revoke all tokens
     */
    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Refresh authentication token
     */
    public function refreshToken(User $user): string
    {
        // Revoke old token
        /** @var \Laravel\Sanctum\PersonalAccessToken|null $token */
        $token = $user->currentAccessToken();
        if ($token) {
            $token->delete();
        }

    // Generate new token
    return $user->createToken('auth_token')->plainTextToken;
}

    /**
     * Validate registration input
     * Simplified for testing - only require username and password
     * 
     * @throws \Exception
     */
    private function validateRegistration(array $data): void
    {
        $errors = [];

        // Only require username and password for testing
        if (!isset($data['username']) || empty($data['username'])) {
            $errors['username'] = 'Username is required';
        }

        if (!isset($data['password']) || empty($data['password'])) {
            $errors['password'] = 'Password is required';
        }

        if (!empty($errors)) {
            throw new \Exception(json_encode($errors), 422);
        }

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        // Simple validation: username min 3 chars, password min 3 chars for testing
        if (strlen($username) < 3) {
            throw new \Exception('Username must be at least 3 characters', 422);
        }

        if (strlen($password) < 3) {
            throw new \Exception('Password must be at least 3 characters', 422);
        }
    }
}
