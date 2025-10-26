<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Track last activity time for authenticated users
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user is banned/inactive
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account has been suspended. Please contact support.'
                ]);
            }
            
            $user->last_active_at = now();
            $user->save();
        }
        
        // Store session data (only if session is available)
        if ($request->hasSession()) {
            $request->session()->put('last_page', $request->fullUrl());
            $request->session()->put('last_activity', now()->timestamp);
            
            // Track page views
            if (!$request->session()->has('page_views')) {
                $request->session()->put('page_views', 0);
            }
            $request->session()->increment('page_views');
        }
        
        return $next($request);
    }
}
