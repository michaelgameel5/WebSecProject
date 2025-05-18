<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotEmployee
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasRole('employee')) {
            return redirect()->route('home')->with('error', 'Employees are not allowed to access this feature.');
        }

        return $next($request);
    }
} 