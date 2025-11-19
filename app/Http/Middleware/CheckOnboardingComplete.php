<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

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
        if ($request->user()) {
            try {
                // Refresh user to get latest data from database
                $user = $request->user();
                $user->refresh();
                
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
                
                // Get the onboarding status
                $onboardingCompleted = $user->onboarding_completed;
                
                // Use explicit boolean check - anything that's not explicitly true should redirect
                if ($onboardingCompleted !== true && $onboardingCompleted !== 1) {
                    return redirect()->route('onboarding.index');
                }
                
            } catch (\Exception $e) {
                // If there's a database error checking onboarding status, allow access
                // This prevents users from being locked out due to column issues
                Log::error('Error checking onboarding status: ' . $e->getMessage());
                return $next($request);
            }
        }

        return $next($request);
    }
}
