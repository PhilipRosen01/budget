<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
                $user = $request->user();
                
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
                
                // Check onboarding status directly from database to avoid caching issues
                $onboardingCompleted = DB::table('users')
                    ->where('id', $user->id)
                    ->value('onboarding_completed');
                
                // If not completed (null, 0, or false), redirect to onboarding
                if (!$onboardingCompleted) {
                    return redirect()->route('onboarding.index');
                }
                
            } catch (\Exception $e) {
                // If there's a database error, allow access to prevent lockouts
                Log::error('Error checking onboarding status: ' . $e->getMessage());
                return $next($request);
            }
        }

        return $next($request);
    }
}
