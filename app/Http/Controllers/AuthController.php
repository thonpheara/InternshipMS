<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Your account is currently inactive or pending approval. Please contact the administrator.',
                ]);
            }

            return $this->redirectByRole($user)->with('success', "Welcome back, {$user->name}!");
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.register');
    }

    /**
     * Handle registration for new users (Default role: student).
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:student,company'],
        ]);

        $role = $validated['role'];

        $user = DB::transaction(function () use ($validated, $role) {
            $newUser = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $role,
                'status' => 'active',
            ]);

            if ($role === 'student') {
                StudentProfile::create([
                    'user_id' => $newUser->id,
                ]);
            } else {
                CompanyProfile::create([
                    'user_id' => $newUser->id,
                    'company_name' => $validated['name'],
                    'contact_person' => $validated['name'],
                    'verification_status' => 'pending',
                ]);

                // Notify all administrators about new company registration
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\AppNotification(
                        title: 'New Company Pending Verification',
                        message: "{$newUser->name} registered and requested institutional partnership.",
                        actionUrl: route('admin.companies.index', ['status' => 'pending']),
                        icon: 'fa-solid fa-building-circle-check',
                        color: 'amber'
                    ));
                }
            }

            return $newUser;
        });

        // Automatically log the new user in
        Auth::login($user);
        $request->session()->regenerate();

        $targetRoute = $role === 'student' ? route('student.profile') : route('company.profile');

        return redirect($targetRoute)->with('success', "Welcome to Internship Management System, {$user->name}! Please complete your profile information below.");
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been successfully logged out.');
    }

    /**
     * Route user to their corresponding role portal.
     */
    protected function redirectByRole(User $user): RedirectResponse
    {
        return match ($user->role) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'company' => redirect()->intended(route('company.dashboard')),
            'student' => redirect()->intended(route('student.dashboard')),
            default => redirect()->route('login'),
        };
    }
}
