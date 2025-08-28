<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Contracts\UserRepositoryInterface;
use App\Helpers\ApiResponse;
use App\Enums\MainUserStatusEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Example controller demonstrating best practices for UserRepository usage
 */
class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * Get user profile
     */
    public function profile(Request $request): JsonResponse
    {
        // Using repository to find by ID with error handling
        $user = $this->userRepository->find($request->user()->id);

        if (!$user) {
            return ApiResponse::error('User not found', 404);
        }

        return ApiResponse::success($user);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($request->user()->id)
            ],
            'phone' => [
                'sometimes',
                'string',
                Rule::unique('users')->ignore($request->user()->id)
            ]
        ]);

        // Check if email is being changed and already exists
        if (isset($validated['email'])) {
            $existingUser = $this->userRepository->findByEmail($validated['email']);
            if ($existingUser && $existingUser->id !== $request->user()->id) {
                return ApiResponse::error('Email already exists', 409);
            }
        }

        // Check if phone is being changed and already exists
        if (isset($validated['phone'])) {
            $existingUser = $this->userRepository->findByPhone($validated['phone']);
            if ($existingUser && $existingUser->id !== $request->user()->id) {
                return ApiResponse::error('Phone already exists', 409);
            }
        }

        // Use repository to update user
        $user = $this->userRepository->update($request->user()->id, $validated);

        return ApiResponse::success([
            'user' => $user,
            'message' => 'Profile updated successfully'
        ]);
    }

    /**
     * Get all active users (admin functionality)
     */
    public function getActiveUsers(Request $request): JsonResponse
    {
        // Using repository method for specific business logic
        $users = $this->userRepository->getActiveUsers(
            ['id', 'name', 'email', 'phone', 'created_at'],
            [] // No relations needed for this endpoint
        );

        return ApiResponse::success($users);
    }

    /**
     * Search users by various criteria
     */
    public function searchUsers(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => 'required|string|min:2',
            'type' => 'sometimes|in:email,phone,name'
        ]);

        $searchTerm = $validated['search'];
        $searchType = $validated['type'] ?? 'name';

        $user = match($searchType) {
            'email' => $this->userRepository->findByEmail($searchTerm),
            'phone' => $this->userRepository->findByPhone($searchTerm),
            'name' => $this->userRepository->findByCol('name', $searchTerm),
            default => null
        };

        if (!$user) {
            return ApiResponse::error('User not found', 404);
        }

        return ApiResponse::success($user);
    }

    /**
     * Deactivate user account
     */
    public function deactivateAccount(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        // Use repository to update user status
        $user = $this->userRepository->update($userId, [
            'status' => MainUserStatusEnum::INACTIVE
        ]);

        if ($user) {
            // Revoke all tokens
            $request->user()->tokens()->delete();

            return ApiResponse::success([
                'message' => 'Account deactivated successfully'
            ]);
        }

        return ApiResponse::error('Failed to deactivate account', 500);
    }

    /**
     * Check if email exists (useful for registration flows)
     */
    public function checkEmailExists(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        $user = $this->userRepository->findByEmail($validated['email']);

        return ApiResponse::success([
            'exists' => $user !== null,
            'email' => $validated['email']
        ]);
    }

    /**
     * Check if phone exists (useful for registration flows)
     */
    public function checkPhoneExists(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => 'required|string'
        ]);

        $user = $this->userRepository->findByPhone($validated['phone']);

        return ApiResponse::success([
            'exists' => $user !== null,
            'phone' => $validated['phone']
        ]);
    }
}
