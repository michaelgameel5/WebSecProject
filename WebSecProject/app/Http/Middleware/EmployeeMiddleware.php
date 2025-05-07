<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isEmployee()) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        return $next($request);
    }
} 