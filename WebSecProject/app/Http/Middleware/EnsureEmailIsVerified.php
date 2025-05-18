<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

class EnsureEmailIsVerified
{
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        // Temporarily bypass email verification
        return $next($request);

        /* Original verification code - commented out
        if (! $request->user()) {
            return $request->expectsJson()
                ? abort(403, 'You must be logged in.')
                : redirect()->route('login');
        }

        if ($request->user() instanceof MustVerifyEmail &&
            ! $request->user()->hasVerifiedEmail() &&
            ! $request->user()->isEmployee()) {
            
            // Log the verification attempt
            Log::info('Email verification required', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email
            ]);

            // If the user has a pending verification, allow them to resend
            if ($request->user()->verification_sent_at) {
                return $request->expectsJson()
                    ? abort(403, 'Your email address is not verified. Please check your email or request a new verification link.')
                    : redirect()->route('verification.notice')->with('resend', true);
            }

            return $request->expectsJson()
                ? abort(403, 'Your email address is not verified.')
                : Redirect::guest(URL::route($redirectToRoute ?: 'verification.notice'));
        }
        */
    }
} 