<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OTPMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($request->routeIs('otp.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        if ($user && $user->otp_verified === false) {
        return redirect()->route('otp.verify');
        }

        return $next($request);
    }
}
