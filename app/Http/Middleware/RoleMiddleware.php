<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please sign in to access this area.');
        }

        $user = Auth::user();

        // Check if user has one of the allowed roles
        if (!in_array($user->role, $roles, true)) {
            // Redirect to their respective dashboard instead of a blank 403 error for better UX
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard')->with('error', 'Access denied to that section.'),
                'company' => redirect()->route('company.dashboard')->with('error', 'Access denied to that section.'),
                'student' => redirect()->route('student.dashboard')->with('error', 'Access denied to that section.'),
                default => redirect()->route('login'),
            };
        }

        return $next($request);
    }
}
