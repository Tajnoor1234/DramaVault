<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModeratorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isModerator()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. Moderator access required.'], 403);
            }
            
            return redirect('/')->with('error', 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}