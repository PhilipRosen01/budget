<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboardingComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated users
        if ($request->user() && !$request->user()->onboarding_completed) {
            // Allow access to onboarding routes, logout, and API routes
            $allowedRoutes = [
                'onboarding.*',
                'logout',
                'profile.destroy',
            ];

            // Allow access if current route is in allowed routes
            foreach ($allowedRoutes as $pattern) {
                if ($request->routeIs($pattern)) {
                    return $next($request);
                }
            }

            // Redirect to onboarding if not completed
            return redirect()->route('onboarding.index');
        }

        return $next($request);
    }
}
