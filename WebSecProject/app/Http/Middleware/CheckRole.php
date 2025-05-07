<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            abort(403, 'Unauthorized action.');
        }

        $userRoles = $request->user()->roles()->pluck('name')->toArray();
        
        if (!in_array($role, $userRoles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
} 