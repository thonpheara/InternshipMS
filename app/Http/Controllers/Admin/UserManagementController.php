<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users with search and role/status filtering.
     */
    public function index(Request $request): View
    {
        $role = $request->query('role', 'all');
        $status = $request->query('status', 'all');
        $search = trim($request->query('search', ''));

        $query = User::with(['studentProfile', 'companyProfile'])
            ->whereIn('role', ['student', 'company', 'coordinator']);

        if ($role !== 'all') {
            $query->where('role', $role);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('studentProfile', function ($sq) use ($search) {
                      $sq->where('student_id_number', 'like', "%{$search}%")
                         ->orWhere('major', 'like', "%{$search}%")
                         ->orWhere('department', 'like', "%{$search}%");
                  })
                  ->orWhereHas('companyProfile', function ($cq) use ($search) {
                      $cq->where('company_name', 'like', "%{$search}%")
                         ->orWhere('industry', 'like', "%{$search}%")
                         ->orWhere('location', 'like', "%{$search}%");
                  });
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        // Metric counts for quick badges
        $counts = [
            'total' => User::whereIn('role', ['student', 'company', 'coordinator'])->count(),
            'students' => User::where('role', 'student')->count(),
            'companies' => User::where('role', 'company')->count(),
            'active' => User::whereIn('role', ['student', 'company', 'coordinator'])->where('status', 'active')->count(),
        ];

        return view('Admin.users.index', compact('users', 'counts', 'role', 'status', 'search'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(Request $request): View
    {
        $selectedRole = $request->query('role', 'student');
        return view('Admin.users.create', compact('selectedRole'));
    }

    /**
     * Store a newly created user along with role-specific profile in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $role = $request->input('role');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:student,company'],
            'status' => ['required', 'in:active,inactive'],
        ];

        if ($role === 'student') {
            $rules += [
                'student_id_number' => ['nullable', 'string', 'max:50'],
                'department' => ['nullable', 'string', 'max:255'],
                'major' => ['nullable', 'string', 'max:255'],
                'cohort_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
                'gpa' => ['nullable', 'numeric', 'between:0.00,4.00'],
                'phone' => ['nullable', 'string', 'max:50'],
                'skills' => ['nullable', 'string', 'max:1000'],
                'bio' => ['nullable', 'string', 'max:2000'],
                'eligibility_status' => ['nullable', 'in:eligible,ineligible,pending'],
            ];
        } elseif ($role === 'company') {
            $rules += [
                'company_name' => ['required', 'string', 'max:255'],
                'industry' => ['nullable', 'string', 'max:255'],
                'website' => ['nullable', 'url', 'max:255'],
                'location' => ['nullable', 'string', 'max:255'],
                'address' => ['nullable', 'string', 'max:500'],
                'contact_person' => ['nullable', 'string', 'max:255'],
                'contact_phone' => ['nullable', 'string', 'max:50'],
                'description' => ['nullable', 'string', 'max:3000'],
                'verification_status' => ['nullable', 'in:verified,pending,rejected'],
            ];
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $role) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $role,
                'status' => $validated['status'] ?? 'active',
            ]);

            if ($role === 'student') {
                $skillsArray = null;
                if (!empty($validated['skills'])) {
                    $skillsArray = array_values(array_filter(array_map('trim', explode(',', $validated['skills']))));
                }

                StudentProfile::create([
                    'user_id' => $user->id,
                    'student_id_number' => $validated['student_id_number'] ?? null,
                    'department' => $validated['department'] ?? null,
                    'major' => $validated['major'] ?? null,
                    'cohort_year' => $validated['cohort_year'] ?? null,
                    'gpa' => $validated['gpa'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'skills' => $skillsArray,
                    'bio' => $validated['bio'] ?? null,
                    'eligibility_status' => $validated['eligibility_status'] ?? 'eligible',
                ]);
            } elseif ($role === 'company') {
                CompanyProfile::create([
                    'user_id' => $user->id,
                    'company_name' => $validated['company_name'],
                    'industry' => $validated['industry'] ?? null,
                    'website' => $validated['website'] ?? null,
                    'location' => $validated['location'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'contact_person' => $validated['contact_person'] ?? $validated['name'],
                    'contact_phone' => $validated['contact_phone'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'verification_status' => $validated['verification_status'] ?? 'verified',
                ]);
            }
        });

        return redirect()->route('admin.users.index', ['role' => $role])
            ->with('success', "New {$role} user '{$validated['name']}' has been created successfully.");
    }

    /**
     * Display the specified user and full profile data.
     */
    public function show(User $user): View
    {
        $user->load(['studentProfile', 'companyProfile']);

        $activity = [];
        if ($user->isStudent() && $user->studentProfile) {
            $activity['applications_count'] = $user->studentProfile->applications()->count();
            $activity['placements_count'] = $user->studentProfile->placements()->count();
        } elseif ($user->isCompany() && $user->companyProfile) {
            $activity['posts_count'] = $user->companyProfile->internshipPosts()->count();
            $activity['applicants_count'] = $user->companyProfile->applications()->count();
        }

        return view('Admin.users.show', compact('user', 'activity'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $user->load(['studentProfile', 'companyProfile']);
        return view('Admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $role = $user->role;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'status' => ['required', 'in:active,inactive'],
        ];

        if ($role === 'student') {
            $rules += [
                'student_id_number' => ['nullable', 'string', 'max:50'],
                'department' => ['nullable', 'string', 'max:255'],
                'major' => ['nullable', 'string', 'max:255'],
                'cohort_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
                'gpa' => ['nullable', 'numeric', 'between:0.00,4.00'],
                'phone' => ['nullable', 'string', 'max:50'],
                'skills' => ['nullable', 'string', 'max:1000'],
                'bio' => ['nullable', 'string', 'max:2000'],
                'eligibility_status' => ['nullable', 'in:eligible,ineligible,pending'],
            ];
        } elseif ($role === 'company') {
            $rules += [
                'company_name' => ['required', 'string', 'max:255'],
                'industry' => ['nullable', 'string', 'max:255'],
                'website' => ['nullable', 'url', 'max:255'],
                'location' => ['nullable', 'string', 'max:255'],
                'address' => ['nullable', 'string', 'max:500'],
                'contact_person' => ['nullable', 'string', 'max:255'],
                'contact_phone' => ['nullable', 'string', 'max:50'],
                'description' => ['nullable', 'string', 'max:3000'],
                'verification_status' => ['nullable', 'in:verified,pending,rejected'],
            ];
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($user, $validated, $role) {
            $userPayload = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'status' => $validated['status'],
            ];

            if (!empty($validated['password'])) {
                $userPayload['password'] = Hash::make($validated['password']);
            }

            $user->update($userPayload);

            if ($role === 'student') {
                $skillsArray = null;
                if (!empty($validated['skills'])) {
                    $skillsArray = array_values(array_filter(array_map('trim', explode(',', $validated['skills']))));
                }

                $user->studentProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'student_id_number' => $validated['student_id_number'] ?? null,
                        'department' => $validated['department'] ?? null,
                        'major' => $validated['major'] ?? null,
                        'cohort_year' => $validated['cohort_year'] ?? null,
                        'gpa' => $validated['gpa'] ?? null,
                        'phone' => $validated['phone'] ?? null,
                        'skills' => $skillsArray,
                        'bio' => $validated['bio'] ?? null,
                        'eligibility_status' => $validated['eligibility_status'] ?? 'eligible',
                    ]
                );
            } elseif ($role === 'company') {
                $user->companyProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'company_name' => $validated['company_name'],
                        'industry' => $validated['industry'] ?? null,
                        'website' => $validated['website'] ?? null,
                        'location' => $validated['location'] ?? null,
                        'address' => $validated['address'] ?? null,
                        'contact_person' => $validated['contact_person'] ?? $validated['name'],
                        'contact_phone' => $validated['contact_phone'] ?? null,
                        'description' => $validated['description'] ?? null,
                        'verification_status' => $validated['verification_status'] ?? 'verified',
                    ]
                );
            }
        });

        return redirect()->route('admin.users.index', ['role' => $role])
            ->with('success', "User '{$user->name}' updated successfully.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own administrator account.');
        }

        $userName = $user->name;
        $userRole = $user->role;

        DB::transaction(function () use ($user) {
            if ($user->studentProfile) {
                $user->studentProfile->delete();
            }
            if ($user->companyProfile) {
                $user->companyProfile->delete();
            }
            $user->delete();
        });

        return redirect()->route('admin.users.index')
            ->with('success', "The {$userRole} account '{$userName}' has been deleted successfully.");
    }
}
