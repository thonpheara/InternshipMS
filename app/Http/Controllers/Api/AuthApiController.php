<?php

namespace App\Http\Controllers\Api;

use App\Models\CompanyProfile;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthApiController extends BaseApiController
{
    /**
     * Authenticate user from Android App and generate a Sanctum Bearer token.
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'       => ['required', 'email'],
            'password'    => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation failed', $validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError('Invalid email or password credentials.', [], 401);
        }

        if ($user->status !== 'active') {
            return $this->sendError('Your account is currently inactive or pending approval. Please contact administration.', [], 403);
        }

        $deviceName = $request->device_name ?: 'Android Device';
        $token = $user->createToken($deviceName)->plainTextToken;

        // Load profile based on role
        if ($user->isStudent()) {
            $user->load('studentProfile');
        } elseif ($user->isCompany()) {
            $user->load('companyProfile');
        }

        return $this->sendResponse([
            'token'      => $token,
            'token_type' => 'Bearer',
            'user'       => $user,
        ], 'Logged in successfully.');
    }

    /**
     * Register a new user account (student or company) and return a Sanctum token.
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'role'                  => ['required', 'in:student,company'],
            'device_name'           => ['nullable', 'string', 'max:100'],
            // Optional registration extras
            'phone'                 => ['nullable', 'string', 'max:50'],
            'company_name'          => ['nullable', 'string', 'max:255'],
            'industry'              => ['nullable', 'string', 'max:255'],
            'student_id_number'     => ['nullable', 'string', 'max:50'],
            'major'                 => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Registration validation failed', $validator->errors(), 422);
        }

        $role = $request->role;

        $user = DB::transaction(function () use ($request, $role) {
            $newUser = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $role,
                'status'   => 'active',
            ]);

            if ($role === 'student') {
                StudentProfile::create([
                    'user_id'           => $newUser->id,
                    'student_id_number' => $request->student_id_number,
                    'major'             => $request->major,
                    'phone'             => $request->phone,
                ]);
            } else {
                CompanyProfile::create([
                    'user_id'             => $newUser->id,
                    'company_name'        => $request->company_name ?: $request->name,
                    'contact_person'      => $request->name,
                    'contact_phone'       => $request->phone,
                    'industry'            => $request->industry,
                    'verification_status' => 'pending',
                ]);

                // Notify administrators
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\AppNotification(
                        title: 'New Company Pending Verification',
                        message: "{$newUser->name} registered via mobile app and requested company verification.",
                        actionUrl: route('admin.companies.index', ['status' => 'pending']),
                        icon: 'fa-solid fa-building-circle-check',
                        color: 'amber'
                    ));
                }
            }

            return $newUser;
        });

        $deviceName = $request->device_name ?: 'Android Device';
        $token = $user->createToken($deviceName)->plainTextToken;

        if ($user->isStudent()) {
            $user->load('studentProfile');
        } elseif ($user->isCompany()) {
            $user->load('companyProfile');
        }

        return $this->sendResponse([
            'token'      => $token,
            'token_type' => 'Bearer',
            'user'       => $user,
        ], 'Account registered successfully.', 201);
    }

    /**
     * Get the authenticated user's current profile and unread statistics.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->isStudent()) {
            $user->load('studentProfile');
        } elseif ($user->isCompany()) {
            $user->load('companyProfile');
        }

        return $this->sendResponse([
            'user'                  => $user,
            'unread_notifications' => $user->unreadNotifications()->count(),
            'unread_messages'      => $user->unreadMessagesCount(),
        ], 'Profile retrieved successfully.');
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Password validation failed', $validator->errors(), 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->sendError('Current password does not match our records.', [
                'current_password' => ['Current password is incorrect.']
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return $this->sendResponse(null, 'Password updated successfully.');
    }

    /**
     * Upload or update the user's avatar image via multipart form.
     */
    public function updateAvatar(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Avatar validation failed', $validator->errors(), 422);
        }

        $user = $request->user();

        // Delete old avatar if present
        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $path = $request->file('avatar')->store("avatars/{$user->id}", 'public');
        $user->update(['avatar_path' => $path]);

        if ($user->isStudent()) {
            $user->load('studentProfile');
        } elseif ($user->isCompany()) {
            $user->load('companyProfile');
        }

        return $this->sendResponse([
            'avatar_url' => $user->avatar_url,
            'user'       => $user,
        ], 'Avatar updated successfully.');
    }

    /**
     * Remove the user's avatar image.
     */
    public function removeAvatar(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->update(['avatar_path' => null]);

        return $this->sendResponse([
            'user' => $user,
        ], 'Avatar removed successfully.');
    }

    /**
     * Revoke the user's current access token (Logout).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return $this->sendResponse(null, 'Logged out successfully.');
    }
}
