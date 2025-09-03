<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If no specific roles provided, use the route parameter
        if (empty($roles)) {
            $route = $request->route();
            if ($route && $route->getName() === 'student') {
                $roles = ['student'];
            } elseif ($route && $route->getName() === 'instructor') {
                $roles = ['instructor'];
            } elseif ($route && $route->getName() === 'admin') {
                $roles = ['admin'];
            }
        }

        // Check if user has any of the required roles
        $hasRole = false;
        foreach ($roles as $role) {
            if ($user->role === $role) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }
            
            return redirect()->route('home')->with('error', __('auth.unauthorized'));
        }

        return $next($request);
    }
}
